# Task Management Application

Aplikasi web sederhana untuk manajemen task dengan fitur drag & drop, dibuat menggunakan Laravel dan Bootstrap.

## Fitur Utama

### 1. Task Management (CRUD)
- ✅ Create Task - Tambah task baru dengan nama dan project
- ✅ Read Task - Lihat daftar semua task dengan informasi lengkap
- ✅ Update Task - Edit nama dan project task
- ✅ Delete Task - Hapus task dengan konfirmasi
- ✅ Drag & Drop Reorder - Ubah urutan task dengan drag & drop
- ✅ Auto Priority Update - Priority otomatis diupdate via AJAX

### 2. Project Management
- ✅ Create Project - Tambah project baru
- ✅ List Projects - Lihat semua project dengan jumlah task
- ✅ Delete Project - Hapus project (cascade delete tasks)
- ✅ Filter Tasks by Project - Filter task berdasarkan project

### 3. UI/UX Features
- 🎨 Desain modern dan bersih dengan Bootstrap 5
- 📱 Responsive design (mobile-friendly)
- 🎯 Drag handle yang jelas untuk reorder
- 🏷️ Badge untuk priority dan project
- ✨ Smooth animations dan hover effects
- 💬 Alert messages untuk feedback user
- ⚠️ Konfirmasi dialog sebelum delete

## Struktur Database

### Table: projects
```
- id (bigint, primary key)
- name (string)
- created_at (timestamp)
- updated_at (timestamp)
```

### Table: tasks
```
- id (bigint, primary key)
- name (string)
- priority (integer)
- project_id (bigint, foreign key, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Relasi
- Project hasMany Tasks
- Task belongsTo Project

## Instalasi & Setup

### 1. Clone atau Setup Project
```bash
# Jika belum ada, clone atau buat project Laravel baru
composer create-project laravel/laravel task-management
cd task-management
```

### 2. Konfigurasi Database
Edit file `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

### 3. Jalankan Migration
```bash
php artisan migrate
```

### 4. Seed Data Sample (Opsional)
```bash
php artisan db:seed
```

### 5. Jalankan Development Server
```bash
php artisan serve
```

Buka browser: `http://localhost:8000`

## Struktur File

```
app/
├── Http/Controllers/
│   ├── TaskController.php      # CRUD + Reorder logic
│   └── ProjectController.php   # Project management
├── Models/
│   ├── Task.php               # Task model dengan relasi
│   └── Project.php            # Project model dengan relasi

database/
├── migrations/
│   ├── xxxx_create_projects_table.php
│   └── xxxx_create_tasks_table.php
└── seeders/
    └── ProjectSeeder.php      # Sample data

resources/views/
├── layouts/
│   └── app.blade.php          # Main layout dengan navbar & styles
├── tasks/
│   ├── index.blade.php        # Task list dengan drag & drop
│   └── form.blade.php         # Create/Edit form (reusable)
└── projects/
    └── index.blade.php        # Project management page

routes/
└── web.php                    # Route definitions
```

## Cara Penggunaan

### Menambah Task
1. Klik tombol "Tambah Task" di halaman utama
2. Isi nama task
3. Pilih project (opsional)
4. Klik "Simpan Task"

### Mengubah Urutan Task (Drag & Drop)
1. Klik dan tahan pada icon grip (☰) di sebelah kiri task
2. Drag task ke posisi yang diinginkan
3. Lepaskan mouse
4. Priority akan otomatis terupdate via AJAX

### Edit Task
1. Klik tombol "Edit" pada task yang ingin diubah
2. Ubah nama atau project
3. Klik "Update Task"

### Hapus Task
1. Klik tombol "Hapus" pada task
2. Konfirmasi penghapusan
3. Task akan terhapus

### Filter Task by Project
1. Gunakan dropdown "Filter berdasarkan Project" di bagian atas
2. Pilih project yang diinginkan
3. Hanya task dari project tersebut yang akan ditampilkan

### Mengelola Project
1. Klik menu "Projects" di navbar
2. Tambah project baru di form sebelah kiri
3. Lihat daftar project di sebelah kanan
4. Hapus project jika diperlukan (akan menghapus semua task di dalamnya)

## Teknologi yang Digunakan

- **Backend**: Laravel 11.x
- **Frontend**: Bootstrap 5.3
- **Icons**: Bootstrap Icons
- **Database**: SQLite (bisa diganti MySQL/PostgreSQL)
- **JavaScript**: Vanilla JS untuk drag & drop

## Best Practices yang Diterapkan

✅ **Laravel Conventions**
- Resource Controllers
- Eloquent ORM & Relationships
- Form Request Validation
- CSRF Protection
- Route Model Binding

✅ **Code Organization**
- Separation of Concerns (Controller, Model, View)
- Reusable Blade Components
- Clean and readable code
- Proper comments

✅ **UI/UX**
- Consistent spacing and padding
- Clear visual hierarchy
- User feedback (alerts, confirmations)
- Responsive design
- Accessible forms with labels

✅ **Security**
- CSRF tokens on all forms
- SQL injection prevention (Eloquent)
- XSS protection (Blade escaping)

## API Endpoints

### Tasks
- `GET /tasks` - List all tasks
- `GET /tasks/create` - Show create form
- `POST /tasks` - Store new task
- `GET /tasks/{task}/edit` - Show edit form
- `PUT /tasks/{task}` - Update task
- `DELETE /tasks/{task}` - Delete task
- `POST /tasks/reorder` - Update task priorities (AJAX)

### Projects
- `GET /projects` - List all projects
- `POST /projects` - Store new project
- `DELETE /projects/{project}` - Delete project

## Troubleshooting

### Drag & Drop tidak berfungsi
- Pastikan JavaScript tidak di-block oleh browser
- Cek console browser untuk error
- Pastikan CSRF token sudah benar

### Task tidak tersimpan
- Cek validasi form
- Pastikan database connection sudah benar
- Lihat log di `storage/logs/laravel.log`

### Priority tidak terupdate
- Cek network tab di browser developer tools
- Pastikan route `/tasks/reorder` sudah terdaftar
- Cek response dari server

## Pengembangan Lebih Lanjut

Fitur yang bisa ditambahkan:
- [ ] User authentication
- [ ] Task due dates
- [ ] Task status (todo, in progress, done)
- [ ] Task assignment to users
- [ ] Task categories/tags
- [ ] Search functionality
- [ ] Export tasks to CSV/PDF
- [ ] Task comments
- [ ] File attachments

## Lisensi

Open source - bebas digunakan dan dimodifikasi.

---

Dibuat dengan ❤️ menggunakan Laravel & Bootstrap
