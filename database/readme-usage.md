# Database Usage Guide

## Overview
This guide explains how to set up and use the PostgreSQL database for the template management system.

## Setup

### 1. Configuration
Copy `.env.example` to `.env` and set your credentials:
```bash
cp .env.example .env
```

Required environment variables:
- `POSTGRES_DB`: Database name
- `POSTGRES_USER`: Database user
- `POSTGRES_PASSWORD`: User password

### 2. Container Management
The database runs in a Podman/Docker container. Basic commands:

```bash
# Start the container
podman-compose up -d

# Stop the container
podman-compose down

# View logs
podman logs templates-db

# Connect to psql console
podman exec -it templates-db psql -U icarusadmin -d icarusdb
```

## Database Schema

### Templates Table
Main table for storing template information:
```sql
CREATE TABLE templates (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    type VARCHAR(50) NOT NULL DEFAULT 'page',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT true,
    parent_id VARCHAR(36) DEFAULT NULL,
    FOREIGN KEY (parent_id) REFERENCES templates(id)
);
```

### Template Versions Table
Stores version history of templates:
```sql
CREATE TABLE template_versions (
    id VARCHAR(36) PRIMARY KEY,
    template_id VARCHAR(36) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by VARCHAR(36) NOT NULL,
    FOREIGN KEY (template_id) REFERENCES templates(id)
);
```

### Template Variables Table
Stores variables used in templates:
```sql
CREATE TABLE template_variables (
    id VARCHAR(36) PRIMARY KEY,
    template_id VARCHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    default_value TEXT,
    type VARCHAR(50) NOT NULL DEFAULT 'string',
    is_required BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (template_id) REFERENCES templates(id)
);
```

## PDO Module Usage

### Connection
The PDO_Module provides a singleton connection to the database:

```php
use Main\Modules\PDO_Module;

$db = PDO_Module::getInstance();
```

### Basic Queries

#### Fetch All Templates
```php
// Using direct PDO
$stmt = $db->prepare("SELECT * FROM templates ORDER BY name ASC");
$stmt->execute();
$templates = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Using convenience method
$templates = $db->executeQuery("SELECT * FROM templates ORDER BY name ASC");
```

#### Get Single Template
```php
// Using direct PDO
$stmt = $db->prepare("SELECT * FROM templates WHERE id = ?");
$stmt->execute([$id]);
$template = $stmt->fetch(PDO::FETCH_ASSOC);

// Using convenience method
$template = $db->fetchOne("SELECT * FROM templates WHERE id = ?", [$id]);
```

#### Insert Template
```php
$stmt = $db->prepare("
    INSERT INTO templates (id, name, content, type) 
    VALUES (?, ?, ?, ?)
");
$stmt->execute([
    $id,
    $name,
    $content,
    $type
]);
```

#### Update Template
```php
$db->executeStatement("
    UPDATE templates 
    SET name = ?, content = ?, updated_at = NOW() 
    WHERE id = ?
", [$name, $content, $id]);
```

### Transactions
For operations that require multiple queries:

```php
try {
    $db->beginTransaction();
    
    // Insert template
    $db->executeStatement("
        INSERT INTO templates (id, name, content) 
        VALUES (?, ?, ?)
    ", [$id, $name, $content]);
    
    // Add variables
    foreach ($variables as $var) {
        $db->executeStatement("
            INSERT INTO template_variables (template_id, name, default_value) 
            VALUES (?, ?, ?)
        ", [$id, $var['name'], $var['default']]);
    }
    
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    throw $e;
}
```

## Connection Details

The database container is accessible at:
- Host: localhost
- Port: 5432
- Database: icarusdb
- User: icarusadmin
