<?php
/**
 * TabelLowongan.php
 * Implementasi KontrakModelLowongan untuk tabel "lowongan".
 * Berisi query prepared statement dan konversi baris database menjadi entity Lowongan.
 */
class TabelLowongan extends DB implements KontrakModelLowongan {
    // Mengubah satu baris hasil query menjadi objek Lowongan.
    private function mapRowToLowongan(array $r): Lowongan {
        return new Lowongan((int)$r['id'], $r['judul'], $r['departemen'], $r['lokasi'], (int)$r['kuota'], $r['status']);
    }

    // Mengambil semua lowongan berdasarkan id agar data baru tampil paling bawah.
    public function getAllLowongan(): array {
        $this->executeQuery("SELECT * FROM lowongan ORDER BY id ASC");
        $list = [];
        foreach ($this->getAllResult() as $row) { $list[] = $this->mapRowToLowongan($row); }
        return $list;
    }

    // Mengambil satu lowongan berdasarkan id; null bila tidak ditemukan.
    public function getLowonganById(int $id): ?Lowongan {
        $this->executeQuery("SELECT * FROM lowongan WHERE id = :id LIMIT 1", [':id' => $id]);
        $rows = $this->getAllResult();
        return empty($rows) ? null : $this->mapRowToLowongan($rows[0]);
    }

    // Menambah lowongan baru dengan prepared statement.
    public function addLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): bool {
        return $this->executeQuery(
            "INSERT INTO lowongan (judul, departemen, lokasi, kuota, status) VALUES (:j, :d, :l, :k, :s)",
            [':j' => $judul, ':d' => $departemen, ':l' => $lokasi, ':k' => $kuota, ':s' => $status]);
    }

    // Memperbarui data lowongan berdasarkan id.
    public function updateLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): bool {
        return $this->executeQuery(
            "UPDATE lowongan SET judul=:j, departemen=:d, lokasi=:l, kuota=:k, status=:s WHERE id=:id",
            [':j' => $judul, ':d' => $departemen, ':l' => $lokasi, ':k' => $kuota, ':s' => $status, ':id' => $id]);
    }

    // Menghapus lowongan berdasarkan id.
    public function deleteLowongan(int $id): bool {
        return $this->executeQuery("DELETE FROM lowongan WHERE id = :id", [':id' => $id]);
    }
}
