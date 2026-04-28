# 📄 PRODUCT REQUIREMENTS DOCUMENT (PRD)

## Aplikasi Harga Pasar Komoditi Pertanian

### Dinas Pertanian Kabupaten Muna Barat

---

# 1. 🎯 LATAR BELAKANG

Fluktuasi harga komoditi pertanian di pasar sering tidak transparan dan sulit diakses oleh petani dan masyarakat. Hal ini menyebabkan ketimpangan informasi yang berdampak pada keputusan jual beli.

Aplikasi ini dibangun untuk menyediakan **informasi harga pasar harian yang akurat, transparan, dan mudah diakses**.

---

# 2. 🎯 TUJUAN

* Menyediakan informasi harga komoditi harian
* Meningkatkan transparansi harga pasar
* Mendukung pengambilan keputusan petani
* Digitalisasi layanan publik dinas

---

# 3. 👥 ROLE & USER

## 3.1 Admin

* Kelola master data
* Validasi harga
* Monitoring data

## 3.2 Petugas Lapangan

* Input harga per pedagang

## 3.3 Publik

* Melihat harga tanpa login

---

# 4. 🧩 SCOPE FITUR

## 4.1 Dashboard

* Statistik harga hari ini
* Grafik tren harga
* Komoditas naik/turun

## 4.2 Master Data

* Komoditas
* Kategori
* Pasar
* Kecamatan
* Desa
* Pedagang

## 4.3 Input Harga Detail (RAW DATA)

* Input harga per pedagang
* Field:

  * komoditas
  * pedagang
  * pasar
  * tanggal
  * harga

## 4.4 Agregasi Harga

* Otomatis generate:

  * harga_min
  * harga_max
  * harga_avg

## 4.5 Validasi Data

* Status:

  * draft
  * approved

## 4.6 Halaman Publik

* Tabel harga hari ini
* Filter:

  * tanggal
  * komoditas
  * pasar

## 4.7 Grafik

* Tren harga per komoditas

## 4.8 Laporan

* Export PDF
* Export Excel

---

# 5. 🗄️ DESAIN DATABASE

## 5.1 tabel_kategori

* id
* nama

## 5.2 tabel_komoditas

* id
* kategori_id
* nama

## 5.3 tabel_kecamatan

* id
* nama

## 5.4 tabel_desa

* id
* kecamatan_id
* nama

## 5.5 tabel_pasar

* id
* kecamatan_id
* nama

## 5.6 tabel_pedagang

* id
* pasar_id
* nama

## 5.7 tabel_harga_detail

* id
* komoditas_id
* pedagang_id
* pasar_id
* tanggal
* harga

## 5.8 tabel_harga

* id
* komoditas_id
* pasar_id
* tanggal
* harga_min
* harga_max
* harga_avg
* status

---

# 6. 🔄 FLOW BISNIS

1. Petugas input harga dari pedagang
2. Data masuk ke harga_detail
3. Sistem hitung agregasi
4. Admin validasi
5. Data tampil ke publik

---

# 7. ⚙️ ARSITEKTUR

## Layer

* Controller
* Service
* Repository
* Model

## Best Practice

* Gunakan eager loading
* Hindari N+1
* Gunakan caching untuk publik

---

# 8. 🔌 API (OPSIONAL)

* GET /api/harga-hari-ini
* GET /api/harga?komoditas=

---

# 9. 🎨 UI/UX

## Admin

* Sidebar navigation
* Table + filter
* Form repeater input harga
* Modern, Profesional, Rapi, Keren
* Bootstrap 5

## Publik

* Clean table
* Mobile friendly
* Modern, Profesional, Rapi, Keren
* Bootstrap 5

---

# 10. 🚀 NON-FUNCTIONAL REQUIREMENTS

* Performance cepat
* Responsive
* Secure (validasi input)

---

# 11. 📊 KPI SUKSES

* Data harga terupdate setiap hari
* Pengguna publik meningkat
* Transparansi harga meningkat

---

# 12. 🗓️ SPRINT PLAN (REKOMENDASI)

## Sprint 1

* Setup project
* Master data

## Sprint 2

* Input harga detail
* Agregasi harga

## Sprint 3

* Dashboard
* Publik page

## Sprint 4

* Export
* API

---

# 13. ⚠️ RISIKO

* Data tidak valid
* Keterlambatan input

## Mitigasi

* Validasi admin
* Reminder petugas

---

# 14. 🏁 PENUTUP

Aplikasi ini menjadi solusi digital untuk transparansi harga komoditi pertanian dan mendukung kesejahteraan petani melalui akses informasi yang akurat dan real-time.
