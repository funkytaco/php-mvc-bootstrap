# PHP 8.2 Setup for Local Development

This guide explains how to set up the PHP MVC Framework for local development with PHP 8.2 on macOS.

## Prerequisites

1. Homebrew (Package Manager for macOS)
2. Composer (PHP Package Manager)

## Installation Steps

1. Install PHP 8.2 via Homebrew:
```bash
brew install php@8.2
```

2. Start PHP 8.2 service:
```bash
brew services start php@8.2
```

3. Configure PHP 8.2 in Apache (if using):
Add to httpd.conf:
```apache
LoadModule php_module /opt/homebrew/opt/php@8.2/lib/httpd/modules/libphp.so

<FilesMatch \.php$>
    SetHandler application/x-httpd-php
</FilesMatch>

# Ensure DirectoryIndex includes index.php
DirectoryIndex index.php index.html
```

4. PHP Configuration Files:
- php.ini location: `/opt/homebrew/etc/php/8.2/php.ini`
- php-fpm.ini location: `/opt/homebrew/etc/php/8.2/php-fpm.ini`

5. Update PATH (add to ~/.zshrc):
```bash
export PATH="/opt/homebrew/opt/php@8.2/bin:$PATH"
export PATH="/opt/homebrew/opt/php@8.2/sbin:$PATH"
```

## Local Development

1. Start the development server:
```bash
composer serve
```
This will start the server at http://localhost:3000

2. The application includes PHP 8.2 compatibility patches in:
- src/PHP8Compatibility.php
- src/Bootstrap.php

## Troubleshooting

If you encounter any issues:

1. Verify PHP version:
```bash
php -v
```

2. Check PHP modules:
```bash
php -m
```

3. Common Issues:
- If you see return type compatibility warnings, the PHP8Compatibility layer should handle these
- For PDO issues, ensure the PDO extensions are enabled in php.ini
- For permission issues, check that the web server has access to the project directory

## Development Workflow

1. The application uses Composer for dependency management:
```bash
# Update dependencies
composer update

# Install dependencies
composer install
```

2. Available Composer scripts:
```bash
# Start development server
composer serve

# Install MVC template
composer install-mvc

# Install Semantic UI template
composer install-semanticui

# Commit changes to installer directory
composer commit
```

## Notes

- The framework has been updated to support PHP 8.2 while maintaining backward compatibility
- Klein router has been patched to work with PHP 8.2 return types
- Asset handling remains unchanged, using the existing mime types configuration
