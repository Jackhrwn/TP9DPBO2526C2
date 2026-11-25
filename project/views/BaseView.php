<?php
/**
 * BaseView.php
 * Kelas dasar View untuk memuat template HTML dan membungkusnya dengan layout.
 * Tidak ada akses ke Model di lapisan ini (Strict Passive View).
 */
abstract class BaseView {
    // Membaca file template dari folder template/.
    protected function load(string $file): string {
        return file_get_contents(__DIR__ . '/../template/' . $file);
    }

    // Menggabungkan konten dengan layout.html dan mengisi menu aktif.
    protected function layout(string $title, string $content, string $active): string {
        return strtr($this->load('layout.html'), [
            '{{TITLE}}' => $title,
            '{{CONTENT}}' => $content,
            '{{ACTIVE_KANDIDAT}}' => $active === 'kandidat' ? 'active' : '',
            '{{ACTIVE_LOWONGAN}}' => $active === 'lowongan' ? 'active' : '',
        ]);
    }
}
