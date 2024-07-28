## Setup Project E-Rikmin

### Server Requirements

-   PHP >= 8.3
-   Composer >= 2.5.8
-   Node Js >= 18.18.1

### Instalasi

-   Clone Repositori
-   Masuk ke direktori yang anda buat

```bash
cd <folder_nama_project>
```

-   Install Dependensi Composer

```bash
composer install
```

### Copy file .env

```bash
cp .env.example .env
```

### Generate Laravel Key

```bash
php artisan key:generate
```

### Konfigurasi koneksi database

```
DB_CONNECTION=sqlite
```

### Jalankan Migrasi Database

```bash
php artisan migrate
```

### Jalankan Database Seeder

```bash
php artisan db:seed
```

### install node module

```bash
npm ci
```

### running development

```bash
npm run dev
```

### build production

```bash
npm run prod
```
