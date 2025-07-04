# EALifeCycle Changelog

All notable changes to **EALifeCycle** (Expert Advisor Lifecycle Management) will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Security
- Restricted API documentation access to authenticated administrators only
- Disabled runtime Swagger documentation generation in production
- Added middleware protection for all Swagger-related routes

### Added
- New `CheckIfAdmin` middleware for protecting administrative routes
- Environment-specific Swagger generation configuration
- Implemented Swagger UI for API documentation, accessible at `/api/documentation`.
- Added an "API" dropdown menu with links to "API Tokens" and "API Docs".
- Added Symbol Management feature with CSV data import support.
- Integrated Symbol Management into the Admin interface.

### Changed
- Moved Swagger package from dev dependencies to main requirements
- Updated Swagger configuration for better security practices
- **Symbol Management UI**: Improved table design and user experience
  - Professional dark theme styling consistent with timeframes
  - Enhanced table layout with proper spacing and typography
  - Improved action buttons and hover states
  - Added informative documentation box
  - Responsive design for all device sizes

### Fixed
- Corrected vertical alignment for dropdown menus in the main navigation bar.
- Fixed `RouteNotFoundException` for admin links in the navigation.
- Resolved duplicate admin menu items by consolidating navigation structure.

## [0.4.0] - 2025-06-11

### Added
- Feature: Users can now upload, download, and update MT4/MT5 source code files (.mq4, .mq5, .ex4, .ex5) for each strategy.
- The original filename is preserved for downloads.
- A dedicated, secure route now handles file downloads.

## [0.3.6] - 2025-06-02

### Added
- **Professional Trading Dashboard Image**: Created new `trading-dashboard.svg` for hero section
  - Professional control center interface showing portfolio performance, risk metrics, and EA status
  - Integrated EA lifecycle concepts (💡 IDEA, 🧪 DEMO, 🚀 PRODUCTION, ⏸️ PAUSED, 📦 RETIRED)
  - Real-time profit charts with animated data visualization
  - Control metrics including risk gauges and stability meters
  - Transparent background for seamless website integration
  - Represents control, management, stability, and profit themes

### Changed
- **Hero Section Enhancement**: Replaced lifecycle flow diagram with professional trading dashboard
- **Image Size Optimization**: Increased EA lifecycle flow image from `max-w-4xl` to `max-w-7xl` (100% bigger)
- **Content Streamlining**: Removed duplicate lifecycle stage information grid to avoid redundancy

### Fixed
- **XML Parsing Issues**: Fixed unescaped ampersand in SVG causing XML parsing errors
- **Background Transparency**: Completely removed all background elements for seamless blending
- **Cache Management**: Cleared all Laravel caches to ensure updated assets are served

### Technical Details
- Created transparent SVG with animated elements and gradients
- Enhanced hero section layout with better visual hierarchy
- Improved asset caching and delivery
- Maintained responsive design across all device sizes

## [0.3.5] - 2025-06-02

### Added
- **Algorithmic Trading Images**: Created three custom SVG illustrations for the home page:
  - `trading-charts.svg`: Advanced candlestick chart with trend lines and technical indicators
  - `robot-trader.svg`: Animated AI-powered trading robot with real-time market displays
  - `automation-flow.svg`: Comprehensive algorithmic trading automation workflow diagram
- **Enhanced Home Page Visual Appeal**: 
  - Added hero section with automation flow image
  - Integrated trading charts visualization in features section
  - Included AI robot trader image in benefits section
  - Improved layout with grid-based design for better content presentation

### Changed
- Restructured hero section to use two-column layout with image
- Enhanced "EA Management Features" section with trading visualization
- Replaced static dashboard preview with dynamic AI robot trader illustration
- Improved visual hierarchy and spacing throughout the home page

### Technical Details
- Created `public/images/algo-trading/` directory structure
- Used modern SVG with CSS animations and gradients
- Maintained dark theme consistency with existing design
- Optimized for responsive display across all device sizes

## [0.3.4] - 2025-06-02

### Fixed
- **Deployment Issues**: Fixed multiple deployment workflow problems
  - Added PHP version detection and automatic fallback (php8.4/php8.3/php8.2)
  - Implemented automatic .htaccess creation for Laravel routing
  - Fixed tar packaging with two-stage rsync approach
  - Added package-lock.json to repository for npm caching
  - Resolved "405 Method Not Allowed" errors with proper mod_rewrite rules

### Added
- **Enhanced Deployment Workflow**: 
  - Automatic PHP version detection on Dreamhost servers
  - Comprehensive error handling and rollback capabilities
  - Automated .htaccess file creation for subdirectory deployment
  - Platform requirement checks with ignore flags for compatibility

### Updated
- **Deployment Documentation**: Added comprehensive subdirectory deployment guide
- **Error Handling**: Improved deployment error messages and troubleshooting steps
- **Workflow Reliability**: Enhanced GitHub Actions workflow with better error recovery

## [0.3.3] - 2025-06-02

### Enhanced
- **Strategy Form Examples**: Added comprehensive trading symbol examples
  - **Forex pairs**: EURUSD, GBPJPY, XAUUSD (Gold), XAGUSD (Silver)
  - **Stock symbols**: AAPL, TSLA, SPY, QQQ, MSFT, AMZN
  - **Futures contracts**: ES (S&P 500), NQ (Nasdaq), CL (Crude Oil), GC (Gold)
- **User Experience**: Improved "Symbols Traded" field with clear examples covering multiple asset classes
- **Forms Updated**: Enhanced both create and edit strategy forms

## [0.3.2] - 2025-06-02

### Changed
- **Branding Consistency**: Updated all instances from "EALifecycle" to "EALifeCycle" (capital C)
- **Comprehensive Update**: Applied consistent capitalization across:
  - All view templates and layouts
  - Configuration files (app.php, package.json, composer.json)
  - Documentation (README.md, project reflection)
  - Authentication pages and navigation
  - Error pages and components

### Technical
- Maintained backward compatibility
- Updated application name in all relevant configuration files
- Enhanced professional appearance with consistent branding

## [0.3.1] - 2025-06-02

### Fixed
- **Branding Update**: Replaced "Trading Strategy Tracker" with "EALifeCycle" across authentication pages
- **Updated Pages**:
  - Registration page: Enhanced with EA lifecycle messaging
  - Login page: Updated branding and descriptions
  - Guest layout: Consistent EALifeCycle branding

### Enhanced
- **User Experience**: Improved registration page with EA-specific feature highlights
- **Messaging**: Updated copy to focus on Expert Advisor lifecycle management
- **Visual Consistency**: Aligned all authentication flows with EALifeCycle brand

## [0.3.0] - 2025-06-02

### Added
- **User Group Management System**: Complete multi-user collaboration features
  - User groups with customizable permissions (view, create, edit, delete, admin)
  - Admin interface for managing groups and user assignments
  - Role-based access control for strategies and trades
- **Enhanced Strategy Management**: 
  - Group assignment for strategies
  - Improved filtering and organization by user groups
  - Permission-based strategy visibility
- **Admin Features**:
  - User management interface
  - Group creation and permission management
  - User-to-group assignment capabilities

### Technical Improvements
- Database migrations for user groups and permissions
- Middleware for role-based access control
- Enhanced Eloquent models with relationships
- Comprehensive admin interfaces

## [0.2.0] - 2025-06-01

### Added
- **Complete Strategy Lifecycle Management**: Full CRUD operations for Expert Advisor strategies
- **Advanced Trade Import System**: 
  - FX Blue CSV format support with intelligent parsing
  - Automatic strategy mapping via magic numbers
  - Bulk import capabilities with duplicate detection
  - Comprehensive trade validation and error handling
- **Comprehensive Admin Interface**:
  - Status management (Development, Demo, Production, On Hold, Retired)
  - Timeframe management (M1, M5, M15, M30, H1, H4, D1, W1, MN1)
  - Configurable EA lifecycle stages
- **Professional Dashboard**: 
  - Real-time EA status overview
  - Performance metrics and analytics
  - Quick action buttons for common tasks
- **Status History Tracking**: Complete audit trail of EA lifecycle changes

### Technical Features
- Database architecture for scalable EA management
- Robust CSV parsing with error recovery
- Magic number-based strategy identification
- Professional UI with Tailwind CSS dark theme
- Responsive design for all device types

### Enhanced User Experience
- Intuitive strategy creation and editing workflows
- Comprehensive search and filtering capabilities
- Professional algorithmic trading interface
- DevOps-inspired EA management workflows

## [0.1.0] - 2025-06-01

### Added
- **Initial EALifeCycle Release**: Core Expert Advisor lifecycle management platform
- **Laravel 11 Foundation**: Modern PHP framework with professional architecture
- **User Authentication System**: Complete registration, login, and profile management
- **Database Structure**: Initial schema for strategies, trades, and user management
- **Professional UI**: Dark theme interface optimized for trading professionals
- **DevOps-Inspired Workflows**: EA management with development lifecycle concepts

### Core Features
- User registration and authentication
- Basic strategy and trade models
- Professional dark theme interface
- Responsive design for desktop and mobile
- Foundation for EA lifecycle management

### Technical Foundation
- Laravel 11 with modern PHP 8.4+ support
- Tailwind CSS for professional UI components
- SQLite database for development (MySQL for production)
- Comprehensive testing environment
- Git-based version control with semantic versioning

## Version Numbering Scheme

This project follows a modified semantic versioning approach:
- **0.x.y** format for pre-release versions
- **y** increments by 1 for each significant commit/feature
- **x** increments for major feature releases or breaking changes
- Will move to **1.0.0** when ready for production release

## Categories

- **Added** for new features
- **Changed** for changes in existing functionality  
- **Deprecated** for soon-to-be removed features
- **Removed** for now removed features
- **Fixed** for any bug fixes
- **Security** for vulnerability fixes
- **Enhanced** for improvements to existing features
- **Technical** for development/infrastructure changes

### CI/CD
- Added a validation step to the GitHub Actions workflow to ensure `composer.lock` is up-to-date on all pull requests. 