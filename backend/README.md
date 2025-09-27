# Adil GFX Portfolio Backend System

A complete PHP + MySQL backend system for managing the Adil GFX portfolio website. This system provides a full admin panel for content management, form handling, and site configuration.

## Features

### 🎯 Core Functionality
- **Dynamic Page Management** - Add, edit, delete pages with SEO optimization
- **Element Control** - Manage headings, paragraphs, images, videos, buttons, forms
- **Content & Media Management** - Upload/manage images, videos, files
- **Portfolio Management** - CRUD for projects with categories, tags, images
- **Service Management** - Manage service packages with pricing and features
- **Blog Management** - Full blog system with SEO fields, categories, tags
- **Testimonials** - Client testimonials with ratings and project details
- **Forms & Leads** - Handle all form submissions with email notifications
- **User Management** - Admin and Editor roles with permissions

### 🔧 Technical Features
- **RESTful API** - Clean API endpoints for all functionality
- **Security** - CSRF protection, XSS prevention, SQL injection protection
- **Email System** - SMTP integration with PHPMailer
- **Media Library** - File upload with validation and organization
- **Settings Management** - Site-wide settings and integrations
- **Export Functionality** - CSV export for leads and data
- **Responsive Admin Panel** - Mobile-friendly administration interface

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer (for dependencies)

### Step 1: Upload Files
Upload all backend files to your Hostinger hosting account in a `backend` directory.

### Step 2: Configure Database
1. Create a MySQL database in your Hostinger control panel
2. Update `config/config.php` with your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
```

### Step 3: Install Dependencies
Run composer to install PHPMailer:
```bash
composer install
```

### Step 4: Run Installation
1. Navigate to `yourdomain.com/backend/install.php`
2. Follow the installation wizard
3. The system will create all necessary database tables and default admin user

### Step 5: Configure Email (Optional)
Update email settings in `config/config.php`:
```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
```

### Step 6: Set Permissions
Ensure these directories are writable:
- `uploads/` (755)
- `exports/` (755)
- `admin/logs/` (755)

## Default Login
- **Username:** admin
- **Password:** admin123

**⚠️ Change the default password immediately after installation!**

## API Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `GET /api/auth/me` - Get current user info

### Pages
- `GET /api/pages` - Get all pages
- `GET /api/pages/{id}` - Get page by ID
- `GET /api/pages/slug/{slug}` - Get page by slug
- `POST /api/pages/create` - Create new page
- `PUT /api/pages/{id}` - Update page
- `DELETE /api/pages/{id}` - Delete page

### Portfolio
- `GET /api/portfolio` - Get all projects
- `GET /api/portfolio/categories` - Get project categories
- `GET /api/portfolio/slug/{slug}` - Get project by slug
- `POST /api/portfolio` - Create new project
- `PUT /api/portfolio/{id}` - Update project
- `DELETE /api/portfolio/{id}` - Delete project

### Services
- `GET /api/services` - Get all services
- `POST /api/services` - Create new service
- `PUT /api/services/{id}` - Update service
- `DELETE /api/services/{id}` - Delete service

### Blog
- `GET /api/blog` - Get all blog posts
- `GET /api/blog/categories` - Get blog categories
- `GET /api/blog/slug/{slug}` - Get post by slug
- `POST /api/blog` - Create new post
- `PUT /api/blog/{id}` - Update post
- `DELETE /api/blog/{id}` - Delete post

### Testimonials
- `GET /api/testimonials` - Get all testimonials
- `POST /api/testimonials` - Create new testimonial
- `PUT /api/testimonials/{id}` - Update testimonial
- `DELETE /api/testimonials/{id}` - Delete testimonial

### Forms
- `POST /api/forms/submit` - Submit form data
- `GET /api/forms` - Get all submissions (admin only)
- `GET /api/forms/stats` - Get form statistics
- `POST /api/forms/export` - Export submissions to CSV
- `PUT /api/forms/{id}` - Update submission status
- `DELETE /api/forms/{id}` - Delete submission

### Media
- `POST /api/media/upload` - Upload file
- `GET /api/media` - Get all media files
- `PUT /api/media/{id}` - Update media info
- `DELETE /api/media/{id}` - Delete media file

### Settings
- `GET /api/settings` - Get all settings
- `PUT /api/settings` - Update settings

## Frontend Integration

### JavaScript Example
```javascript
// Submit contact form
async function submitContactForm(formData) {
    const response = await fetch('/backend/api/forms/submit', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            form_type: 'contact',
            ...formData
        })
    });
    
    return await response.json();
}

// Get portfolio projects
async function getPortfolioProjects(category = null) {
    const url = category 
        ? `/backend/api/portfolio?category=${category}`
        : '/backend/api/portfolio';
    
    const response = await fetch(url);
    const data = await response.json();
    
    return data.projects;
}
```

### PHP Example
```php
// Get page content
function getPageContent($slug) {
    $url = "https://yourdomain.com/backend/api/pages/slug/{$slug}";
    $response = file_get_contents($url);
    return json_decode($response, true);
}
```

## Security Features

### Input Validation
- All inputs are sanitized and validated
- SQL injection prevention with prepared statements
- XSS protection with htmlspecialchars

### Authentication
- Session-based authentication
- CSRF token validation
- Role-based access control (Admin/Editor)

### File Upload Security
- File type validation
- File size limits
- Secure file naming
- Upload directory protection

## Admin Panel Features

### Dashboard
- Overview statistics
- Recent activity feed
- Quick access to common tasks

### Content Management
- Visual page editor
- Media library with drag-and-drop upload
- SEO optimization tools
- Content scheduling

### Lead Management
- Form submission tracking
- Lead status management
- CSV export functionality
- Email notification system

### Settings
- Site configuration
- Integration management (Google Analytics, Meta Pixel, etc.)
- Email settings
- User management

## Troubleshooting

### Common Issues

**Database Connection Error**
- Verify database credentials in `config/config.php`
- Ensure MySQL service is running
- Check database user permissions

**File Upload Issues**
- Verify `uploads/` directory permissions (755)
- Check PHP `upload_max_filesize` and `post_max_size`
- Ensure sufficient disk space

**Email Not Sending**
- Verify SMTP settings in `config/config.php`
- Check if PHPMailer is installed via Composer
- Test with a simple email client

**Permission Denied Errors**
- Set correct file permissions (644 for files, 755 for directories)
- Ensure web server has write access to required directories

### Debug Mode
Enable debug mode by adding to `config/config.php`:
```php
define('DEBUG_MODE', true);
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## Maintenance

### Regular Tasks
1. **Backup Database** - Regular MySQL backups
2. **Update Dependencies** - Keep PHPMailer updated
3. **Monitor Logs** - Check error logs regularly
4. **Clean Uploads** - Remove unused media files
5. **Export Data** - Regular data exports for backup

### Performance Optimization
1. **Database Indexing** - Ensure proper indexes on frequently queried columns
2. **Image Optimization** - Compress uploaded images
3. **Caching** - Implement caching for frequently accessed data
4. **CDN Integration** - Use CDN for static assets

## Support

For technical support or questions about this backend system:

1. Check the troubleshooting section above
2. Review the API documentation
3. Verify your server meets all requirements
4. Check error logs for specific error messages

## License

This backend system is proprietary software created specifically for the Adil GFX portfolio website.

---

**Version:** 1.0.0  
**Last Updated:** January 2025  
**Compatibility:** PHP 7.4+, MySQL 5.7+, Hostinger Hosting