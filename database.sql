-- =========================================
-- DATABASE SISTEM INFORMASI AKADEMIK
-- =========================================

CREATE DATABASE IF NOT EXISTS akademik_db;
USE akademik_db;

-- =========================================
-- TABEL FAKULTAS
-- =========================================
CREATE TABLE fakultas (
    id_fakultas VARCHAR(10) PRIMARY KEY,
    nama_fakultas VARCHAR(100) NOT NULL
);

-- =========================================
-- TABEL PROGRAM STUDI
-- =========================================
CREATE TABLE prodi (
    id_prodi VARCHAR(10) PRIMARY KEY,
    id_fakultas VARCHAR(10) NOT NULL,
    nama_prodi VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_fakultas) REFERENCES fakultas(id_fakultas)
);

-- =========================================
-- TABEL USERS (MAHASISWA, DOSEN, AKADEMIK)
-- =========================================
CREATE TABLE users (
    id_user VARCHAR(10) PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('mahasiswa','dosen','akademik') NOT NULL,
    nim VARCHAR(20),
    nidn VARCHAR(20),
    nama VARCHAR(100) NOT NULL,
    id_prodi VARCHAR(10),
    id_fakultas VARCHAR(10),
    semester INT,
    jabatan VARCHAR(50),
    FOREIGN KEY (id_prodi) REFERENCES prodi(id_prodi),
    FOREIGN KEY (id_fakultas) REFERENCES fakultas(id_fakultas)
);

-- =========================================
-- TABEL MATA KULIAH
-- =========================================
CREATE TABLE mata_kuliah (
    id_mk VARCHAR(10) PRIMARY KEY,
    nama_mk VARCHAR(100) NOT NULL,
    id_prodi VARCHAR(10) NOT NULL,
    semester INT NOT NULL,
    FOREIGN KEY (id_prodi) REFERENCES prodi(id_prodi)
);

-- =========================================
-- TABEL JADWAL
-- =========================================
CREATE TABLE jadwal (
    id_jadwal VARCHAR(10) PRIMARY KEY,
    id_mk VARCHAR(10) NOT NULL,
    id_dosen VARCHAR(10) NOT NULL,
    hari VARCHAR(20),
    jam VARCHAR(20),
    FOREIGN KEY (id_mk) REFERENCES mata_kuliah(id_mk),
    FOREIGN KEY (id_dosen) REFERENCES users(id_user)
);

-- =========================================
-- TABEL ABSENSI
-- =========================================
CREATE TABLE absensi (
    id_absensi VARCHAR(10) PRIMARY KEY,
    id_jadwal VARCHAR(10) NOT NULL,
    id_mahasiswa VARCHAR(10) NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('Hadir','Tidak Hadir') NOT NULL,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal),
    FOREIGN KEY (id_mahasiswa) REFERENCES users(id_user)
);

-- =========================================
-- TABEL NILAI
-- =========================================
CREATE TABLE nilai (
    id_nilai VARCHAR(10) PRIMARY KEY,
    id_mahasiswa VARCHAR(10) NOT NULL,
    id_mk VARCHAR(10) NOT NULL,
    nilai_angka DECIMAL(5,2),
    nilai_huruf CHAR(2),
    FOREIGN KEY (id_mahasiswa) REFERENCES users(id_user),
    FOREIGN KEY (id_mk) REFERENCES mata_kuliah(id_mk)
);

-- =========================================
-- TABEL LAPORAN
-- =========================================
CREATE TABLE laporan (
    id_laporan VARCHAR(10) PRIMARY KEY,
    id_mahasiswa VARCHAR(10) NOT NULL,
    id_mk VARCHAR(10) NOT NULL,
    id_dosen VARCHAR(10) NOT NULL,
    tanggal DATE NOT NULL,
    FOREIGN KEY (id_mahasiswa) REFERENCES users(id_user),
    FOREIGN KEY (id_mk) REFERENCES mata_kuliah(id_mk),
    FOREIGN KEY (id_dosen) REFERENCES users(id_user)
);

-- =========================================
-- DATA DUMMY
-- =========================================

INSERT INTO fakultas VALUES
('F001','Fakultas Teknik');

INSERT INTO prodi VALUES
('P001','F001','Teknik Informatika'),
('P002','F001','Sistem Informasi');

INSERT INTO users VALUES
('M001','budi','mhs123','mahasiswa','2021110001',NULL,'Budi Santoso','P001','F001',5,NULL),
('M002','siti','mhs123','mahasiswa','2021110002',NULL,'Siti Aminah','P002','F001',5,NULL),
('D001','ahmad','dsn123','dosen',NULL,'0312088901','Dr. Ahmad Fauzi',NULL,'F001',NULL,NULL),
('D002','sri','dsn123','dosen',NULL,'0315089002','Prof. Sri Wahyuni',NULL,'F001',NULL,NULL),
('A001','rina','akd123','akademik',NULL,NULL,'Rina Kusuma',NULL,'F001',NULL,'Staff Akademik');

INSERT INTO mata_kuliah VALUES
('MK01','Pemrograman Web','P001',5),
('MK02','Basis Data','P001',5),
('MK03','Algoritma','P002',5);

INSERT INTO jadwal VALUES
('J001','MK01','D001','Senin','08:00 - 10:00'),
('J002','MK02','D001','Selasa','10:00 - 12:00'),
('J003','MK03','D002','Rabu','08:00 - 10:00');

INSERT INTO absensi VALUES
('ABS01','J001','M001','2024-01-15','Hadir'),
('ABS02','J001','M002','2024-01-15','Hadir'),
('ABS03','J002','M001','2024-01-16','Tidak Hadir');

INSERT INTO nilai VALUES
('N001','M001','MK01',85,'A'),
('N002','M001','MK02',78,'B'),
('N003','M002','MK03',88,'A');

INSERT INTO laporan VALUES
('LAP01','M001','MK01','D001','2024-01-20'),
('LAP02','M002','MK03','D002','2024-01-21');

-- =========================================
-- QUERY LAPORAN NILAI LENGKAP
-- =========================================
SELECT 
    u.nama AS nama_mahasiswa,
    p.nama_prodi,
    f.nama_fakultas,
    mk.nama_mk,
    n.nilai_angka,
    n.nilai_huruf,
    d.nama AS dosen_pengampu
FROM laporan l
JOIN users u ON l.id_mahasiswa = u.id_user
JOIN mata_kuliah mk ON l.id_mk = mk.id_mk
JOIN nilai n ON u.id_user = n.id_mahasiswa AND mk.id_mk = n.id_mk
JOIN users d ON l.id_dosen = d.id_user
JOIN prodi p ON u.id_prodi = p.id_prodi
JOIN fakultas f ON p.id_fakultas = f.id_fakultas;
