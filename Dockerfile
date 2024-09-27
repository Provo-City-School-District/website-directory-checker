FROM php:8.2-cli

# Set working directory
WORKDIR /app

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    wget

# Copy PHP script to the container
COPY directory_check.php /app
COPY .env /app/.env

# COPY ckroot.crt /usr/local/share/ca-certificates/ckroot.crt
RUN wget -P /usr/local/share/ca-certificates/ "https://ckr01.provo.edu/ckroot/ckroot.crt"
RUN chmod 644 /usr/local/share/ca-certificates/ckroot.crt && update-ca-certificates

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo_mysql zip

# Install dotenv package
RUN composer require vlucas/phpdotenv

# Set SHELL to /bin/bash
SHELL ["/bin/bash", "-c"]

# Load environment variables from the .env file
CMD php -r "require_once '/app/vendor/autoload.php'; Dotenv\Dotenv::createImmutable('/app')->load(); include '/app/directory_check.php';"
# CMD ["php", "directory_check.php"]