<?php
/**
 * KontrakPresenter.php
 * Kumpulan interface Presenter dan helper escape HTML.
 * Interface membuat index.php bisa memakai kedua presenter dengan cara yang sama.
 */

// Fungsi global untuk meng-escape data sebelum ditampilkan (mencegah XSS).
function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

// Kontrak operasi Presenter untuk modul Kandidat.
interface KontrakPresenter {
    public function tampilkanKandidat(): string;
    public function tampilkanFormKandidat(?int $id = null): string;
    public function tambahKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    public function ubahKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    public function hapusKandidat(int $id): void;
}

// Kontrak operasi Presenter untuk modul Lowongan.
interface KontrakPresenterLowongan {
    public function tampilkanLowongan(): string;
    public function tampilkanFormLowongan(?int $id = null): string;
    public function tambahLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): void;
    public function ubahLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): void;
    public function hapusLowongan(int $id): void;
}
