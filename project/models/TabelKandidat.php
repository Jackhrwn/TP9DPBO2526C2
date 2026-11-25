<?php
/**
 * TabelKandidat.php
 * Implementasi KontrakModel untuk tabel "kandidat".
 * Berisi query prepared statement dan konversi baris database menjadi entity Kandidat.
 */
class TabelKandidat extends DB implements KontrakModel {
    // Mengubah satu baris hasil query menjadi objek Kandidat.
    private function mapRowToKandidat(array $row): Kandidat {
        return new Kandidat((int)$row['id'], $row['nama'], $row['posisi'], $row['domisili'], (int)$row['skorTes'], (int)$row['pengalamanTahun']);
    }

    // Mengambil semua kandidat berdasarkan id agar data baru tampil paling bawah.
    public function getAllKandidat(): array {
        $this->executeQuery("SELECT * FROM kandidat ORDER BY id ASC");
        $list = [];
        foreach ($this->getAllResult() as $row) { $list[] = $this->mapRowToKandidat($row); }
        return $list;
    }

    // Mengambil satu kandidat berdasarkan id; null bila tidak ditemukan.
    public function getKandidatById(int $id): ?Kandidat {
        $this->executeQuery("SELECT * FROM kandidat WHERE id = :id LIMIT 1", [':id' => $id]);
        $rows = $this->getAllResult();
        return empty($rows) ? null : $this->mapRowToKandidat($rows[0]);
    }

    // Menambah kandidat baru dengan prepared statement.
    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): bool {
        return $this->executeQuery(
            "INSERT INTO kandidat (nama, posisi, domisili, skorTes, pengalamanTahun) VALUES (:nama, :posisi, :domisili, :skor, :peng)",
            [':nama' => $nama, ':posisi' => $posisi, ':domisili' => $domisili, ':skor' => $skorTes, ':peng' => $pengalamanTahun]);
    }

    // Memperbarui data kandidat berdasarkan id.
    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): bool {
        return $this->executeQuery(
            "UPDATE kandidat SET nama=:nama, posisi=:posisi, domisili=:domisili, skorTes=:skor, pengalamanTahun=:peng WHERE id=:id",
            [':nama' => $nama, ':posisi' => $posisi, ':domisili' => $domisili, ':skor' => $skorTes, ':peng' => $pengalamanTahun, ':id' => $id]);
    }

    // Menghapus kandidat berdasarkan id.
    public function deleteKandidat(int $id): bool {
        return $this->executeQuery("DELETE FROM kandidat WHERE id = :id", [':id' => $id]);
    }
}
