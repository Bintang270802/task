# Task Management Application

Aplikasi manajemen task sederhana dengan fitur drag & drop untuk mengatur prioritas task.

## Fitur

- ✅ Buat, edit, dan hapus task
- ✅ Kelompokkan task berdasarkan project
- ✅ Drag & drop untuk mengatur urutan prioritas
- ✅ Form wizard 2 langkah untuk membuat task
- ✅ Desain responsive dan profesional
- ✅ Keamanan tingkat enterprise (CSRF, XSS protection)

## Teknologi

- Laravel 11.31
- PHP 8.3
- Bootstrap 5.3
- MySQL (Laragon)

## Instalasi

### Untuk Pengguna Laragon

1. Pastikan Laragon sudah running (Apache & MySQL)
2. Letakkan folder `task` di `C:\laragon\www\`
3. Buka terminal Laragon (klik kanan icon Laragon > Terminal)

### 1. Install Dependencies

```bash
composer install
npm install
```

### 2. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Setup Database

```bash
# Edit .env untuk MySQL (Laragon):
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=

# Buat database:
# Cara 1: Lewat HeidiSQL (klik icon database di Laragon)
# Cara 2: Lewat phpMyAdmin (http://localhost/phpmyadmin)
# Cara 3: Lewat terminal:
mysql -u root -e "CREATE DATABASE task_management"

# Jalankan migration
php artisan migrate
```

### 4. Seed Data (Opsional)

```bash
php artisan db:seed
```

Ini akan membuat 5 project dan 15 task contoh.

### 5. Build Assets & Jalankan

```bash
# Build assets
npm run dev

# Jalankan server (pilih salah satu):
# 1. Pakai Laragon: klik Start All, akses via http://task.test
# 2. Manual: php artisan serve
```

Buka: **http://localhost:8000/task** atau **http://task.test** (jika pakai Laragon)

## Cara Pakai

1. **Buat Task**: Klik tombol "Add New Task", isi nama task, pilih project (opsional), review, lalu save
2. **Edit Task**: Klik icon pensil pada task yang ingin diedit
3. **Hapus Task**: Klik icon tempat sampah (akan ada konfirmasi)
4. **Reorder Task**: Drag & drop task menggunakan handle (⋮⋮) di sebelah kiri
5. **Buat Project**: Di halaman Projects, klik "Add New Project"

## Struktur Folder

```
task/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # TaskController, ProjectController
│   │   ├── Middleware/       # SecurityHeaders
│   │   └── Requests/         # Form validation
│   └── Models/               # Task, Project
├── database/
│   ├── migrations/           # Schema database
│   └── seeders/              # Data contoh
├── resources/
│   └── views/                # Blade templates
├── routes/
│   └── web.php               # Route aplikasi
└── public/                   # Assets
```

## Deploy ke Production

### 1. Optimize

```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Set Environment

Edit `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 3. Web Server

Point document root ke folder `public/`

**Laragon**: Sudah otomatis setup, akses via `http://task.test`

**Apache Manual**: `.htaccess` sudah include di folder `public/`

**Nginx**:
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

### 4. Set Permissions

```bash
# Linux/Mac:
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows (Laragon): biasanya tidak perlu, tapi jika ada error:
# Klik kanan folder storage > Properties > Security > Edit
# Berikan Full Control untuk user Anda
```

### 5. SSL (Recommended)

```bash
certbot --nginx -d yourdomain.com
```

## Troubleshooting

**Port sudah dipakai (Laragon):**
```bash
# Ganti port di Laragon: Menu > Preferences > General
# Atau stop service lain yang pakai port 80/443
```

**Permission error:**
```bash
chmod -R 775 storage bootstrap/cache
# Atau di Windows: klik kanan folder > Properties > Security
```

**Assets tidak muncul:**
```bash
npm run build
php artisan view:clear
```

**Database error:**
```bash
# Pastikan MySQL di Laragon sudah running
# Cek di HeidiSQL apakah database task_management ada
php artisan config:clear
php artisan migrate:fresh --seed
```

**500 Error:**
```bash
# Cek log error
tail -f storage/logs/laravel.log
# Atau buka file: storage/logs/laravel.log
```

## Fitur Keamanan

- CSRF protection pada semua form
- XSS prevention (input sanitization)
- SQL injection prevention (Eloquent ORM)
- Security headers (CSP, X-Frame-Options, HSTS)
- Input validation server-side
- Mass assignment protection

## Code Quality

- PSR-12 coding standards
- SOLID principles
- Laravel best practices
- PHPDoc comments
- Type hints & return types
- Clean, readable code

## Testing

```bash
php artisan test
```

## License

MIT License

---

**Built with Laravel 11**
