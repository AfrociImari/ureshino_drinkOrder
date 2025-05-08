# Use the official PHP image
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update \
    && apt-get install -y \
    git \
    zip \
    unzip \
    vim \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libfontconfig1 \
    libxrender1 \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd bcmath pdo_mysql mysqli exif

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
ENV NODE_VERSION=20.15.0
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"

# Set the working directory
WORKDIR /src

# Ensure that all project files, including composer.json, are copied into the container
COPY . /src/

# Ensure the correct permissions for the storage folder
RUN mkdir -p /src/storage && chown -R www-data:www-data /src/storage

# Install Node.js dependencies and compile assets for production
RUN npm install ----legacy-peer-deps

# Install PHP dependencies using Composer
RUN composer install --no-scripts --prefer-dist

# Set correct file permissions for all files in /src
RUN chown -R www-data:www-data /src

# Expose the correct port for your app (if needed)
EXPOSE 9000
