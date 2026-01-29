# Contributing & Deployment Guide

## 🔄 Making Changes & Deploying

This project uses **GitHub Actions** for automatic deployment. Every time you push changes to the `main` branch, the application is automatically deployed to the server.

## 📝 Development Workflow

### 1. Make Changes Locally
```bash
# Create a feature branch
git checkout -b feature/your-feature-name

# Make your changes
# Edit files as needed

# Commit changes
git add .
git commit -m "feat: add new feature"
```

### 2. Test Your Changes
```bash
# Run tests
php artisan test

# Check code style
vendor/bin/pint --dirty

# Start local server
php artisan serve
```

### 3. Push to GitHub
```bash
# Push your branch
git push origin feature/your-feature-name

# Create a Pull Request on GitHub
# Request review if needed
```

### 4. Merge to Main
```bash
# Once approved, merge to main branch
# This triggers automatic deployment!
```

## 🚀 Automatic Deployment

Once code is merged to `main`:

1. **GitHub Actions** automatically starts
2. **SSH connects** to your server
3. **Code is pulled** from GitHub
4. **Dependencies installed** (composer)
5. **Database migrations** run
6. **Cache cleared** and optimized
7. **Live on server!** ✅

### Monitor Deployment
```
Go to: GitHub Repository → Actions tab
Watch the deployment progress in real-time
```

## 📋 Setup Requirements (First Time Only)

### Add GitHub Secrets

1. Go to repository **Settings**
2. Select **Secrets and variables → Actions**
3. Add these secrets:

```
SERVER_HOST         = Your server IP/domain
SERVER_USER         = SSH username
SERVER_SSH_KEY      = Your private SSH key content
SERVER_PORT         = 22 (or custom SSH port)
PROJECT_PATH        = /var/www/gallreyapi
```

### Generate SSH Key

```bash
# On your local machine
ssh-keygen -t rsa -b 4096 -f ~/.ssh/deploy_key -N ""

# Add public key to server
ssh-copy-id -i ~/.ssh/deploy_key.pub user@server

# Get private key for GitHub
cat ~/.ssh/deploy_key
# Copy entire output to GitHub SECRET: SERVER_SSH_KEY
```

## 🛠️ Manual Deployment (Alternative)

If you prefer manual deployment:

```bash
# SSH to your server
ssh user@server

# Run deployment script
cd /var/www/gallreyapi
bash scripts/deploy.sh
```

## ✅ Deployment Checklist

Before pushing to main:

- [ ] Code tested locally
- [ ] All tests passing (`php artisan test`)
- [ ] Code formatted (`vendor/bin/pint`)
- [ ] No console errors
- [ ] Database migrations created (if needed)
- [ ] `.env` variables documented

## 📊 What Gets Deployed

When you push to `main`:

✅ Latest source code
✅ Updated views & assets
✅ New database migrations
✅ Configuration changes
✅ New controllers & models
✅ API updates
✅ CSS/styling updates (Tailwind)

## 🔍 Verify Deployment

After deployment completes:

```bash
# Check if live
curl https://gallreyapi.test

# View server logs
ssh user@server "tail -50 /var/www/gallreyapi/storage/logs/laravel.log"

# Check git status on server
ssh user@server "cd /var/www/gallreyapi && git log -1"
```

## 🆘 Troubleshooting

### Deployment Failed in GitHub Actions
- Check the **Actions tab** for error details
- Fix the issue locally
- Commit and push again

### Code Didn't Update on Server
- Check GitHub Actions shows ✅ success
- Verify SSH credentials are correct
- Manually SSH and run: `git status`

### Database Migration Error
- Check `.env` database credentials
- View migration status: `php artisan migrate:status`
- Rollback if needed: `php artisan migrate:rollback`

## 📚 Files Related to Deployment

```
.github/workflows/deploy.yml    # GitHub Actions configuration
scripts/deploy.sh               # Manual deployment script
DEPLOYMENT.md                   # Full deployment documentation
.gitignore                      # Files excluded from repository
```

## 🔐 Security Tips

1. **Never commit secrets** to GitHub
2. **Rotate SSH keys** regularly
3. **Use strong passwords** for server
4. **Keep dependencies updated**
5. **Monitor deployment logs** for errors

## 📞 Need Help?

See [DEPLOYMENT.md](DEPLOYMENT.md) for complete deployment documentation.

---

**Happy Deploying!** 🎉
