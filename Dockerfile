FROM registery.gitlab.silvain.eu/silvain.eu/cv-ludwig_php:base

COPY . /var/www/web
COPY ./tools/docker/web/php.ini /etc/php/7.4/fpm/php.ini

CMD ["/usr/sbin/apache2", "-D",  "FOREGROUND"]
