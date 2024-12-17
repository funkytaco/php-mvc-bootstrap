To enable PHP in Apache add the following to httpd.conf and restart Apache:
    LoadModule php_module /opt/homebrew/opt/php@8.2/lib/httpd/modules/libphp.so

    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>

Finally, check DirectoryIndex includes index.php
    DirectoryIndex index.php index.html

The php.ini and php-fpm.ini file can be found in:
    /opt/homebrew/etc/php/8.2/

php@8.2 is keg-only, which means it was not symlinked into /opt/homebrew,
because this is an alternate version of another formula.

If you need to have php@8.2 first in your PATH, run:
  echo 'export PATH="/opt/homebrew/opt/php@8.2/bin:$PATH"' >> /Users/neo/.zshrc
  echo 'export PATH="/opt/homebrew/opt/php@8.2/sbin:$PATH"' >> /Users/neo/.zshrc

For compilers to find php@8.2 you may need to set:
  export LDFLAGS="-L/opt/homebrew/opt/php@8.2/lib"
  export CPPFLAGS="-I/opt/homebrew/opt/php@8.2/include"

To start php@8.2 now and restart at login:
  brew services start php@8.2
Or, if you don't want/need a background service you can just run:
  /opt/homebrew/opt/php@8.2/sbin/php-fpm --nodaemonize
==> Summary
🍺  /opt/homebrew/Cellar/php@8.2/8.2.26: 521 files, 83.7MB
==> Running `brew cleanup php@8.2`...
Disable this behaviour by setting HOMEBREW_NO_INSTALL_CLEANUP.
Hide these hints with HOMEBREW_NO_ENV_HINTS (see `man brew`).
