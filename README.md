<div align="center" style="display: flex; justify-content: space-between; align-items: center; width: 100%; max-width: 800px; margin: auto;">

<h1 style="margin: 0;"># Avera App</h1>
<div style="display: flex; justify-content: space-between;">
<img width="149" height="170" alt="avera-logo-new" src="https://github.com/user-attachments/assets/becaabad-f916-40b8-b108-61df81d89e05" />
<img width="500" height="5295" alt="Homepage" src="https://github.com/user-attachments/assets/7372c6a5-cd48-4896-aab5-1910974aab6f" />
</div>

</div>

## License

[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

---

## 1. Deskripsi Proyek

**Avera App** adalah aplikasi e-commerce modern yang dirancang untuk memberikan pengalaman belanja online yang **cepat, aman, dan nyaman**.
Aplikasi ini menggunakan **Next.js (TypeScript)** untuk frontend dan **Laravel 11 + PHP 8.3-FPM** untuk backend, dengan arsitektur monorepo.

**Fitur Utama:**

* Browsing produk & kategori
* Manajemen toko & produk
* Keranjang belanja & checkout
* Pembayaran COD & Midtrans Snap token
* Estimasi ongkir & tracking order (Indonesia)
* Sistem voucher, promo & diskon
* Moderasi konten gambar menggunakan **Sightengine**
* Perhitungan ongkir & wilayah dengan **RajaOngkir**
* Sistem user dengan roles: buyer, seller, admin

---

## 2. Fitur Utama

### Frontend (Next.js + TypeScript)

* Product listing, detail, carousel, filter
* Cart management
* Checkout & Midtrans integration
* Responsive UI dengan Tailwind CSS
* Preview gambar & upload dengan moderation (Sightengine)
* Scrollable lists & interaktif modal untuk pengalaman user-friendly

### Backend (Laravel 11 + PHP 8.3 + Redis)

* REST API untuk produk, cart, checkout, user, voucher, order
* Policy & Gate untuk otorisasi user
* Integrasi Midtrans (Snap token) & pembayaran COD
* Integrasi API ongkir (RajaOngkir / kurir lokal)
* Redis caching untuk session & queue (Predis client)
* Unit & Feature tests untuk core order flow
* Queue & Event system untuk email & push notification
* Validasi dan moderasi upload gambar menggunakan **Sightengine API**

---

## 3. Arsitektur Monorepo

```
/Avera
├─ /avera-be      # Backend Laravel 11 + PHP-FPM + Redis
│  ├─ app/
│  ├─ config/
│  ├─ database/
│  └─ docker/
├─ /avera-fe      # Frontend Next.js + TypeScript + Tailwind CSS
├─ docker-compose.yml
└─ README.md
```

---

## 4. Teknologi yang Digunakan

| Layer         | Teknologi                                              |
| ------------- | -------------------------------------------------------|
| Frontend      | Next.js, TypeScript, Tailwind CSS, Shadcn, React Query |
| Backend       | Laravel 11, PHP 8.3-FPM, Redis (Predis)                |
| Database      | PostgreSQL 15                                          |
| Caching/Queue | Redis (Predis)                                         |
| Payments      | Midtrans (Snap token, COD)                             |
| Shipping      | RajaOngkir API                                         |
| Moderation    | Sightengine (image moderation)                         |
| Container     | Docker, Docker Compose                                 |
| Testing       | PHPUnit (Feature & Unit test)                          |

---

## 5. Database Schema

**Tabel Utama:**


### **users**
- Menyimpan data pengguna aplikasi.
- Role: `buyer`, `seller`, `admin`.

### **stores**
- Menyimpan data toko.
- Satu user bisa memiliki **satu toko**.
- Relasi: `products`, `store_vouchers`, `banners`.

### **products**
- Menyimpan daftar produk.
- Bisa memiliki lebih dari satu gambar (relasi ke tabel `images`).
- Bisa masuk dalam `promotions` atau `campaigns`.

### **orders**
- Menyimpan data order dari user.
- Menyertakan subtotal, shipping cost, total price.
- Status: `pending`, `awaiting_payment`, `paid`, `processing`, `shipped`, `completed`, `cancelled`.

### **order_items**
- Detail tiap produk dalam order.
- Termasuk: quantity, price, discount, weight, voucher yang digunakan.

### **cart_items**
- Menyimpan data produk di keranjang user.
- Mempermudah proses checkout.

### **reviews**
- Menyimpan ulasan dan rating produk.
- Bisa terkait ke `order_item` untuk produk yang sudah dibeli.

### **promotions / promotion_products**
- Menyimpan data promo toko: `product_discount`, `bundle`, `combo`.
- Menyimpan produk yang termasuk dalam promo.

### **store_vouchers / campaign_vouchers / user_vouchers**
- Menyimpan data voucher: `cashback`, `discount`, `free_shipping`.
- Relasi ke toko/campaign dan user yang mengklaim voucher.

### **banners / banner_images**
- Menyimpan banner homepage, kategori, promo, toko, atau admin.
- Bisa memiliki multiple images dengan urutan dan status moderasi.

### **checkouts / checkout_items / checkout_shipments**
- Menyimpan data sementara sebelum menjadi order.
- Termasuk produk, harga, ongkir, voucher, promo, dan pilihan kurir.

### **payments**
- Menyimpan data transaksi.
- Metode: `COD` atau payment gateway (misal: Midtrans).
- Menyimpan status pembayaran dan informasi gateway.

### **shipments**
- Menyimpan data pengiriman order.
- Termasuk kurir, service, estimasi hari, tracking number, recipient info, dan status pengiriman.

### **invoices**
- Menyimpan data invoice untuk setiap order.
- Termasuk nomor invoice, tanggal diterbitkan, due date, status pembayaran, total amount.

### **chats**
- Menyimpan pesan antara user (buyer-seller) untuk komunikasi terkait order.

### **courier_slas**
- Menyimpan estimasi waktu pengiriman (SLAs) untuk masing-masing kurir yang aktif.

---

## Catatan
- Tabel `images` menyimpan file gambar untuk `products`, `stores`, `users`, `banners`, dan kategori.
- Tabel `user_addresses` dan `store_addresses` menyimpan detail alamat lengkap, termasuk provinsi, kota, distrik, dan desa, menggunakan data `rajaongkir`.
- Moderation dilakukan pada `images` dan `banners` untuk memastikan konten sesuai aturan.


**Diagram Tabel Akhir:**


> Berikut diagram tabel Avera App :

![Database Schema](https://github.com/user-attachments/assets/ed64a337-e24a-4f82-b94d-e1de1fc1b427)


```
users ---< stores ---< products
users ---< cart_items --- products
users ---< orders ---< order_items --- products
orders ---< payments
products ---< reviews
orders ---< shipments
```

---

## 6. Setup & Menjalankan

### 6.1. Clone repository

```bash
git clone https://github.com/username/avera.git
cd avera
```

### 6.2. Copy environment file

```bash
cp avera-be/.env.example avera-be/.env
cp avera-fe/.env.example avera-fe/.env
```

### 6.3. Tambahkan konfigurasi `.env` Backend

```env
# Database
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Midtrans
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

# Sightengine
SIGHTENGINE_API_USER=your_user
SIGHTENGINE_API_SECRET=your_secret

# RajaOngkir
RAJAONGKIR_API_KEY=your_api_key
```

### 6.4. Jalankan Docker Compose

```bash
docker-compose up --build -d
```

### 6.5. Migrasi database & generate key

```bash
docker exec -it avera-backend bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 6.6. Akses aplikasi

* Frontend: `http://localhost:3000`
* Backend API: `http://localhost:8001/api/v1`

---

## 7. Contoh Endpoint

### Produk

```http
GET /api/v1/products
GET /api/v1/products/{id}
```

### Cart

```http
GET /api/v1/cart
POST /api/v1/cart
DELETE /api/v1/cart/{id}
```

### Checkout & Order

```http
POST /api/v1/checkout/{checkout_id}/place-order
```

### Payment

```http
  POST /api/v1/order/payment/callback
```


### Moderation (image upload)

```http
POST /api/v1/seller/product/image
# otomatis dicek menggunakan Sightengine API
```

---

## 8. Testing

Backend Laravel:

```bash
docker exec -it avera-backend bash
php artisan test
```

---

## 9. Roadmap Fitur Mendatang

* Push notification & email otomatis
* Multi-vendor marketplace
* Wishlist & rekomendasi produk
* Admin dashboard analytics
* Multi-language & multi-currency support
* CI/CD GitHub Actions + Docker build otomatis
* Optimasi caching Redis & queue untuk performa

---

## 10. Screenshot & Flow

**Order Flow:**

```
User selects product → Add to Cart → Checkout → Place Order → Payment (Midtrans/COD) → Shipment → Order Completed
```

**Moderation Flow:**

```
User uploads product image → Sightengine moderation → Approved / Rejected
```

**Ongkir Flow:**

```
User selects product → Add to Cart → Checkout → RajaOngkir API calculates cost → Checkout Page
```
