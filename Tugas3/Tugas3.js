//Soal no 6
const produk = [
  { nama: 'Pensil', harga: 2000, stok: 10 },
  { nama: 'Buku', harga: 8000, stok: 0 },
  { nama: 'Penghapus', harga: 1500, stok: 5 }
];
//a
const namaProduk = produk
  .filter(p => p.stok > 0)
  .map(p => p.nama);

console.log(namaProduk);
//b
const namaProduk2 = produk
    .find(p => p.nama === 'Penghapus');

console.log(namaProduk2);
//c
produk.forEach (p => {
    console.log(p.nama+ " - Rp"+ p.harga);
});

//Soal no 7
//a
function tunggu(ms) {
  return new Promise(resolve => {
    setTimeout(() => {
      resolve("Waktu habis!");
    }, ms);
  });
}
//b
async function jalanLangkah() {
  console.log("Mulai");
  
  await tunggu(1000); k
  console.log("Langkah 1");
  
  await tunggu(1000); 
  console.log("Selesai");
}
jalanLangkah();
//c
function cekStok(jumlah) {
  return new Promise((resolve, reject) => {
    if (jumlah > 0) {
      resolve("Stok aman, barang ada!"); 
    } else {
      reject("Waduh, stok habis bro!");  
    }
  });
}
async function tesStok() {
  try {
    const hasil = await cekStok(0); 
    console.log(hasil);
  } catch (error) {
    console.log("Error tertangkap:", error);
  }
}

tesStok();

//Soal no 8
const daftarBuku = [
  { judul: 'Laskar Pelangi', harga: 85000, stok: 5, kategori: 'Novel' },
  { judul: 'Filosofi Teras', harga: 98000, stok: 0, kategori: 'Self-Help' },
  { judul: 'Atomic Habits', harga: 120000, stok: 3, kategori: 'Self-Help' },
  { judul: 'Bumi Manusia', harga: 105000, stok: 2, kategori: 'Novel' }
];
//a
const bukuTersedia = daftarBuku
  .filter(b => b.stok > 0)
  .map(b => b.judul);

console.log("a) Buku tersedia:", bukuTersedia);
//b
const totalHarga = daftarBuku.reduce((total, b) => total + b.harga, 0);

console.log("Total harga:", totalHarga);
//c
daftarBuku.forEach(b => {
  console.log(b.judul + " - Rp" + b.harga + " (Stok: " + b.stok + ")");
});
//d dan e
function prosesPembayaran(judulBuku) {
  return new Promise((resolve, reject) => {
    setTimeout(() => {
      const buku = daftarBuku.find(b => b.judul === judulBuku);
      
      if (buku && buku.stok > 0) {
        resolve("Pembayaran berhasil untuk buku: " + judulBuku);
      } else {
        reject("Gagal: Buku tidak tersedia atau stok habis!");
      }
    }, 1500); 
  });
}
//f
async function transaksiToko() {
  console.log("--- Memulai Transaksi ---");
  
  try {
    const hasilSukses = await prosesPembayaran('Atomic Habits');
    console.log(hasilSukses);
  } catch (error) {
    console.log("Error:", error);
  }

  try {
    const hasilGagal = await prosesPembayaran('Filosofi Teras');
    console.log(hasilGagal);
  } catch (error) {
    console.log("Error:", error);
  }
}

transaksiToko();