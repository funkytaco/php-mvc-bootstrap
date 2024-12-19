# Testing Documentation

## Overview
This document outlines the testing process for the PHP MVC framework, with a particular focus on database mocking using our custom PDO implementation.

## Requirements
- PHP 8.0 or higher
- PHPUnit 9.0 or higher
- Composer (for dependency management)

## Running Tests
To run all tests:
```bash
./vendor/bin/phpunit
```

To run a specific test suite:
```bash
./vendor/bin/phpunit test/src/Mock/PDO_Test.php
```

Note: Before running tests, install dependencies:
```bash
composer install
```

Then you can also use the composer script:
```bash
composer test
```

## Test Structure
```
test/
├── bootstrap.php          # PHPUnit bootstrap file
├── src/
│   ├── Controllers/      # Controller tests
│   └── Mock/             # Mock object tests
└── README.md             # This file
```

## Mock PDO Usage
The framework includes a mock PDO implementation for testing database interactions without requiring a real database connection.

### Example Usage
```php
use Main\Mock\PDO;

// Create mock PDO instance
$db = new PDO();

// Prepare and execute statements
$stmt = $db->prepare("SELECT * FROM users");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

### Mock Data
The mock PDO implementation provides predefined data sets for testing:
```php
$mockUsers = [
    ["name" => "@funkytaco"],
    ["name" => "@Foo"],
    ["name" => "@Bar"]
];
```

### Writing Tests
When writing tests that use the mock PDO:

1. Extend `PHPUnit\Framework\TestCase`
2. Use setUp() to initialize the mock PDO
3. Write test methods with clear, descriptive names
4. Use appropriate annotations (@test, @small)

Example:
```php
namespace Test\Mock;

use PHPUnit\Framework\TestCase;
use Main\Mock\PDO;

class PDO_Test extends TestCase
{
    private PDO $conn;
    
    public function setUp(): void {
        $this->conn = new PDO();
    }
    
    /**
     * @test
     * @small
     */
    public function shouldReturnMockUsers() {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($result);
    }
}
```

## Best Practices
1. Always use descriptive test method names that explain the expected behavior
2. Keep tests focused and atomic
3. Use appropriate assertions for validation
4. Follow the AAA pattern (Arrange, Act, Assert)
5. Use type hints and return types for better code clarity
6. Add proper PHPDoc blocks with test annotations

## Troubleshooting
If tests are failing, check:
1. PHP version compatibility
2. PHPUnit version
3. Autoloader configuration in bootstrap.php
4. Namespace declarations
5. Mock data consistency

## Contributing
When adding new tests:
1. Follow the existing namespace structure
2. Add appropriate test cases for both success and failure scenarios
3. Update this documentation if adding new mock implementations
4. Ensure all tests pass before submitting changes
