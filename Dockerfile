FROM php:7.4-apache
RUN apt-get update
RUN apt-get install -y git unzip zip
RUN apt-get install -y wget
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN curl -sS https://getcomposer.org/installer | php \
	&& mv composer.phar /usr/local/bin/ \
	&& ln -s /usr/local/bin/composer.phar /usr/local/bin/composer
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /app
COPY . /app
CMD [ "symfony", "server:start", "--port=8000", "--no-tls" ]
