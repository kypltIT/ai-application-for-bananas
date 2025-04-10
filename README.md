/*
 * Changes:
 * - Created a new README file that provides an overview of the system's functionality
 */

# AI Application for Bananas - E-commerce and Content Management System

This is a comprehensive e-commerce application built with Laravel, focused on selling banana products with integrated AI-powered trend analysis.

## System Overview

This application consists of two main sections:

1. **Admin Panel** - Backend management system for administrators
2. **Customer-Facing Website** - Frontend shopping experience for customers

## Key Features

### Admin Panel Features

1. **Dashboard**
   - Overview of key metrics and analytics
   - Sales statistics and performance indicators

2. **Product Management**
   - Product categories management
   - Product creation and editing
   - Product variant management with images
   - Inventory tracking

3. **Order Management**
   - View and process customer orders
   - Order status updates
   - Transaction history

4. **Customer Management**
   - Customer profile viewing and management
   - Address and contact information

5. **Content Management**
   - Blog management with categories
   - Testimonial management
   - Site settings and configuration

6. **AI-Powered Trend Analysis**
   - Fashion trend analysis using OpenAI integration
   - Market insights and recommendations

### Customer-Facing Features

1. **Shopping Experience**
   - Product browsing and filtering
   - Product detail pages with variant selection
   - Product image galleries

2. **Cart Functionality**
   - Add/remove items from cart
   - Update quantities
   - View cart summary

3. **Checkout Process**
   - User-friendly checkout flow
   - Address selection
   - Order confirmation and payment status

4. **User Engagement**
   - Blog/News section
   - Contact form
   - Responsive design for all devices

## Technical Stack

- **Backend**: Laravel PHP Framework
- **Database**: MySQL
- **Frontend**: Blade templates with JavaScript
- **Authentication**: Laravel built-in authentication
- **External APIs**: OpenAI integration for trend analysis

## Installation and Setup

1. Clone the repository
2. Configure your `.env` file based on `.env.example`
3. Install dependencies: `composer install` and `npm install`
4. Run migrations: `php artisan migrate`
5. Seed the database: `php artisan db:seed`
6. Start the development server: `php artisan serve`

## Environment Configuration

Key environment variables:
- `APP_NAME`: Application name
- `DB_DATABASE`: Database name (default: biha)
- `OPENAI_API_KEY`: API key for OpenAI integration
- `WEB_PRIMARY_COLOR`: Primary theme color
- `WEB_SECONDARY_COLOR`: Secondary theme color

## License

This application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
