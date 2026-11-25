<?php
/**
 * Lowongan.php
 * Entity data lowongan (immutable): hanya menyimpan data dan menyediakan getter.
 * Objek ini dibuat oleh TabelLowongan dari baris database.
 */
class Lowongan {
    /**
     * Konstruktor memakai property promotion agar ringkas.
     * Status yang valid: "Buka" atau "Tutup".
     */
    public function __construct(
        private int $id, private string $judul, private string $departemen,
        private string $lokasi, private int $kuota, private string $status
    ) {}

    // Getter dipakai Presenter untuk merakit tampilan.
    public function getId(): int { return $this->id; }
    public function getJudul(): string { return $this->judul; }
    public function getDepartemen(): string { return $this->departemen; }
    public function getLokasi(): string { return $this->lokasi; }
    public function getKuota(): int { return $this->kuota; }
    public function getStatus(): string { return $this->status; }
}
