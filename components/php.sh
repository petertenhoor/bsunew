#!/bin/bash

#Php and Drivers 
apt-get install -y php5 libapache2-mod-php5 php5-intl php-apc php5-gd php5-curl php5-mysql php5-mcrypt php5-xsl php5-imagick

#Mcryp requires manual enabling:
php5enmod mcrypt

#Php Configuration
sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 1000M/" /etc/php5/apache2/php.ini
sed -i "s/post_max_size = 8M/post_max_size = 1000M/" /etc/php5/apache2/php.ini
sed -i "s/short_open_tag = On/short_open_tag = Off/" /etc/php5/apache2/php.ini
sed -i "s/;date.timezone =/date.timezone = Europe\/Amsterdam/" /etc/php5/apache2/php.ini
sed -i "s/memory_limit = 128M/memory_limit = 1024M/" /etc/php5/apache2/php.ini
sed -i "s/_errors = Off/_errors = On/" /etc/php5/apache2/php.ini
sed -i "s/;max_input_vars = 1000/max_input_vars = 10000/" /etc/php5/apache2/php.ini

#Phpunit
wget https://phar.phpunit.de/phpunit.phar
chmod +x phpunit.phar
mv phpunit.phar /usr/local/bin/phpunit
