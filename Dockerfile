FROM php:5.6-apache
RUN apt-get update
RUN apt-get install -y vim
RUN docker-php-ext-install pdo_mysql 
RUN docker-php-ext-install bcmath
# RUN set -x && \
#     yum update -y && \
#     yum install -y \
#     php56w-bcmath && \
#     yum reinstall -y