# E-Store API

A simple REST API for an e-store application built with Laravel.

The API provides authentication, users, sellers, products, categories, and orders.

## Features

- User registration and login
- user can bay a product
- user can be a sellar
- sellar can add product to sell them
- Email verification and password reset
- Seller and product management
- Product categories
- Order creation and management
- Admin management
- Token authentication with Laravel Sanctum
- API validation and error handling

## Relationships

- A user can have one seller profile.
- A seller can have many products.
- A product belong to many categories.
- A product belong to many order.
- A user can have many orders.
- An order can has many products.

### Requirements

- PHP 8.2+
- Composer
- MySQL


