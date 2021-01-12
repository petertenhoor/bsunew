server 'aruba.happyserver.nl', user: 'bsu', roles: %w{web}

set :application, 'bsu'
set :repo_url, 'git@github.com:HappyOnline/bsu-old.git'
set :keep_releases, 2

# Linked directories (WordPress):
set :linked_dirs,  %w{public/wp-content/uploads public/wp-content/themes/salient/lang public/wp-content/cache}