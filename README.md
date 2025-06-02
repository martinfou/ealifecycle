# Trading Strategy Tracker

![Version](https://img.shields.io/badge/version-0.2.0-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.4%2B-purple.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

A comprehensive web-based application designed to help individual traders effectively track, manage, and analyze their trading strategies. Built with Laravel 12.16.0 and featuring a professional dark theme with monochromatic design and complete user management system.

## 🎯 Project Overview

The Trading Strategy Tracker addresses the fragmented nature of strategy management by providing a centralized platform for monitoring trading strategies across different statuses, managing historical data, and facilitating performance analysis. Version 0.2.0 introduces a complete admin user management system with group-based permissions and enhanced collaboration features.

## ✨ Latest Features (v0.2.0)

### 👥 Complete Admin User Management System
- **User Administration**: Full CRUD operations for managing user accounts
- **Interactive Group Assignment**: Real-time user-group permission management
- **User Activity Tracking**: Monitor user strategies, trade imports, and system activity
- **Advanced User Statistics**: Group memberships, strategy counts, and detailed metrics
- **Data Integrity Protection**: Smart user deletion with dependency validation

### 🔧 Enhanced User Experience
- **User Creation Interface**: Create new users with optional group assignments
- **User Detail Management**: Comprehensive user profiles with group membership controls
- **Permission Management**: Inline editing of user-group permissions (read/write)
- **Strategy Edit Dark Theme**: Fixed strategy edit form with consistent dark styling
- **Group Selection**: Added missing group assignment field to strategy edit form

### 🛡️ Security & Permission Improvements
- **Enhanced Authorization**: Comprehensive permission checking in all admin operations
- **Group Access Control**: Proper validation of user permissions before assignments
- **Session Management**: Improved admin session handling and security validation
- **Data Protection**: Safe operations with dependency checks to prevent data loss

## ✅ Implemented Features

### Core Strategy Management
- **Strategy CRUD Operations**: Create, view, edit, and delete trading strategies
- **Group-Based Sharing**: Assign strategies to groups for controlled collaboration
- **Permission-Based Access**: Read/write permissions for group members
- **Status Tracking**: Monitor strategies across Demo, Production, On Hold, and Retired statuses
- **Status History**: Complete audit trail of status changes with timestamps and notes
- **Timeframe Management**: Organize strategies by trading timeframes (M1, M5, H1, D1, etc.)
- **Magic Number Integration**: Associate strategies with trading platform magic numbers

### User & Group Management
- **User Administration**: Complete user management with creation, editing, and deletion
- **Group-Based Permissions**: Fine-grained read/write access control for strategy sharing
- **Activity Monitoring**: Track user strategies, imports, and system engagement
- **Permission Validation**: Comprehensive authorization checks throughout the system
- **Interactive Management**: Real-time user-group assignment with permission controls

### Data Import & Management
- **FX Blue Import**: Seamless CSV import from FX Blue trading history
- **Automatic Strategy Matching**: Auto-associate trades with strategies via magic numbers
- **Trade Management**: View, filter, and analyze imported trade data
- **Duplicate Detection**: Prevent duplicate trade imports

### Admin Interface
- **User Management**: Complete admin interface for user accounts and group assignments
- **Group Management**: CRUD operations for groups with user and strategy associations
- **Status Management**: CRUD operations for strategy statuses with color coding
- **Timeframe Management**: CRUD operations for trading timeframes with sort ordering
- **Usage Validation**: Prevent deletion of statuses/timeframes/users with associated data

### User Experience
- **Consistent Dark Theme**: Professional dark interface across all pages including forms
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
- **Users**: User accounts and authentication with relationship tracking
- **Strategies**: Core strategy information with group associations
- **Groups**: User groups for strategy sharing and collaboration
- **User Groups**: Pivot table with read/write permission management
- **Statuses**: Configurable strategy statuses with colors
- **Timeframes**: Trading timeframes with sort ordering
- **Status History**: Complete audit trail of status changes
- **Trades**: Imported trading data linked to strategies

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
- Complete strategy management workflow with group-based sharing
- User and group administration with permission management
- Status tracking and history
- FX Blue trade import functionality
- Admin interfaces for all system entities
- Responsive dashboard and navigation
- Dark theme across all interfaces

### 🔧 Recent Enhancements (v0.2.0)
- **Added**: Complete admin user management system with CRUD operations
- **Added**: Interactive group assignment with real-time permission management
- **Added**: User activity tracking and advanced statistics
- **Fixed**: Strategy edit form converted to dark theme with group selection
- **Fixed**: View cache issues and pivot table timestamp formatting
- **Enhanced**: Permission validation and authorization throughout system

## 🎯 Business Requirements Compliance

The application fully meets and exceeds the core business requirements:

- ✅ Centralized strategy management with status tracking
- ✅ Group-based collaboration with permission controls
- ✅ Complete user administration and access management
- ✅ Historical status monitoring with audit trail
- ✅ FX Blue trade history import with strategy association
- ✅ Secure user authentication and data protection
- ✅ Standardized timeframe and status management
- ✅ Performance data integration and analysis foundation

## 🔧 Development Guidelines

### Code Structure
- Controllers follow Laravel resource patterns with comprehensive validation
- Models include relationships, scopes, and permission checking methods
- Views use consistent dark theme with Blade components
- Database migrations include proper foreign keys, indexes, and constraints

### Security
- CSRF protection on all forms
- Comprehensive user authorization with group-based permissions
- Data validation on all inputs with detailed error handling
- Secure password hashing and session management

### Testing
- PHPUnit configuration included
- Test database seeding available
- Feature tests recommended for critical workflows
- Permission testing for group-based access control

## 📝 Deployment Notes

### Production Considerations
- Configure proper database (MySQL/PostgreSQL)
- Set up proper queue worker for background jobs
- Configure file storage for CSV imports
- Set up proper logging and monitoring
- Enable SSL/HTTPS
- Configure user group permissions and admin accounts

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
5. Test permission systems thoroughly
6. Maintain dark theme consistency

## 📄 License

This project is proprietary software developed for individual trading strategy management.

## 📋 Version History

For detailed release notes, see [CHANGELOG.md](CHANGELOG.md).

### Current Version: 0.2.0
- Complete admin user management system
- Interactive group permission management
- Strategy edit form dark theme fixes
- Enhanced security and validation

### Previous Versions
- **0.1.5**: Group-based permission system for strategies
- **0.1.4**: Complete dark theme implementation
- **0.1.3**: Professional dark design system
- **0.1.2**: Public landing page access
- **0.1.1**: Professional landing page
- **0.1.0**: Core trading strategy tracker

### Version Management
- Versions follow 0.x.y format for pre-release
- Major features increment x, minor features/fixes increment y
- Use `./version-bump.sh` script to automatically update versions
- See [CHANGELOG.md](CHANGELOG.md) for complete history

---

**Version**: 0.2.0  
**Last Updated**: December 28, 2024  
**Laravel Version**: 12.16.0  
**PHP Version**: 8.4.7
