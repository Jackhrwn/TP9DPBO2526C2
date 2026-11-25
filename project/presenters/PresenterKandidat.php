<?php
/**
 * PresenterKandidat.php
 * Presenter modul Kandidat.
 * Mengambil data kandidat dari Model, merakit HTML tabel, dan menyiapkan
 * dropdown posisi dari daftar lowongan yang berstatus "Buka".
 */
class PresenterKandidat implements KontrakPresenter {
    // Dependensi disimpan sebagai interface agar mudah diganti saat pengujian.
    private KontrakModel $model;
    private KontrakView $view;
    private KontrakModelLowongan $lowonganModel;

    /**
     * Menyuntikkan Model Kandidat, View, dan Model Lowongan.
     * Model Lowongan dipakai agar posisi yang dilamar berasal dari data nyata.
     */
    public function __construct(KontrakModel $model, KontrakView $view, KontrakModelLowongan $lowonganModel) {
        $this->model = $model;
        $this->view = $view;
        $this->lowonganModel = $lowonganModel;
    }

    // Mengambil daftar posisi unik dari lowongan yang masih berstatus "Buka".
    private function getPosisiBuka(): array {
        $posisi = [];
        foreach ($this->lowonganModel->getAllLowongan() as $lowongan) {
            if ($lowongan->getStatus() === 'Buka') {
                // Associative array dipakai agar judul yang sama tidak tampil ganda.
                $posisi[$lowongan->getJudul()] = true;
            }
        }
        $hasil = array_keys($posisi);
        sort($hasil, SORT_NATURAL | SORT_FLAG_CASE);
        return $hasil;
    }

    /**
     * Merakit pilihan <option> untuk dropdown posisi.
     * Pada mode ubah, posisi lama yang lowongannya sudah tidak aktif tetap
     * ditampilkan agar data kandidat lama tidak hilang saat diedit.
     */
    private function buildPosisiOptions(string $selected = '', ?string $posisiSaatIni = null): string {
        $posisiBuka = $this->getPosisiBuka();
        $posisiTidakAktif = $posisiSaatIni !== null
            && $posisiSaatIni !== ''
            && !in_array($posisiSaatIni, $posisiBuka, true);

        if ($posisiTidakAktif) {
            array_unshift($posisiBuka, $posisiSaatIni);
        }

        // Kondisi saat belum ada lowongan yang bisa dilamar.
        if (empty($posisiBuka)) {
            return '<option value="" selected disabled>Belum ada lowongan berstatus Buka</option>';
        }

        $html = '<option value="" disabled' . ($selected === '' ? ' selected' : '') . '>-- Pilih posisi yang dilamar --</option>';
        foreach ($posisiBuka as $posisi) {
            $label = h($posisi);
            if ($posisiTidakAktif && $posisi === $posisiSaatIni) {
                $label .= ' (lowongan tidak aktif)';
            }
            $html .= '<option value="' . h($posisi) . '"' . ($posisi === $selected ? ' selected' : '') . '>' . $label . '</option>';
        }
        return $html;
    }

    // Memastikan posisi yang dikirim memang berasal dari lowongan berstatus Buka.
    private function posisiValid(string $posisi): bool {
        return $posisi !== '' && in_array($posisi, $this->getPosisiBuka(), true);
    }

    // Merakit satu baris <tr> kandidat beserta tombol Edit dan Hapus.
    private function buildRowHtml(int $no, Kandidat $k): string {
        $id = $k->getId();
        return '<tr><td>' . $no . '</td><td><b>' . h($k->getNama()) . '</b></td><td>' . h($k->getPosisi())
            . '</td><td>' . h($k->getDomisili()) . '</td><td>' . $k->getSkorTes() . ' / 100</td><td>'
            . $k->getPengalamanTahun() . ' Tahun</td><td><div class="aksi">'
            . '<a class="lnk edit" href="index.php?page=kandidat&aksi=form&id=' . $id . '">Edit</a>'
            . '<form method="post" action="index.php" onsubmit="return confirm(\'Hapus kandidat ini?\')">'
            . '<input type="hidden" name="page" value="kandidat"><input type="hidden" name="action" value="hapus">'
            . '<input type="hidden" name="id" value="' . $id . '"><button class="lnk">Hapus</button></form></div></td></tr>';
    }

    // Menampilkan daftar kandidat; View hanya menerima HTML baris dan jumlah data.
    public function tampilkanKandidat(): string {
        $list = $this->model->getAllKandidat();
        $total = count($list);
        $rowsHtml = '';
        if (empty($list)) {
            // Baris khusus saat tabel belum memiliki data.
            $rowsHtml = '<tr><td colspan="7" class="empty">Belum ada kandidat. Tekan "Tambah Kandidat" untuk mulai.</td></tr>';
        } else {
            $no = 1;
            foreach ($list as $k) { $rowsHtml .= $this->buildRowHtml($no, $k); $no++; }
        }
        return $this->view->renderDaftar($rowsHtml, $total);
    }

    // Menampilkan form kosong (tambah) atau form terisi (ubah).
    public function tampilkanFormKandidat(?int $id = null): string {
        if ($id !== null) {
            $k = $this->model->getKandidatById($id);
            // Jika id tidak ada, kembalikan pengguna ke daftar kandidat.
            if ($k === null) { header("Location: index.php"); exit(); }

            // Nilai teks di-escape; opsi posisi dirakit dari lowongan yang tersedia.
            $data = array_map('h', [
                'id' => $k->getId(), 'nama' => $k->getNama(),
                'domisili' => $k->getDomisili(), 'skorTes' => $k->getSkorTes(), 'pengalamanTahun' => $k->getPengalamanTahun(),
            ]);
            $data['posisiOptions'] = $this->buildPosisiOptions($k->getPosisi(), $k->getPosisi());
            return $this->view->renderForm("Ubah Data Kandidat", "ubah", $data);
        }

        return $this->view->renderForm("Tambah Kandidat Baru", "tambah", [
            'posisiOptions' => $this->buildPosisiOptions(),
        ]);
    }

    // Menambah kandidat; posisi wajib berasal dari lowongan yang sedang dibuka.
    public function tambahKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void {
        if ($nama === '' || !$this->posisiValid($posisi)) return;
        $this->model->addKandidat($nama, $posisi, $domisili, max(0, min(100, $skorTes)), max(0, $pengalamanTahun));
    }

    /**
     * Mengubah kandidat; id harus valid dan field wajib tidak boleh kosong.
     * Posisi lama tetap boleh dipertahankan, tetapi posisi baru harus aktif.
     */
    public function ubahKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void {
        if ($id <= 0 || $nama === '') return;

        $kandidat = $this->model->getKandidatById($id);
        if ($kandidat === null) return;

        // Jika posisi diganti, pastikan posisi baru ada di lowongan berstatus Buka.
        if ($posisi !== $kandidat->getPosisi() && !$this->posisiValid($posisi)) return;

        $this->model->updateKandidat($id, $nama, $posisi, $domisili, max(0, min(100, $skorTes)), max(0, $pengalamanTahun));
    }

    // Menghapus kandidat bila id valid.
    public function hapusKandidat(int $id): void {
        if ($id > 0) $this->model->deleteKandidat($id);
    }
}
