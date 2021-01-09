FROM php:7.4-fpm-buster
RUN apt update -y && apt upgrade -y

RUN apt-get clean && apt-get update && apt-get install --fix-missing wget apt-transport-https lsb-release ca-certificates gnupg2 git curl sudo -y

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
    && sh -c 'echo "deb https://packages.sury.org/php/ buster main" > /etc/apt/sources.list.d/php.list' \
    && apt-get update

RUN apt install -y unzip libzip-dev libicu-dev libxml2-dev libonig-dev libedit-dev libssl-dev libcurl4-openssl-dev unixodbc-dev libpng-dev zlib1g-dev libpng-dev libjpeg-dev

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN docker-php-ext-configure intl
RUN docker-php-ext-install opcache pdo pdo_mysql json zip intl xml ctype mbstring readline simplexml phar curl dom

# Install GD PHP extension.
RUN docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd

# Install GeoIP PHP extension.
RUN apt-get update -y\
    && apt-get install -y  libgeoip-dev wget \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install geoip-1.1.1 \
    && docker-php-ext-enable geoip

# Install Redis PHP extension.
RUN pecl install redis \
    &&  docker-php-ext-enable redis

# Install Calendar PHP extension.
RUN docker-php-ext-configure calendar && docker-php-ext-install calendar

# Install xslt PHP extension.
RUN apt-get update -y
RUN apt install libxslt1-dev -y && docker-php-ext-configure xsl && docker-php-ext-install xsl

# Graphviz
RUN apt install graphviz -y

# Symfony tool
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
  mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
  && addgroup --force-badname _www \
  && adduser --no-create-home --force-badname --disabled-login --disabled-password --system _www \
  && addgroup _www _www

RUN mkdir -p /usr/local/etc/php/extra/ \
	&& wget http://browscap.org/stream?q=PHP_BrowsCapINI -O /usr/local/etc/php/extra/full_php_browscap.ini

COPY php.ini /usr/local/etc/php/php.ini
COPY conf.d/ /usr/local/etc/php/conf.d/

CMD ["php-fpm"]
