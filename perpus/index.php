<?php
// ===== KONEKSI DATABASE =====
$koneksi = mysqli_connect("localhost", "root", "", "db_perpus");
if (!$koneksi) die("Koneksi gagal: " . mysqli_connect_error());

// ===== PROSES TAMBAH BUKU =====
if (isset($_POST['simpan_buku'])) {
    $judul    = $_POST['judul'];
    $penulis  = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun    = $_POST['tahun'];
    $stok     = $_POST['stok'];
    mysqli_query($koneksi, "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, stok) 
                            VALUES ('$judul', '$penulis', '$penerbit', '$tahun', '$stok')");
    header("Location: index.php");
    exit;
}

// ===== PROSES HAPUS BUKU =====
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku='$id'");
    header("Location: index.php");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'buku';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perpustakaan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📚 Sistem Informasi Perpustakaan</h1>
        <p>Kelola buku, anggota, dan transaksi peminjaman</p>
    </div>
    <div class="nav">
        <a href="index.php?page=buku">📖 Buku</a>
        <a href="index.php?page=peminjaman">📋 Peminjaman</a>
        <a href="index.php?page=tambah">➕ Tambah Buku</a>
    </div>
    <div class="content">
        <?php if ($page == 'buku'): ?>
            <h2>Daftar Buku</h2>
            <table>
                <thead>
                    <tr><th>No</th><th>Judul</th><th>Penulis</th><th>Penerbit</th><th>Tahun</th><th>Stok</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $q = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY judul ASC");
                    while ($row = mysqli_fetch_assoc($q)):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['judul'] ?></td>
                        <td><?= $row['penulis'] ?></td>
                        <td><?= $row['penerbit'] ?></td>
                        <td><?= $row['tahun_terbit'] ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td>
                            <a href="index.php?hapus=<?= $row['id_buku'] ?>" 
                               class="action-link action-hapus"
                               onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($page == 'tambah'): ?>
            <h2>Tambah Buku Baru</h2>
            <a href="index.php" class="btn" style="background:#888;">← Kembali</a>
            <form method="POST" style="margin-top:15px; max-width:500px;">
                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" required>
                </div>
                <div class="form-group">
                    <label>Penulis</label>
                    <input type="text" name="penulis" required>
                </div>
                <div class="form-group">
                    <label>Penerbit</label>
                    <input type="text" name="penerbit">
                </div>
                <div class="form-group">
                    <label>Tahun Terbit</label>
                    <input type="number" name="tahun">
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" required>
                </div>
                <button type="submit" name="simpan_buku" class="btn btn-success">💾 Simpan Buku</button>
            </form>

        <?php elseif ($page == 'peminjaman'): ?>
            <h2>Riwayat Peminjaman & Pengembalian</h2>
            <table>
                <thead>
                    <tr>
                        <th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th><th>Status</th><th>Kembali</th><th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = mysqli_query($koneksi, "
                        SELECT a.nama AS nama_anggota, b.judul AS judul_buku,
                               p.tgl_pinjam, p.tgl_jatuh_tempo, p.status,
                               pg.tgl_kembali, pg.denda
                        FROM peminjaman p
                        JOIN anggota a ON p.id_anggota = a.id_anggota
                        JOIN buku b ON p.id_buku = b.id_buku
                        LEFT JOIN pengembalian pg ON p.id_pinjam = pg.id_pinjam
                        ORDER BY p.tgl_pinjam DESC
                    ");
                    while ($row = mysqli_fetch_assoc($q)):
                    ?>
                    <tr>
                        <td><?= $row['nama_anggota'] ?></td>
                        <td><?= $row['judul_buku'] ?></td>
                        <td><?= $row['tgl_pinjam'] ?></td>
                        <td><?= $row['tgl_jatuh_tempo'] ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($row['status']) ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td><?= $row['tgl_kembali'] ?? '-' ?></td>
                        <td>Rp <?= number_format($row['denda'] ?? 0, 0, ',', '.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <div class="footer">
        &copy; <?= date('Y') ?> Sistem Perpustakaan - PHP Native & MySQL
    </div>
</div>
</body>
</html>
