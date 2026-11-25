<?php
/**
 * index.php
 * ---------------------------------------------------------------------
 * Front controller aplikasi MVP (Model-View-Presenter).
 * Semua permintaan masuk lewat file ini: memuat kelas, memilih modul
 * (kandidat/lowongan), menangani aksi POST, lalu memanggil Presenter.
 * ---------------------------------------------------------------------
 */

// Memuat seluruh kelas Model, View, dan Presenter yang dibutuhkan aplikasi.
foreach ([
    'models/DB', 'models/Kandidat', 'models/Lowongan', 'models/KontrakModel', 'models/TabelKandidat', 'models/TabelLowongan',
    'presenters/KontrakPresenter', 'presenters/PresenterKandidat', 'presenters/PresenterLowongan',
    'views/KontrakView', 'views/BaseView', 'views/ViewKandidat', 'views/ViewLowongan',
] as $f) {
    require_once __DIR__ . "/$f.php";
}

// Menentukan modul aktif; nilai selain "lowongan" dianggap "kandidat".
$page = ($_GET['page'] ?? $_POST['page'] ?? 'kandidat') === 'lowongan' ? 'lowongan' : 'kandidat';

// Presenter dibuat dengan Model dan View konkret, tetapi bertipe interface.
// Presenter Kandidat juga menerima model Lowongan untuk mengisi dropdown posisi.
$presenter = $page === 'lowongan'
    ? new PresenterLowongan(new TabelLowongan(), new ViewLowongan())
    : new PresenterKandidat(new TabelKandidat(), new ViewKandidat(), new TabelLowongan());

// Helper untuk mengambil input POST sekaligus membuang spasi di awal/akhir.
$in = fn(string $k): string => trim($_POST[$k] ?? '');

// Menangani semua aksi tulis (tambah, ubah, hapus) yang dikirim lewat POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    // Aksi untuk modul Lowongan.
    if ($page === 'lowongan') {
        $a = [$in('judul'), $in('departemen'), $in('lokasi'), (int)$in('kuota'), $in('status')];
        match ($aksi) {
            'tambah' => $presenter->tambahLowongan(...$a),
            'ubah'   => $presenter->ubahLowongan($id, ...$a),
            'hapus'  => $presenter->hapusLowongan($id),
            default  => null,
        };
    } else {
        // Aksi untuk modul Kandidat.
        $a = [$in('nama'), $in('posisi'), $in('domisili'), (int)$in('skorTes'), (int)$in('pengalamanTahun')];
        match ($aksi) {
            'tambah' => $presenter->tambahKandidat(...$a),
            'ubah'   => $presenter->ubahKandidat($id, ...$a),
            'hapus'  => $presenter->hapusKandidat($id),
            default  => null,
        };
    }

    // Pola Post/Redirect/Get agar refresh halaman tidak mengulang aksi.
    header("Location: index.php?page=$page");
    exit();
}

// Permintaan GET: tampilkan form (jika ?aksi=form) atau daftar data.
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (($_GET['aksi'] ?? '') === 'form') {
    echo $page === 'lowongan' ? $presenter->tampilkanFormLowongan($id) : $presenter->tampilkanFormKandidat($id);
} else {
    echo $page === 'lowongan' ? $presenter->tampilkanLowongan() : $presenter->tampilkanKandidat();
}
