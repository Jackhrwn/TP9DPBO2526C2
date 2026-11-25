<?php
/**
 * ViewLowongan.php
 * View modul Lowongan.
 * Hanya mengisi token template dengan data yang sudah disiapkan Presenter.
 */
class ViewLowongan extends BaseView implements KontrakViewLowongan {
    // Merender daftar lowongan memakai skin_lowongan.html lalu layout utama.
    public function renderDaftarLowongan(string $rowsHtml, int $totalData): string {
        $isi = strtr($this->load('skin_lowongan.html'), ['{{TABLE_ROWS}}' => $rowsHtml, '{{TOTAL_DATA}}' => (string)$totalData]);
        return $this->layout('Lowongan', $isi, 'lowongan');
    }

    // Merender form tambah/ubah memakai form_lowongan.html lalu layout utama.
    public function renderFormLowongan(string $judul, string $aksi, array $data): string {
        $isi = strtr($this->load('form_lowongan.html'), [
            '{{HEADING}}' => $judul, '{{AKSI}}' => $aksi,
            '{{ID}}' => $data['id'] ?? '', '{{JUDUL}}' => $data['judul'] ?? '',
            '{{DEPARTEMEN}}' => $data['departemen'] ?? '', '{{LOKASI}}' => $data['lokasi'] ?? '',
            '{{KUOTA}}' => $data['kuota'] ?? '', '{{STATUS_OPTIONS}}' => $data['statusOptions'] ?? '',
        ]);
        return $this->layout($judul, $isi, 'lowongan');
    }
}
