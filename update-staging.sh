#!/bin/sh

# Variables:
SSH_ADDRESS="root@?.happy-online.nl"

LOCAL_USER="root"
LOCAL_PASSWORD="happy123"
LOCAL_DB=""

STAGING_USER=""
STAGING_PASSWORD=""
STAGING_DB=""

PRODUCTION_USER=""
PRODUCTION_PASSWORD=""
PRODUCTION_DB=""

echo "Update script of manage databases for development"
echo "----"

if [ ! $1 ]; then
    echo "Usage:"
    echo "./update-staging.sh update-staging    : Dump local database to staging (creates backup of staging)"
    echo "./update-staging.sh restore-staging   : Restore staging with created backup"
    echo "./update-staging.sh update-local      : Dump production database tot local (creates backup of local)"
    exit 1;
fi

set -e

if [ "$1" == "restore-staging" ]; then

    echo "Empty remote database"
    TABLES=$(ssh "$SSH_ADDRESS" 'mysql -u '"$STAGING_USER"' -p'"$STAGING_PASSWORD"' '"$STAGING_DB"' -e "show tables;"' | awk '{ print $1}' | grep -v '^Tables')

    for t in $TABLES
    do
        echo "Dropping $t table from remote database..."
        ssh "$SSH_ADDRESS" 'mysql -u '"$STAGING_USER"' -p'"$STAGING_PASSWORD"' '"$STAGING_DB"' -e "SET FOREIGN_KEY_CHECKS=0; drop table '"$t"'; SET FOREIGN_KEY_CHECKS=1;"'
    done

    echo "Import local backed up database to remote"
    ssh "$SSH_ADDRESS" mysql -u "$STAGING_USER" -p"$STAGING_PASSWORD" "$STAGING_DB" < staging_db_backup.sql

elif [ "$1" == "update-staging" ]; then

    echo "Create dump of local database"
    vagrant ssh -c "mysqldump -Q -u $LOCAL_USER -p$LOCAL_PASSWORD $LOCAL_DB > dbdump.sql && mv dbdump.sql /vagrant"

    echo "Add rule at the beginning of line:"
    sed -i '' "1i\ 
    SET sql_mode='NO_AUTO_VALUE_ON_ZERO';
    " dbdump.sql

    echo "Find and replace stuff"
    sed 's/www.bestdeal.nl/bestdeal.happy-online.nl/g' dbdump.sql > dbdump.sql.new

    echo "Create backup of remote database"
    ssh "$SSH_ADDRESS" mysqldump -Q -u "$STAGING_USER" -p"$STAGING_PASSWORD" "$STAGING_DB" > staging_db_backup.sql

    echo "Empty remote database"
    TABLES=$(ssh "$SSH_ADDRESS" 'mysql -u '"$STAGING_USER"' -p'"$STAGING_PASSWORD"' '"$STAGING_DB"' -e "show tables;"' | awk '{ print $1}' | grep -v '^Tables')

    DROP_QUERY="SET FOREIGN_KEY_CHECKS=0;"
    for t in $TABLES
    do
        TABLE_NAME=$(echo "$t" | tr -d '\r')
        DROP_QUERY="$DROP_QUERY DROP TABLE \`$TABLE_NAME\`;"
    done
    DROP_QUERY="$DROP_QUERY SET FOREIGN_KEY_CHECKS=1;"
    ssh "$SSH_ADDRESS" 'mysql -u '"$STAGING_USER"' -p'"$STAGING_PASSWORD"' '"$STAGING_DB"' -e "'"$DROP_QUERY"'"'
    
    echo "Import local database to remote"
    ssh "$SSH_ADDRESS" mysql -u "$STAGING_USER" -p"$STAGING_PASSWORD" "$STAGING_DB" < dbdump.sql.new

elif [ "$1" == "update-local" ]; then

    echo "Create dump of local database"
    vagrant ssh -c "mysqldump -Q -u $LOCAL_USER -p$LOCAL_PASSWORD $LOCAL_DB > dbdump.local.sql && mv dbdump.local.sql /vagrant"

    echo "Empty local database"
    set +e

    TABLES=$(vagrant ssh -c "mysql -u $LOCAL_USER -p$LOCAL_PASSWORD $LOCAL_DB -e 'show tables;' | awk '{ print \$1}' | grep -v '^Tables'")
    
    DROP_QUERY="SET FOREIGN_KEY_CHECKS=0;"
    
    for t in $TABLES
    do
        TABLE_NAME=$(echo "$t" | tr -d '\r')
        DROP_QUERY="$DROP_QUERY DROP TABLE \`$TABLE_NAME\`;"
        # echo "Dropping $TABLE_NAME table from local database..."
    done
    
    DROP_QUERY="$DROP_QUERY SET FOREIGN_KEY_CHECKS=1;"
    vagrant ssh -c "mysql -u $LOCAL_USER -p$LOCAL_PASSWORD $LOCAL_DB -e '$DROP_QUERY'"
    
    set -e
    
    echo "Fetch production database"
    ssh "$SSH_ADDRESS" mysqldump -Q -u "$PRODUCTION_USER" -p"$PRODUCTION_PASSWORD" "$PRODUCTION_DB" > production_db.sql
    
    echo "Add rule at the beginning of line:"
    sed -i '' "1i\ 
    SET sql_mode='NO_AUTO_VALUE_ON_ZERO';
    " production_db.sql

    #echo "Find and replace stuff"
    #sed 's/www.bestdeal.nl/bestdeal.happy-online.nl/g' dbdump.sql > dbdump.sql.new
    
    echo "Import database into local machine"
    vagrant ssh -c "mysql -u $LOCAL_USER -p$LOCAL_PASSWORD $LOCAL_DB < /vagrant/production_db.sql"
        
fi
