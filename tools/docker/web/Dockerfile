FROM debian:buster

RUN apt update && apt install apache2 git gnupg2 curl sudo wget -y

RUN curl https://packages.sury.org/php/apt.gpg | sudo apt-key add - \
    && echo "deb https://packages.sury.org/php/ buster main" | sudo tee /etc/apt/sources.list.d/php7.x.list \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list \
    && apt update -y && apt upgrade -y

RUN apt install php7.4 php7.4-xml php7.4-ctype php7.4-zip unzip php7.4-geoip php7.4-mbstring php7.4-readline php7.4-uuid php7.4-simplexml php7.4-json php7.4-phar php7.4-curl php7.4-memcached php7.4-intl php7.4-odbc php7.4-opcache php7.4-yaml php7.4-cli php7.4-dom php7.4-fpm php7.4-apcu php7.4-pdo php7.4-dev php7.4-xml php7.4-intl php7.4-gd php7.4-redis -y
RUN apt install -y yarn unixodbc-dev  wkhtmltopdf \
    && cp /usr/bin/wkhtmlto* /usr/local/bin

RUN  apt-get clean && apt-get -y update && apt-get install -y locales \
    && sed -i 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/g' /etc/locale.gen \
    && locale-gen \
    && pecl install sqlsrv \
    && pecl install pdo_sqlsrv \
    && printf "; priority=20\nextension=sqlsrv.so\n" > /etc/php/7.4/mods-available/sqlsrv.ini \
    && printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /etc/php/7.4/mods-available/pdo_sqlsrv.ini \
    && phpenmod -v 7.4 sqlsrv pdo_sqlsrv

RUN a2enmod proxy_fcgi setenvif \
    && a2enconf php7.4-fpm \
    && a2dismod mpm_event \
    && a2enmod mpm_worker \
    && a2enmod rewrite \
    && service apache2 restart \
    && service php7.4-fpm restart

RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update -y \
    && ACCEPT_EULA=Y apt-get install msodbcsql17 -y \
    && echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bash_profile \
    && echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc \
    && /bin/bash -c "source ~/.bashrc" \
    && sudo apt-get install unixodbc-dev -y \
    && sudo apt-get install libgssapi-krb5-2 -y
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

RUN mkdir /var/www/portail-php
WORKDIR /var/www/portail-php
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

COPY ./apache.conf /etc/apache2/sites-available/portail_php.conf
RUN a2ensite portail_php && a2dissite 000-default

RUN echo "exit 0" > /usr/sbin/policy-rc.d

#CMD ["/usr/sbin/apache2", "-D",  "FOREGROUND"]
