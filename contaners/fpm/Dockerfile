FROM php:7-fpm
MAINTAINER oloriin <mihaz2003@mail.ru>

RUN apt-get update && apt-get install -y \
        libmcrypt-dev unzip libpq-dev \
        git \
        && docker-php-ext-install -j$(nproc) mcrypt
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install exif
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pcntl

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

COPY ./conf/ /usr/local/etc/php-fpm.d/

CMD /root/init.sh && php-fpm
#CMD /root/init.sh && /var/www/html/bin/WebSocketServer.php start -d && php-fpm