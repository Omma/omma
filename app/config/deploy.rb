set :application, "Omma"
set :domain,      "symfony.ownbox.eu"
set :deploy_to,   "/var/www/omma"
set :app_path,    "app"
set :user,        "deploy"

set :repository,  "ssh://git@git.ownbox.eu/praktikum-symfony/praktikum-web.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set   :use_composer,     true

after 'deploy:create_symlink', 'symfony:doctrine:schema:update'

set :deploy_via, :remote_cache

set :shared_files,        ["app/config/parameters.yml", "app/config/ldap.yml"]
set :shared_children,     [app_path + "/spool", app_path + "/logs"]
set :composer_options,    "--no-dev --verbose --prefer-dist --optimize-autoloader"
set :assets_install,      true
set :dump_assetic_assets, true

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This may be the same as your `Web` server

set  :keep_releases,  3

# set permissions
set  :use_sudo,      false
set :writable_dirs,       ["app/cache", "app/logs", "app/spool"]
set :webserver_user,      "www-data"
set :permission_method,   :acl
set :use_set_permissions, true


before 'symfony:composer:install', 'composer:copy_vendors'
before 'symfony:composer:update', 'composer:copy_vendors'

namespace :composer do
  task :copy_vendors, :except => { :no_release => true } do
    capifony_pretty_print "--> Copy vendor file from previous release"

    run "vendorDir=#{current_path}/vendor; if [ -d $vendorDir ] || [ -h $vendorDir ]; then cp -a $vendorDir #{latest_release}/vendor; fi;"
    capifony_puts_ok
  end
end

namespace :deploy do
  task :restart, :except => { :no_release => true } do
    run "#{sudo} /usr/sbin/service php5-fpm reload"
  end
end

before 'symfony:assetic:dump', 'npm:install'
after 'npm:install', 'bower:install'
after 'npm:install', 'grunt:build'

namespace :npm do
 desc 'Run npm install'
 task :install do
   capifony_pretty_print "--> Installing npm components"
   invoke_command "sh -c 'cd #{latest_release} && npm install'"
   capifony_puts_ok
 end
end

namespace :bower do
    desc 'Run bower install'
    task :install do
      capifony_pretty_print "--> Installing bower components"
      invoke_command "sh -c 'cd #{latest_release} && bower install'"
      capifony_puts_ok
    end
end

namespace :grunt do
    desc 'Run grunt build'
    task :build do
      capifony_pretty_print "--> Grunt"
      invoke_command "sh -c 'cd #{latest_release} && grunt build'"
      capifony_puts_ok
    end
end

# autocleanup
after "deploy:update", "deploy:cleanup"

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL
