FROM nginx:1.17

COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./public/static /var/www/html/static