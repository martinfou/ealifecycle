# Trading Strategy Tracker

![Version](https://img.shields.io/badge/version-0.1.4-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.4%2B-purple.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

A comprehensive web-based application designed to help individual traders effectively track, manage, and analyze their trading strategies. Built with Laravel 12.16.0 and featuring a professional dark theme with monochromatic design.

## 🎯 Project Overview

The Trading Strategy Tracker addresses the fragmented nature of strategy management by providing a centralized platform for monitoring trading strategies across different statuses, managing historical data, and facilitating performance analysis. Version 0.1.4 introduces a complete dark theme with modern monochromatic styling.

## ✨ Latest Features (v0.1.4)

### 🎨 Dark Theme & Design System
- **Complete Dark Theme**: Professional dark interface across all pages
- **Monochromatic Design**: Consistent gray color palette for all actions
- **Enhanced Navigation**: Clickable brand name linking to homepage
- **Smooth Transitions**: Professional hover effects and animations
- **Safety-First UX**: Red styling preserved for delete buttons

### 📱 User Experience Improvements
- **Visual Hierarchy**: Different gray shades for button importance levels
- **Accessibility**: Improved color contrast ratios
- **Professional Styling**: Modern interface optimized for trading professionals
- **Responsive Design**: Enhanced mobile and desktop experience

## ✅ Implemented Features

### Core Strategy Management
- **Strategy CRUD Operations**: Create, view, edit, and delete trading strategies
- **Status Tracking**: Monitor strategies across Demo, Production, On Hold, and Retired statuses
- **Status History**: Complete audit trail of status changes with timestamps and notes
- **Timeframe Management**: Organize strategies by trading timeframes (M1, M5, H1, D1, etc.)
- **Magic Number Integration**: Associate strategies with trading platform magic numbers

### Data Import & Management
- **FX Blue Import**: Seamless CSV import from FX Blue trading history
- **Automatic Strategy Matching**: Auto-associate trades with strategies via magic numbers
- **Trade Management**: View, filter, and analyze imported trade data
- **Duplicate Detection**: Prevent duplicate trade imports

### Admin Interface
- **Status Management**: CRUD operations for strategy statuses with color coding
- **Timeframe Management**: CRUD operations for trading timeframes with sort ordering
- **Usage Validation**: Prevent deletion of statuses/timeframes in use

### User Experience
- **Responsive Design**: Mobile-friendly interface with Tailwind CSS
- **Dashboard**: Overview of strategies, recent activities, and quick actions
- **Authentication**: Secure user registration, login, and password reset
- **Professional UI**: Clean, modern interface designed for traders

## 🏗️ Technical Architecture

### Framework & Dependencies
- **Backend**: Laravel 12.16.0 with PHP 8.4.7
- **Database**: SQLite (easily configurable for other databases)
- **Frontend**: Tailwind CSS with Alpine.js components
- **Build Tools**: Vite for asset compilation

### Database Schema
- **Users**: User accounts and authentication
- **Strategies**: Core strategy information with foreign keys
- **Statuses**: Configurable strategy statuses with colors
- **Timeframes**: Trading timeframes with sort ordering
- **Status History**: Complete audit trail of status changes
- **Trades**: Imported trading data linked to strategies
- **Groups & User Groups**: Role-based access (foundation implemented)

## 🚀 Quick Start

### Prerequisites
- PHP 8.4+
- Composer
- Node.js & NPM

### Installation
1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Setup environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
5. Build assets:
   ```bash
   npm run build
   ```
6. Start the development server:
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access the application.

## 📊 Current Status

### ✅ Fully Functional
- User authentication and registration
- Complete strategy management workflow
- Status tracking and history
- FX Blue trade import functionality
- Admin interfaces for statuses and timeframes
- Responsive dashboard and navigation

### ⚠️ Partially Implemented
- **Groups Management**: Controller exists but implementation pending
- **Users Management**: Controller exists but implementation pending

### 🔄 Recent Bug Fixes (Latest Commit)
- **Fixed**: Missing timeframes admin views causing 500 errors
- **Fixed**: Missing strategy detail, edit, and history views
- **Fixed**: StatusHistory table name mismatch causing dashboard crashes
- **Enhanced**: Professional .gitignore with comprehensive exclusions

## 🎯 Business Requirements Compliance

The application fully meets the core business requirements outlined in the Business Needs Document:

- ✅ Centralized strategy management with status tracking
- ✅ Historical status monitoring with audit trail
- ✅ FX Blue trade history import with strategy association
- ✅ Secure user authentication and data protection
- ✅ Standardized timeframe and status management
- ✅ Performance data integration and analysis foundation

## 🔧 Development Guidelines

### Code Structure
- Controllers follow Laravel resource patterns
- Models include appropriate relationships and scopes
- Views use Blade components for consistency
- Database migrations include proper foreign keys and indexes

### Security
- CSRF protection on all forms
- User authorization with policies
- Data validation on all inputs
- Secure password hashing

### Testing
- PHPUnit configuration included
- Test database seeding available
- Feature tests recommended for critical workflows

## 📝 Deployment Notes

### Production Considerations
- Configure proper database (MySQL/PostgreSQL)
- Set up proper queue worker for background jobs
- Configure file storage for CSV imports
- Set up proper logging and monitoring
- Enable SSL/HTTPS

### Environment Variables
Key variables to configure:
- `DB_*`: Database connection settings
- `MAIL_*`: Email configuration for notifications
- `APP_URL`: Application URL for proper links
- `LOG_LEVEL`: Logging configuration

## 🤝 Contributing

1. Follow Laravel coding standards
2. Include tests for new features
3. Update documentation for significant changes
4. Ensure migrations are reversible
5. Test thoroughly across different screen sizes

## 📄 License

This project is proprietary software developed for individual trading strategy management.

## 📋 Version History

For detailed release notes, see [CHANGELOG.md](CHANGELOG.md).

### Current Version: 0.1.4
- Complete dark theme implementation
- Monochromatic design system
- Enhanced navigation and UX

### Version Management
- Versions follow 0.x.y format for pre-release
- Increment by 0.0.1 for each significant commit
- Use `./version-bump.sh` script to automatically update versions
- See [CHANGELOG.md](CHANGELOG.md) for complete history

---

**Version**: 0.1.4  
**Last Updated**: December 28, 2024  
**Laravel Version**: 12.16.0  
**PHP Version**: 8.4.7
