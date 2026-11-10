const express = require('express');
const app = express();
app.use(express.json());

let daftarBuku = [
    { id: 1, judul: "Laskar Pelangi", harga: 80000, stok: 5, kategori: "Novel" },
    { id: 2, judul: "Atomic Habits", harga: 100000, stok: 0, kategori: "Self-Help" },
    { id: 3, judul: "Filosofi Teras", harga: 90000, stok: 3, kategori: "Self-Help" }
];

let idBerikutnya = 4; 

//Soal 1
app.get('/kali/:a/:b', (req, res) => {
    const angkaA = Number(req.params.a);
    const angkaB = Number(req.params.b);

    if (isNaN(angkaA)|| isNaN(angkaB)){
        return res.status(400).json({
            pesan: "Input harus angka"
    });
    } 

    const hasil = angkaA * angkaB;
    res.json({
        a: angkaA,
        b: angkaB,
        hasil: hasil
    });
});

//soal 1b
app.get('/sapa', (req, res) => {
    const nama = req.query.nama;
    const pesan= "Halo, " + nama;

    res.json({
        pesan: pesan
    });
});

//soal 1c
app.post('/hitung-rata', (req, res) => {
    const nilai = req.body.nilai;

    if (!Array.isArray(nilai) || nilai.length === 0){
        return res.status(400).json({
            pesan: "Data harus berupa array angka"
        });
    }
    const total = nilai.reduce((akumulator, nilaiSekarang) => akumulator + nilaiSekarang, 0);
    const rataRata = total/nilai.length;

    res.json({
        rata: rataRata
    });
});

//soal 2b
app.get('/buku', (req, res) => {
    let hasil = daftarBuku;

    if (req.query.kategori){
        hasil = hasil.filter(buku => buku.kategori === req.query.kategori);
    }
    if (req.query.tersedia === 'true') {
        hasil = hasil.filter(buku => buku.stok > 0);
    }
    res.json(hasil);
});
//soal 2h
app.get('/statistik', (req, res) => {
    const jumlahJudul = daftarBuku.length;
    
    const totalHarga = daftarBuku.reduce((akumulator, buku) => akumulator + buku.harga, 0);
    
    const jumlahTersedia = daftarBuku.filter(buku => buku.stok > 0).length;

    res.json({
        jumlahJudul,
        totalHarga,
        jumlahTersedia
    });
});

//soal 2c
app.get('/buku/:id', (req, res) => {
    const id = Number(req.params.id);
    const buku = daftarBuku.find(b => b.id === id);

    if (!buku) {
        return res.status(404).json({
            pesan: "Buku tidak ditemukan"
        });
    }
    res.json(buku);
});

//soal 2d
app.post('/buku', (req, res) => {
    const { judul, harga, stok, kategori } = req.body;

    if (!judul || harga === undefined || stok === undefined || !kategori) {
        return res.status(400).json({
            pesan: "Data buku tidak lengkap (judul, harga, stok, dan kategori wajib diisi)"
        });
    }

    const bukuBaru = {
        id: idBerikutnya++,
        judul,
        harga: Number(harga),
        stok: Number(stok),
        kategori
    };

    daftarBuku.push(bukuBaru);
    res.status(201).json(bukuBaru);
});

//soal 2e
app.put('/buku/:id', (req, res) => {
    const id = Number(req.params.id);
    const buku = daftarBuku.find(b => b.id === id);

    if (!buku) {
        return res.status(404).json({
            pesan: "Buku tidak ditemukan"
        });
    }

    const { judul, harga, stok, kategori } = req.body;

    // Validasi harga atau stok jika dikirim tapi tidak valid (negatif atau bukan angka)
    if ((harga !== undefined && (isNaN(harga) || harga < 0)) || 
        (stok !== undefined && (isNaN(stok) || stok < 0))) {
        return res.status(400).json({
            pesan: "Harga atau stok tidak valid"
        });
    }
    if (judul !== undefined) buku.judul = judul;
    if (harga !== undefined) buku.harga = Number(harga);
    if (stok !== undefined) buku.stok = Number(stok);
    if (kategori !== undefined) buku.kategori = kategori;

    res.json({
        pesan: "Buku berhasil diperbarui",
        buku
    });
});
//soal 2f
app.delete('/buku/:id', (req, res) => {
    const id = Number(req.params.id);
    const index = daftarBuku.findIndex(b => b.id === id);

    if (index === -1) {
        return res.status(404).json({
            pesan: "Buku tidak ditemukan"
        });
    }

    const bukuDihapus = daftarBuku.splice(index, 1);
    res.json({
        pesan: "Buku berhasil dihapus",
        buku: bukuDihapus[0]
    });
});
//soal 2g
app.post('/buku/:id/beli', (req, res) => {
    const id = Number(req.params.id);
    const buku = daftarBuku.find(b => b.id === id);

    if (!buku) {
        return res.status(404).json({
            pesan: "Buku tidak ditemukan"
        });
    }

    if (buku.stok > 0) {
        buku.stok -= 1;
        return res.status(200).json({
            pesan: `Pembayaran berhasil untuk buku: ${buku.judul}`,
            sisaStok: buku.stok
        });
    } else {
        return res.status(400).json({
            pesan: "Gagal: Buku tidak tersedia atau stok habis!"
        });
    }
});

app.listen(3000, () => {
    console.log('Server berjalan di http://localhost:3000');
});
