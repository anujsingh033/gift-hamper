# Cute Gift Hamper Website

A fully functional e-commerce website for selling beautifully curated gift hampers for various occasions.

## Features

### Frontend
- Responsive design that works on all devices
- Product catalog with categories and search functionality
- Shopping cart and checkout system
- User registration and login system
- About page with company information
- Contact page with inquiry form
- Product detail pages with images and descriptions

### Backend (Admin Panel)
- Secure admin login with honeypot protection
- Dashboard with statistics overview
- Product management (add, edit, delete)
- Category management (add, edit, delete)
- Order management with status tracking
- User management capabilities

### Security Features
- SQL injection prevention with prepared statements
- XSS prevention with input sanitization
- Password hashing for user security
- Session management and fixation prevention
- Admin panel honeypot to mislead unauthorized access
- robots.txt to prevent indexing of sensitive areas

### SEO Optimization
- Meta descriptions for all pages
- Canonical URLs implementation
- Sitemap.xml for search engine indexing
- Responsive design for mobile SEO

## Technology Stack
- PHP 7.4+
- MySQL/MariaDB database
- HTML5, CSS3, JavaScript
- Font Awesome for icons
- Responsive design principles

## Installation

1. Clone or download the repository
2. Import the database schema from `gift_hamper_db.sql`
3. Update database connection settings in `includes/config.php`
4. Ensure proper file permissions (755 for directories, 644 for files)
5. Create the `assets/images` directory for product images
6. Test the installation

## Database Setup

1. Create a MySQL database named `gift_hamper_db`
2. Import the `gift_hamper_db.sql` file to create tables and sample data
3. Update the database credentials in `includes/config.php`

## Default Admin Credentials

Username: admin
Password: admin123

Note: For security, please change these credentials after first login.

## Directory Structure

```
├── admin/              # Admin panel files
├── assets/             # CSS, JavaScript, and images
│   ├── css/            # Stylesheets
│   └── images/         # Product and site images
├── includes/           # Shared files and configuration
├── pages/              # Frontend pages
├── gift_hamper_db.sql  # Database schema and sample data
├── index.php           # Homepage
├── robots.txt          # Search engine directives
└── sitemap.xml         # SEO sitemap
```

## Security Measures

The admin panel implements several security measures:
- Honeypot login page to mislead unauthorized access attempts
- Prepared statements to prevent SQL injection
- Password hashing for user authentication
- Session management to prevent fixation attacks
- robots.txt to prevent indexing of admin areas

## SEO Features

- Meta descriptions for improved search visibility
- Canonical URLs to prevent duplicate content issues
- Responsive design for mobile search ranking
- Sitemap.xml for search engine crawling
- Semantic HTML structure

## Customization

To customize the website:
1. Update the `includes/config.php` file with your database settings
2. Modify the CSS in `assets/css/style.css` to change the design
3. Update content in the various PHP files
4. Replace images in the `assets/images` directory
5. Modify the database schema if needed

## Contributing

1. Fork the repository
2. Create a new branch for your feature
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is for educational and demonstration purposes. Feel free to use and modify it for your own projects.

## Support

For support, please open an issue on the repository or contact the development team.