# config valid only for current version of Capistrano
#lock '3.4.0'

set :application, 'moneyapp'
set :repo_url, 'git@gitlab.com:moneyapp3/moneyapp.git'

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/moneyapp
set :deploy_to, '/var/www/moneyapp'

set :format_options, command_output: true, log_file: 'storage/logs/capistrano.log', color: :auto, truncate: :auto

# Default value for :log_level is :debug
set :log_level, :debug

# Default value for :pty is false
set :pty, true

# Default value for :linked_files is []
set :linked_files, fetch(:linked_files, []).push('.env')

# Default value for linked_dirs is []
set :linked_dirs, fetch(:linked_dirs, []).push('storage')

namespace :deploy do
    desc "Build"
    after :updated, :build do
        on roles(:app) do
            within release_path do
                execute :composer, "install --no-dev --no-interaction --quiet --optimize-autoloader --apcu-autoloader" # install dependencies
                execute :chmod, "u+x artisan" # make artisan executable
                execute :php, "artisan migrate -n --force"
            end
        end
        run_locally do
            # Access environment variables here
            pusher_app_key = fetch(:PUSHER_APP_KEY)
            execute "npm ci --silent"
            execute "NODE_OPTIONS=--openssl-legacy-provider PUSHER_APP_KEY=#{pusher_app_key} MIX_PUSHER_APP_KEY=#{pusher_app_key} npm run prod --silent"
            roles(:app).each do |host|
                execute :rsync, "-r ./public/ #{host.user}@#{host.hostname}:#{release_path}/public"
            end
        end
    end

    desc "Finish"
    after :finished, :queueRestart do
        on roles(:app) do
            within release_path do
                execute :php, "artisan queue:restart"
            end
        end
    end
end
