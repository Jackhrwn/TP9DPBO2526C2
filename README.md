# TP9DPBO2526C2 – MVP Kandidat & Lowongan (Strict Passive View)

Aplikasi CRUD dua modul, **Kandidat** dan **Lowongan**, dengan tema rekrutmen/HR
perusahaan IT **NusaTech Solutions**. Dibangun memakai arsitektur
**Model–View–Presenter (MVP)**, PHP 8, dan MySQL melalui PDO prepared statement.

## Janji

Saya **Jaka Permana Herawan** dengan NIM **2509371** mengerjakan Tugas Praktikum 9 pada
Mata Kuliah Desain dan Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka
saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

## Deskripsi

- Modul **Kandidat** mengelola data pelamar: nama, posisi yang dilamar, domisili,
  skor tes, dan pengalaman kerja. Posisi dipilih dari lowongan yang berstatus
  `Buka`, bukan diketik bebas.
- Modul **Lowongan** mengelola data lowongan: posisi, departemen, lokasi, kuota,
  dan status rekrutmen (`Buka`/`Tutup`).
- Kedua modul memakai satu front controller (`project/index.php`), satu layout,
  dan satu stylesheet agar tampilan konsisten.
- Relasi kandidat–lowongan bersifat logis melalui kolom `posisi`: nilainya harus
  sama dengan `judul` salah satu lowongan berstatus `Buka`. Tidak ada foreign key
  agar skema tetap sederhana, tetapi validasi dilakukan di Presenter.

## Teknologi

| Komponen | Keterangan |
|---|---|
| Bahasa | PHP 8.0+ (memakai `match`, typed properties, constructor promotion) |
| Database | MySQL/MariaDB, diakses lewat PDO prepared statement |
| Arsitektur | Model–View–Presenter dengan **Strict Passive View** |
| Tampilan | HTML5, CSS3, Google Fonts (Space Grotesk), tema IT biru–teal |
| Server lokal | XAMPP/Laragon atau PHP built-in server |
| Tanpa dependensi | Tidak memakai framework, Composer, atau rewrite rule |

## Struktur Folder

```text
TP9DPBO2526C2/
├── dokumentasi/                      # demo aplikasi
│   ├── CRUD-Kandidat.gif             # animasi demo modul Kandidat 
│   └── CRUD-Lowongan.gif             # animasi demo modul Lowongan 
├── project/                          # document root aplikasi
│   ├── assets/                       # berkas statis
│   │   ├── logo.svg                  # logo header + favicon
│   │   └── style.css                 # stylesheet utama (berkomentar, per bagian)
│   ├── models/                       # entity, koneksi, query, kontrak model
│   │   ├── DB.php                    # koneksi PDO + eksekusi prepared statement
│   │   ├── Kandidat.php              # entity kandidat
│   │   ├── KontrakModel.php          # interface KontrakModel & KontrakModelLowongan
│   │   ├── Lowongan.php              # entity lowongan
│   │   ├── TabelKandidat.php         # query tabel kandidat
│   │   └── TabelLowongan.php         # query tabel lowongan
│   ├── presenters/                   # logika penyajian dan validasi
│   │   ├── KontrakPresenter.php      # interface presenter + helper h()
│   │   ├── PresenterKandidat.php     # CRUD kandidat + dropdown posisi
│   │   └── PresenterLowongan.php     # CRUD lowongan + opsi status
│   ├── template/                     # template HTML ber-token
│   │   ├── form.html                 # form tambah/ubah kandidat
│   │   ├── form_lowongan.html        # form tambah/ubah lowongan
│   │   ├── layout.html               # kerangka halaman + navigasi bawah
│   │   ├── skin.html                 # daftar kandidat
│   │   └── skin_lowongan.html        # daftar lowongan
│   ├── views/                        # perakit token template
│   │   ├── BaseView.php              # memuat template + membungkus layout
│   │   ├── KontrakView.php           # interface view kandidat & lowongan
│   │   ├── ViewKandidat.php          # render daftar/form kandidat
│   │   └── ViewLowongan.php          # render daftar/form lowongan
│   └── index.php                     # front controller aplikasi
├── mvp_db.sql                        # skema database + data contoh
└── README.md                         # dokumen ini
```

Keterangan singkat:

- `dokumentasi/` menyimpan GIF demo yang dipakai di README.
- `project/` adalah document root; jalankan aplikasi lewat web server, bukan
  dengan membuka `index.php` langsung dari filesystem.
- `mvp_db.sql` membuat database `mvp_kandidat_db`, kedua tabel, dan data contoh.
- Semua aset tampilan (`style.css`, `logo.svg`) bersifat lokal; hanya font yang
  diambil dari Google Fonts.

## Arsitektur

```text
Browser → index.php → Presenter → Model → MySQL (PDO)
                          │
                          └→ View → template/*.html → HTML
```

| Lapisan | Modul Kandidat | Modul Lowongan | File kontrak |
|---|---|---|---|
| Model (entity) | `Kandidat` | `Lowongan` | – |
| Model (tabel) | `TabelKandidat` | `TabelLowongan` | `models/KontrakModel.php` |
| Presenter | `PresenterKandidat` | `PresenterLowongan` | `presenters/KontrakPresenter.php` |
| View | `ViewKandidat` | `ViewLowongan` | `views/KontrakView.php` |
| Template | `skin.html`, `form.html` | `skin_lowongan.html`, `form_lowongan.html` | – |

Dipakai bersama:

- `models/DB.php` – koneksi PDO dan eksekusi prepared statement.
- `views/BaseView.php` – memuat template lalu membungkusnya dengan `layout.html`.
- `project/assets/style.css` – seluruh gaya tampilan, disusun per bagian.
- `project/assets/logo.svg` – logo NusaTech (hexagon + kurung kode), dipakai di
  header dan favicon.

### Tanggung jawab tiap lapisan

- **Model** hanya berurusan dengan database: query, pemetaan baris menjadi entity,
  dan implementasi interface `KontrakModel`/`KontrakModelLowongan`.
- **Presenter** memanggil Model, memvalidasi input, menghitung total data,
  merakit baris `<tr>`, meng-escape data dengan `h()`, lalu memanggil View.
  `PresenterKandidat` juga memakai `KontrakModelLowongan` untuk mengisi dropdown
  posisi dari lowongan yang sedang dibuka.
- **View** hanya membaca template (`file_get_contents`), mengganti token dengan
  `strtr`, dan mengembalikan HTML. View tidak menyentuh Model.
- **Template** menyimpan kerangka HTML dengan token seperti `{{TABLE_ROWS}}`
  dan `{{TOTAL_DATA}}`.

## Skema Database

Database: `mvp_kandidat_db` (dibuat otomatis oleh `mvp_db.sql`).

### Tabel `kandidat`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT, PK, AUTO_INCREMENT | Identitas kandidat |
| `nama` | VARCHAR(100), NOT NULL | Nama lengkap |
| `posisi` | VARCHAR(100), NOT NULL | Posisi yang dilamar; harus cocok dengan `judul` lowongan berstatus `Buka` |
| `domisili` | VARCHAR(100), NOT NULL | Kota domisili |
| `skorTes` | INT, default 0 | Skor tes 0–100 |
| `pengalamanTahun` | INT, default 0 | Pengalaman kerja dalam tahun |

### Tabel `lowongan`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT, PK, AUTO_INCREMENT | Identitas lowongan |
| `judul` | VARCHAR(100), NOT NULL | Nama posisi |
| `departemen` | VARCHAR(100), NOT NULL | Departemen pembuka lowongan |
| `lokasi` | VARCHAR(100), NOT NULL | Lokasi kerja |
| `kuota` | INT, default 1 | Jumlah orang yang dibutuhkan |
| `status` | ENUM('Buka','Tutup'), default 'Buka' | Status rekrutmen |

Data contoh: **5 kandidat** dan **5 lowongan** (4 `Buka`, 1 `Tutup`).
Kandidat `QA Engineer` pada data contoh mengacu ke lowongan yang berstatus
`Tutup`; posisi lama tersebut tetap dipertahankan saat kandidat diedit.

## Fitur

**CRUD dan navigasi**

- Branding dan konten bertema perusahaan IT **NusaTech Solutions**.
- Daftar kandidat dan lowongan diurutkan berdasarkan id secara ascending,
  sehingga data yang baru ditambahkan selalu muncul di baris paling bawah.
- Tambah, ubah, dan hapus data untuk kedua modul.
- Navigasi bawah hanya berisi menu **Kandidat** dan **Lowongan**.
- Konfirmasi sebelum menghapus data.

**Dropdown posisi kandidat**

- Hanya berisi posisi dari lowongan berstatus `Buka`.
- Posisi lama yang lowongannya sudah tidak aktif tetap ditampilkan saat edit.
- Jika belum ada lowongan `Buka`, dropdown menampilkan pesan khusus.

**Validasi dan keamanan**

- Validasi server-side:
  - nama/judul/posisi/departemen wajib diisi;
  - posisi kandidat harus berasal dari lowongan `Buka` (kecuali posisi lama
    yang tidak diubah saat edit);
  - skor tes dibatasi 0–100;
  - pengalaman tidak boleh negatif;
  - kuota minimal 1;
  - status lowongan hanya `Buka` atau `Tutup`.
- Form juga memakai atribut HTML `required`, `min`, dan `max` sebagai validasi
  awal di browser.
- Escape output dengan `htmlspecialchars` untuk mencegah XSS.
- Semua query memakai prepared statement untuk mencegah SQL injection.

**Tampilan**

- Badge status lowongan (hijau untuk `Buka`, merah untuk `Tutup`).
- Empty state saat tabel belum berisi data.
- Layout responsif: tabel dapat digeser dan navigasi bawah tetap terlihat.

## Alur Program

1. `project/index.php` memuat seluruh kelas Model, View, dan Presenter.
2. Modul dipilih dari `?page=kandidat|lowongan`; default ke kandidat.
3. Presenter dibuat dengan Model dan View konkret melalui interface.
4. **Read (GET):**
   - `tampilkanKandidat()` / `tampilkanLowongan()` mengambil data dari Model;
   - Presenter merakit baris tabel dan menghitung total;
   - View mengisi `skin*.html`, lalu membungkusnya dengan `layout.html`.
5. **Form (GET):**
   - `?aksi=form` menampilkan form tambah dengan dropdown posisi dari lowongan `Buka`;
   - `?aksi=form&id=N` menampilkan form ubah dengan data ter-prefill dan posisi
     lama tetap terpilih.
6. **Write (POST):**
   - `action=tambah|ubah|hapus` diproses Presenter;
   - Presenter memvalidasi input lalu memanggil Model;
   - setelah selesai, aplikasi redirect ke daftar (pola Post/Redirect/Get).

## Rute Aplikasi

| Method | URL | Fungsi |
|---|---|---|
| GET | `index.php` | Daftar kandidat |
| GET | `index.php?aksi=form` | Form tambah kandidat |
| GET | `index.php?aksi=form&id=1` | Form ubah kandidat |
| GET | `index.php?page=lowongan` | Daftar lowongan |
| GET | `index.php?page=lowongan&aksi=form` | Form tambah lowongan |
| GET | `index.php?page=lowongan&aksi=form&id=1` | Form ubah lowongan |
| POST | `index.php` + `page`, `action`, `id` | Proses tambah/ubah/hapus |

## Aturan Strict Passive View

- Folder `views/` **tidak** memakai `require_once` Model/Entity.
- View tidak memakai `foreach`, `count()`, atau query database.
- View hanya memuat template dan mengganti token dengan `strtr`.
- Presenter yang:
  - memanggil Model;
  - menghitung total data;
  - merakit `<tr>`;
  - meng-escape data dengan `h()`;
  - merakit opsi status lowongan;
  - merakit opsi posisi kandidat dari lowongan berstatus `Buka`.
- `h()` didefinisikan sekali di `presenters/KontrakPresenter.php`.

## Dokumentasi

Demo berikut direkam langsung dari aplikasi dan disimpan di folder
`dokumentasi/`.

### Modul Kandidat

<img width="720" height="371" alt="CRUD-Kandidat" src="https://github.com/user-attachments/assets/45c389d6-4159-4181-bf56-8c3021d1fd16" />

Skenario: melihat daftar, menambah kandidat dengan posisi dari lowongan `Buka`,
mengubah data, lalu menghapus data dengan konfirmasi.

### Modul Lowongan

<img width="720" height="372" alt="CRUD-Lowongan" src="https://github.com/user-attachments/assets/f43a3e26-2687-46d5-843e-9cb8f6b78c77" />

Skenario: menambah lowongan baru, memilih status `Buka`/`Tutup`, mengubah data,
dan menghapus lowongan.

## Pengujian Manual

1. Halaman awal menampilkan 5 kandidat; data lama di atas dan data baru
   ditambahkan ke baris paling bawah.
2. Menu **Lowongan** menampilkan 5 data dengan badge status yang sesuai.
3. Tambah kandidat: dropdown posisi hanya berisi lowongan berstatus `Buka`;
   field wajib kosong ditolak browser; skor di luar 0–100 dibatasi oleh server.
4. Tambah kandidat dengan posisi yang tidak ada di dropdown (misalnya lewat
   manipulasi request) ditolak oleh validasi Presenter.
5. Ubah kandidat: form terisi otomatis dengan posisi lama terpilih, lalu data
   berubah setelah disimpan.
6. Hapus kandidat: muncul konfirmasi, data hilang, dan kembali ke daftar.
7. Tambah/ubah lowongan: status `Buka`/`Tutup` tersimpan, data baru muncul
   paling bawah, dan badge berubah.
8. Hapus lowongan: konfirmasi muncul dan daftar diperbarui.
9. Perkecil layar: tabel dapat digeser dan navigasi bawah tetap rapi.
10. Refresh setelah POST tidak mengulang aksi (karena redirect).

## Catatan

- `kandidat` dan `lowongan` tidak memiliki foreign key; penghapusan di satu tabel
  tidak memengaruhi tabel lain. Relasi posisi kandidat ke lowongan divalidasi di
  Presenter dengan mencocokkan `kandidat.posisi` dan `lowongan.judul` berstatus
  `Buka`.
- Menghapus lowongan tidak otomatis menghapus kandidat; kandidat lama tetap
  menyimpan posisi yang pernah dilamar dan posisi tersebut ditandai
  “lowongan tidak aktif” saat diedit.
- Semua query memakai prepared statement, sehingga input pengguna tidak
  digabungkan langsung ke string SQL.
- Status lowongan dibatasi oleh ENUM di database dan divalidasi ulang oleh
  Presenter.
- Data contoh dapat dihapus kapan saja melalui antarmuka aplikasi.
- Mengimpor ulang `mvp_db.sql` akan mengembalikan database ke kondisi awal dan
  menghapus perubahan data.
