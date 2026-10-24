# PHP 8.2 + Apache image for the Food Order app.
# The app is plain PHP (mysqli only), served from the Apache document root.
FROM php:8.2-apache

# mysqli is used by config/constants.php and every page that queries the DB.
RUN docker-php-ext-install mysqli

# Avoid Apache's "Could not reliably determine the server's fully qualified
# domain name" warning and enable rewrite rules for future use.
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite

# PHP runtime settings for a small app (upload limits, logging to stderr).
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini

# Deny script execution under the uploads directory (images are still served).
COPY docker/apache-images.conf /etc/apache2/conf-available/zz-images.conf
RUN a2enconf zz-images.conf

# Entrypoint wrapper that fixes image upload permissions, then hands over to
# the official docker-php-entrypoint / CMD.
COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint.sh
RUN chmod +x /usr/local/bin/app-entrypoint.sh

# Application code (see .dockerignore for what is excluded).
COPY . /var/www/html

# The docker/ folder only exists to build the image; keep it out of the web root.
RUN rm -rf /var/www/html/docker

# Uploads go to images/food and images/category via move_uploaded_file().
RUN mkdir -p /var/www/html/images/food /var/www/html/images/category \
    && chown -R www-data:www-data /var/www/html/images \
    && chmod -R u+rwX,g+rwX /var/www/html/images

EXPOSE 80

ENTRYPOINT ["docker-php-entrypoint", "/usr/local/bin/app-entrypoint.sh"]
CMD ["apache2-foreground"]
