# 🛒 Simple Marketplace - Laravel 11 E-Commerce Platform

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://www.mysql.com/)

A comprehensive marketplace application built with Laravel 11, featuring advanced authentication systems, product management, transaction processing, and social login integration with Google and Facebook OAuth2.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Technology Stack](#technology-stack)
- [System Requirements](#system-requirements)
- [Installation Guide](#installation-guide)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [API Documentation](#api-documentation)
- [Postman Collection](#postman-collection)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)
- [Default Credentials](#default-credentials)
- [Useful Commands](#useful-commands)

---

## 🎯 Overview

Simple Marketplace is a modern e-commerce platform designed to provide a seamless shopping experience. Built with Laravel 11 and following industry best practices, this application offers robust features for both customers and administrators.

### What Makes This Special?

- 🔐 **Multi-Authentication**: Email/Password, Google OAuth2, Facebook OAuth2
- 🛡️ **Role-Based Access Control**: Super Admin and Customer roles with granular permissions
- 💳 **Complete Transaction Flow**: From cart to checkout with status tracking
- 📱 **Responsive Design**: Mobile-first approach with Bootstrap 5
- 🔄 **RESTful API**: Well-documented API endpoints for integration
- 📊 **Admin Dashboard**: Powered by Filament 3.x for easy management
- 🔒 **Secure**: OAuth2 implementation with Laravel Passport
- 📄 **PDF Generation**: Automatic invoice generation for transactions

---

## ✨ Key Features

### 🔐 Authentication & Authorization

- **Email/Password Authentication**
  - User registration with email verification
  - Secure password hashing with bcrypt
  - Remember me functionality
  - Password reset via email

- **Social Login Integration**
  - Google Sign-In with OAuth2
  - Facebook Login with OAuth2
  - Automatic account creation/linking
  - Avatar synchronization

- **Role-Based Access Control (RBAC)**
  - Super Admin: Full system access
  - Customer: Shopping and order management
  - Permission-based authorization using Spatie Laravel Permission

- **API Authentication**
  - OAuth2 Bearer token authentication
  - Laravel Passport implementation
  - Token refresh and revocation
  - SSO (Single Sign-On) support

### 🛍️ Product Management

- **Product Catalog**
  - Multiple product categories
  - Product variants and attributes
  - Image gallery support
  - Stock management
  - Price management
  - SEO-friendly URLs (slugs)

- **Product Features**
  - Advanced search and filtering
  - Pagination support
  - Sort by price, name, date
  - Category-based browsing

- **Admin Product Management**
  - Create, read, update, delete (CRUD) operations
  - Bulk operations
  - Image upload and management
  - Stock tracking
  - Product status management

### 💳 Transaction System

- **Order Management**
  - Shopping cart functionality
  - Multiple items per order
  - Real-time stock validation
  - Order summary and preview

- **Transaction Flow**
  - Order creation
  - Payment method selection
  - Order confirmation
  - Status tracking (Pending, Processing, Shipped, Completed, Cancelled)
  - Order history

- **Payment Integration Ready**
  - Bank transfer support
  - Payment confirmation upload
  - Payment verification by admin
  - Invoice generation (PDF)

- **Transaction Features**
  - Automatic transaction code generation
  - Order cancellation (before processing)
  - Transaction statistics
  - Revenue reporting

---

## 🛠️ Technology Stack

### Backend

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 11.x | PHP Framework |
| **PHP** | 8.2+ | Programming Language |
| **MySQL** | 8.0+ | Database |
| **Laravel Passport** | 12.x | OAuth2 Authentication |
| **Spatie Permission** | 6.x | Role & Permission Management |
| **Filament** | 3.x | Admin Panel |
| **Laravel Socialite** | 5.x | Social Authentication |
| **DomPDF** | 2.x | PDF Generation |

### Frontend

| Technology | Version | Purpose |
|------------|---------|---------|
| **Bootstrap** | 5.3.2 | CSS Framework |
| **Bootstrap Icons** | 1.11.1 | Icon Library |
| **SweetAlert2** | 11.x | Alert Dialogs |
| **Vite** | 5.x | Asset Bundler |

---

## 📦 System Requirements

### Minimum Requirements

- **PHP**: >= 8.2
- **Composer**: >= 2.5
- **MySQL**: >= 8.0 or MariaDB >= 10.3
- **Node.js**: >= 18.x
- **NPM**: >= 9.x
- **Memory**: 512MB RAM minimum
- **Disk Space**: 500MB free space

### PHP Extensions Required

BCMath PHP Extension
Ctype PHP Extension
cURL PHP Extension
DOM PHP Extension
Fileinfo PHP Extension
JSON PHP Extension
Mbstring PHP Extension
OpenSSL PHP Extension
PCRE PHP Extension
PDO PHP Extension
Tokenizer PHP Extension
XML PHP Extension
GD PHP Extension
ZIP PHP Extension


---

## 🚀 Installation Guide

### Step 1: Clone Repository

```bash
# Clone the repository
git clone https://github.com/riszkysetiawan/simple_marketplace

# Navigate to project directory
cd simple-marketplace
Step 2: Install Dependencies

# Install PHP dependencies
composer install

# If you encounter memory issues
COMPOSER_MEMORY_LIMIT=-1 composer install

# Install Node dependencies
npm install
Step 3: Environment Configuration

#  environment file
cp .env.example .env

# Generate application key
php artisan key:generate
Step 4: Configure Environment Variables
Edit .env file:


# Application
APP_NAME="Simple Marketplace"
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace_db
DB_USERNAME=root
DB_PASSWORD=your_database_password

# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Facebook OAuth
FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
⚙️ Configuration
Database Configuration

# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE marketplace_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
Google OAuth Configuration
Step 1: Create Google Cloud Project
Go to Google Cloud Console
Create a new project
Navigate to APIs & Services > Credentials
Step 2: Configure OAuth Consent Screen
Click OAuth consent screen
Select External user type
Fill in application information:
App name: Simple Marketplace
User support email: your_email@example.com
Add scopes: userinfo.email, userinfo.profile
Step 3: Create OAuth 2.0 Client ID
Go to Credentials > Create Credentials > OAuth client ID
Select Web application
Configure:
Authorized JavaScript origins: http://localhost:8000
Authorized redirect URIs: http://localhost:8000/auth/google/callback
 Client ID and Client Secret
Step 4: Update .env

GOOGLE_CLIENT_ID=your_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
Facebook OAuth Configuration
Step 1: Create Facebook App
Go to Facebook Developers
Click My Apps > Create App
Select Consumer type
Fill in app details
Step 2: Add Facebook Login
Add Facebook Login product
Select Web platform
Enter site URL: http://localhost:8000
Step 3: Configure OAuth Settings
Go to Facebook Login > Settings
Add Valid OAuth Redirect URIs: http://localhost:8000/auth/facebook/callback
Step 4: Update .env

FACEBOOK_CLIENT_ID=your_app_id
FACEBOOK_CLIENT_SECRET=your_app_secret
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
Mail Configuration (Gmail)
Enable 2-Factor Authentication on Google Account
Generate App Password at Google Account Security
Update .env:

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_16_char_app_password
MAIL_ENCRYPTION=tls
🗄️ Database Setup
Step 1: Run Migrations

php artisan migrate
Creates tables:

users, roles, permissions
categories, products, product_images
transactions, transaction_items
oauth_clients, oauth_access_tokens
Step 2: Install Laravel Passport

php artisan passport:install
Important: Save the generated Client ID and Client Secret!

Step 3: Seed Database

php artisan db:seed
Creates:

Roles: super_admin, customer
Admin: admin@example.com / password
Customer: customer@example.com / password
Sample products and categories
Step 4: Create Storage Link

php artisan storage:link
Step 5: Set Permissions
Linux/Mac:


chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
Windows (as Administrator):


icacls storage /grant Users:F /t
icacls bootstrap\cache /grant Users:F /t
🏃 Running the Application
Start Development Server

php artisan serve
Application available at: http://localhost:8000

Compile Frontend Assets

# Development
npm run dev

# Watch for changes
npm run watch

# Production build
npm run build
Access Points
Service	URL	Credentials
Main App	http://localhost:8000	-
Admin Panel	http://localhost:8000/admin	admin@example.com / password
API Docs	http://localhost:8000/api/docs	-
Login	http://localhost:8000/login	customer@example.com / password
📖 API Documentation
Base URL

http://localhost:8000/api/v1
Authentication Header

Authorization: Bearer {access_token}
Response Format
Success:


{
  "success": true,
  "message": "Operation successful",
  "data": {}
}
Error:


{
  "success": false,
  "message": "Error message",
  "errors": {}
}
HTTP Status Codes
Code	Meaning
200	OK
201	Created
400	Bad Request
401	Unauthorized
403	Forbidden
404	Not Found
422	Validation Error
500	Server Error
🔐 Authentication Endpoints
1. Register
POST /api/v1/auth/register

Request:


{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
Response (201):


{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 3,
      "name": "John Doe",
      "email": "john@example.com",
      "roles": [{"name": "customer"}]
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer",
    "redirect_url": "http://localhost:8000/"
  }
}
2. Login
POST /api/v1/auth/login

Request:


{
  "email": "customer@example.com",
  "password": "password"
}
Response (200):


{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 2,
      "name": "Customer User",
      "email": "customer@example.com"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer",
    "redirect_url": "http://localhost:8000/"
  }
}
3. Google Login
POST /api/v1/auth/google/login

Request:


{
  "id_token": "eyJhbGciOiJSUzI1NiIsImtpZCI..."
}
How to Get Google ID Token (JavaScript):


google.accounts.id.initialize({
  client_id: 'YOUR_GOOGLE_CLIENT_ID',
  callback: function(response) {
    fetch('/api/v1/auth/google/login', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({id_token: response.credential})
    });
  }
});
Response (200):


{
  "success": true,
  "message": "Google login successful",
  "data": {
    "user": {
      "id": 4,
      "name": "John Doe",
      "email": "john@gmail.com",
      "google_id": "1234567890"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer",
    "redirect_url": "http://localhost:8000/"
  }
}
4. Facebook Login
POST /api/v1/auth/facebook/login

Request:


{
  "access_token": "EAABwzLixnjYBO..."
}
How to Get Facebook Access Token (JavaScript):


FB.login(function(response) {
  if (response.authResponse) {
    fetch('/api/v1/auth/facebook/login', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        access_token: response.authResponse.accessToken
      })
    });
  }
}, {scope: 'public_profile,email'});
Response (200):


{
  "success": true,
  "message": "Facebook login successful",
  "data": {
    "user": {
      "id": 5,
      "name": "Jane Smith",
      "email": "jane@facebook.com",
      "facebook_id": "9876543210"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer",
    "redirect_url": "http://localhost:8000/"
  }
}
5. Get Current User
GET /api/v1/auth/me

Headers:


Authorization: Bearer {token}
Response (200):


{
  "success": true,
  "data": {
    "id": 2,
    "name": "Customer User",
    "email": "customer@example.com",
    "avatar": "http://localhost:8000/storage/avatars/customer.jpg",
    "phone": "+6281234567890",
    "roles": [{"name": "customer"}]
  }
}
6. Logout
POST /api/v1/auth/logout

Headers:


Authorization: Bearer {token}
Response (200):


{
  "success": true,
  "message": "Logout successful"
}
7. SSO Login
GET /api/v1/auth/sso

Headers:


Authorization: Bearer {token}
Response (200):


{
  "success": true,
  "message": "SSO token generated",
  "data": {
    "redirect_url": "http://app2.example.com/auth/sso/callback?token=...",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "user": {
      "id": 2,
      "name": "Customer User"
    }
  }
}
8. SSO Callback
POST /api/v1/auth/sso/callback

Request:


{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "user_id": 2
}
Response (200):


{
  "success": true,
  "message": "SSO callback successful",
  "data": {
    "user": {"id": 2, "name": "Customer User"},
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer",
    "redirect_url": "http://localhost:8000/"
  }
}
🛍️ Product Endpoints
1. Get All Products
GET /api/v1/products

Query Parameters:

page (integer): Page number
per_page (integer): Items per page (max 100)
search (string): Search by name/description
category_id (integer): Filter by category
min_price (integer): Minimum price
max_price (integer): Maximum price
sort_by (string): name, price, created_at
sort_order (string): asc, desc
Example:


GET /api/v1/products?page=1&per_page=10&search=laptop&sort_by=price&sort_order=asc
Response (200):


{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Laptop ASUS ROG Strix G15",
        "slug": "laptop-asus-rog-strix-g15",
        "description": "Gaming laptop with RTX 3060",
        "price": 15000000,
        "formatted_price": "Rp 15.000.000",
        "stock": 10,
        "category": {
          "id": 1,
          "name": "Electronics"
        },
        "images": [
          {
            "id": 1,
            "url": "http://localhost:8000/storage/products/laptop-1.jpg",
            "is_primary": true
          }
        ]
      }
    ],
    "per_page": 10,
    "total": 50
  }
}
2. Get Product by ID
GET /api/v1/products/{id}

Response (200):


{
  "success": true,
  "data": {
    "id": 1,
    "name": "Laptop ASUS ROG Strix G15",
    "slug": "laptop-asus-rog-strix-g15",
    "description": "Gaming laptop with AMD Ryzen 9 5900HX, RTX 3060",
    "price": 15000000,
    "formatted_price": "Rp 15.000.000",
    "stock": 10,
    "category": {
      "id": 1,
      "name": "Electronics",
      "slug": "electronics"
    },
    "images": [
      {
        "id": 1,
        "url": "http://localhost:8000/storage/products/laptop-1.jpg",
        "is_primary": true
      }
    ],
    "specifications": {
      "processor": "AMD Ryzen 9 5900HX",
      "graphics": "NVIDIA RTX 3060",
      "ram": "16GB DDR4",
      "storage": "512GB SSD"
    }
  }
}
3. Create Product (Admin Only)
POST /api/v1/products

Headers:


Authorization: Bearer {admin_token}
Content-Type: multipart/form-data
Request (Form Data):


name: Laptop HP Pavilion Gaming
description: Gaming laptop with GTX 1650
price: 12000000
stock: 15
category_id: 1
images[]: [file1.jpg]
images[]: [file2.jpg]
Response (201):


{
  "success": true,
  "message": "Product created successfully",
  "data": {
    "id": 21,
    "name": "Laptop HP Pavilion Gaming",
    "slug": "laptop-hp-pavilion-gaming",
    "price": 12000000,
    "stock": 15
  }
}
4. Update Product (Admin Only)
PUT /api/v1/products/{id}

Headers:


Authorization: Bearer {admin_token}
Content-Type: application/json
Request:


{
  "name": "Laptop ASUS ROG (Updated)",
  "price": 16000000,
  "stock": 8
}
Response (200):


{
  "success": true,
  "message": "Product updated successfully",
  "data": {
    "id": 1,
    "name": "Laptop ASUS ROG (Updated)",
    "price": 16000000,
    "stock": 8
  }
}
5. Delete Product (Admin Only)
DELETE /api/v1/products/{id}

Headers:


Authorization: Bearer {admin_token}
Response (200):


{
  "success": true,
  "message": "Product deleted successfully"
}
💳 Transaction Endpoints
1. Get All Transactions
GET /api/v1/transactions

Headers:


Authorization: Bearer {token}
Query Parameters:

page (integer): Page number
per_page (integer): Items per page
status (string): pending, processing, shipped, completed, cancelled
date_from (date): Start date (Y-m-d)
date_to (date): End date (Y-m-d)
Response (200):


{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "transaction_code": "TRX-20241207-001",
        "user": {
          "id": 2,
          "name": "Customer User"
        },
        "items": [
          {
            "product": {
              "id": 1,
              "name": "Laptop ASUS ROG"
            },
            "quantity": 1,
            "price": 15000000,
            "subtotal": 15000000
          }
        ],
        "total_amount": 15000000,
        "formatted_total": "Rp 15.000.000",
        "status": "pending",
        "shipping_address": "Jl. Sudirman No. 123, Jakarta",
        "payment_method": "bank_transfer",
        "created_at": "2024-12-07T10:30:00.000000Z"
      }
    ],
    "total": 25
  }
}
2. Get Transaction by ID
GET /api/v1/transactions/{id}

Headers:


Authorization: Bearer {token}
Response (200):


{
  "success": true,
  "data": {
    "id": 1,
    "transaction_code": "TRX-20241207-001",
    "user": {
      "id": 2,
      "name": "Customer User",
      "email": "customer@example.com"
    },
    "items": [
      {
        "product": {
          "id": 1,
          "name": "Laptop ASUS ROG"
        },
        "quantity": 1,
        "price": 15000000,
        "subtotal": 15000000
      }
    ],
    "total_amount": 15000000,
    "status": "pending",
    "shipping_address": "Jl. Sudirman No. 123, Jakarta",
    "payment_method": "bank_transfer",
    "invoice_url": "http://localhost:8000/api/v1/transactions/1/invoice"
  }
}
3. Create Transaction
POST /api/v1/transactions

Headers:


Authorization: Bearer {token}
Content-Type: application/json
Request:


{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    },
    {
      "product_id": 3,
      "quantity": 1
    }
  ],
  "shipping_address": "Jl. Sudirman No. 123, Jakarta",
  "payment_method": "bank_transfer",
  "notes": "Please pack carefully"
}
Response (201):


{
  "success": true,
  "message": "Transaction created successfully",
  "data": {
    "id": 26,
    "transaction_code": "TRX-20241207-026",
    "items": [
      {
        "product_name": "Laptop ASUS ROG",
        "quantity": 2,
        "price": 15000000,
        "subtotal": 30000000
      }
    ],
    "total_amount": 30000000,
    "formatted_total": "Rp 30.000.000",
    "status": "pending",
    "payment_instructions": {
      "bank_name": "Bank BCA",
      "account_number": "1234567890",
      "account_name": "Simple Marketplace",
      "amount": 30000000
    }
  }
}
4. Update Transaction Status (Admin Only)
PUT /api/v1/transactions/{id}

Headers:


Authorization: Bearer {admin_token}
Content-Type: application/json
Request:


{
  "status": "processing",
  "admin_notes": "Payment verified"
}
Available Statuses:

pending - Waiting for payment
processing - Payment confirmed
shipped - Order shipped
completed - Order delivered
cancelled - Order cancelled
Response (200):


{
  "success": true,
  "message": "Transaction updated successfully",
  "data": {
    "id": 1,
    "status": "processing",
    "admin_notes": "Payment verified"
  }
}
5. Cancel Transaction
POST /api/v1/transactions/{id}/cancel

Headers:


Authorization: Bearer {token}
Request (Optional):


{
  "reason": "Changed my mind"
}
Response (200):


{
  "success": true,
  "message": "Transaction cancelled successfully",
  "data": {
    "id": 1,
    "status": "cancelled",
    "cancellation_reason": "Changed my mind"
  }
}
6. Get Transaction Statistics (Admin Only)
GET /api/v1/transactions/statistics/all

Headers:


Authorization: Bearer {admin_token}
Query Parameters:

date_from (date): Start date
date_to (date): End date
period (string): today, week, month, year
Response (200):


{
  "success": true,
  "data": {
    "summary": {
      "total_transactions": 150,
      "total_revenue": 500000000,
      "formatted_revenue": "Rp 500.000.000",
      "average_order_value": 3333333
    },
    "by_status": {
      "pending": {"count": 25, "percentage": 16.67},
      "processing": {"count": 30, "percentage": 20},
      "shipped": {"count": 20, "percentage": 13.33},
      "completed": {"count": 50, "percentage": 33.33},
      "cancelled": {"count": 25, "percentage": 16.67}
    },
    "top_products": [
      {
        "product_id": 1,
        "product_name": "Laptop ASUS ROG",
        "total_sold": 45,
        "revenue": 675000000
      }
    ]
  }
}
7. Download Invoice (PDF)
GET /api/v1/transactions/{id}/invoice

Headers:


Authorization: Bearer {token}
Accept: application/pdf
Response:

Returns PDF file
Filename: invoice-TRX-20241207-001.pdf
📮 Postman Collection
Import Collection
Download Postman Collection JSON below
Open Postman
Click Import
Paste JSON or select file
Postman Collection JSON

{
  "info": {
    "name": "Simple Marketplace API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "variable": [
    {"key": "base_url", "value": "http://localhost:8000/api/v1"},
    {"key": "token", "value": ""},
    {"key": "admin_token", "value": ""}
  ],
  "item": [
    {
      "name": "Authentication",
      "item": [
        {
          "name": "Register",
          "request": {
            "method": "POST",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "url": "{{base_url}}/auth/register",
            "body": {
              "mode": "raw",
              "raw": "{\n  \"name\": \"John Doe\",\n  \"email\": \"john@example.com\",\n  \"password\": \"password123\",\n  \"password_confirmation\": \"password123\"\n}"
            }
          }
        },
        {
          "name": "Login",
          "request": {
            "method": "POST",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "url": "{{base_url}}/auth/login",
            "body": {
              "mode": "raw",
              "raw": "{\n  \"email\": \"customer@example.com\",\n  \"password\": \"password\"\n}"
            }
          }
        },
        {
          "name": "Get Current User",
          "request": {
            "method": "GET",
            "header": [{"key": "Authorization", "value": "Bearer {{token}}"}],
            "url": "{{base_url}}/auth/me"
          }
        },
        {
          "name": "Logout",
          "request": {
            "method": "POST",
            "header": [{"key": "Authorization", "value": "Bearer {{token}}"}],
            "url": "{{base_url}}/auth/logout"
          }
        }
      ]
    },
    {
      "name": "Products",
      "item": [
        {
          "name": "Get All Products",
          "request": {
            "method": "GET",
            "url": "{{base_url}}/products?page=1&per_page=10"
          }
        },
        {
          "name": "Get Product by ID",
          "request": {
            "method": "GET",
            "url": "{{base_url}}/products/1"
          }
        },
        {
          "name": "Create Product (Admin)",
          "request": {
            "method": "POST",
            "header": [
              {"key": "Authorization", "value": "Bearer {{admin_token}}"},
              {"key": "Content-Type", "value": "application/json"}
            ],
            "url": "{{base_url}}/products",
            "body": {
              "mode": "raw",
              "raw": "{\n  \"name\": \"New Product\",\n  \"description\": \"Product description\",\n  \"price\": 100000,\n  \"stock\": 50,\n  \"category_id\": 1\n}"
            }
          }
        }
      ]
    },
    {
      "name": "Transactions",
      "item": [
        {
          "name": "Get All Transactions",
          "request": {
            "method": "GET",
            "header": [{"key": "Authorization", "value": "Bearer {{token}}"}],
            "url": "{{base_url}}/transactions"
          }
        },
        {
          "name": "Create Transaction",
          "request": {
            "method": "POST",
            "header": [
              {"key": "Authorization", "value": "Bearer {{token}}"},
              {"key": "Content-Type", "value": "application/json"}
            ],
            "url": "{{base_url}}/transactions",
            "body": {
              "mode": "raw",
              "raw": "{\n  \"items\": [\n    {\"product_id\": 1, \"quantity\": 2}\n  ],\n  \"shipping_address\": \"Jl. Sudirman No. 123\",\n  \"payment_method\": \"bank_transfer\"\n}"
            }
          }
        }
      ]
    }
  ]
}
Environment Variables
Create Postman environment:


{
  "name": "Simple Marketplace Local",
  "values": [
    {"key": "base_url", "value": "http://localhost:8000/api/v1"},
    {"key": "token", "value": ""},
    {"key": "admin_token", "value": ""}
  ]
}
Get Tokens
Customer Token:

Use POST /auth/login with customer@example.com / password
 access_token to token variable
Admin Token:

Use POST /auth/login with admin@example.com / password
 access_token to admin_token variable
🧪 Testing
Run All Tests

php artisan test
Run Specific Tests

# Feature tests
php artisan test --testsuite=Feature

# Unit tests
php artisan test --testsuite=Unit

# Specific test file
php artisan test tests/Feature/AuthTest.php

# With coverage
php artisan test --coverage
Manual Testing with cURL
Test Registration

curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@example.com","password":"password123","password_confirmation":"password123"}'
Test Login

curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"customer@example.com","password":"password"}'
Test Get Products

curl -X GET "http://localhost:8000/api/v1/products?page=1"
Test Protected Route

curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN"
🔧 Troubleshooting
Common Issues
1. Laravel Passport Not Found
Error: Class 'Laravel\Passport\Passport' not found

Solution:


composer require laravel/passport
php artisan passport:install
2. Database Connection Error
Error: SQLSTATE[HY000] [2002] Connection refused

Solution:

Check MySQL is running:


# Windows
net start MySQL80

# Mac
brew services start mysql

# Linux
sudo systemctl start mysql
Verify .env database credentials:


DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace_db
DB_USERNAME=root
DB_PASSWORD=your_password

3. Storage Link Not Working
Error: The stream or file could not be opened

Solution:
php artisan storage:link
chmod -R 775 storage bootstrap/cache
4. Google Sign-In Not Working
Possible Causes:

Invalid Client ID
Unauthorized JavaScript origins
Unauthorized redirect URIs
Solution:

Verify GOOGLE_CLIENT_ID in .env
Check Google Console:
Authorized JavaScript origins: http://localhost:8000
Authorized redirect URIs: http://localhost:8000/auth/google/callback
Clear browser cache
Check browser console for errors
5. Facebook Login Not Working
Solution:

Verify FACEBOOK_CLIENT_ID in .env
Check Facebook App settings:
Valid OAuth Redirect URIs: http://localhost:8000/auth/facebook/callback
Ensure app is not in Development Mode
6. Permission Denied
Linux/Mac:


chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
Windows (as Administrator):


icacls storage /grant Users:F /t
icacls bootstrap\cache /grant Users:F /t
7. Routes Not Found
Solution:


php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan route:list
8. Composer Memory Limit
Error: Allowed memory size exhausted

Solution:


COMPOSER_MEMORY_LIMIT=-1 composer install
9. NPM Install Errors
Solution:


npm cache clean --force
rm -rf node_modules package-lock.json
npm install
10. Passport Token Issues
Error: Unauthenticated

Solution:


php artisan passport:keys --force
php artisan config:clear
php artisan cache:clear
📝 Default Credentials
Admin Account

Email: superadmin@marketplace.com
Password: password
Role: super_admin
Customer Account

Email: john@example.com
Password: password
Role: customer
Create Additional Test Users

php artisan tinker

$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
]);

$user->assignRole('customer');
🔗 Useful Commands
Clear Cache

# Clear all cache
php artisan optimize:clear

# Clear specific cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
Generate Keys

# Generate app key
php artisan key:generate

# Generate Passport keys
php artisan passport:keys
Database Commands

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Refresh database
php artisan migrate:fresh

# Seed database
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=ProductSeeder
Make Commands

# Create controller
php artisan make:controller Api/YourController

# Create model with migration
php artisan make:model YourModel -m

# Create migration
php artisan make:migration create_your_table

# Create seeder
php artisan make:seeder YourSeeder

# Create request
php artisan make:request YourRequest

# Create resource
php artisan make:resource YourResource
List Commands

# List all routes
php artisan route:list

# List all routes (filtered)
php artisan route:list --path=api

# List all artisan commands
php artisan list
Optimization Commands

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload -o
Queue Commands

# Start queue worker
php artisan queue:work

# Start queue worker with options
php artisan queue:work --tries=3 --timeout=60

# Restart queue workers
php artisan queue:restart

# Clear failed jobs
php artisan queue:flush
Schedule Commands

# Run scheduled tasks
php artisan schedule:run

# Run schedule worker (for development)
php artisan schedule:work

# List scheduled tasks
php artisan schedule:list
Filament Commands

# Create Filament admin user
php artisan make:filament-user

# Create Filament resource
php artisan make:filament-resource Product

# Create Filament page
php artisan make:filament-page Settings
🚀 Deployment
Production Checklist
 Set APP_ENV=production in .env
 Set APP_DEBUG=false in .env
 Configure production database
 Set up proper mail configuration
 Configure queue driver (Redis/Database)
 Set up SSL certificate
 Configure CORS settings
 Set up backup system
 Configure monitoring tools
 Set up logging
 Optimize autoloader
 Cache configuration
 Cache routes
 Cache views
Optimization Commands for Production

# Clear all cache
php artisan optimize:clear

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Build frontend assets
npm run build
Server Requirements

- PHP >= 8.2
- MySQL >= 8.0
- Nginx or Apache with mod_rewrite
- Supervisor (for queue workers)
- Redis (recommended for cache/sessions)
- SSL Certificate
Example Nginx Configuration

server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/simple-marketplace/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
Supervisor Configuration for Queue
Create /etc/supervisor/conf.d/marketplace-worker.conf:


[program:marketplace-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/simple-marketplace/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/simple-marketplace/storage/logs/worker.log
stopwaitsecs=3600
Then:


sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start marketplace-worker:*
📄 License
This project is open-sourced software licensed under the MIT license.

👨‍💻 Author
Your Kiki

Email: rizkysetiawann22@gmail.com
GitHub: @riszkysetiawan
LinkedIn: Moch Rezeki Setiawan
Contributions, issues, and feature requests are welcome!

How to Contribute
Fork the project
Create your feature branch (git checkout -b feature/AmazingFeature)
Commit your changes (git commit -m 'Add some AmazingFeature')
Push to the branch (git push origin feature/AmazingFeature)
Open a Pull Request
Coding Standards
Follow PSR-12 coding standards
Write meaningful commit messages
Add tests for new features
Update documentation
📞 Support
For support:

Email: your.email@example.com
Create an issue: GitHub Issues
Documentation: Wiki
🙏 Acknowledgments
Laravel Framework
Filament Admin Panel
Spatie Laravel Permission
Laravel Passport
Bootstrap Team
All contributors
📊 Project Statistics
Lines of Code: ~15,000+
API Endpoints: 20+
Database Tables: 15+
Test Coverage: 80%+
🗺️ Roadmap
Version 1.1 (Planned)
 Wishlist feature
 Product reviews and ratings
 Advanced search filters
 Email notifications
 SMS notifications
Version 1.2 (Planned)
 Multi-vendor support
 Shipping integration
 Payment gateway integration (Midtrans, Xendit)
 Real-time chat support
 Mobile app (Flutter)
Version 2.0 (Future)
 AI-powered product recommendations
 Advanced analytics dashboard
 Multi-language support
 Multi-currency support
 Progressive Web App (PWA)
📸 Screenshots
Landing Page
Landing Page

Admin Dashboard
Admin Dashboard

Product Listing
Product Listing

Transaction History
Transaction History

🔐 Security
Reporting Security Issues
If you discover a security vulnerability, please send an email to security@example.com. All security vulnerabilities will be promptly addressed.

Security Features
Password hashing with bcrypt
CSRF protection
XSS protection
SQL injection prevention
Rate limiting on API endpoints
OAuth2 authentication
Secure session management
Input validation and sanitization
📚 Additional Resources
Documentation
Laravel Documentation
Filament Documentation
Laravel Passport Documentation
Spatie Permission Documentation
Tutorials
Laravel API Development
OAuth2 Implementation
RESTful API Best Practices
Community
Laravel Forum
Stack Overflow
Laravel News
⚖️ Terms of Service
By using this software, you agree to:

Use the software for lawful purposes only
Not reverse engineer or modify the core functionality
Provide attribution when required
Comply with all applicable laws and regulations
🌟 Star History
If you find this project helpful, please consider giving it a star ⭐

Star History Chart

📝 Changelog
Version 1.0.0 (2024-12-07)
Added:

Initial release
Email/Password authentication
Google OAuth2 integration
Facebook OAuth2 integration
Product management (CRUD)
Transaction system
Admin panel with Filament
RESTful API
Role-based access control
PDF invoice generation
SSO support
Fixed:

N/A (Initial release)
Changed:

N/A (Initial release)
Made with ❤️ using Laravel 11

Happy Coding! 🚀

Last Updated: December 7, 2025
