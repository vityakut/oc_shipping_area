#!/bin/sh

until nc -z -v -w30 mysql 3306
do
  echo "Waiting for database connection..."
  sleep 5
done

make -C /oc_shipping_area run

mkdir -p /oc_shipping_area/www/system/storage/session

sed -i 's/\?>//g' /oc_shipping_area/www/config.php
sed -i 's/\?>//g' /oc_shipping_area/www/admin/config.php
echo '\nini_set("session.save_path", DIR_SYSTEM . "storage/session");' >> /oc_shipping_area/www/config.php
echo '\nini_set("session.save_path", DIR_SYSTEM . "storage/session");' >> /oc_shipping_area/www/admin/config.php

#chown -R www-data:www-data /oc_shipping_area \
#    && find /oc_shipping_area -type d -exec chmod 755 {} \; \
#    && find /oc_shipping_area -type f -exec chmod 644 {} \;

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- apache2-foreground "$@"
fi

exec "$@"
