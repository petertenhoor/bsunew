#!/bin/bash

# Post-build tasks

set -e

if [ ! $1 ]; then
    echo "Usage: ./postbuild.sh (branch)"
    echo "  branch : The Git branch to deploy (master or staging)"
    exit 1;
fi

# Check whether or not this script is run on Jenkins
if [ ! $WORKSPACE ]; then
    echo "This script is only allowed to run on the buildserver"
    exit 1;
fi

echo "Starting post-build ..."

# Add all changes
git add --all

# Commit all changes (catch errors to allow for empty commits)
git commit -am 'Jenkins build' || echo 'Commit failed. There is probably nothing to commit.'

# Push it all
git push origin build-"$1" --force

# Capistrano deployment:
cap build-"$1" deploy