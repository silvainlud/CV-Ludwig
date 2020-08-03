FROM debian:buster

RUN apt update && apt install apache2 git gnupg2 curl sudo wget -y
RUN apt-get -y install apt-transport-https lsb-release ca-certificates curl

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
    && sh -c 'echo "deb https://packages.sury.org/php/ buster main" > /etc/apt/sources.list.d/php.list' \
    && apt-get update \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list \
    && apt update -y && apt upgrade -y

RUN apt install php7.4 php7.4-xml php7.4-ctype php7.4-zip unzip php7.4-geoip php7.4-mbstring php7.4-readline php7.4-uuid php7.4-simplexml php7.4-json php7.4-phar php7.4-curl php7.4-memcached php7.4-intl php7.4-odbc php7.4-opcache php7.4-yaml php7.4-cli php7.4-dom php7.4-fpm php7.4-apcu php7.4-pdo php7.4-dev php7.4-xml php7.4-intl php7.4-gd php7.4-redis php7.4-pgsql php7.4-mysql php7.4-pdo-mysql php7.4-pdo-sqlite -y
#RUN apt install -y yarn unixodbc-dev  wkhtmltopdf \
#    && cp /usr/bin/wkhtmlto* /usr/local/bin

RUN a2enmod proxy_fcgi setenvif \
    && a2enconf php7.4-fpm \
    && a2dismod mpm_event \
    && a2enmod mpm_worker \
    && a2enmod rewrite \
    && service apache2 restart \
    && service php7.4-fpm restart

#Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Symfony tool
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
  mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
  && addgroup --force-badname _www \
  && adduser --no-create-home --force-badname --disabled-login --disabled-password --system _www \
  && addgroup _www _www

RUN cd /etc/php/7.4/fpm/conf.d/ && wget http://browscap.org/stream?q=PHP_BrowsCapINI -O /etc/php/7.4/fpm/browscap.ini
RUN apt install -y graphviz

RUN mkdir /var/www/web
WORKDIR /var/www/web
#
EXPOSE 9000

#Apache2

RUN chown -R www-data:www-data /var/www

ENV APACHE_RUN_USER  www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR   /var/log/apache2
ENV APACHE_PID_FILE  /var/run/apache2/apache2.pid
ENV APACHE_RUN_DIR   /var/run/apache2
ENV APACHE_LOCK_DIR  /var/lock/apache2
ENV APACHE_LOG_DIR   /var/log/apache2

RUN mkdir -p $APACHE_RUN_DIR
RUN mkdir -p $APACHE_LOCK_DIR
RUN mkdir -p $APACHE_LOG_DIR

COPY ./apache.conf /etc/apache2/sites-available/web.conf
RUN a2ensite web && a2dissite 000-default

RUN echo "exit 0" > /usr/sbin/policy-rc.d

#CMD ["/usr/sbin/apache2", "-D",  "FOREGROUND"]
