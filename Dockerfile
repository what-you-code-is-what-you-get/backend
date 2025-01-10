# Install site and everything
FROM composer as vendor

COPY composer.json composer.json
COPY composer.lock composer.lock
COPY config/ config/
COPY web/ web/

# Install only required packages (no dev-require)
RUN composer install     --ignore-platform-reqs     --no-interaction     --no-dev     --prefer-dist

# Rename fleet settings file to local settings file
ADD web/sites/default/settings.fleet.php web/sites/default/settings.local.php

FROM webdevops/php-nginx:8.3

LABEL org.opencontainers.image.source https://github.com/Dre90/wyciwyg-drupal-backend

# Copy precompiled codebase into the container.
COPY --from=vendor /app/ /var/www/html/

RUN echo "0 */1 * * * root /var/www/html/vendor/bin/drush cron --root=/var/www/html/web" > /etc/cron.d/drupal-cron
