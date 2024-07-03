FROM ghcr.io/devgine/composer-php:latest

# Install additional tools
RUN apk add --no-cache curl git ruby rsync npm nodejs openssh-client \
    && gem install capistrano capistrano-sentry capistrano-locally capistrano-rsync

# Install AWS-CLI (v1) for Production deployments
RUN apk add --no-cache aws-cli

# Setup SSH
RUN mkdir -p ~/.ssh \
    && chmod 700 ~/.ssh \
    && echo "$SSH_KNOWN_HOSTS" > ~/.ssh/known_hosts \
    && chmod 644 ~/.ssh/known_hosts

# Ensure AWS directory exists
RUN mkdir -p ~/.aws

# Install PHP extensions conditionally (skip ext-pcntl and ext-gd)
RUN apk add --no-cache php83 \
    && if ! php -m | grep -q '^pcntl$'; then \
           apk add --no-cache php83-pcntl && echo 'extension=pcntl.so' > /usr/local/etc/php/conf.d/docker-php-ext-pcntl.ini; \
       fi \
    && if ! php -m | grep -q '^gd$'; then \
           apk add --no-cache php83-gd && echo 'extension=gd.so' > /usr/local/etc/php/conf.d/docker-php-ext-gd.ini; \
       fi

# Configure Composer to ignore required PHP extensions
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer global require hirak/prestissimo --no-plugins --no-scripts --ignore-platform-reqs
ENV PATH="${PATH}:/root/.composer/vendor/bin"

EXPOSE 8080
