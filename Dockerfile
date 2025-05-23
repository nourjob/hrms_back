FROM php:8.2-fpm

# إعداد التحديث وتثبيت الحزم الأساسية
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    libxml2-dev \
    default-mysql-client && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# تثبيت امتدادات PHP المطلوبة
RUN docker-php-ext-install mbstring zip pdo_mysql bcmath exif pcntl

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# مجلد العمل
WORKDIR /var/www

# نسخ ملفات Laravel
COPY . .

# تثبيت الاعتمادات
RUN composer install --no-dev --optimize-autoloader

# صلاحيات
RUN chown -R www-data:www-data /var/www

# بدء Laravel: تنظيف الكاش، التهجير، تشغيل Seeder، وتشغيل السيرفر
CMD php artisan config:clear && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=10000
