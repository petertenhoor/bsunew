set :deploy_to, '/home/bsunew/build-staging'
set :branch, 'build-staging'

task :deploy do
    on roles(:web), in: :sequence, wait: 5 do
        # Execute shell commands on the server:

        # Wordpress installation:
        execute "mv -f ~/build-staging/current/wp-config.php.staging ~/build-staging/current/public/wp-config.php"

        # set htaccess and htpasswd for staging environment
        execute "mv -f ~/build-staging/current/.htaccess.staging ~/build-staging/current/public/.htaccess"
        execute "mv -f ~/build-staging/current/.htpasswd ~/build-staging/current/public/.htpasswd"
    end
end