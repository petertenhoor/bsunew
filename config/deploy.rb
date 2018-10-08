server 'maseru.happy-online.nl', user: 'bsunew', roles: %w{web}

set :application, 'BSU_new'
set :repo_url, 'git@github.com:HappyOnline/bsunew.git'
set :keep_releases, 2

# Linked directories (WordPress):
set :linked_dirs,  %w{public/wp-content/uploads public/wp-content/themes/salient/lang public/wp-content/cache}