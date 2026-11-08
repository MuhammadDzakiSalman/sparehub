# SpareHub

Aplikasi web sederhana untuk pencatatan peminjaman dan pengembalian alat di pos gudang pabrik (Kasus Pak Anto).

---

## Cara Menjalankan Aplikasi

1. **Clone repositori dan masuk ke direktori proyek:**
   ```bash
   git clone <url-repository>
   cd sparehub
   ```

2. **Install dependensi:**
   ```bash
   composer install
   ```

3. **Salin file environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Sesuaikan pengaturan database di file `.env` jika diperlukan.*

4. **Jalankan migrasi dan seeder:**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Jalankan server:**
   ```bash
   php artisan serve
   ```
   Buka browser di `http://127.0.0.1:8000`.

---

## Akun Login

* **Email:** `admin@sparehub.my.id`
* **Password:** `password`
