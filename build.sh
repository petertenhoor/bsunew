#!/bin/bash

set -e

# This is the default build script used by Happy Online for their deployment protocol.

if [ ! $1 ] && [ ! $2 ]; then
    echo "Usage: ./build.sh (branch) (test)"
    echo "  branch : The Git branch to check (master or staging)"
    echo "  test   : Whether or not to run tests (1 or 0)"
    exit 1;
fi

# Check whether or not this script is run on Jenkins
if [ ! $WORKSPACE ]; then
    echo "This script is only allowed to run on the buildserver"
    exit 1;
fi

echo "Starting build ..."

# Checkout build-staging
git checkout build-"$1" || git checkout -b build-"$1"

# Remove everything except the .git and .vagrant-folders
ls -a | grep -v '\(^.git$\)\|\(^.vagrant$\)\|\(^.$\)\|\(^..$\)' | xargs rm -rf

# Reset this branch to origin/staging, making it equal to the branch
git reset --hard origin/"$1"

# If there is a package.json, run an npm install
if [ -e package.json ]; then npm install; fi

# If there is a Gruntfile found, execute a 'grunt build'
if [ -e Gruntfile.js ]; then grunt build; fi

# If there is a Gulpfile found, execute a 'gulp build'
if [ -e gulpfile.js ]; then gulp build; fi

# Run the obvious tests
node /home/bob/obvious-tests/obvious_tests.js "$WORKSPACE/public"

# Check if there needs to bee tested
if [ $2 = "1" ]; then
    ./tests/runtests.sh
fi
