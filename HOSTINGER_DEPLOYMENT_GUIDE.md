# 🚀 HOSTINGER DEPLOYMENT GUIDE
**Adil GFX Portfolio - Complete Deployment Instructions**

---

## 📋 PRE-DEPLOYMENT CHECKLIST

### **Requirements Verification**
- [ ] Hostinger hosting account active
- [ ] Domain name configured (optional)
- [ ] FTP/File Manager access available
- [ ] MySQL database creation capability
- [ ] PHP 7.4+ support confirmed
- [ ] Composer access (for dependencies)

### **Files Preparation**
- [ ] All backend files ready for upload
- [ ] Frontend build completed
- [ ] Database schema file prepared
- [ ] Configuration templates ready

---

## 🗄️ STEP 1: DATABASE SETUP

### **1.1 Create MySQL Database**

**Via Hostinger Control Panel:**
1. Login to Hostinger control panel
2. Navigate to **Databases → MySQL Databases**
3. Click **Create Database**
4. Database details:
   ```
   Database Name: portfolio_db
   Username: portfolio_user
   Password: [Generate strong password]
   ```
5. **Save credentials** - you'll need them for configuration

### **1.2 Import Database Schema**

**Option A: Via phpMyAdmin**
1. Access phpMyAdmin from Hostinger control panel
2. Select your database (`portfolio_db`)
3. Click **Import** tab
4. Upload file: `supabase/migrations/20250927175835_crimson_spark.sql`
5. Click **Go** to execute

**Option B: Via SQL Command**
```sql
-- Copy and paste the entire content of the migration file
-- Execute in phpMyAdmin SQL tab
```

### **1.3 Verify Database Setup**
```sql
-- Check tables were created
SHOW TABLES;

-- Verify admin user exists
SELECT * FROM users WHERE role = 'admin';
```

**Expected Result**: 15+ tables created, default admin user present

---

## 📁 STEP 2: FILE UPLOAD

### **2.1 Prepare Backend Files**

**Create deployment package:**
```
backend/
├── .htaccess
├── install.php
├── composer.json
├── config/
├── classes/
├── api/
├── admin/
├── uploads/ (create empty directory)
├── exports/ (create empty directory)
└── vendor/ (after composer install)
```

### **2.2 Upload to Hostinger**

**Via File Manager:**
1. Login to Hostinger control panel
2. Go to **Files → File Manager**
3. Navigate to `public_html/` directory
4. Create folder: `backend/`
5. Upload all backend files to `public_html/backend/`

**Via FTP (Alternative):**
```bash
# FTP credentials from Hostinger
Host: your-domain.com
Username: your-ftp-username
Password: your-ftp-password
Port: 21

# Upload to: /public_html/backend/
```

### **2.3 Set File Permissions**

**Required Permissions:**
```bash
# Directories (755)
chmod 755 backend/
chmod 755 backend/uploads/
chmod 755 backend/exports/
chmod 755 backend/admin/logs/

# PHP Files (644)
chmod 644 backend/*.php
chmod 644 backend/config/*.php
chmod 644 backend/classes/*.php
chmod 644 backend/api/*.php
chmod 644 backend/admin/*.php

# .htaccess (644)
chmod 644 backend/.htaccess
```

---

## ⚙️ STEP 3: CONFIGURATION

### **3.1 Update Database Configuration**

**Edit `backend/config/config.php`:**
```php
<?php
// Database configuration - UPDATE WITH YOUR HOSTINGER DETAILS
define('DB_HOST', 'localhost'); // Usually localhost for Hostinger
define('DB_NAME', 'your_database_name'); // From Step 1.1
define('DB_USER', 'your_database_user'); // From Step 1.1
define('DB_PASS', 'your_database_password'); // From Step 1.1

// Site configuration - UPDATE WITH YOUR DOMAIN
define('SITE_URL', 'https://yourdomain.com'); // Your actual domain
define('ADMIN_URL', SITE_URL . '/backend/admin');
define('API_URL', SITE_URL . '/backend/api');

// Upload configuration
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB

// Email configuration - UPDATE WITH YOUR EMAIL
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com'); // Your email
define('SMTP_PASSWORD', 'your-app-password'); // Gmail app password
define('FROM_EMAIL', 'noreply@yourdomain.com');
define('FROM_NAME', 'Adil GFX Portfolio');

// Security - GENERATE NEW KEYS
define('JWT_SECRET', 'your-unique-jwt-secret-key-here');
define('CSRF_TOKEN_NAME', 'csrf_token');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

session_start();
?>
```

### **3.2 Install Dependencies**

**Via Hostinger Terminal (if available):**
```bash
cd public_html/backend
composer install
```

**Manual Installation (if no terminal):**
1. Download PHPMailer manually
2. Upload to `backend/vendor/` directory
3. Ensure autoload.php is present

### **3.3 Create Required Directories**

**Via File Manager:**
```
backend/uploads/ (755 permissions)
backend/exports/ (755 permissions)
backend/admin/logs/ (755 permissions)
```

---

## 🔧 STEP 4: INSTALLATION

### **4.1 Run Installation Script**

**Access Installation:**
1. Open browser
2. Navigate to: `https://yourdomain.com/backend/install.php`
3. Follow installation wizard
4. Verify all requirements are met
5. Click **Install Database & Setup System**

**Expected Output:**
```
✅ Database connection successful
✅ Created directory: uploads
✅ Created directory: exports
✅ Directory writable: uploads
✅ Directory writable: exports
✅ Database schema installed successfully
✅ Installation completed successfully!
✅ Default admin login: admin / admin123
```

### **4.2 Security Cleanup**

**After successful installation:**
1. **Delete install.php** for security
2. Change default admin password immediately
3. Verify .htaccess protection is active

---

## 🧪 STEP 5: TESTING & VERIFICATION

### **5.1 Admin Panel Access**

**Test Admin Login:**
1. Navigate to: `https://yourdomain.com/backend/admin/`
2. Login with: `admin` / `admin123`
3. **Immediately change password** in admin panel
4. Verify dashboard loads with statistics

### **5.2 Functionality Testing**

**Test Core Features:**
```
✅ Pages Management
   - Navigate to Pages section
   - Edit homepage content
   - Verify changes appear on frontend

✅ Portfolio Management
   - Add new portfolio project
   - Upload project images
   - Verify frontend display

✅ Form Processing
   - Submit contact form from frontend
   - Check submission appears in admin
   - Verify email notifications

✅ Media Library
   - Upload test image
   - Verify thumbnail generation
   - Test image replacement

✅ Blog Management
   - Create test blog post
   - Verify SEO fields work
   - Check frontend display
```

### **5.3 Integration Testing**

**Email System:**
```bash
# Test email configuration
1. Submit contact form
2. Check admin receives notification
3. Verify auto-reply sent to user
4. Test newsletter signup
```

**API Endpoints:**
```bash
# Test API responses
GET https://yourdomain.com/backend/api/pages
GET https://yourdomain.com/backend/api/portfolio
POST https://yourdomain.com/backend/api/forms/submit
```

---

## 🔒 STEP 6: SECURITY HARDENING

### **6.1 Update Default Credentials**

**Change Admin Password:**
1. Login to admin panel
2. Navigate to Settings → Users
3. Edit admin user
4. Set strong password (12+ characters)
5. Save changes

### **6.2 Verify Security Settings**

**Check .htaccess Protection:**
```apache
# Test these URLs should return 403 Forbidden:
https://yourdomain.com/backend/config/config.php
https://yourdomain.com/backend/classes/Auth.php
https://yourdomain.com/backend/composer.json
```

### **6.3 SSL Configuration**

**Enable HTTPS:**
1. Activate SSL in Hostinger control panel
2. Update SITE_URL in config.php to use https://
3. Test all functionality with SSL

---

## 📊 STEP 7: PERFORMANCE OPTIMIZATION

### **7.1 Hostinger-Specific Optimizations**

**PHP Configuration:**
```ini
# Add to .htaccess if needed
php_value memory_limit 256M
php_value max_execution_time 300
php_value upload_max_filesize 10M
php_value post_max_size 10M
```

**Database Optimization:**
```sql
-- Add indexes for better performance
ALTER TABLE form_submissions ADD INDEX idx_form_type (form_type);
ALTER TABLE portfolio_projects ADD INDEX idx_category (category);
ALTER TABLE blog_posts ADD INDEX idx_published (is_published, published_at);
```

### **7.2 Caching Setup**

**Browser Caching (.htaccess):**
```apache
# Already included in provided .htaccess
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

---

## 🔍 STEP 8: FINAL VERIFICATION

### **8.1 Complete Functionality Check**

**Admin Panel Verification:**
- [ ] Login/logout working
- [ ] All sections accessible
- [ ] Content editing functional
- [ ] Media upload working
- [ ] Form submissions visible
- [ ] Email notifications sent
- [ ] CSV export working

**Frontend Integration:**
- [ ] API endpoints responding
- [ ] Dynamic content loading
- [ ] Form submissions processing
- [ ] Media displaying correctly

### **8.2 Performance Testing**

**Load Testing:**
```bash
# Test response times
- Admin panel load: < 2 seconds
- API responses: < 500ms
- File uploads: < 5 seconds
- Database queries: < 100ms
```

**Resource Usage:**
```bash
# Monitor Hostinger resources
- Memory usage: < 128MB
- CPU usage: < 50%
- Storage: Monitor file sizes
```

---

## 🚨 TROUBLESHOOTING GUIDE

### **Common Issues & Solutions**

**Database Connection Error:**
```
Error: "Connection failed"
Solution: 
1. Verify database credentials in config.php
2. Check database exists in Hostinger panel
3. Ensure database user has proper permissions
```

**File Upload Issues:**
```
Error: "Permission denied"
Solution:
1. Set uploads/ directory to 755 permissions
2. Check PHP upload limits in .htaccess
3. Verify disk space available
```

**Email Not Sending:**
```
Error: "SMTP connection failed"
Solution:
1. Verify SMTP settings in config.php
2. Use Gmail app password (not regular password)
3. Check Hostinger SMTP restrictions
```

**Admin Panel Not Loading:**
```
Error: "500 Internal Server Error"
Solution:
1. Check .htaccess syntax
2. Verify PHP version compatibility
3. Check error logs in Hostinger panel
```

### **Debug Mode**

**Enable Debugging:**
```php
// Add to config.php for troubleshooting
define('DEBUG_MODE', true);
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

**Check Error Logs:**
- Hostinger Control Panel → Advanced → Error Logs
- Look for PHP errors and warnings

---

## 📞 SUPPORT & MAINTENANCE

### **Post-Deployment Support**

**Regular Maintenance:**
1. **Weekly**: Check error logs and performance
2. **Monthly**: Update dependencies and security
3. **Quarterly**: Database optimization and cleanup

**Backup Procedures:**
1. **Database**: Export via phpMyAdmin weekly
2. **Files**: Download uploads/ directory monthly
3. **Configuration**: Keep config.php backup secure

**Monitoring:**
1. **Uptime**: Monitor site availability
2. **Performance**: Check page load times
3. **Security**: Monitor for suspicious activity

---

## 🎯 SUCCESS CRITERIA

**Deployment Considered Successful When:**
- ✅ Admin panel accessible and functional
- ✅ All CRUD operations working
- ✅ Email notifications sending
- ✅ Frontend-backend integration complete
- ✅ All security measures active
- ✅ Performance within acceptable limits
- ✅ All business functionality operational

---

**Deployment Guide Version**: 1.0  
**Last Updated**: January 2025  
**Compatibility**: Hostinger Shared Hosting, PHP 7.4+, MySQL 5.7+

---

*Follow this guide step-by-step for successful deployment to Hostinger. Contact support if you encounter any issues during the process.*