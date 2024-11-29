You must use PHP7 for local development currently.

# Uninstall current PHP
brew uninstall php

# Install PHP 7.4
brew install php@7.4

#Unlink PHP
brew unlink php

# Link PHP 7.4
brew link php@7.4 --force

# Verify PHP version
php -v

#Run your local dev server on port 300
composer serve
`> Tasks\ApplicationTasks::startDevelopmentWebServer
Starting PHP Development Server...
Host: localhost
Port: 3000`

#Development workflow
Local development is done in app/ folder. This is NOT the same path used in the container environment.
NOTE: Your changes are not saved until you use `composer commit`