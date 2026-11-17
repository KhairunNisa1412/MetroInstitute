DROP DATABASE IF EXISTS db_klinik;
CREATE DATABASE db_klinik CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_klinik;


CREATE TABLE poli (
    id_poli     INT AUTO_INCREMENT PRIMARY KEY,
    nama_poli   VARCHAR(50) NOT NULL UNIQUE,
    lantai      TINYINT NOT NULL
);

CREATE TABLE dokter (
    id_dokter    INT AUTO_INCREMENT PRIMARY KEY,
    id_poli      INT NOT NULL,
    nama_dokter  VARCHAR(100) NOT NULL,
    no_sip       VARCHAR(30) NOT NULL UNIQUE,
    no_hp        VARCHAR(15),
    CONSTRAINT fk_dokter_poli FOREIGN KEY (id_poli) REFERENCES poli(id_poli)
);

CREATE TABLE pasien (
    id_pasien       INT AUTO_INCREMENT PRIMARY KEY,
    no_rekam_medis  VARCHAR(10) NOT NULL UNIQUE,
    nama_pasien     VARCHAR(100) NOT NULL,
    tanggal_lahir   DATE NOT NULL,
    jenis_kelamin   ENUM('L','P') NOT NULL,
    alamat          VARCHAR(150),
    no_hp           VARCHAR(15)
);

CREATE TABLE kunjungan (
    id_kunjungan       INT AUTO_INCREMENT PRIMARY KEY,
    id_pasien          INT NOT NULL,
    id_dokter          INT NOT NULL,
    tanggal_kunjungan  DATE NOT NULL,
    keluhan            VARCHAR(200) NOT NULL,
    diagnosa           VARCHAR(200),
    biaya_konsultasi   DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_kunjungan_pasien FOREIGN KEY (id_pasien) REFERENCES pasien(id_pasien),
    CONSTRAINT fk_kunjungan_dokter FOREIGN KEY (id_dokter) REFERENCES dokter(id_dokter)
);

CREATE TABLE obat (
    id_obat    INT AUTO_INCREMENT PRIMARY KEY,
    nama_obat  VARCHAR(100) NOT NULL,
    satuan     VARCHAR(20) NOT NULL,
    harga      DECIMAL(10,2) NOT NULL,
    stok       INT NOT NULL DEFAULT 0
);

CREATE TABLE resep (
    id_kunjungan  INT NOT NULL,
    id_obat       INT NOT NULL,
    jumlah        INT NOT NULL,
    aturan_pakai  VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_kunjungan, id_obat),
    CONSTRAINT fk_resep_kunjungan FOREIGN KEY (id_kunjungan) REFERENCES kunjungan(id_kunjungan),
    CONSTRAINT fk_resep_obat FOREIGN KEY (id_obat) REFERENCES obat(id_obat)
);

SHOW TABLES;


INSERT INTO poli (id_poli, nama_poli, lantai) VALUES
(1, 'Poli Umum', 1),
(2, 'Poli Gigi', 1),
(3, 'Poli Anak', 2),
(4, 'Poli Mata', 2);

INSERT INTO dokter (id_dokter, id_poli, nama_dokter, no_sip, no_hp) VALUES
(1, 1, 'dr. Andi Pratama', 'SIP-001/2024', '081211110001'),
(2, 2, 'drg. Siti Rahma', 'SIP-002/2024', '081211110002'),
(3, 3, 'dr. Budi Santoso, Sp.A', 'SIP-003/2024', '081211110003'),
(4, 1, 'dr. Lina Marlina', 'SIP-004/2024', '081211110004');

INSERT INTO pasien (id_pasien, no_rekam_medis, nama_pasien, tanggal_lahir, jenis_kelamin, alamat, no_hp) VALUES
(1, 'RM-0001', 'Sari Wulandari', '1998-05-14', 'P', 'Jl. Sudirman No. 10, Padang', '085211220001'),
(2, 'RM-0002', 'Rizki Ramadhan', '2001-11-02', 'L', 'Jl. Veteran No. 5, Padang', '085211220002'),
(3, 'RM-0003', 'Salsabila Putri', '2018-03-20', 'P', 'Jl. Khatib Sulaiman No. 7, Padang', '085211220003'),
(4, 'RM-0004', 'Hendra Gunawan', '1985-07-30', 'L', 'Jl. Permindo No. 21, Padang', '085211220004');

INSERT INTO kunjungan (id_kunjungan, id_pasien, id_dokter, tanggal_kunjungan, keluhan, diagnosa, biaya_konsultasi) VALUES
(1, 1, 1, '2026-11-02', 'Demam dan batuk 3 hari', 'ISPA', 75000.00),
(2, 2, 2, '2026-11-03', 'Sakit gigi geraham', 'Pulpitis', 120000.00),
(3, 3, 3, '2026-11-05', 'Demam dan diare', 'Gastroenteritis', 100000.00),
(4, 1, 4, '2026-11-10', 'Sakit kepala berulang', 'Tension headache', 75000.00);

INSERT INTO obat (id_obat, nama_obat, satuan, harga, stok) VALUES
(1, 'Paracetamol 500mg', 'tablet', 1500.00, 500),
(2, 'Amoxicillin 500mg', 'kapsul', 3000.00, 300),
(3, 'Oralit', 'sachet', 2500.00, 200),
(4, 'Ibuprofen 400mg', 'tablet', 2000.00, 250);

INSERT INTO resep (id_kunjungan, id_obat, jumlah, aturan_pakai) VALUES
(1, 1, 10, '3 x 1 sesudah makan'),
(1, 2, 15, '3 x 1 sesudah makan'),
(2, 4, 10, '3 x 1 sesudah makan'),
(3, 1, 6, '3 x 1/2 bila demam'),
(3, 3, 6, '1 sachet tiap diare'),
(4, 1, 10, '3 x 1 bila nyeri');

SELECT * FROM poli;
SELECT * FROM dokter;
SELECT * FROM pasien;
SELECT * FROM kunjungan;
SELECT * FROM obat;
SELECT * FROM resep;


-- Query 1: INNER JOIN 2 tabel - daftar dokter beserta poli-nya
SELECT d.id_dokter, d.nama_dokter, p.nama_poli, p.lantai
FROM dokter d
INNER JOIN poli p ON d.id_poli = p.id_poli;

-- Query 2: INNER JOIN 3 tabel - riwayat kunjungan (kunjungan, pasien, dokter)
SELECT k.id_kunjungan, k.tanggal_kunjungan, ps.nama_pasien, d.nama_dokter, k.keluhan, k.diagnosa
FROM kunjungan k
INNER JOIN pasien ps ON k.id_pasien = ps.id_pasien
INNER JOIN dokter d ON k.id_dokter = d.id_dokter
ORDER BY k.tanggal_kunjungan;

-- Query 3: LEFT JOIN - semua poli beserta dokternya (poli tanpa dokter tetap tampil)
SELECT p.nama_poli, d.nama_dokter
FROM poli p
LEFT JOIN dokter d ON p.id_poli = d.id_poli
ORDER BY p.id_poli;

-- Query 4: WHERE LIKE ORDER BY - obat yang namanya mengandung 'cillin' atau diawali 'Para'
SELECT id_obat, nama_obat, satuan, harga, stok
FROM obat
WHERE nama_obat LIKE '%cillin%' OR nama_obat LIKE 'Para%'
ORDER BY harga DESC;

-- Query 5 (tambahan): JOIN 4 tabel - resep lengkap beserta total harga obat per baris
SELECT k.id_kunjungan, ps.nama_pasien, o.nama_obat, r.jumlah, r.aturan_pakai,
       (r.jumlah * o.harga) AS total_harga
FROM resep r
INNER JOIN kunjungan k ON r.id_kunjungan = k.id_kunjungan
INNER JOIN pasien ps ON k.id_pasien = ps.id_pasien
INNER JOIN obat o ON r.id_obat = o.id_obat
ORDER BY k.id_kunjungan, o.nama_obat;
