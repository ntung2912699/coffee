# Sử dụng PHP với FPM
FROM php:8.2-fpm

# Cài đặt các extension cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    zip \
    git && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql pdo_pgsql

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Thiết lập thư mục làm việc
WORKDIR /var/www

# Sao chép mã nguồn vào container
COPY . .

# Cài đặt dependencies của Laravel
RUN composer install --no-dev --optimize-autoloader

# Sao chép và tạo file .env
RUN cp .env.example .env && php artisan key:generate && php artisan config:cache

# Chỉnh quyền cho các thư mục storage và bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose cổng 8080 để Render có thể kết nối
EXPOSE 8080

# Chạy lệnh migrate trong entrypoint
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080
