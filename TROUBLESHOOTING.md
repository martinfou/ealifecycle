# EALifeCycle Troubleshooting Guide

This guide helps you quickly resolve common issues with the EALifeCycle application.

## 🚨 HTTP 405 "Method Not Allowed" Error

### Symptoms
- Site returns "405 Method Not Allowed" 
- Error message: "The server returned a '405 Method Not Allowed'"
- Laravel routes work via SSH but not in browser

### Root Cause
Stale Laravel optimization caches (config, routes, views) after deployment.

### ⚡ Quick Fix
```bash
# On production server:
ssh dh_4xhacker@4xhacker.com "cd ealifecycle && php artisan optimize:clear"

# Or run the automated script:
./deploy-fix-caches.sh
```

### 🛠️ Detailed Fix Steps

1. **Connect to production server:**
   ```bash
   ssh dh_4xhacker@4xhacker.com
   cd ealifecycle
   ```

2. **Clear all Laravel caches:**
   ```bash
   php artisan optimize:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```

3. **Fix permissions:**
   ```bash
   chmod -R 755 storage/ bootstrap/cache/ public/
   chmod 644 public/index.php
   ```

4. **Test the fix:**
   ```bash
   curl -I https://4xhacker.com/ealifecycle/
   # Should return: HTTP/2 200
   ```

## 🔄 Prevention Strategies

### 1. Automated Deployment Cache Clearing
The deployment workflow now automatically:
- Clears all caches before deployment
- Runs migrations safely
- Sets proper permissions
- Clears problematic caches after optimization

### 2. Use the Cache Fix Script
Run this script after any manual changes:
```bash
./deploy-fix-caches.sh
```

### 3. Deployment Best Practices
- Always use GitHub Actions for deployment
- Never manually edit files on production server
- Test locally before deploying
- Monitor deployment logs for errors

## 🔍 Other Common Issues

### HTTP 403 Forbidden
**Cause:** File permission issues
**Fix:**
```bash
chmod -R 755 storage/ bootstrap/cache/ public/
chmod 644 public/index.php public/.htaccess
```

### HTTP 500 Internal Server Error
**Cause:** Laravel configuration or database issues
**Fix:**
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify .env file exists and is correct
3. Run database migrations: `php artisan migrate --force`
4. Clear caches: `php artisan optimize:clear`

### Missing Assets/Images
**Cause:** Symlink issues or deployment problems
**Fix:**
1. Check if symlink exists: `ls -la /home/dh_4xhacker/4xhacker.com/ealifecycle`
2. Recreate if needed: `ln -s /home/dh_4xhacker/ealifecycle/public /home/dh_4xhacker/4xhacker.com/ealifecycle`
3. Verify permissions: `chmod -R 755 public/`

### Database Connection Issues
**Cause:** Incorrect database credentials or server issues
**Fix:**
1. Verify .env database settings:
   ```bash
   cat .env | grep DB_
   ```
2. Test database connection:
   ```bash
   php artisan tinker
   DB::connection()->getPdo();
   ```

## 📊 Monitoring & Prevention

### Quick Health Check Script
Create a simple health check:
```bash
#!/bin/bash
echo "🔍 EALifeCycle Health Check"
echo "=========================="

# Test HTTP response
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://4xhacker.com/ealifecycle/)
echo "HTTP Response: $HTTP_CODE"

if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ Application is healthy"
else
    echo "❌ Application needs attention"
    echo "Run: ./deploy-fix-caches.sh"
fi
```

### Deployment Checklist
Before each deployment:
- [ ] Test changes locally
- [ ] Commit changes to version control
- [ ] Use GitHub Actions for deployment
- [ ] Monitor deployment logs
- [ ] Test production site after deployment
- [ ] Check for any 404/405/500 errors

### Emergency Rollback
If deployment fails:
```bash
# The workflow automatically creates backups
ssh dh_4xhacker@4xhacker.com
cd /home/dh_4xhacker
mv ealifecycle ealifecycle-broken
mv ealifecycle-backup ealifecycle
```

## 📞 Quick Reference Commands

| Issue | Command |
|-------|---------|
| 405 Error | `php artisan optimize:clear` |
| Clear all caches | `php artisan optimize:clear` |
| Fix permissions | `chmod -R 755 storage/ public/` |
| Check logs | `tail -f storage/logs/laravel.log` |
| Test routes | `php artisan route:list` |
| Database migrate | `php artisan migrate --force` |
| Full deployment fix | `./deploy-fix-caches.sh` |

## 🆘 Emergency Contacts

- **GitHub Repository:** https://github.com/[your-username]/ealifecycle
- **Production URL:** https://4xhacker.com/ealifecycle/
- **Server:** Dreamhost (dh_4xhacker@4xhacker.com)

## 📝 Adding to This Guide

When you encounter new issues:
1. Document the symptoms
2. Record the solution that worked
3. Add prevention steps
4. Update this guide

---

*Last updated: 2025-06-02 - Version 0.3.6* 