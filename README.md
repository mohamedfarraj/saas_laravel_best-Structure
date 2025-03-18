# Multi-Tenant SaaS Project with Laravel

A robust multi-tenant SaaS platform built with Laravel using a modular architecture for better code organization and maintainability.

## Project Overview

This project implements a multi-tenant architecture that allows for:
- Separate Core and Tenant modules
- Independent database for each tenant
- Shared core functionality
- Easy maintenance and development

## Key Framework Modifications

### 1. Module Generation System
Custom commands for generating Core and Tenant modules:

```bash
# Generate Core Module
php artisan make:core:module ModuleName

# Generate Tenant Module
php artisan make:tenant:module ModuleName
```

Generated structure for each module:
```
ModuleName/
├── Controllers/
├── Models/
├── Services/
├── Repositories/
├── Routes/
├── Requests/
├── Resources/
└── Database/
    ├── Migrations/
    └── Seeders/
```

### 2. Authentication & Authorization
- Laravel Sanctum for authentication
- Custom middleware for permission checks
- Automatic permission generation for each module:
  - index (View All)
  - store (Create)
  - show (View Single)
  - update (Edit)
  - destroy (Delete)

### 3. Automatic Documentation
The system automatically generates:
- Postman Collection for all modules
- Complete API endpoint documentation
- Request/Response examples

### 4. Development Infrastructure
- PHP 8.3
- MySQL 8.0
- Redis for caching
- PHPMyAdmin for database management

### 5. Additional Features

#### Multi-Tenant System
- Separate database for each tenant
- Tenant-specific configurations
- Shared core functionality
- Tenant isolation

#### Module Structure
- Service Layer for business logic
- Repository Pattern for database operations
- Request Classes for validation
- Resource Classes for API responses

#### Task Automation
- Automatic database table creation
- Permission seeder updates
- Documentation file generation
- Postman collection updates

## Usage Example

### Creating a New Module

```bash
# Create Core Module
php artisan make:core:module Product

# Create Tenant Module
php artisan make:tenant:module Order
```

### Module Implementation

```php
// Controller Usage
public function index()
{
    return $this->service->getAll();
}

// Service Usage
public function getAll()
{
    return $this->repository->all();
}

// Repository Usage
public function all()
{
    return $this->model->paginate();
}
```

## Benefits

1. **Clean Architecture**
   - Clear separation of concerns
   - Easy to test each layer
   - Flexible for modifications
   - Reusable components

2. **Development Efficiency**
   - Rapid module creation
   - Consistent structure
   - Automated documentation
   - Built-in permission management

3. **Multi-Tenant Support**
   - Data isolation
   - Custom configurations
   - Scalable architecture
   - Easy tenant management

4. **Maintainability**
   - Organized code structure
   - Modular design
   - Easy to scale
   - Well-documented

## Installation

1. Clone the repository
```bash
git clone [repository-url]
```

2. Install dependencies
```bash
composer install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database
```bash
# Edit .env file with your database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saas_core
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations and seeders
```bash
# Core Database
php artisan migrate
php artisan db:seed

# Tenant Database
php artisan tenants:migrate
php artisan tenants:seed
```

## Available Commands

### Core Database Commands
```bash
# Run Core Migrations
php artisan migrate

# Run Core Migrations with Fresh Data
php artisan migrate:fresh --seed

# Run Core Seeders
php artisan db:seed

# Create Core Module
php artisan make:core:module {name}
```

### Tenant Database Commands
```bash
# Run Tenant Migrations for All Tenants
php artisan tenants:migrate

# Run Tenant Migrations for Specific Tenant
php artisan tenants:migrate --tenant=test.localhost

# Run Tenant Migrations with Fresh Data
php artisan tenants:migrate:fresh --seed

# Run Tenant Seeders
php artisan tenants:seed

# Create Tenant Module
php artisan make:tenant:module {name}
```

### Database Refresh Commands
```bash
# Refresh Core Database with Seeders
php artisan migrate:fresh --seed

# Refresh Tenant Database with Seeders
php artisan tenants:migrate:fresh --seed

# Refresh Specific Tenant Database
php artisan tenants:migrate:fresh --tenant={tenant_id} --seed
```

## Default Admin Users

### Core Admin
- Email: admin@core.com
- Password: password

### Tenant Admin
- Email: admin@tenant.com
- Password: password

## Contributing

Please read our contributing guidelines before submitting pull requests.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Author
> Mohamed Farraj
