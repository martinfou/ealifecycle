# Dreamhost Deployment Guide

This guide explains how to set up automatic deployment to Dreamhost using GitHub Actions.

## 🚀 Quick Setup

The GitHub Actions workflow (`.github/workflows/deploy.yml`) will automatically deploy your Trading Strategy Tracker to Dreamhost when you push to the `master` or `main` branch.

## 📋 Prerequisites

### 1. Dreamhost Setup
- SSH access enabled on your Dreamhost account
- PHP 8.4+ available
- MySQL database created
- Domain configured

### 2. SSH Key Setup
1. Generate an SSH key pair on your local machine:
   ```bash
   ssh-keygen -t rsa -b 4096 -C "github-actions@yourdomain.com"
   ```
2. Add the public key to your Dreamhost server:
   ```bash
   ssh-copy-id username@yourdomain.com
   ```

## 🔧 GitHub Repository Secrets

Go to your GitHub repository → Settings → Secrets and variables → Actions, and add these secrets:

| Secret Name | Description | Example |
|-------------|-------------|---------|
| `DREAMHOST_HOST` | Your Dreamhost server hostname | `yourdomain.com` or `psXXXXXX.dreamhostps.com` |
| `DREAMHOST_USERNAME` | Your SSH username | `username` |
| `DREAMHOST_SSH_KEY` | Private SSH key content | Copy entire private key file |
| `DREAMHOST_PATH` | Path to your domain directory | `/home/username/yourdomain.com` |

### Example SSH Key Format
```
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAACFwAAAAdzc2gtcn
...
-----END OPENSSH PRIVATE KEY-----
```

## 📁 Server Directory Structure

Your Dreamhost server should have this structure:
```
/home/username/yourdomain.com/
├── current/          # Active deployment
├── backup/           # Previous deployment (for rollback)
└── .env.production   # Production environment file
```

## ⚙️ Production Environment Setup

Create `.env.production` file on your Dreamhost server:

```bash
# SSH into your server
ssh username@yourdomain.com

# Navigate to your domain directory
cd /home/username/yourdomain.com

# Create production environment file
nano .env.production
```

### Production Environment Template
```env
APP_NAME="Trading Strategy Tracker"
APP_ENV=production
APP_KEY=base64:your-production-app-key-here
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://yourdomain.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

# Database Configuration (Update with your Dreamhost MySQL details)
DB_CONNECTION=mysql
DB_HOST=mysql.yourdomain.com
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

# Email Configuration (Dreamhost SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.dreamhost.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

## 🎯 Generate Production App Key

Generate a production app key:
```bash
# On your local machine
php artisan key:generate --show

# Or on the server after first deployment
cd /home/username/yourdomain.com/current
php artisan key:generate
```

## 🗄️ Database Setup

1. **Create MySQL Database** in Dreamhost panel
2. **Note credentials** (host, database name, username, password)
3. **Update .env.production** with database details
4. **First deployment will run migrations automatically**

## 🚀 Deployment Process

### Automatic Deployment
1. Push changes to `master` or `main` branch
2. GitHub Actions automatically triggers deployment
3. Application is built, tested, and deployed
4. Zero-downtime deployment with automatic rollback on failure

### Manual Deployment
You can also trigger deployment manually:
1. Go to GitHub repository → Actions
2. Select "Deploy to Dreamhost" workflow
3. Click "Run workflow"

## 📊 Deployment Steps

The workflow performs these steps:
1. ✅ **Build**: Install dependencies and compile assets
2. ✅ **Package**: Create deployment archive
3. ✅ **Backup**: Save current deployment
4. ✅ **Upload**: Transfer new version to server
5. ✅ **Configure**: Set up environment and permissions
6. ✅ **Optimize**: Cache routes, views, and config
7. ✅ **Migrate**: Run database migrations
8. ✅ **Activate**: Switch to new deployment
9. 🔄 **Rollback**: Automatic rollback on failure

## 🔧 Troubleshooting

### Common Issues

**Permission Errors:**
```bash
# Fix file permissions on server
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs storage/framework
```

**Composer Not Found:**
```bash
# Install Composer on Dreamhost
curl -sS https://getcomposer.org/installer | php
mv composer.phar /home/username/bin/composer
```

**Database Connection Issues:**
- Verify MySQL credentials in .env.production
- Check Dreamhost MySQL hostname (usually mysql.yourdomain.com)
- Ensure database user has proper permissions

**SSH Connection Issues:**
- Verify SSH key is properly added to GitHub secrets
- Test SSH connection: `ssh username@yourdomain.com`
- Check Dreamhost SSH settings

### Logs and Debugging

**GitHub Actions Logs:**
- Go to repository → Actions → Select failed workflow
- Check each step for error details

**Server Logs:**
```bash
# Laravel logs
tail -f /home/username/yourdomain.com/current/storage/logs/laravel.log

# Apache/Nginx logs (check Dreamhost panel)
```

## 🔐 Security Considerations

- ✅ SSH keys are encrypted in GitHub secrets
- ✅ Environment variables are not committed to repository
- ✅ Production environment has debug disabled
- ✅ Database credentials are secured
- ✅ Automatic rollback prevents broken deployments

## 📈 Monitoring

After deployment, verify:
- [ ] Website loads correctly
- [ ] Database migrations completed
- [ ] User authentication works
- [ ] Strategy management functions
- [ ] FX Blue import works
- [ ] Admin interfaces accessible

## 🎉 Success!

Once configured, your deployment workflow will:
- 🚀 Deploy automatically on every push to master
- 🔄 Provide zero-downtime deployments
- 📊 Show deployment status in GitHub Actions
- 🛡️ Rollback automatically on failures
- 📧 Notify you of deployment results

Your Trading Strategy Tracker is now production-ready with professional CI/CD! 🎯 