set :deploy_to, '/home/bsunew/build-master'
set :branch, 'build-master'

task :deploy do
    on roles(:web), in: :sequence, wait: 5 do
        # Execute shell commands on the server:

        # Wordpress installation:
        execute "mv -f ~/build-master/current/wp-config.php.master ~/build-master/current/public/wp-config.php"

        # set htaccess for master environment
        execute "mv -f ~/build-master/current/.htaccess.master ~/build-master/current/public/.htaccess"
    end
end