<?php
/**
 * DB.php
 * Kelas dasar koneksi database memakai PDO.
 * Seluruh Tabel* mewarisi kelas ini agar tidak perlu membuka koneksi sendiri.
 */
class DB {
    // Koneksi PDO yang dipakai selama objek hidup.
    private PDO $pdo;

    // Menyimpan prepared statement terakhir agar hasilnya bisa diambil ulang.
    private ?PDOStatement $stmt = null;

    /**
     * Membuka koneksi MySQL dengan konfigurasi bawaan XAMPP.
     * Ubah $user/$pass bila pengaturan server berbeda.
     */
    public function __construct(string $host = 'localhost', string $db = 'mvp_kandidat_db', string $user = 'root', string $pass = '') {
        $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
            // Error ditampilkan sebagai exception agar mudah dilacak.
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Setiap baris hasil query otomatis berbentuk array asosiatif.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    /**
     * Menjalankan query dengan prepared statement.
     * Parameter dipisahkan dari SQL untuk mencegah SQL injection.
     */
    protected function executeQuery(string $query, array $params = []): bool {
        $this->stmt = $this->pdo->prepare($query);
        return $this->stmt->execute($params);
    }

    // Mengambil seluruh baris dari statement yang terakhir dijalankan.
    protected function getAllResult(): array {
        return $this->stmt ? $this->stmt->fetchAll() : [];
    }
}
