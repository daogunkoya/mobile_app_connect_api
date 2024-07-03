role :app, %w{deploy@stage81.dell.aws}

role :primary, %w{deploy@stage81.dell.aws}

set :deploy_to, '/var/www/moneyapp'

set :stage, 'stage'

set :branch, ENV["CI_COMMIT_REF_NAME"]

set :local_user, ENV["CI_COMMIT_AUTHOR"]