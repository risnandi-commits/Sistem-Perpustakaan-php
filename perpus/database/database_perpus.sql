-- Buat Database
CREATE DATABASE IF NOT EXISTS db_perpus;
USE db_perpus;

-- 1. Tabel Perpustakaan
CREATE TABLE perpustakaan (
    id_perpus INT AUTO_INCREMENT PRIMARY KEY,
    nama_perpus VARCHAR(100) NOT NULL,
    alamat TEXT,
    no_telepon VARCHAR(15),
    email VARCHAR(50)
);

-- 2. Tabel Buku
CREATE TABLE buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    id_perpus INT,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100),
    penerbit VARCHAR(100),
    tahun_terbit YEAR,
    stok INT DEFAULT 0,
    FOREIGN KEY (id_perpus) REFERENCES perpustakaan(id_perpus) ON DELETE SET NULL
);

-- 3. Tabel Anggota
CREATE TABLE anggota (
    id_anggota INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT,
    no_telepon VARCHAR(15),
    email VARCHAR(50),
    tanggal_daftar DATE
);

-- 4. Tabel Peminjaman
CREATE TABLE peminjaman (
    id_pinjam INT AUTO_INCREMENT PRIMARY KEY,
    id_anggota INT,
    id_buku INT,
    tgl_pinjam DATE NOT NULL,
    tgl_jatuh_tempo DATE NOT NULL,
    status ENUM('Dipinjam', 'Terlambat', 'Selesai') DEFAULT 'Dipinjam',
    FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE
);

-- 5. Tabel Pengembalian
CREATE TABLE pengembalian (
    id_kembali INT AUTO_INCREMENT PRIMARY KEY,
    id_pinjam INT,
    tgl_kembali DATE NOT NULL,
    denda INT DEFAULT 0,
    FOREIGN KEY (id_pinjam) REFERENCES peminjaman(id_pinjam) ON DELETE CASCADE
);

-- Data Dummy (Contoh)
INSERT INTO perpustakaan (nama_perpus, alamat, no_telepon, email) VALUES 
('Perpustakaan Daerah', 'Jl. Merdeka No. 10', '08123456789', 'admin@perpusda.id');

INSERT INTO buku (id_perpus, judul, penulis, penerbit, tahun_terbit, stok) VALUES 
(1, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 5),
(1, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 1980, 3),
(1, 'Filosofi Teras', 'Henry Manampiring', 'Kompas', 2018, 10);

INSERT INTO anggota (nama, alamat, no_telepon, email, tanggal_daftar) VALUES 
('Budi Santoso', 'Jl. Sudirman No. 5', '08987654321', 'budi@gmail.com', '2023-01-15'),
('Siti Aminah', 'Jl. Thamrin No. 8', '08555566677', 'siti@gmail.com', '2023-02-20');

INSERT INTO peminjaman (id_anggota, id_buku, tgl_pinjam, tgl_jatuh_tempo, status) VALUES 
(1, 1, '2023-10-01', '2023-10-08', 'Selesai'),
(2, 2, '2023-10-05', '2023-10-12', 'Dipinjam');

INSERT INTO pengembalian (id_pinjam, tgl_kembali, denda) VALUES 
(1, '2023-10-07', 0);
