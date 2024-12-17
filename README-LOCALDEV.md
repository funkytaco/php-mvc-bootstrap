# Local Development Setup

## macOS Setup

### PHP 8.2 (Recommended)
```bash
# Install PHP 8.2
brew install php@8.2

# Start PHP 8.2 service
brew services start php@8.2

# Add PHP 8.2 to your PATH (add to ~/.zshrc)
export PATH="/opt/homebrew/opt/php@8.2/bin:$PATH"
export PATH="/opt/homebrew/opt/php@8.2/sbin:$PATH"

# Verify PHP version
/opt/homebrew/opt/php@8.2/bin/php -v
```

## Windows Setup

### Using PHP 8.2 (Recommended)
1. Download PHP 8.2 from [Windows PHP Downloads](https://windows.php.net/download#php-8.2)
2. Extract to `C:\php8.2`
3. Add to PATH:
   - Open System Properties > Advanced > Environment Variables
   - Under System Variables, find PATH
   - Add `C:\php8.2`
4. Copy `php.ini-development` to `php.ini`
5. Enable required extensions in php.ini:
   - uncomment `extension=openssl`
   - uncomment `extension=pdo_mysql`
   - uncomment `extension=mbstring`

### Install Composer on Windows
1. Download and run [Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe)
2. Follow installation wizard
3. Verify installation: `composer --version`

## Running the Development Server

Start the local development server on port 3000:
```bash
composer serve
```

This will output:
```
> Tasks\ApplicationTasks::startDevelopmentWebServer
Starting PHP Development Server...
Host: localhost
Port: 3000
```

The application will be available at http://localhost:3000

## Development Workflow

1. Local development is done in the `app/` folder. This is NOT the same path used in the container environment.

2. Make your changes in the appropriate directories:
   - Controllers: `app/Controllers/`
   - Views: `app/Views/`
   - Routes: `app/CustomRoutes.php`
   - Config: `app/app.config.php`

3. Test your changes locally at http://localhost:3000

4. IMPORTANT: Your changes are not saved until you use:
```bash
composer commit
```

This command will copy your changes from the `app/` directory to the installer directory.

## Troubleshooting

### macOS
- If you see PHP version conflicts, ensure you're using the correct PHP binary:
  ```bash
  /opt/homebrew/opt/php@8.2/bin/php -v
  ```
- For permission issues: `chmod -R 755 html/assets`

### Windows
- If PHP isn't recognized, verify PATH environment variable
- For permission issues, run Command Prompt as Administrator
- Enable Windows Subsystem for Linux (WSL) for better development experience

## Notes
- The framework supports both PHP 7.4 and PHP 8.2
- PHP 8.2 is recommended for new development
- Legacy PHP 7.4 support is maintained for backward compatibility
- Use `composer commit` to save your changes
