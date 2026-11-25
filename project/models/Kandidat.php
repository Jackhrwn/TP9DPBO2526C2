<?php
/**
 * Kandidat.php
 * Entity data kandidat (immutable): hanya menyimpan data dan menyediakan getter.
 * Objek ini dibuat oleh TabelKandidat dari baris database.
 */
class Kandidat {
    /**
     * Konstruktor memakai property promotion agar ringkas.
     * Semua field diisi sekali saat objek dibuat.
     */
    public function __construct(
        private int $id, private string $nama, private string $posisi,
        private string $domisili, private int $skorTes, private int $pengalamanTahun
    ) {}

    // Getter dipakai Presenter untuk merakit tampilan.
    public function getId(): int { return $this->id; }
    public function getNama(): string { return $this->nama; }
    public function getPosisi(): string { return $this->posisi; }
    public function getDomisili(): string { return $this->domisili; }
    public function getSkorTes(): int { return $this->skorTes; }
    public function getPengalamanTahun(): int { return $this->pengalamanTahun; }
}
