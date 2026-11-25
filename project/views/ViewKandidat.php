<?php
/**
 * ViewKandidat.php
 * View modul Kandidat.
 * Hanya mengisi token template dengan data yang sudah disiapkan Presenter.
 */
class ViewKandidat extends BaseView implements KontrakView {
    // Merender daftar kandidat memakai skin.html lalu layout utama.
    public function renderDaftar(string $rowsHtml, int $totalData): string {
        $isi = strtr($this->load('skin.html'), ['{{TABLE_ROWS}}' => $rowsHtml, '{{TOTAL_DATA}}' => (string)$totalData]);
        return $this->layout('Kandidat', $isi, 'kandidat');
    }

    // Merender form tambah/ubah memakai form.html lalu layout utama.
    public function renderForm(string $judul, string $aksi, array $data): string {
        $isi = strtr($this->load('form.html'), [
            '{{HEADING}}' => $judul, '{{AKSI}}' => $aksi,
            '{{ID}}' => $data['id'] ?? '', '{{NAMA}}' => $data['nama'] ?? '',
            '{{POSISI_OPTIONS}}' => $data['posisiOptions'] ?? '', '{{DOMISILI}}' => $data['domisili'] ?? '',
            '{{SKOR}}' => $data['skorTes'] ?? '', '{{PENGALAMAN}}' => $data['pengalamanTahun'] ?? '',
        ]);
        return $this->layout($judul, $isi, 'kandidat');
    }
}
