-- =====================================================================
-- mvp_db.sql
-- Skema dan data contoh untuk aplikasi MVP Kandidat & Lowongan.
-- =====================================================================

-- Membuat database utama bila belum tersedia, lalu memakainya.
CREATE DATABASE IF NOT EXISTS mvp_kandidat_db;
USE mvp_kandidat_db;

-- ---------------------------------------------------------------------
-- Tabel kandidat
-- Menyimpan data pelamar yang dikelola modul Kandidat.
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS kandidat;
CREATE TABLE kandidat (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  posisi VARCHAR(100) NOT NULL,
  domisili VARCHAR(100) NOT NULL,
  skorTes INT NOT NULL DEFAULT 0,
  pengalamanTahun INT NOT NULL DEFAULT 0
);

-- Data contoh agar daftar kandidat langsung terisi saat aplikasi dibuka.
-- Nilai kolom posisi harus sama dengan judul lowongan berstatus "Buka" di bawah.
INSERT INTO kandidat (nama,posisi,domisili,skorTes,pengalamanTahun) VALUES
('Raka Pratama','Backend Developer','Bandung',92,3),
('Nadia Kusuma','Frontend Developer','Surabaya',90,2),
('Sinta Maharani','UI/UX Designer','Jakarta',88,2),
('Dimas Nugroho','Data Analyst','Yogyakarta',85,1),
('Bagas Setiawan','QA Engineer','Bandung',81,1);

-- ---------------------------------------------------------------------
-- Tabel lowongan
-- Menyimpan lowongan rekrutmen. Tidak ada foreign key ke kandidat, tetapi
-- kolom judul dipakai sebagai sumber pilihan posisi yang dilamar kandidat.
-- Status dibatasi ENUM agar hanya berisi "Buka" atau "Tutup".
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS lowongan;
CREATE TABLE lowongan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(100) NOT NULL,
  departemen VARCHAR(100) NOT NULL,
  lokasi VARCHAR(100) NOT NULL,
  kuota INT NOT NULL DEFAULT 1,
  status ENUM('Buka','Tutup') NOT NULL DEFAULT 'Buka'
);

-- Data contoh lowongan dari beberapa departemen.
-- Posisi kandidat pada data contoh mengacu ke judul lowongan di tabel ini.
INSERT INTO lowongan (judul,departemen,lokasi,kuota,status) VALUES
('Backend Developer','Engineering','Bandung',2,'Buka'),
('Frontend Developer','Engineering','Surabaya',2,'Buka'),
('UI/UX Designer','Product','Jakarta',1,'Buka'),
('Data Analyst','Data','Yogyakarta',1,'Buka'),
('QA Engineer','Engineering','Remote',1,'Tutup');
