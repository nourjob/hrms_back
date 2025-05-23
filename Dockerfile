FROM php:8.2-fpm

# تثبيت الاعتمادات الأساسية
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    mysql-client

# تثبيت ملحقات PHP المطلوبة للـ Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# إعداد مجلد العمل
WORKDIR /var/www

# نسخ ملفات المشروع
COPY . .

# تثبيت الحزم عبر Composer
RUN composer install --no-dev --optimize-autoloader

# صلاحيات الملفات
RUN chown -R www-data:www-data /var/www

# أمر التشغيل
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
