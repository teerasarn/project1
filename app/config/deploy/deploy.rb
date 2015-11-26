# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

set :stages,        %w(prod testprod staging dev)
set :default_stage, 'staging'
set :stage_dir,     'app/config/deploy'

require 'capistrano/ext/multistage'

set :application,      'poptimize'
set :symfony_env_prod, 'dev'

# Symfony location from GIT root folder
# Makre sure to add trailing / if you add somehting
set :symfony_basedir, ''
set :app_path,        'app'
set :web_path,        'web'
set :cache_path,      'app/cache'
#set :symfony_console, '/app/console'

# To make sure ssh gets the right user, add username in front
# E.g spalepin@dev.eis5.com
set :domain, ''

# Server deploy path
# E.g /home/www/fb.dev.eis5.com/tamtam/nissan/mosaique
set :deploy_to,   ''

# GIT + COPY SETUP
# Branch name to deploy
set :branch, 'dev-master'
set :git_enable_submodules, 1
# Your git repo
# E.g. git@git.eis5.com:/tamtam/nissan/mosaique.git
set :repository,  ''
set :scm,         :git
set :scm_verbose, true
set :deploy_via,  :rsync_with_remote_cache

set :model_manager, 'doctrine'

set :keep_releases,   3
set :shared_files,    [ 'app/config/parameters.yml', 'app/config/config_dev.yml', 'web/.htaccess', 'app/AppKernel.php' ]
set :shared_children, [ 'app/logs', 'web/js', 'web/css', 'web/uploads' ]

set :use_composer       , true
# after first deployment you might want to change this to false. Setting to true will always install vendors each time
set :update_vendors     , false
set :dump_assetic_assets, true

set :use_sudo    , false
# Usually s/b same user as your current user
set :user        , ENV[ 'USER' ]
set :group       , 'www-data'
# Usually s/b same user as your current user
set :admin_runner, ENV[ 'USER' ]

ssh_options[ :forward_agent ] = true
ssh_options[ :keys ]          = [ File.join( ENV[ 'HOME' ], '.ssh', 'id_rsa' ) ]

set :ftp_git_exclude_paths, [ 'app', 'bin', 'vendor', 'web/uploads', 'composer.phar', 'REVISION', 'web/bundles' ]

# Make sure to copy the vendors before doing an update or new release
#before 'symfony:composer:install', 'composer:copy_vendors'
#before 'symfony:composer:update',  'composer:copy_vendors'

# Automatically upload parameters, htaccess, config files
after 'deploy:setup', 'upload_parameters'
after 'deploy:setup', 'upload_htaccess'
after 'deploy:setup', 'upload_config'

# Removes old releases
after 'deploy', 'deploy:cleanup'