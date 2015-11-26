namespace :stats do
  task :pictures_by_dates do
    capifony_pretty_print "--> Count pictures created between dates!"
    #if ! :from
      set( :from ) { Capistrano::CLI.ui.ask( "From date :") }
    #end

    #if ! :to
      set( :to ) { Capistrano::CLI.ui.ask( "To date :") }
    #end
    run "touch --date " + from + " #{latest_release}/web/uploads/start"
    run "touch --date " + to + " #{latest_release}/web/uploads/end"
    run "find #{latest_release}/web/uploads/ -maxdepth 1 -type f -newer #{latest_release}/web/uploads/start -not -newer #{latest_release}/web/uploads/end | wc -l"
  end
end

namespace :composer do
  task :copy_vendors, :except => { :no_release => true }, :on_error => :continue do
    capifony_pretty_print "--> Copy vendor file from previous release - Five Top code"
    run "vendorDir=#{current_path}/vendor; if [ -d $vendorDir ] || [ -h $vendorDir ]; then cp -a $vendorDir #{latest_release}/vendor; fi;"
    #run "vendorDir=#{current_path}/vendor; if [ -d $vendorDir ] || [ -h $vendorDir ]; then cp -a $vendorDir #{latest_release}/vendor; fi;"
    capifony_puts_ok
  end
end

task :robots_disallow, :on_error => :continue do
  capifony_pretty_print "--> Robots.txt -> Disallow all indexing"
  try_sudo "chmod 755 #{latest_release}/web/robots.txt"
  try_sudo "echo "" > #{latest_release}/web/robots.txt"
  try_sudo "echo \"User-agent: *\" >> #{latest_release}/web/robots.txt"
  try_sudo "echo \"Disallow: /\" >> #{latest_release}/web/robots.txt"
  capifony_puts_ok
end

task :fix_centos_permission, :on_error => :continue do
  try_sudo "chmod 755 #{deploy_to}"
  try_sudo "chmod 755 #{deploy_to}shared"
  try_sudo "chmod 755 #{latest_release}/web"
  try_sudo "chmod 644 #{deploy_to}current/web/app_dev.php"
  try_sudo "chmod 644 #{deploy_to}current/web/app.php"
  try_sudo "chmod 644 #{deploy_to}current/web/.htaccess"
  try_sudo "chmod 775 #{deploy_to}current/web/uploads"
  
  try_sudo "chmod 755 #{deploy_to}current"
  try_sudo "chmod 755 #{deploy_to}releases"
  try_sudo "chmod 755 #{latest_release}"
end

task :upload_parameters do
  origin_file = parameters_file if parameters_file
  if origin_file && File.exists?(origin_file)
    ext = File.extname(parameters_file)
    relative_path = "app/config/parameters" + ext

    if shared_files && shared_files.include?(relative_path)
      destination_file = shared_path + "/" + relative_path
    else
      destination_file = latest_release + "/" + relative_path
    end
    try_sudo "mkdir -p #{File.dirname(destination_file)}"

    top.upload(origin_file, destination_file)
  end
end

task :upload_htaccess do
  origin_file = htaccess_file if htaccess_file
  if origin_file && File.exists?(origin_file)
    ext = File.extname(parameters_file)
    relative_path = "web/.htaccess"

    if shared_files && shared_files.include?(relative_path)
      destination_file = shared_path + "/" + relative_path
    else
      destination_file = latest_release + "/" + relative_path
    end
    try_sudo "mkdir -p #{File.dirname(destination_file)}"

    top.upload(origin_file, destination_file)
  end
end

task :upload_config do
  origin_file = config_file if config_file
  if origin_file && File.exists?(origin_file)
    ext = File.extname(parameters_file)
    relative_path = origin_file

    if shared_files && shared_files.include?(relative_path)
      destination_file = shared_path + "/" + relative_path
    else
      destination_file = latest_release + "/" + relative_path
    end
    try_sudo "mkdir -p #{File.dirname(destination_file)}"

    try_sudo top.upload(origin_file, destination_file)
  end
end

namespace :permission do
  desc "Reset Permission"
  task :ubuntu, :on_error => :continue do

    capifony_pretty_print "---> Reset Permission (ubuntu:symfony) - FIVE"

    # Set general permission
    # On all releases : this fix permission problem on deploy:cleanup
      run "sudo chown -R #{user}:#{group} #{releases_path}/"
      run "sudo chown -R #{user}:#{group} #{shared_path}/"
      run "sudo chmod -R g+w #{shared_path}/app/logs"
      run "sudo chmod u+s,g+s #{shared_path}/app/logs"
      run "sudo find #{shared_path}/app/logs -type d -exec chmod u+s,g+s '{}' \\;"

    # Set permission on latest release
    run "sudo find #{latest_release}/ -type f -exec chmod 644 '{}' \\; && find #{latest_release}/ -type d -exec chmod 755 '{}' \\;"

    writable_folders.each do |folder|
      run "sudo chmod -R g+w #{latest_release}/#{folder}"
      run "sudo chmod u+s,g+s #{latest_release}/#{folder}"
      run "sudo find #{latest_release}/#{folder} -type d -exec chmod u+s,g+s '{}' \\;"
    end

      run "sudo chmod -R g+w #{shared_path}/web/uploads"
      run "sudo chmod u+s,g+s #{shared_path}//web/uploads"      

    capifony_puts_ok
  end
  task :iprod, :on_error => :continue do
    run "chmod 755 #{deploy_to}"
    run "chmod 755 #{deploy_to}shared"
    run "chmod 755 #{latest_release}/web"
   # try_sudo "chmod 644 #{latest_release}/web/app_dev.php"
    run "chmod 644 #{latest_release}/web/app.php"
    run "chmod 755 #{deploy_to}current"
    run "chmod 755 #{deploy_to}releases"
    run "chmod 755 #{latest_release}"
    run "chmod -R g-w #{deploy_to}"
    run "chmod -R 770 #{latest_release}/app/logs"
    run "chmod -R 770 #{latest_release}/app/cache"

  end
end

namespace :minify do
  desc "Reset Permission"
  task :assets, :on_error => :continue do
    if minify_assets
      capifony_pretty_print "---> Minifying Assets (minify:assets) - FIVE"
      run "sudo -u #{minify_user} php #{latest_release}/app/console assetic:dump"
      capifony_puts_ok
    end
  end
end

namespace :symfony do
  namespace :project do
    desc "Clears all non production environment controllers"
    task :clear_controllers do
      capifony_pretty_print "--> Clear controllers -- nope"

      #try_sudo "for file in #{latest_release}/web/*.php; do grep -HLP \"new AppKernel\\\('prod'\" $file | xargs -I {} rm -f {}; done;"
      capifony_puts_ok
    end
  end
end