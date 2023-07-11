FROM php:7.4-apache
#Debian....
# Install Composer
COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install dependencies
#RUN docker-php-ext-enable php-zip
# RUN apt-get update && \
#     apt-get install vim git -y
# RUN docker-php-ext-install mysqli mysqlnd pdo pdo_mysql zip 

RUN apt-get update && apt-get install -y \
        libicu-dev \
        libbz2-dev \
        libjpeg-dev \
        libmemcached-dev \
        libpng-dev \
        libwebp-dev \ 
        libmcrypt-dev \
        libreadline-dev \
        libfreetype6-dev \
        zlib1g-dev \
        libxml2-dev \
        libz-dev \
        libssl-dev \
        libzip-dev \
        libonig-dev \
        libpq-dev \
        zip \
        curl \
        unzip \
        git \
        sudo \
        g++\
        vim \
        nano \
        supervisor \
        jpegoptim \ 
        optipng \
        pngquant \
        gifsicle \
        && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite
RUN rm -rf html && ln -s public html 
RUN COMPOSER_ALLOW_SUPERUSER=1 /usr/bin/composer install --ignore-platform-reqs && COMPOSER_ALLOW_SUPERUSER=1 /usr/bin/composer install-mdbootstrap


# Expose port 80
EXPOSE 80

# Start Apache web server
CMD ["apache2-foreground"]