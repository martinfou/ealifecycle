# EALifeCycle Development Workflow

## 🚀 Local Development Setup

### Prerequisites
- PHP 8.2+ 
- Composer
- Node.js & npm
- Git

### Quick Start
```bash
# Clone the repository
git clone https://github.com/martinfou/ealifecycle.git
cd ealifecycle

# Install dependencies
composer install
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Run migrations (SQLite by default for local dev)
php artisan migrate

# Build assets
npm run build

# Start local development server
php artisan serve
```

## 🌳 Branching Strategy

### Branch Types
- **main**: Production-ready code only
- **feature/**: New features (`feature/add-user-management`)
- **fix/**: Bug fixes (`fix/database-connection`)
- **refactor/**: Code improvements (`refactor/optimize-queries`)

### Development Workflow

#### 1. Create Feature Branch
```bash
# Always start from main
git checkout main
git pull origin main

# Create new feature branch
git checkout -b feature/your-feature-name
```

#### 2. Develop & Test Locally
```bash
# Start development server
php artisan serve

# For real-time asset compilation
npm run dev

# Run tests
php artisan test
```

#### 3. Commit Changes
```bash
git add .
git commit -m "Add feature: your feature description"
```

#### 4. Push Feature Branch
```bash
# Push to remote repository
git push origin feature/your-feature-name
```

#### 5. Create Pull Request
- Go to GitHub
- Create Pull Request from `feature/your-feature-name` to `main`
- Review changes before merging

### 🧪 Local Testing Commands

```bash
# Start development server
php artisan serve
# App available at: http://localhost:8000

# Watch for asset changes (in separate terminal)
npm run dev

# Run database migrations
php artisan migrate

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Run tests
php artisan test

# Check code style
./vendor/bin/pint --test
```

## 🚀 Deployment Process

### Local to Production Workflow

1. **Develop locally** on feature branch
2. **Test thoroughly** using `php artisan serve`
3. **Push feature branch** to GitHub
4. **Create Pull Request** for review
5. **Merge to main** only after testing
6. **Production deployment** happens automatically via GitHub Actions

### Manual Production Deployment (if needed)
```bash
# Only merge to main when ready for production
git checkout main
git merge feature/your-feature-name
git push origin main
# This triggers automatic deployment to https://4xhacker.com/ealifecycle/
```

## 📁 Project Structure

```
ealifecycle/
├── app/                    # Laravel application code
├── database/              # Migrations, seeders, factories
├── public/                # Web server document root
│   └── images/            # Static images
│       └── algo-trading/  # Trading-related SVG images
├── resources/             # Views, CSS, JS source files
├── routes/                # Application routes
├── storage/               # Logs, cache, sessions
├── .env                   # Local environment config
├── .env.example           # Environment template
└── .github/workflows/     # CI/CD configuration
```

## 🔧 Environment Configuration

### Local Development (.env)
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# SQLite database automatically created
```

### Production (.env.production on server)
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://4xhacker.com/ealifecycle

DB_CONNECTION=mysql
DB_HOST=mysql.4xhacker.com
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

## 🛠️ Troubleshooting

### Local Development Issues

**Permission errors:**
```bash
chmod -R 755 storage bootstrap/cache
```

**Cache issues:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

**Database issues:**
```bash
# Reset local database
rm database/database.sqlite
php artisan migrate:fresh
```

**Asset compilation issues:**
```bash
rm -rf node_modules public/build
npm install
npm run build
```

## 📝 Best Practices

1. **Never push directly to main**
2. **Always test locally first**
3. **Use descriptive commit messages**
4. **Keep feature branches small and focused**
5. **Update .env files on server manually** (they're excluded from deployment)
6. **Test database migrations on local copy first**

---

Happy coding! 🚀 