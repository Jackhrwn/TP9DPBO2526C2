<?php
/**
 * PresenterLowongan.php
 * Presenter modul Lowongan.
 * Mengambil data dari Model, merakit HTML tabel/opsi status, lalu meminta View merender template.
 */
class PresenterLowongan implements KontrakPresenterLowongan {
    // Dependensi disimpan sebagai interface agar mudah diganti saat pengujian.
    private KontrakModelLowongan $model;
    private KontrakViewLowongan $view;

    // Menyuntikkan Model dan View melalui constructor (dependency injection).
    public function __construct(KontrakModelLowongan $model, KontrakViewLowongan $view) {
        $this->model = $model;
        $this->view = $view;
    }

    // Merakit satu baris <tr> lowongan beserta badge status dan tombol aksi.
    private function buildRowHtml(int $no, Lowongan $l): string {
        $id = $l->getId();
        // Nama class badge mengikuti status agar warna hijau/merah otomatis.
        $badge = $l->getStatus() === 'Buka' ? 'buka' : 'tutup';
        return '<tr><td>' . $no . '</td><td><b>' . h($l->getJudul()) . '</b></td><td>' . h($l->getDepartemen())
            . '</td><td>' . h($l->getLokasi()) . '</td><td>' . $l->getKuota() . ' orang</td>'
            . '<td><span class="badge ' . $badge . '">' . h($l->getStatus()) . '</span></td><td><div class="aksi">'
            . '<a class="lnk edit" href="index.php?page=lowongan&aksi=form&id=' . $id . '">Edit</a>'
            . '<form method="post" action="index.php" onsubmit="return confirm(\'Hapus lowongan ini?\')">'
            . '<input type="hidden" name="page" value="lowongan"><input type="hidden" name="action" value="hapus">'
            . '<input type="hidden" name="id" value="' . $id . '"><button class="lnk">Hapus</button></form></div></td></tr>';
    }

    // Merakit pilihan <option> status untuk form, menandai pilihan yang aktif.
    private function buildStatusOptions(string $selected): string {
        $html = '';
        foreach (['Buka', 'Tutup'] as $s) {
            $html .= '<option value="' . $s . '"' . ($s === $selected ? ' selected' : '') . '>' . $s . '</option>';
        }
        return $html;
    }

    // Menampilkan daftar lowongan; View hanya menerima HTML baris dan jumlah data.
    public function tampilkanLowongan(): string {
        $list = $this->model->getAllLowongan();
        $total = count($list);
        $rowsHtml = '';
        if (empty($list)) {
            // Baris khusus saat tabel belum memiliki data.
            $rowsHtml = '<tr><td colspan="7" class="empty">Belum ada lowongan. Tekan "Tambah Lowongan" untuk mulai.</td></tr>';
        } else {
            $no = 1;
            foreach ($list as $l) { $rowsHtml .= $this->buildRowHtml($no, $l); $no++; }
        }
        return $this->view->renderDaftarLowongan($rowsHtml, $total);
    }

    // Menampilkan form kosong (tambah) atau form terisi (ubah).
    public function tampilkanFormLowongan(?int $id = null): string {
        if ($id !== null) {
            $l = $this->model->getLowonganById($id);
            // Jika id tidak ada, kembalikan pengguna ke daftar lowongan.
            if ($l === null) { header("Location: index.php?page=lowongan"); exit(); }
            // Semua nilai di-escape sebelum dimasukkan ke atribut value.
            $data = array_map('h', [
                'id' => $l->getId(), 'judul' => $l->getJudul(), 'departemen' => $l->getDepartemen(),
                'lokasi' => $l->getLokasi(), 'kuota' => $l->getKuota(),
            ]);
            // Opsi status dirakit presenter, bukan diulang di dalam View.
            $data['statusOptions'] = $this->buildStatusOptions($l->getStatus());
            return $this->view->renderFormLowongan("Ubah Data Lowongan", "ubah", $data);
        }
        return $this->view->renderFormLowongan("Tambah Lowongan Baru", "tambah", ['statusOptions' => $this->buildStatusOptions('Buka')]);
    }

    // Menambah lowongan dengan validasi dan pembatasan nilai.
    public function tambahLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): void {
        if ($judul === '' || $departemen === '') return;
        $this->model->addLowongan($judul, $departemen, $lokasi, max(1, $kuota), $status === 'Tutup' ? 'Tutup' : 'Buka');
    }

    // Mengubah lowongan; id harus valid dan field wajib tidak boleh kosong.
    public function ubahLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): void {
        if ($id <= 0 || $judul === '' || $departemen === '') return;
        $this->model->updateLowongan($id, $judul, $departemen, $lokasi, max(1, $kuota), $status === 'Tutup' ? 'Tutup' : 'Buka');
    }

    // Menghapus lowongan bila id valid.
    public function hapusLowongan(int $id): void {
        if ($id > 0) $this->model->deleteLowongan($id);
    }
}
