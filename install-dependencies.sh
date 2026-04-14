#! /bin/bash

# Pastas padrões
mkdir -p yii-app/WebRoot/assets && chmod 777 yii-app/WebRoot/assets
mkdir -p yii-app/WebRoot/protected/runtime && chmod 777 yii-app/WebRoot/protected/runtime
mkdir -p yii-app/WebRoot/protected/migrations && chmod 777 yii-app/WebRoot/protected/migrations

# Composer e dependências
EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]
then
    >&2 echo 'ERROR: Invalid installer checksum'
    rm composer-setup.php
    exit 1
fi

php composer-setup.php
rm composer-setup.php
cd yii-app/YiiRoot
php ../../composer.phar install