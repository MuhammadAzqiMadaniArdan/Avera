# Avera App

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

| Layer         | Teknologi                                      |
| ------------- | ---------------------------------------------- |
| Frontend      | Next.js, TypeScript, Tailwind CSS, React Query |
| Backend       | Laravel 11, PHP 8.3-FPM, Redis (Predis)        |
| Database      | PostgreSQL 15                                  |
| Caching/Queue | Redis (Predis)                                 |
| Payments      | Midtrans (Snap token, COD)                     |
| Shipping      | RajaOngkir API                                 |
| Moderation    | Sightengine (image moderation)                 |
| Container     | Docker, Docker Compose                         |
| Testing       | PHPUnit (Feature & Unit test)                  |

---

## 5. Database Schema

**Tabel Utama:**

* **users**: data user, roles (`buyer`, `seller`, `admin`)
* **stores**: data toko, satu user bisa punya satu toko
* **products**: daftar produk, bisa lebih dari satu image
* **orders**: data order + status (`pending`, `awaiting_payment`, `shipped`, `completed`)
* **order_items**: detail tiap produk dalam order
* **cart_items**: keranjang user
* **reviews**: review produk & order
* **promos**: kode voucher dan diskon
* **banners**: gambar banner homepage / toko
* **payments**: data transaksi (Midtrans / COD)
* **shipments**: estimasi ongkir & tracking

**Diagram Tabel Sederhana:**


> Berikut diagram tabel Avera App (produk, toko, order, user, review, promo, banner):

![Database Schema](https://github.com/user-attachments/assets/5bd62362-df8b-46b5-9add-702e4f9717db)


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
* Backend API: `http://localhost:8000/api/v1`

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
GET /api/v1/payment/{order_id}/status
```

### Shipping

```http
GET /api/v1/shipments/cost?origin=...&destination=...
```

### Moderation (image upload)

```http
POST /api/v1/products/{id}/upload-image
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

## 10. Screenshot & Flow (Portfolio-ready)

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
User enters address → RajaOngkir API calculates cost → Checkout
```
