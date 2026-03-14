# 🚗 ParkirKu

**ParkirKu** adalah aplikasi sistem parkir berbasis web yang dibuat untuk mengelola kendaraan yang masuk dan keluar area parkir.
Sistem ini mendukung **member berlangganan** dan **pengunjung non-member** dengan sistem pembayaran parkir yang berbeda.

---

## ✨ Fitur

### 👑 Admin

Admin bertugas mengelola sistem parkir.

Fitur:

* Login ke dashboard admin
* Mengelola data member
* Melihat data kendaraan masuk dan keluar
* Mengelola transaksi parkir
* Melihat laporan parkir

---

### 🪪 Member

Member adalah pengguna yang memiliki **langganan parkir**.

Fitur:

* Terdaftar sebagai member
* Membayar biaya langganan parkir
* Parkir **gratis selama masa langganan aktif**
* Riwayat parkir tetap tercatat di sistem

---

### 🚶 Non Member

Pengunjung yang tidak memiliki langganan parkir.

Fitur:

* Masuk area parkir tanpa registrasi member
* Biaya parkir dihitung berdasarkan **durasi waktu parkir**
* Melakukan pembayaran saat keluar area parkir

---

## 🛠 Teknologi

Project ini dibuat menggunakan:

* **Laravel**
* **Tailwind CSS**
* **PHP**
* **MySQL**
* **JavaScript**

---

## 🚀 Cara Menjalankan Project

1. Clone repository

```
git clone https://github.com/SatriaFarel/parkirku.git
```

2. Masuk ke folder project

```
cd parkirku
```

3. Install dependency Laravel

```
composer install
```

4. Install dependency frontend

```
npm install
```

5. Copy file environment

```
cp .env.example .env
```

6. Generate application key

```
php artisan key:generate
```

7. Jalankan migration database

```
php artisan migrate
```

8. Jalankan server Laravel

```
php artisan serve
```

Akses aplikasi di browser:

```
http://localhost:8000
```

---

## 📊 Sistem Laporan

Admin dapat melihat laporan seperti:

* Data kendaraan yang parkir
* Transaksi parkir
* Aktivitas member dan non-member

Laporan ini membantu memantau penggunaan parkir dan pemasukan dari sistem.

---

## 🎯 Tujuan Project

Project ini dibuat sebagai latihan pengembangan aplikasi web menggunakan **Laravel** dengan konsep:

* Multi role system
* Manajemen member parkir
* Sistem transaksi parkir
* Dashboard laporan parkir

---

## 👨‍💻 Author

**Farel (Kishi)**
Student & Web Developer
