# Add the username,
set( :use_sudo ){ true }

set :application    , 'PFVerdun'
set :user,          "ubuntu"
set :user_folder,   "/home/ubuntu"
set :group,         "www-data"
set :admin_runner,  "ubuntu"

set :domain,       user + '@dev.eis5.com'

server domain,     :app, :web, :db, :primary => true

set :repository,       'git@git.eis5.com:porte-fenetre-verdun/cuisine/site'
set :deploy_to,        '/home/www/verdun.dev.eis5.com/deploy'
#set :deploy_to,        '/home/www/cuisinesverdun.dev.eis5.com/httpdocs/'

set :symfony_env_prod, 'dev'
set :update_vendors     , false
set :dump_assetic_assets, false
set :branch             , 'dev-master-admingallery'
#set :branch             , 'dev-master'
# Shared files among releases
set :shared_files,    [ 'app/config/parameters.yml', 'app/config/config_dev.yml', 'app/config/config_prod.yml', 'web/.htaccess' ]

set :dump_assetic_assets, true
set :assets_symlinks, true

set :controllers_to_clear, []

# Automatic upload of these files when doing deploy setup
set :parameters_file, app_path + '/config/parameters_dev.yml'
set :htaccess_file,   app_path + '/config/.htaccess_dev'
set :config_file,     app_path + '/config/config_dev.yml'
set :writable_folders, [ "app/logs", 'app/cache', 'web/css', 'web/js', 'web/uploads']
set :writable_dirs, writable_folders

set :copy_vendors, true

default_run_options[:pty] = false
set :webserver_user,      "#{user}:#{group}"
set :permission_method,   :chown
set :use_set_permissions, true

set :keep_releases, 1

set :minify_user, 'www-data'
set :minify_assets, true

after 'deploy:update', 'permission:ubuntu'
#after 'permission:ubuntu', 'minify:assets'
after "deploy:finalize_update", 'robots_disallow'
