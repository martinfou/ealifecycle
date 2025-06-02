# 🤖 EALifecycle: Expert Advisor Lifecycle Management

![Version](https://img.shields.io/badge/version-0.3.0-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.4%2B-purple.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

**The definitive platform for managing Expert Advisors professionally** - A comprehensive web-based application designed to help algorithmic traders effectively track, manage, and analyze their automated trading strategies throughout their complete lifecycle. Built with Laravel 12.16.0 and featuring a professional dark theme with monochromatic design and complete user management system.

## 🎯 Project Overview

**EALifecycle** addresses the fragmented nature of Expert Advisor (EA) management by providing a centralized platform for monitoring trading robots across different lifecycle stages, managing historical data, and facilitating performance analysis. Our platform transforms manual, error-prone processes into professional DevOps-inspired workflows for autonomous trading systems.

Version 0.2.0 introduces a complete admin user management system with group-based permissions, making EALifecycle the perfect solution for:
- 🏢 **Algorithmic trading teams**
- 💰 **Prop trading firms** 
- 👨‍💻 **Individual EA developers**
- 🔬 **Trading robot researchers**
- 🏦 **Financial institutions with automated trading**

## 🤖 What "EA" Means in Trading

| Term | Definition |
|------|------------|
| **Expert Advisor** | Automated trading robots/algorithms in platforms like MetaTrader |
| **Expert Agent** | Autonomous trading systems with AI/ML capabilities |
| **Essential Automation** | Core automated trading functionality |

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

## 🔄 EA Lifecycle Management Scope

### 1. 🛠️ **Development Phase**
- Strategy creation and backtesting
- Code development and optimization
- Risk management implementation

### 2. 🧪 **Testing Phase**
- Demo account deployment
- Paper trading validation
- Performance metrics collection

### 3. 🚀 **Production Phase**
- Live deployment management
- Real-time monitoring
- Performance tracking

### 4. 🔧 **Maintenance Phase**
- Parameter optimization
- Market condition adaptation
- Risk adjustment

### 5. 📊 **Retirement Phase**
- Graceful shutdown procedures
- Historical analysis
- Knowledge preservation

## ✅ Core EALifecycle Features

### Expert Advisor Management
- **EA CRUD Operations**: Create, view, edit, and delete trading strategies/robots
- **Group-Based Sharing**: Assign EAs to groups for controlled collaboration
- **Permission-Based Access**: Read/write permissions for group members
- **Status Tracking**: Monitor EAs across Demo, Production, On Hold, and Retired statuses
- **Status History**: Complete audit trail of status changes with timestamps and notes
- **Timeframe Management**: Organize strategies by trading timeframes (M1, M5, H1, D1, etc.)
- **Magic Number Integration**: Associate strategies with trading platform magic numbers

### 📚 **EA Registry**
Central repository for all trading agents

### 📋 **Stage Management** 
Move EAs through development → testing → production

### 📊 **Performance Monitoring**
Real-time metrics and health checks

### 🔄 **Version Control**
Track EA iterations and rollback capabilities

### ⚠️ **Risk Management**
Automated controls and emergency stops

### 📝 **Compliance Tracking**
Audit trails and regulatory reporting

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

## ✅ Why EALifecycle Works

| Advantage | Description |
|-----------|-------------|
| **🎯 Domain-Specific** | Immediately recognizable to algorithmic traders |
| **📈 Comprehensive** | Covers entire EA journey from birth to retirement |
| **💼 Professional** | Suggests enterprise-grade management capabilities |
| **📊 Scalable** | Implies handling multiple EAs across different stages |
| **⚙️ DevOps-Inspired** | Lifecycle approach mirrors modern software operations |

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

Visit `http://localhost:8000` to access EALifecycle.

## 📊 Current Status

### ✅ Fully Functional
- User authentication and registration
- Complete EA management workflow with group-based sharing
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

EALifecycle fully meets and exceeds the core business requirements for Expert Advisor management:

- ✅ Centralized EA lifecycle management with status tracking
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

This project is proprietary software developed for Expert Advisor lifecycle management.

## 📋 Version History

For detailed release notes, see [CHANGELOG.md](CHANGELOG.md).

### Current Version: 0.2.0
- Complete admin user management system
- Enhanced EA lifecycle management capabilities

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


