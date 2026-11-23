# SIAKAD — Kelompok 3B (Praktikum Pemrograman)

Sistem Informasi Akademik berbasis Laravel dengan tiga portal: **Admin**, **Dosen**, **Mahasiswa**.

## Menjalankan

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite      # DB_CONNECTION=sqlite
php artisan migrate --seed
php artisan serve
```

Tampilan memakai Tailwind CDN + Font Awesome CDN, jadi tidak perlu `npm run build` (butuh internet saat dibuka).

## Akun

| Peran | Cara mendapatkan akun | Masuk dengan |
| --- | --- | --- |
| Mahasiswa | Daftar sendiri di `/register` | NIM atau email + kata sandi |
| Dosen | Daftar sendiri di `/register` | NIP atau email + kata sandi |
| Admin | Dari seeder (`admin@siakad.test` / `password`, ID staf `ADM001`) | Email atau ID staf |

Akun contoh dari seeder: `dosen@siakad.test` / `password`, `mhs@siakad.test` / `password`.

## Pemantauan akun oleh admin

Menu **Akun & Kata Sandi** (`/admin/accounts`) menampilkan semua akun: peran, NIM/NIP, waktu daftar
(mandiri/dibuat admin), terakhir masuk (+IP), kapan kata sandi dibuat/diubah, dan status. Admin dapat
**mengaktifkan/menonaktifkan** akun dan **mereset** kata sandi (kata sandi sementara tampil sekali).

Kata sandi disimpan sebagai **hash bcrypt**, bukan teks biasa, sehingga tidak bisa dibaca siapa pun
termasuk admin. Ini praktik keamanan standar; jika pengguna lupa, admin memakai tombol Reset.

## Tes

```bash
php artisan test
```
