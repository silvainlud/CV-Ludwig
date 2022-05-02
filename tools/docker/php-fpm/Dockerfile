
FROM php:8.1-fpm-buster

ARG UNAME=appuser
ARG UID=1001
ARG GID=1001

RUN apt update -y && apt upgrade -y

RUN apt-get clean && apt-get update && apt-get install --fix-missing wget apt-transport-https lsb-release ca-certificates gnupg2 -y

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
RUN apt-get clean && apt-get update

RUN apt install unzip -y
RUN apt install libzip-dev -y
RUN apt install libicu-dev -y
RUN apt install libxml2-dev -y
RUN apt install libonig-dev -y
RUN apt install libedit-dev -y
RUN apt install libssl-dev -y
RUN apt install libcurl4-openssl-dev -y
RUN apt install unixodbc-dev -y
RUN apt install libpng-dev -y
RUN apt install logrotate -y

RUN apt install -y unzip libzip-dev libicu-dev libxml2-dev libonig-dev libedit-dev libssl-dev libcurl4-openssl-dev unixodbc-dev libpng-dev zlib1g-dev libpng-dev libjpeg-dev

RUN set -ex; \
	docker-php-source extract; \
	{ \
		echo '# https://github.com/docker-library/php/issues/103#issuecomment-271413933'; \
		echo 'AC_DEFUN([PHP_ALWAYS_SHARED],[])dnl'; \
		echo; \
		cat /usr/src/php/ext/odbc/config.m4; \
	} > temp.m4; \
	mv temp.m4 /usr/src/php/ext/odbc/config.m4; \
	docker-php-ext-configure odbc --with-unixODBC=shared,/usr; \
	docker-php-ext-install odbc; \
	docker-php-source delete
# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN docker-php-ext-configure intl
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo
#RUN docker-php-ext-install json
RUN docker-php-ext-install zip
RUN docker-php-ext-install intl
RUN docker-php-ext-install xml
RUN docker-php-ext-install ctype
RUN docker-php-ext-install mbstring
RUN #docker-php-ext-install readline
RUN docker-php-ext-install simplexml
RUN docker-php-ext-install phar
RUN docker-php-ext-install curl
RUN docker-php-ext-install dom
# Install GD PHP extension.
RUN docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd
RUN docker-php-ext-install mysqli pdo_mysql

RUN apt-get update -y

# Install yaml PHP extension.
RUN apt-get install libyaml-dev -y && pecl install yaml && docker-php-ext-enable yaml

# Install Redis PHP extension.
RUN pecl install redis \
    &&  docker-php-ext-enable redis

# Install Calendar PHP extension.
RUN docker-php-ext-configure calendar && docker-php-ext-install calendar

# Install xslt PHP extension.
RUN apt install libxslt1-dev -y && docker-php-ext-configure xsl && docker-php-ext-install xsl

# Symfony tool
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
  mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
  && addgroup --force-badname _www \
  && adduser --no-create-home --force-badname --disabled-login --disabled-password --system _www \
  && addgroup _www _www

# Graphviz
RUN apt install graphviz -y

RUN mkdir -p /usr/local/etc/php/extra/ \
	&& wget http://browscap.org/stream?q=PHP_BrowsCapINI -O /usr/local/etc/php/extra/full_php_browscap.ini

COPY php.ini /usr/local/etc/php/php.ini
COPY conf.d /usr/local/etc/php/conf.d/

RUN groupadd -g $GID -o $UNAME
RUN useradd -m -u $UID -g $GID -o -s /bin/bash $UNAME
#USER $UNAME

CMD ["php-fpm"]
