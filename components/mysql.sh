#!/bin/bash

debconf-set-selections <<< 'mysql-server mysql-server/root_password password happy123'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password happy123'
apt-get -y install mysql-server

#Configure:
printf "[mysqld]\nsql_mode=NO_AUTO_VALUE_ON_ZERO\n" >> /etc/mysql/conf.d/sql_mode.cnf
service mysql restart
