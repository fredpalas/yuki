#!/bin/bash
# if RUN_MIGRATION exist and si true, then run the migration
if [[ -n "${RUN_MIGRATION}" ]] && [[ $RUN_MIGRATION == "true" ]]; then
  echo "RUN_MIGRATION is set"
  cd /var/www && /usr/local/bin/php bin/console doctrine:migrations:migrate --no-interaction
  cd /var/www && /usr/local/bin/php bin/console app:user-type:check
fi
if [[ -n "${RUN_CACHE}" ]] && [[ $RUN_CACHE == "true" ]]; then
  echo "RUN_CACHE is set"
  cd /var/www && /usr/local/bin/php bin/console cache:warmup
fi
if [[ -n "${RUN_COMPOSER}" ]] && [[ $RUN_COMPOSER == "true" ]]; then
  echo "RUN_COMPOSER is set"
  cd /var/www && composer dump-autoload --no-dev --classmap-authoritative
fi

php-fpm

