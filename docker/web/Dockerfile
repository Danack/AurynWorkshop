FROM richarvey/nginx-php-fpm:1.4.1

# Documentation at https://github.com/ngineered/nginx-php-fpm

WORKDIR /var/www

RUN touch /var/i_am_docker




RUN ln -sf /proc/self/fd/1 /var/log/nginx/access.log
RUN ln -sf /proc/self/fd/1 /var/log/nginx/error.log