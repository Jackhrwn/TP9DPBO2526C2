<?php
/**
 * KontrakView.php
 * Kumpulan interface untuk lapisan View.
 * Presenter hanya tahu method render, sehingga detail template tetap tersembunyi.
 */

// Kontrak View modul Kandidat.
interface KontrakView {
    public function renderDaftar(string $rowsHtml, int $totalData): string;
    public function renderForm(string $judul, string $aksi, array $data): string;
}

// Kontrak View modul Lowongan.
interface KontrakViewLowongan {
    public function renderDaftarLowongan(string $rowsHtml, int $totalData): string;
    public function renderFormLowongan(string $judul, string $aksi, array $data): string;
}
