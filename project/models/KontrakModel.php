<?php
/**
 * KontrakModel.php
 * Kumpulan interface untuk lapisan Model.
 * Presenter hanya bergantung pada interface ini, bukan pada kelas database konkret.
 */

// Kontrak operasi CRUD kandidat.
interface KontrakModel {
    public function getAllKandidat(): array;
    public function getKandidatById(int $id): ?Kandidat;
    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): bool;
    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): bool;
    public function deleteKandidat(int $id): bool;
}

// Kontrak operasi CRUD lowongan.
interface KontrakModelLowongan {
    public function getAllLowongan(): array;
    public function getLowonganById(int $id): ?Lowongan;
    public function addLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): bool;
    public function updateLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): bool;
    public function deleteLowongan(int $id): bool;
}
