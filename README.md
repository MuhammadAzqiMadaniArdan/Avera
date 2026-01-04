# Avera App

[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

## 1. Deskripsi Proyek
**Avera App** adalah aplikasi e-commerce modern yang dirancang untuk memberikan pengalaman belanja online yang cepat, aman, dan mudah digunakan.  
Aplikasi ini menggunakan **Next.js (TypeScript)** untuk frontend dan **Laravel** untuk backend (tergantung implementasi), dengan arsitektur monorepo.  

Avera App mendukung:  
- Browsing produk dan kategori  
- Manajemen toko dan produk  
- Keranjang belanja dan checkout  
- Integrasi **Midtrans** untuk pembayaran  
- Estimasi ongkir & tracking order (Indonesia)  
- Sistem voucher dan promo  

---

## 2. Fitur Utama
- **Frontend (Next.js + TypeScript)**
  - Product listing, detail, carousel, filter
  - Cart management
  - Checkout & Midtrans integration
  - Responsive UI dengan Tailwind CSS
- **Backend**
  - API untuk produk, cart, checkout, user, voucher
  - Integrasi Midtrans (Snap token)
  - Integrasi API ongkir (RajaOngkir / kurir lokal)

---

## 3. Arsitektur Monorepo
<ul>
<li>/Avera</li>
<li>├─ /avera-be</li>
<li>├─ /avera-fe</li>
<li>└─ README.md</li>
</ul>

## 4. Database Schema

> Berikut diagram tabel Avera App (produk, toko, order, user, review, promo, banner):

![Database Schema](https://github.com/user-attachments/assets/cab53e4c-866f-49ba-81d4-3dbceee593a0")

**Penjelasan tabel utama:**
- **users**: data user termasuk nama, email, role (buyer, seller)  
- **stores**: data toko, satu user bisa punya satu toko  
- **products**: daftar produk, bisa lebih dari satu image  
- **orders**: data order + status (pending, paid, shipped)  
- **order_items**: detail tiap produk dalam order  
- **reviews**: review produk & order  
- **promos**: kode voucher dan diskon  
- **banners**: gambar banner homepage / toko  

---


