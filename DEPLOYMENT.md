# Dreamhost Deployment Guide

This guide explains how to set up automatic deployment to Dreamhost using GitHub Actions.

## 🚀 Quick Setup

The GitHub Actions workflow (`.github/workflows/deploy.yml`) will automatically deploy your EALifeCycle to Dreamhost when you push to the `master` or `main` branch.

## 📁 Subdirectory Deployment Example

### For https://4xhacker.com/ealifecycle Setup

If you want your EALifeCycle application accessible at `https://4xhacker.com/ealifecycle/`, here's the exact folder structure and commands:

#### Server Directory Structure
```
/home/dh_4xhacker/
├── ealifecycle/                    # Laravel application (SECURE - outside web root)
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── storage/
│   ├── vendor/
│   ├── public/                     # Laravel's public directory
│   │   ├── index.php
│   │   ├── build/
│   │   └── assets/
│   ├── .env
│   └── .env.production
└── 4xhacker.com/                   # Your web root (document root)
    ├── index.html                  # Your main site files
    ├── other-files/
    └── ealifecycle/                # SYMLINK → ../../ealifecycle/public
```

#### Setup Commands
```bash
# 1. SSH into your Dreamhost server
ssh dh_4xhacker@4xhacker.com

# 2. Navigate to your web root
cd /home/dh_4xhacker/4xhacker.com

# 3. Remove any existing ealifecycle directory
rm -rf ealifecycle

# 4. Create symlink to Laravel's public folder
ln -s ../ealifecycle/public ealifecycle

# 5. Verify the symlink is correct
ls -la ealifecycle
# Should show: ealifecycle -> ../ealifecycle/public

# 6. Test symlink by checking if Laravel's index.php exists
ls -la ealifecycle/index.php
# Should show the Laravel index.php file
```

#### Environment Configuration
Create `/home/dh_4xhacker/ealifecycle/.env.production`:
```env
APP_NAME="EALifeCycle"
APP_ENV=production
APP_URL=https://4xhacker.com/ealifecycle
APP_DEBUG=false

# Database configuration
DB_CONNECTION=mysql
DB_HOST=mysql.4xhacker.com
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Email configuration
MAIL_FROM_ADDRESS=noreply@4xhacker.com
MAIL_FROM_NAME="EALifeCycle"
```

#### GitHub Repository Secrets
For this specific setup, configure these secrets in your "prod" environment:

| Secret Name | Value |
|-------------|-------|
| `DREAMHOST_HOST` | `4xhacker.com` |
| `DREAMHOST_USERNAME` | `dh_4xhacker` |
| `DREAMHOST_PATH` | `/home/dh_4xhacker` |
| `DREAMHOST_SSH_KEY` | Your complete private SSH key |

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
APP_NAME="EALifeCycle"
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
1. Push changes to `master` or `

**SSH Connection Issues:**
- Verify SSH key is properly added to GitHub secrets
- Test SSH connection: `ssh username@yourdomain.com`
- Check Dreamhost SSH settings

**Symlink Issues (for subdirectory deployment):**
```bash
# Check if symlink exists and points to correct location
ls -la /home/dh_4xhacker/4xhacker.com/ealifecycle

# Remove broken symlink and recreate
rm /home/dh_4xhacker/4xhacker.com/ealifecycle
ln -s ../ealifecycle/public /home/dh_4xhacker/4xhacker.com/ealifecycle

# Test if Laravel app is accessible
curl -I https://4xhacker.com/ealifecycle/
# Should return 200 OK response

# Check Laravel public folder exists
ls -la /home/dh_4xhacker/ealifecycle/public/index.php
```

### Logs and Debugging