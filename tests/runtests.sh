#!/bin/bash

# Disable NFS (otherwise the script will hang on the password prompt)
sed -i "s/:nfs => true/:nfs => false/" Vagrantfile

# Continue the script when any statement returns non-true value.
# We check for this manually
set +e

vagrant up

# Import database
./tests/database.sh
if [ $? -ne 0 ]; then
    echo "Database script failed"
    vagrant halt
    exit 1
fi

# Run tests
./tests/testsuite.sh
if [ $? -ne 0 ]; then
    echo "Test script failed"
    vagrant halt
    exit 1
fi

# Restore bash error checking:
set -e

vagrant halt
