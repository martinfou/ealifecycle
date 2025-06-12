# 🤖 EALifeCycle: Expert Advisor Lifecycle Management

![Version](https://img.shields.io/badge/version-0.3.0-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.4%2B-purple.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

**The definitive platform for managing Expert Advisors professionally** - Complete EA lifecycle management with DevOps-inspired workflows for algorithmic trading teams.

## 🎯 What is EALifeCycle?

EALifeCycle helps algorithmic traders manage their Expert Advisors (trading robots) through their complete lifecycle: Development → Testing → Production → Maintenance → Retirement. Built with Laravel 12 and designed for trading teams, prop firms, and EA developers.

**Key Features:**
- 📚 **EA Registry**: Central repository for all trading robots
- 📋 **Stage Management**: Move EAs through development phases  
- 👥 **Team Collaboration**: Multi-user with group-based permissions
- 📊 **Performance Monitoring**: Track EA performance and status history
- 📥 **Trade Import**: FX Blue CSV integration with automatic EA matching

## Features

- Strategy Management
  - Create, read, update, and delete trading strategies
  - Upload and download strategy source code files
  - Track strategy status and history
  - Manage strategy timeframes and symbols

- Portfolio Management
  - Create and manage trading portfolios
  - Add/remove strategies to/from portfolios
  - Track portfolio performance
  - Manage portfolio allocations

- REST API
  - Secure API endpoints using Laravel Sanctum
  - Token-based authentication
  - Strategy and portfolio management
  - Secure file upload/download
  - OpenAPI/Swagger documentation
  - Proper file handling with download headers

- User Management
  - Role-based access control
  - Group-based permissions
  - API token management interface
  - Secure authentication

## API Documentation

The REST API documentation is available in OpenAPI/Swagger format. Key endpoints include:

### Strategies
- `GET /api/v1/strategies` - List strategies
- `POST /api/v1/strategies` - Create strategy
- `GET /api/v1/strategies/{id}` - Get strategy details
- `PUT /api/v1/strategies/{id}` - Update strategy
- `DELETE /api/v1/strategies/{id}` - Delete strategy
- `POST /api/v1/strategies/{id}/source-code` - Upload source code
- `GET /api/v1/strategies/{id}/source-code` - Download source code

### Portfolios
- `GET /api/v1/portfolios` - List portfolios
- `POST /api/v1/portfolios` - Create portfolio
- `GET /api/v1/portfolios/{id}` - Get portfolio details
- `PUT /api/v1/portfolios/{id}` - Update portfolio
- `DELETE /api/v1/portfolios/{id}` - Delete portfolio

### Authentication
- Generate API tokens through the `/tokens` web interface
- Use tokens in the `Authorization: Bearer {token}` header

---

## 🚀 Quick Start Guide

### For Senior Developers (TL;DR)

```bash
# Clone and setup
git clone https://github.com/martinfou/ealifecycle.git
cd ealifecycle
composer install && npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database and build
php artisan migrate --seed
npm run build

# Start development
php artisan serve
```

Visit `http://localhost:8000` - Default login: `admin@example.com` / `password`

### For Novice Developers (Step-by-Step)

**📋 Prerequisites First**
1. **Install PHP 8.4+**: Download from [php.net](https://www.php.net/downloads)
2. **Install Composer**: Download from [getcomposer.org](https://getcomposer.org/)
3. **Install Node.js**: Download from [nodejs.org](https://nodejs.org/)
4. **Install Git**: Download from [git-scm.com](https://git-scm.com/)

**🔧 Verify Your Installation**
```bash
php --version    # Should show 8.4+
composer --version
node --version
npm --version
git --version
```

**📥 Get the Project**
```bash
# Download the project
git clone https://github.com/martinfou/ealifecycle.git

# Enter the project folder
cd ealifecycle

# Install PHP dependencies (this may take a few minutes)
composer install

# Install JavaScript dependencies
npm install
```

**⚙️ Configure the Application**
```bash
# Create your environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create the database and add sample data
php artisan migrate --seed

# Build the frontend assets
npm run build
```

**🌐 Start the Application**
```bash
# Start the development server
php artisan serve
```

Open your browser and go to: `http://localhost:8000`

**🔑 First Login**
- Email: `admin@example.com`
- Password: `password`

---

## 🛠️ Development Setup

### Project Structure
```
ealifecycle/
├── app/
│   ├── Http/Controllers/     # Application logic
│   ├── Models/              # Database models
│   └── Policies/            # Permission rules
├── database/
│   ├── migrations/          # Database structure
│   └── seeders/            # Sample data
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript
├── routes/
│   └── web.php             # Application routes
└── config/                 # Configuration files
```

### Key Files to Know
- **`routes/web.php`** - All application URLs
- **`app/Models/Strategy.php`** - Main EA model
- **`app/Http/Controllers/StrategyController.php`** - EA management logic
- **`resources/views/strategies/`** - EA management pages
- **`database/migrations/`** - Database structure changes

### Development Workflow

**🔄 Daily Development**
```bash
# Start development server
php artisan serve

# In another terminal, watch for asset changes
npm run dev

# Run migrations when database changes
php artisan migrate

# Build assets before running tests
npm run build

# Run tests
vendor/bin/phpunit
```

**📊 Working with Data**
```bash
# Reset database with fresh data
php artisan migrate:fresh --seed

# Create a new migration
php artisan make:migration add_new_field_to_strategies

# Create a new model
php artisan make:model NewModel -m
```

### Common Development Tasks

#### Adding a New EA Status
1. **Add to database**: Create migration in `database/migrations/`
2. **Update seeder**: Add to `database/seeders/StatusSeeder.php`
3. **Update views**: Add color/styling in blade templates

#### Creating a New Page
1. **Add route**: In `routes/web.php`
2. **Create controller method**: In appropriate controller
3. **Create view**: In `resources/views/`
4. **Add navigation**: In `resources/views/layouts/navigation.blade.php`

#### Working with User Permissions
```php
// Check if user can edit a strategy
if ($user->canEdit($strategy)) {
    // Allow editing
}

// Get strategies user can access
$strategies = $user->accessibleStrategies();
```

### Development Setup

To get started with development, follow these steps after cloning the repository:

1.  **Install Dependencies**:
    ```bash
    composer install
    npm install
    ```

2.  **Activate Git Hooks**:
    To ensure code quality and prevent common errors, we use shared Git hooks. Activate them by running:
    ```bash
    git config core.hooksPath .githooks
    ```
    This will enable automated checks before you commit.

## 🧪 Testing Your Installation

### Verify Everything Works
1. **Login**: Use `admin@example.com` / `password`
2. **Create EA**: Go to Expert Advisors → Create New
3. **Import Trades**: Go to Trades → Import from FX Blue
4. **Manage Users**: Go to Admin → Users (admin only)

### Sample Data Included
- **3 User Accounts**: Admin, Trader, Viewer with different permissions
- **2 Trading Groups**: "Scalping Team" and "Swing Traders"
- **5 Sample EAs**: Various statuses and timeframes
- **Status Types**: Demo, Production, On Hold, Retired
- **Timeframes**: M1, M5, M15, H1, H4, D1, W1

---

## 🆘 Troubleshooting

### Common Issues & Solutions

**❌ "Class 'PDO' not found"**
```bash
# Install PHP PDO extension
# Ubuntu/Debian:
sudo apt-get install php8.4-pdo php8.4-sqlite3

# macOS with Homebrew:
brew install php@8.4
```

**❌ "Permission denied" on storage folder**
```bash
# Fix Laravel permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

**❌ "Vite manifest not found"**
```bash
# Build the assets
npm run build

# Or for development
npm run dev
```

**❌ "Database does not exist"**
```bash
# Create SQLite database file
touch database/database.sqlite

# Run migrations
php artisan migrate
```

**❌ "Node.js version too old"**
```bash
# Update Node.js to latest LTS version
# Then reinstall dependencies
rm -rf node_modules package-lock.json
npm install
```

### Performance Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
```

---

## 📚 Learning Resources

### For Laravel Beginners
- [Laravel Documentation](https://laravel.com/docs) - Official docs
- [Laracasts](https://laracasts.com/) - Video tutorials
- [Laravel Bootcamp](https://bootcamp.laravel.com/) - Hands-on tutorial

### For Trading Domain
- **Expert Advisor**: Automated trading robot (usually MetaTrader)
- **Magic Number**: Unique ID to identify EA trades
- **FX Blue**: Popular trade analysis service for forex
- **Timeframes**: M1=1min, M5=5min, H1=1hour, D1=daily, etc.

### Project-Specific Concepts
- **Strategy = Expert Advisor = Trading Robot** (we use these terms interchangeably)
- **Status History**: Track when EAs move between Demo/Production/etc.
- **Group Permissions**: Control who can view/edit which EAs

---

## 🔧 Configuration

### Environment Variables (.env)
```bash
# Application
APP_NAME=EALifeCycle
APP_URL=http://localhost:8000

# Database (default: SQLite)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# For MySQL instead:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=ealifecycle
# DB_USERNAME=your_username
# DB_PASSWORD=your_password
```

### Switching to MySQL
1. Create MySQL database: `CREATE DATABASE ealifecycle;`
2. Update `.env` with MySQL settings
3. Run migrations: `php artisan migrate --seed`

---

## 🤝 Contributing

Thank you for considering contributing to EALifeCycle! Please see `CONTRIBUTING.md` for details.

### Getting Started
1. **Fork the repository** on GitHub
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Make your changes** following the code style
4. **Test thoroughly** with sample data
5. **Commit with clear message**: `git commit -m "Add amazing feature"`
6. **Push and create Pull Request**

### Code Style
- Follow **Laravel conventions**
- Use **meaningful variable names**
- Add **comments for complex logic**
- Keep **methods focused and small**
- Write **descriptive commit messages**

### Testing Changes
```bash
# Test basic functionality
php artisan migrate:fresh --seed
php artisan serve

# Test with different user roles
# Login as: admin@example.com, trader@example.com, viewer@example.com
```

---

## 📞 Support

### Need Help?
- **📖 Check Documentation**: Most answers are in Laravel docs
- **🐛 Found a Bug?**: Create an issue on GitHub
- **💡 Feature Request?**: Open a discussion on GitHub
- **🤔 General Questions?**: Check Laravel community forums

### Before Asking for Help
1. **Check error logs**: `storage/logs/laravel.log`
2. **Try clearing caches**: `php artisan cache:clear`
3. **Verify environment**: Check `.env` file settings
4. **Search existing issues**: Someone might have solved it already

---

## 📋 Version Information

**Last Updated**: June 2, 2025  
**Current Version**: v0.3.6  
**Next Milestone**: v1.1.0 (Groups & Users Admin) 

For detailed changelog, see [CHANGELOG.md](CHANGELOG.md).

---

**🚀 Ready to manage your Expert Advisors professionally? Start with the Quick Start guide above!** 
# Force deploy trigger

## Security Vulnerabilities

Please review our [security policy](../../security/policy) on how to report security vulnerabilities.

## 🧪 Running Tests

Before running tests locally, make sure you have built the frontend assets so that the Vite manifest exists:

```bash
npm install
npm run build
```

The GitHub Actions workflow now automatically installs Node.js dependencies and builds assets before running PHPUnit tests, so this error should not occur in CI/CD.

# Trading Strategy Tracker

## Features

- **Strategy Management**: Create, edit, and view trading strategies with fields for name, symbols, timeframes, magic number, group, and description.
- **Source Code Upload**: Upload a single source code file per strategy. Download and view the file in a modal with syntax highlighting. Delete or replace the file from the edit page.
- **Backtest Report Upload**: Upload a single PDF backtest report per strategy. Download the report or view it in a modal. Replace or delete the report from the edit page.
- **File Management UX**:
  - On the **show page**: Both source code and backtest report fields display the filename as a blue download link, with a green "View" link for modal viewing. Only one file of each type is allowed per strategy.
  - On the **edit page**: Both fields show the current file (if any) with green "View" and red "Delete" links. Uploading a new file replaces the old one. Deleting removes the file from the strategy.
- **Permissions**: Only users with access to a strategy can upload, view, or delete files.
- **CI/CD**: Asset build and cache clear steps are required for tests and deployment. See below.

## Setup & Development

- Run `php artisan migrate` to set up the database.
- Run `php artisan storage:link` to enable public file access.
- Build frontend assets with `npm install && npm run build` before running tests or deploying.
- Clear caches with `php artisan optimize:clear && php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear` after code or view changes.

## File Storage

- Source code files are stored in `storage/app/public/strategy_source_codes`.
- Backtest reports are stored in `storage/app/public/reports`.
- Only one file of each type is allowed per strategy. Uploading a new file replaces the old one.

## UI/UX

- **Show Page**: Download and view links for both files. No delete option for backtest report.
- **Edit Page**: Download, view, and delete links for both files. Uploading a new file replaces the old one.
- **Modals**: In-page modal viewers for both PDF and source code files, with resizable and fullscreen options.

## UI/UX Improvements

- The close (X) button in all file view modals (PDF and source code) now has extra padding and is positioned further from the top and right borders. This ensures the button is not too close to the edge, providing a more polished and accessible appearance on all devices.

## Contribution

- Please use feature branches and submit pull requests for review.
- Keep documentation and UI consistent with the latest features.

## Strategy File Management (Source Code & Backtest Reports)

- On the **edit strategy** page, you can upload, view, download, or delete both the source code file and the backtest report (PDF).
- If a file is present, its info, download, and view links are shown, along with a red delete button.
- **Deleting a file** (source code or report):
  - Works instantly via AJAX—no confirmation popup.
  - Only the file info and delete button are removed from the page; the upload field remains visible so you can immediately upload a new file.
  - A green toast notification confirms successful deletion.
  - The file is deleted from both the database and the file system.
- On the **show strategy** page, you can view and download files, but not delete them.
- All modals for viewing files are resizable and modern.
