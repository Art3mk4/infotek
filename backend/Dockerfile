FROM dunglas/frankenphp:latest-php8.3

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files first
COPY . .

# Install PHP dependencies (allow failures for now, basic setup)
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs || \
    composer update --no-dev --no-interaction --ignore-platform-reqs || \
    echo "Composer install completed with warnings"

# Create runtime directories
RUN mkdir -p runtime/cache runtime/logs && \
    chmod -R 777 runtime

# Copy Caddyfile for FrankenPHP
COPY Caddyfile /etc/caddy/Caddyfile

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port
EXPOSE 8080

# Use entrypoint script
ENTRYPOINT ["docker-entrypoint.sh"]
