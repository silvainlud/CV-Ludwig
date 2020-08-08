FROM registery.gitlab.silvain.eu/silvain.eu/cv-ludwig_php:base

COPY . /var/www/web
COPY ./tools/docker/web/php.ini /etc/php/7.4/fpm/php.ini

CMD ["service", "php7.4-fpm",  "restart"]
CMD ["php", "bin/console",  "cache:clear"]
CMD ["/usr/sbin/apache2", "-D",  "FOREGROUND"]
