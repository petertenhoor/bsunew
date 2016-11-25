#!/bin/bash

# This script is executed on the build server to run the automated tests.
# It is executed after a successful 'vagrant up'.

# Set the shared folder:
sharedFolder="/home/bob/Shared/"

# Set credentials:
dbuser="root"
dbpassword="happy123"

# The databasename must match with a file called [dbname].sql on the shared folder of the build server
dbname="db"

set -e

# DB Logic (only if database file exists):
if [ -e "$sharedFolder$dbname".sql ]; then
    # Drop existing database:
    echo "Drop existing database ($dbname)"
    vagrant ssh -c "echo \"DROP DATABASE IF EXISTS $dbname\" | mysql -u $dbuser -p$dbpassword"

    # Create test database:
    echo "Creating new database ($dbname)"
    vagrant ssh -c "echo \"CREATE DATABASE $dbname; GRANT ALL PRIVILEGES ON $dbname.* TO $dbuser@localhost IDENTIFIED BY '$dbpassword'\" | mysql -u $dbuser -p$dbpassword"

    # Import test database:
    echo "Importing test database ($sharedFolder$dbname.sql)"
    cp "$sharedFolder$dbname".sql "$WORKSPACE"/_tmp_db.sql
    vagrant ssh -c "mysql -u $dbuser -p$dbpassword $dbname < /vagrant/_tmp_db.sql"
    rm "$WORKSPACE"/_tmp_db.sql
    
else

    echo "No database file found in /home/bob/Shared/$dbname.sql"
    echo "Please make sure this file exists"

    exit 1;

fi;