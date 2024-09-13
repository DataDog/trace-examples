FROM php:5.6-apache

RUN apt-get update \
# Install base packages
    && apt-get install -y \
        apache2 \
        curl \
        git \
        gnupg2 \
        libmcrypt-dev \
        mysql-client \
        unzip \
        vim \
        wget \
        wget \
        zlib1g-dev \
# Install relevant php extensions
    && docker-php-source extract \
    && docker-php-ext-install mcrypt \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install pdo \
    && docker-php-ext-install zip \
    && docker-php-source delete \
# Install composer
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php  --install-dir="/usr/bin" --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && composer self-update \
# Remove installation cache
    && rm -rf /var/lib/apt/lists/*

ARG DD_TRACE_VERSION
ARG WEB_APP_PATH

# Install DDTrace deb
ADD https://github.com/DataDog/dd-trace-php/releases/download/${DD_TRACE_VERSION}/datadog-php-tracer_${DD_TRACE_VERSION}-beta_amd64.deb datadog-php-tracer.deb
RUN dpkg -i datadog-php-tracer.deb

RUN a2enmod rewrite

COPY Dockerfiles/apache2.conf /etc/apache2/apache2.conf
COPY Dockerfiles/apache2-virtualhost.conf /etc/apache2/sites-available/000-default.conf
COPY Dockerfiles/php-dd-ext.ini /usr/local/etc/php/conf.d/php-dd-ext.ini

COPY ${WEB_APP_PATH} /var/www

WORKDIR /var/www

RUN chmod -R a+w /var/www

RUN php -d memory_limit=-1 /usr/bin/composer update

CMD [ "apache2-foreground" ]
