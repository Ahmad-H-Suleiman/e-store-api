# E-Store API

A RESTful API for an e-commerce application built with Laravel 12.

The API provides functionality for user authentication, seller and product management, product categories, order processing, and administrative operations.

## Features

- User registration and login
- Email verification and password reset
- Token-based authentication using Laravel Sanctum
- Seller profile management
- Product creation and management
- Product categorization
- Order creation and management
- Administrative operations
- Request validation and error handling
- Paginated API responses

## Technologies

- PHP 8.2+
- Laravel 12
- Laravel Sanctum
- MySQL
- Eloquent ORM
- REST API
- Bruno for API testing and documentation

## Database Relationships

- A user can have one seller profile.
- A seller can have many products.
- A product can belong to many categories.
- A user can have many orders.
- An order can contain multiple order products.
- Each order product belongs to one product.

The `OrderProduct` model stores information about each product in an order, including its quantity and price.

## Authentication

The API uses Laravel Sanctum for token-based authentication.

Authenticated requests use the following HTTP header:

```http
Authorization: Bearer YOUR_TOKEN
```

Users must verify their email addresses before accessing routes protected by the email verification middleware.

## API Documentation

The API documentation was generated using Bruno and includes API requests and saved response examples.

**[View API Documentation](./store%20collection-documentation.html)**

## Installation

### Requirements

- PHP 8.2 or later
- Composer
- MySQL

### Setup

Clone the repository:

```bash
git clone https://github.com/Ahmad-H-Suleiman/e-store-api.git
cd e-store-api
```

Install the dependencies:

```bash
composer install
```

Create a `.env` file based on `.env.example` and configure your database connection.

Generate the application key:

```bash
php artisan key:generate
```

Run the database migrations:

```bash
php artisan migrate
```

Create the public storage link:

```bash
php artisan storage:link
```

Start the development server:

```bash
php artisan serve
```

The application will be available at:

```text
http://127.0.0.1:8000
```

API endpoints are available under the `/api` prefix.

## Testing

API endpoints can be tested using Bruno. The generated HTML documentation includes saved request and response examples.

## Project Status

This project is a learning project focused on building a RESTful e-commerce API. Its main functionality has been implemented, and further improvements may be added.

## Purpose

The project was built to develop practical Laravel backend skills and understand how authentication, database relationships, validation, authorization, transactions, and RESTful API design work together.
