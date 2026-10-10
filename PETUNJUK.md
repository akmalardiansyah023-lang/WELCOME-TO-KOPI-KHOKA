# Cara pasang (XAMPP + VS Code)

1. Salin folder `kopi.khoka` ke `C:\xampp\htdocs\` lalu buka foldernya di VS Code.
2. Buka XAMPP Control Panel, klik Start pada Apache dan MySQL.
3. Buka http://localhost/kopi.khoka/buat_database.php  (harus tampil "Berhasil"), lalu HAPUS file buat_database.php.
4. Buka http://localhost/kopi.khoka/menu.html  (jangan klik dua kali file .html).
5. Admin: http://localhost/kopi.khoka/php/admin/login.php  (admin / khoka123, ganti di php/config.php)

Catatan: db-connect.js sudah dipasang langsung di menu.html dan kontak.html,
jadi .htaccess dan modul substitute Apache TIDAK diperlukan lagi.
Kalau Apache memakai port 8080, ganti alamat jadi http://localhost:8080/...

## Menu admin (php/admin/index.php)
- Tambah  : form "Tambah menu" (foto dipilih dari daftar file di images/menu/)
- Edit    : tombol "Edit" -> php/admin/edit.php (ubah nama, kategori, harga, deskripsi, foto, status)
- Update  : ubah harga cepat di tabel, tombol Sembunyikan/Tampilkan
- Hapus   : tombol "Hapus" (ada konfirmasi)
Untuk foto baru: taruh file JPG/PNG/WEBP ke folder images/menu/, lalu foto itu otomatis muncul di dropdown.
