### Задача

##### Создать следующую структуру:
1. HTTP API слушает get-запрос и принимает в параметрах одно или несколько целых чисел.
2. Присваивает каждому запросу уникальный токен.
3. Сохраняет числа из запроса вместе с токеном в Postgres в формате JSONB.
4. Добавляет к этому JSONB число записей, которые были в этой таблице на момент вставки.
5. После вставки срабатывает триггер, который вызывает хранимую процедуру.
6. Хранимая процедура распаковывает вставленные данные из JSONB и заносит их в другую таблицу (events, например)
7. Демон читает эту таблицу и пишет данные из нее в лог или в выходной поток.

##### Условие:
Операции должны быть атомарны и последовательны.
Т.е. независимо от количества запросов их данные должны попасть в итоговый вывод в той же последовательности, в которой их принял HTTP-сервер.

### Запуск проекта
##### Сборка и запуск сервера
sudo docker-compose build  
sudo docker-compose up -d  
sudo docker exec -ti testtaskisolatedtransaction_postgres_1 psql -U postgres -d test -a -f /root/initStructure.sql

##### Запуск демона
sudo docker exec -ti testtaskisolatedtransaction_php_1 php /var/www/html/bin/ListenerDaemon.php

##### Описание работы
* API принимает POST запросы на 80 порту http://localhost
* Числа передаются в теле запросы как json массив например [23,34,4343,434]
* Так же можно запустить интеграционный тест(PHPUnit) API->DB project/src/test/MainTransactionScriptTest.php


### Несколько уточнений.
Границы транзакции от прихода запроса на API до фиксации в таблице events. Для полной изоляции должны выполняться последовательно.
* Участок HTTP -> DB Падение PHP - Нарушение D. либо терям событие если допустимо либо очереди с подтверждением
* Возможен вариант когда почти одновременные HTTP запросы(сотые секунды) обработаются не в хронологическом порядке. 
Можно фиксировать timestamp как только пришел запрос но это не поможет поскольку данные будут сразу вычитанны демоном не в том порядке. 
Также на разных серверах возможно разное локальное время. Тогда нужно вводить очереди и работу с часовыми поясами.

Эти проблемы должны решаться пониманием предметной области и зачем это делается те чем можем пожертвовать а чем нет.

### Предпочтительный вариант для ТЗ
HTTP API -> MQ -> single worker -> DB -> log daemon
1. Создаем exchange c подтверждением обработки и сохранением на диск.
2. Как только приходит запрос кладем его в очередь.
3. Запускаем одного воркера который забирает по одному сообщению и транзакцией сохраняет в базу. В обоих таблицах id автоинкримент. По завершении транзакции мы уверенны что данные перенесены во вторую таблицу. Подтверждаем обработку сообщения в очереди. При ошибке пробуем еще раз если опять то откидываем событие или посылаем на другой воркер занимающийся ошибками.
4. Вычитываем демоном ориентируясь на последний вычитанный id. Если важно вычитать все данные то еще храним последний id гденибуть в персистентном хранилище

Узкое место один воркер.  
Вообще HTTP - протокол негаронтированной доставки и лучше писать сразу в MQ по STOMP протоколу например.