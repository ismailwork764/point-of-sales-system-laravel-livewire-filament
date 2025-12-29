# One POS - Point of Sale System

A modern Point of Sale (POS) system built with **Laravel 12**, **Livewire 3**, **Filament 4**, and **Flux UI** for managing sales, inventory, customers, and payments.

> ⚠️ **Status**: Under active development

## Features

- Sales Management & Order Tracking
- Inventory & Product Management (SKU, Pricing)
- Customer Management
- Multiple Payment Methods
- User Management with Roles (Admin, Staff, Cashier)
- Authentication with Laravel Fortify
- Admin Dashboard
- Database Models with Factories for Testing

## Tech Stack

- **Backend**: Laravel 12 with PHP 8.2+
- **Frontend**: Livewire 3, Blade templating, Flux UI components
- **Admin Panel**: Filament 4
- **Database**: MySQL/MariaDB (10.4+)
- **Testing**: Pest PHP
- **Build Tool**: Vite
- **Authentication**: Laravel Fortify

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & npm (for frontend assets)
- MySQL/MariaDB 10.4+
- Git

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/ismailwork764/point-of-sales-system-using-filament-livewire-laravel.git
   cd one-pos
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install frontend dependencies**
   ```bash
   npm install
   ```

4. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Configure database**
   Edit `.env` file and set your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pos_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

9. **Build frontend assets**
   ```bash
   npm run build
   ```

10. **Start the development server**
    ```bash
    php artisan serve
    ```

The application will be available at `http://localhost:8000`


## Database Schema

- **users** - User accounts with roles
- **items** - Product inventory
- **customers** - Customer information
- **sales** - Sales transactions
- **sales_items** - Individual items in each sale
- **payment_methods** - Available payment methods
- **inventories** - Inventory tracking

## License

This project is open source and available under the [MIT License](LICENSE).

## Author

**Ismail Khan** - [GitHub](https://github.com/ismailwork764)
