#!/bin/sh

# Alternate way of initialisation, this is because of a bug in the current version of
# Vagrant / VirtualBox where a symlink of the guest additions must be made prior to
# the instatiation of the chef_solo provisioner
# @see: https://github.com/mitchellh/vagrant/issues/3341

echo "Happy Online Workflow initialisatiescript v1.0"
echo "----------------------------------------------"
echo "Projectnaam (geen spaties):"
read HO_PROJECT

echo "Git repository URL (bijv: git@github.com:HappyOnline/{projectnaam}.git):"
read HO_REPO

echo "SSH Server (bv. oslo.happy-online.nl):"
read HO_SERVER

echo "SSH Gebruiker:"
read HO_USER

echo "----------------------------------------------"

# Find and replace
sed -i '' 's|HO_PROJECT|'"$HO_PROJECT"'|g' config/deploy.rb
sed -i '' 's|HO_REPO|'"$HO_REPO"'|g' config/deploy.rb
sed -i '' 's|HO_SERVER|'"$HO_SERVER"'|g' config/deploy.rb
sed -i '' 's|HO_USER|'"$HO_USER"'|g' config/deploy.rb
sed -i '' 's|HO_USER|'"$HO_USER"'|g' config/deploy/*.rb

# Create pubic dir
mkdir -p public
