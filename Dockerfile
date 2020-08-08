FROM registery.gitlab.silvain.eu/silvain.eu/cv-ludwig_php:base

COPY . /var/www/web
COPY ./tools/docker/web/php.ini /etc/php/7.4/fpm/php.ini

CMD pwd && whoiam && ls -l && ls -l bin && php bin/console cache:clear && service php7.4-fpm start && service php7.4-fpm status && /usr/sbin/apache2 -D FOREGROUND
