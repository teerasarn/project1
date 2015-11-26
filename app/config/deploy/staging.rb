# Add the username,
set( :use_sudo ){ false }

set :application    , 'PFVerdun'
set :user           , "pfverdun"
set :group          , "pfverdun"
set :admin_runner   , "pfverdun"
# set :password       , "*tp$[U_WPyn("

set :domain,        user + '@67.205.103.217'

server domain,     :app, :web, :db, :primary => true

set :repository         , 'git@git.eis5.com:porte-fenetre-verdun/cuisine/site'
set :deploy_to          , '/home/pfverdun/portesetfenetresverdun.staging.eis5.com/deploy/'
set :symfony_env_prod   , 'prod'
set :update_vendors     , false
set :copy_vendors       , false
set :branch             , 'dev-master'

# Shared files among releases
set :shared_files       , [ 'app/config/parameters.yml', 'app/config/config_prod.yml', 'web/.htaccess' ]
set :dump_assetic_assets, true
set :assets_symlinks    , true

# Automatic upload of these files when doing deploy setup
set :parameters_file, app_path + '/config/parameters_prod.yml'
set :htaccess_file,   app_path + '/config/.htaccess_prod'
set :config_file,     app_path + '/config/config_prod.yml'
set :writable_folders, [ "app/logs", 'app/cache']
set :writable_dirs, writable_folders


default_run_options[:pty] = true
set :webserver_user,      "#{user}"
set :permission_method,   :acl
set :use_set_permissions, false

set :keep_releases, 1
after 'deploy:update', 'permission:iprod'
after "deploy:finalize_update", 'robots_disallow'