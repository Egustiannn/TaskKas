# TaskKas

Aplikasi web untuk mengatur tugas sekolah **dan** kas kelas dalam satu platform. PHP MVC (modul Task & Cash), MySQL, tampilan dark glass dengan objek chrome 3D beranimasi (Three.js).

## Halaman
Landing page · Login/Daftar · Dashboard **User** (siswa) · Dashboard **Admin** (bendahara)

## Instalasi
1. Butuh PHP 8+, MySQL/MariaDB, Apache dengan `mod_rewrite` (XAMPP/Laragon cukup). Koneksi internet dibutuhkan untuk memuat Three.js & font dari CDN.
2. Taruh folder proyek di `htdocs/taskkas`.
3. Import `database/taskkas.sql` lewat phpMyAdmin.
4. Salin `config/config.example.php` menjadi `config/config.php`, sesuaikan kredensial DB dan `base_url`.
5. Buka `http://localhost/taskkas/public/`.

## Akun contoh (password: `password123`)
| Peran | Email |
|-------|-------|
| Admin (bendahara) | `admin@taskkas.test` |
| User (siswa) | `user@taskkas.test` |

## Cara pakai
- **User**: Daftar → pilih kelas → *Tambah tugas* (mapel, deadline, prioritas). Centang ✓ jika selesai, *Edit*/*Hapus* bila perlu. Lihat saldo dan transaksi kas kelas di dashboard.
- **Admin**: *Tambah transaksi* (pemasukan/pengeluaran, kategori, iuran per siswa). Pantau saldo, filter riwayat per bulan, lihat rekap iuran.
- Pendaftaran baru otomatis menjadi **user**. Jadikan admin: `UPDATE users SET role='admin' WHERE email='...';`

## Struktur
`core/` router, controller dasar, PDO · `app/models` · `app/controllers` · `app/views` · `public/` (css, js, front controller) · `database/taskkas.sql`

## Catatan
Tombol login sosial (Google/Apple/X/Discord) hanya tampilan, belum terhubung OAuth.
