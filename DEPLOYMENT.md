# Gallrey API Deployment Guide

This guide explains how to set up automatic deployment for Gallrey API.

## 🚀 Quick Start

### Option 1: Automatic Deployment (GitHub Actions) - RECOMMENDED

**GitHub Actions** automatically deploys your code whenever you push to the `main` branch.

#### Setup Steps:

1. **Add Server SSH Credentials to GitHub**
   - Go to your GitHub repository
   - Settings → Secrets and variables → Actions
   - Click "New repository secret" and add:

   ```
   SERVER_HOST          = your-server-ip-or-domain
   SERVER_USER          = your-ssh-username
   SERVER_SSH_KEY       = your-private-ssh-key-content
   SERVER_PORT          = 22 (or your SSH port)
   PROJECT_PATH         = /path/to/gallreyapi
   ```

2. **Generate SSH Key (if needed)**
   ```bash
   # On your local machine
   ssh-keygen -t rsa -b 4096 -f ~/.ssh/deploy_key -N ""
   
   # Copy the public key to your server
   ssh-copy-id -i ~/.ssh/deploy_key.pub user@your-server
   
   # Copy the PRIVATE key content for GitHub Secrets
   cat ~/.ssh/deploy_key
   ```

3. **On Your Server**
   ```bash
   # Navigate to project directory
   cd /var/www/gallreyapi
   
   # Initialize git
   git init
   git remote add origin https://github.com/YOUR-USERNAME/gallreyapi.git
   git pull origin main
   
   # Install composer dependencies
   composer install
   
   # Run initial setup
   php artisan migrate
   php artisan storage:link
   ```

4. **Test the Workflow**
   - Make a small change on GitHub
   - Commit to `main` branch
   - Check GitHub Actions tab to see the deployment in progress
   - Watch the logs for success/failure

#### How it Works:
```
You push code to GitHub
         ↓
GitHub Actions triggers
         ↓
SSH connects to your server
         ↓
Pulls latest code
         ↓
Runs migrations & optimization
         ↓
Application updated!
```

---

### Option 2: Manual Deployment Script

If you prefer manual deployment or testing, use the provided script:

#### On Your Server:
```bash
# Make script executable
chmod +x scripts/deploy.sh

# Run deployment
bash scripts/deploy.sh /path/to/gallreyapi
```

#### Or via SSH from your local machine:
```bash
ssh user@your-server "cd /path/to/gallreyapi && bash scripts/deploy.sh"
```

---

### Option 3: Simple Git Pull + Artisan Commands

For minimal deployments:
```bash
ssh user@your-server << 'EOF'
  cd /var/www/gallreyapi
  git fetch origin
  git reset --hard origin/main
  composer install --no-interaction
  php artisan migrate --force
  php artisan cache:clear
  chmod -R 775 storage bootstrap/cache
  echo "✅ Deployment complete!"
EOF
```

---

## 📋 GitHub Actions Secrets Required

| Secret | Description | Example |
|--------|-------------|---------|
| `SERVER_HOST` | Server IP or domain | `192.168.1.100` or `example.com` |
| `SERVER_USER` | SSH username | `deploy` or `ubuntu` |
| `SERVER_SSH_KEY` | Private SSH key (full content) | `-----BEGIN RSA PRIVATE KEY-----...` |
| `SERVER_PORT` | SSH port | `22` |
| `PROJECT_PATH` | Project directory on server | `/var/www/gallreyapi` |

---

## ✅ Deployment Checklist

Before deploying to production:

- [ ] All code pushed to `main` branch
- [ ] Database migrations tested locally
- [ ] `.env` file configured on server
- [ ] Storage directory permissions set
- [ ] Composer `composer.lock` committed
- [ ] All tests passing
- [ ] SSH keys generated and configured

---

## 🔍 Monitoring Deployments

### GitHub Actions Dashboard
```
Repository → Actions → Deploy to Server → View latest run
```

### Server Logs
```bash
# View Laravel logs in real-time
tail -f storage/logs/laravel.log

# Check previous deployments
git log --oneline -10

# View current branch
git status
```

---

## 🐛 Troubleshooting

### "Permission denied" error
**Solution:**
```bash
# Make sure SSH key is added to agent
ssh-add ~/.ssh/deploy_key

# Test SSH connection
ssh -i ~/.ssh/deploy_key user@server "echo OK"
```

### "Composer install failed"
**Solution:**
```bash
# On server, install Composer if not present
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### "Migration failed"
**Solution:**
- Check `.env` database credentials
- Verify MySQL is running: `mysql -u root -p -e "SELECT 1"`
- Check recent migrations: `php artisan migrate:status`
- Rollback if needed: `php artisan migrate:rollback`

### "Storage permission denied"
**Solution:**
```bash
cd /var/www/gallreyapi
chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

---

## 🚦 Deployment Workflow

### Development
```
Make changes locally
↓
Test thoroughly
↓
Commit to feature branch
↓
Create Pull Request
↓
Review & merge to main
```

### Production
```
Changes merged to main
↓
GitHub Actions triggers automatically
↓
SSH to server
↓
Git pull latest code
↓
Install dependencies
↓
Run migrations
↓
Clear caches
↓
✅ Live!
```

---

## 📊 Status Monitoring

### Check Deployment Status
```bash
# SSH to server
ssh user@server

# Check last deployment
git log -1 --oneline

# Check if services are running
ps aux | grep php
ps aux | grep mysql

# Check application health
curl http://localhost/api/galleries

# View recent errors
tail -100 storage/logs/laravel.log
```

---

## 🔐 Security Best Practices

1. **Keep SSH keys secure**
   - Never commit private keys to repository
   - Rotate keys regularly
   - Use key passphrase

2. **Restrict GitHub Secrets**
   - Only repository admins can view secrets
   - Use separate keys for different environments

3. **Server Security**
   - Disable password SSH login (key-only)
   - Use firewall rules
   - Keep software updated
   - Monitor logs regularly

4. **Database Security**
   - Use strong passwords
   - Restrict database user permissions
   - Backup database before migrations

---

## 🔄 Rollback Procedure

If something goes wrong:

```bash
# SSH to server
ssh user@server

cd /var/www/gallreyapi

# View commit history
git log --oneline -10

# Revert to previous version
git reset --hard COMMIT_HASH

# Run migrations rollback if needed
php artisan migrate:rollback

# Clear caches
php artisan cache:clear

# Restart queue
php artisan queue:restart
```

---

## 📞 Support & Resources

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [SSH Best Practices](https://man.openbsd.org/ssh)

---

**Last Updated:** January 29, 2026
