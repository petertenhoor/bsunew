#!/bin/bash

# This script is executed between a 'vagrant up' and 'vagrant halt' from the host system.

# Make sure the shell scripts fails as soon as a single test fails:
set -e

# Set the path to this script:
SELF_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# If you want to import a complete database before each testrun, you can use this:
# vagrant ssh -c "mysql -u (username) -p(password) (dbname) < "$SELF_DIR"/db.sql"

# CasperJS Example:
# Note that for a CasperJS to work on the build server you might have to edit the /etc/hosts-file on the buildserver
# casperjs test "$SELF_DIR"/casperjs/mytest.js 2> /dev/null

# PHPUnit Example (needs to be executed within Vagrant box):
# vagrant ssh -c "phpunit --bootstrap /vagrant/public/File.php /vagrant/tests/phpunit/FileTest"
