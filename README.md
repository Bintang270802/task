# Task Management App

Simple task manager with drag & drop built using Laravel 11.

## What's Inside

- Create and manage tasks
- Organize tasks into projects
- Drag & drop to reorder priorities
- Fully responsive design (mobile, tablet, desktop)
- Clean, professional interface
- Secure by default

## Tech Stack

- Laravel 11.31
- PHP 8.3
- MySQL
- Bootstrap 5.3 (Responsive Framework)
- Vanilla JavaScript

## Features

- **Task Management**: Full CRUD operations with drag & drop reordering
- **Project Organization**: Group related tasks together
- **Responsive Design**: Works seamlessly on mobile, tablet, and desktop
- **Two-Step Wizard**: Guided task creation with review step
- **Security**: CSRF protection, XSS prevention, SQL injection protection
- **Clean UI**: Classic professional design with Bootstrap 5

## Getting Started

### Prerequisites

Make sure you have PHP 8.3, Composer, Node.js, and MySQL installed. I'm using Laragon on Windows.

### Installation

Clone the repo and install dependencies:

```bash
composer install
npm install
```

Set up your environment:

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=
```

Create the database (use HeidiSQL, phpMyAdmin, or terminal):

```bash
mysql -u root -e "CREATE DATABASE task_management"
```

Run migrations:

```bash
php artisan migrate
```

Optionally seed some sample data:

```bash
php artisan db:seed
```

Build assets and start the server:

```bash
npm run dev
php artisan serve
```

Visit `http://localhost:8000` and you're good to go.

## How to Use

**Tasks**: Click "Add New Task" to create one. You can edit or delete tasks using the icons on each card. Drag the handle on the left to reorder.

**Projects**: Group related tasks together. Create projects from the Projects page.

**Reordering**: Just drag and drop tasks to change their priority. It saves automatically.

## Running Tests

```bash
php artisan test
```

All tests should pass:
- Home redirect test
- Tasks page load test
- Projects page load test

## Browser Compatibility

Tested and working on:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Deployment

For production, optimize everything:

```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Update `.env`:

```env
APP_ENV=production
APP_DEBUG=false
```

Point your web server to the `public` folder. The `.htaccess` file is already set up for Apache.

For Nginx:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/task/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Common Issues

**Can't connect to database**: Check if MySQL is running and your credentials in `.env` are correct.

**Assets not loading**: Run `npm run build` and clear the cache with `php artisan view:clear`.

**Permission errors**: On Linux/Mac, run `chmod -R 775 storage bootstrap/cache`. On Windows, usually not needed.

## Project Structure

```
app/
├── Http/Controllers/    # Request handlers
├── Models/              # Database models
└── Http/Requests/       # Form validation

resources/views/         # Blade templates
routes/web.php          # Routes
database/migrations/    # Database schema
```

## Security

The app includes CSRF protection, XSS prevention, and SQL injection protection out of the box. All user input is validated and sanitized.

## Responsive Design

The application is fully responsive and optimized for:
- **Mobile devices** (320px - 576px): Hamburger menu, stacked layout, touch-friendly buttons
- **Tablets** (577px - 768px): Optimized spacing and font sizes
- **Desktops** (769px+): Full layout with all features

Key responsive features:
- Collapsible navigation menu on mobile
- Touch-friendly drag & drop on mobile devices
- Adaptive form layouts
- Responsive wizard steps (horizontal on desktop, vertical on mobile)
- Optimized button sizes and spacing for touch screens

## License

MIT

---

Built with Laravel
