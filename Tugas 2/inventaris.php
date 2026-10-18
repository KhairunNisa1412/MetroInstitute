<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "db_inventaris";

$koneksi = mysqli_connect ($host, $user, $password, $database);
if (!$koneksi){
    die ("Koneksi ke database gagal;");
} else {
    echo "Koneksi sukses";
}

//hapus
if (isset($_GET['op']) && $_GET['op'] == 'delete'){
    $id_hapus = $_GET['id'];
    $query_hapus ="delete from barang where id = $id_hapus";
    mysqli_query($koneksi, $query_hapus);
    echo "<script>alert ('Data berhasil dihapus'); window.location= 'inventaris.php';</script>";
}
//update
$op= isset($_GET['op']) ? $_GET['op'] :'';
$id_edit = isset($_GET['id']) ? $_GET['id'] : '';
$kode = $nama = $kategori = $stok = $harga = "";

if ($op == 'edit' && $id_edit != '') {
    $ambil_data = mysqli_query($koneksi, "SELECT * FROM barang WHERE id = $id_edit");
    $data_lama = mysqli_fetch_array($ambil_data);
    $kode = $data_lama['kode_barang'];
    $nama = $data_lama['nama_barang'];
    $kategori = $data_lama['kategori'];
    $stok = $data_lama['stok'];
    $harga = $data_lama['harga_satuan'];
}
$pesan ="";
$tipe_pesan ="";

if (isset ($_POST['simpan'])){
    $op_post = $_POST['id'];
    $id_edit = $_POST['id'];

    $kode = $_POST['kode_barang'];
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga_satuan'];

    if (empty ($kode) || empty($nama) || empty($kategori) || empty($stok) || empty ($harga)){
        $pesan = "Semua kolom wajib diisi.";
        $tipe_pesan = "danger";
    } elseif (!is_numeric($stok) || !is_numeric($harga)){
        $pesan = "Stok dan harga harus berupa angka";
        $tipe_pesan = "danger";
    } else {        
        if ($op_post == 'edit' && $id_post != '') {
            $query_simpan = "UPDATE barang SET kode_barang='$kode', nama_barang='$nama', kategori='$kategori', stok='$stok', harga_satuan='$harga' WHERE id = $id_post";
            $aksi = "diupdate";
        } else {
            $cek_kode = mysqli_query($koneksi, "SELECT * FROM barang WHERE kode_barang = '$kode'");
            
            if (mysqli_num_rows($cek_kode) > 0) {
                $pesan = "Gagal! Kode barang '$kode' sudah ada. Gunakan kode yang berbeda.";
                $tipe_pesan = "danger";
            } else {
                $query_simpan = "INSERT INTO barang (kode_barang, nama_barang, kategori, stok, harga_satuan) VALUES ('$kode', '$nama', '$kategori', '$stok', '$harga')";
                $aksi = "ditambahkan";
            }
        }
        if ($tipe_pesan != "danger") {
            $hasil = mysqli_query($koneksi, $query_simpan);
            if ($hasil) {
                $pesan = "Sukses, barang '$nama' berhasil $aksi.";
                $tipe_pesan = "success";
                // Reset form setelah sukses
                $kode = $nama = $kategori = $stok = $harga = "";
                $op = ""; 
                $id_edit = "";
            } else {
                $pesan = "Gagal menyimpan data: " . mysqli_error($koneksi);
                $tipe_pesan = "danger";
            }
        }
    }
}
$query_tampil = "select * from barang order by id desc";
$hasil_tampil = mysqli_query($koneksi, $query_tampil);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris Barang</title>

    <!--bootstrap 5-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0 py-2">Tambah Barang Baru</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="">
                            <input type="hidden" name="op" value="<?php echo $op; ?>">
                            <input type="hidden" name="id" value="<?php echo $id_edit; ?>">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kode Barang</label>
                                <input type="text" name="kode_barang" class="form-control" value="<?php echo $kode; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" value="<?php echo $nama; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <input type="text" name="kategori" class="form-control" value="<?php echo $kategori; ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Stok</label>
                                    <input type="number" name="stok" class="form-control" value="<?php echo $stok; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Harga Satuan</label>
                                    <input type="number" name="harga_satuan" class="form-control" value="<?php echo $harga; ?>" required>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="simpan" class="btn btn-success btn-lg">
                                    <?php echo ($op == 'edit') ? 'Update Data' : 'Simpan Barang'; ?>
                                </button>
                            </div>

                        </form>

                        <?php if ($pesan != ""): ?>
                            <p style="color: <?php echo $tipe_pesan == 'success' ? 'green' : 'red'; ?>; font-weight: bold; margin: 15px 0;">
                                <?php echo $pesan; ?>
                            </p>
                        <?php endif; ?>

                        <!-- TABEL DATA BARANG -->
                        <div class="container mt-4">
                        <h3>Data Inventaris Barang</h3>
                        <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Kode Barang</th>
                                <th scope="col">Nama Barang</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Stok</th>
                                <th scope="col">Harga Satuan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $urut = 1;
                            while ($r2 = mysqli_fetch_array($hasil_tampil)) {
                                $id = $r2['id'];
                                $kode = $r2['kode_barang'];
                                $nama = $r2['nama_barang'];
                                $kategori = $r2['kategori'];
                                $stok = $r2['stok'];
                                $harga = $r2['harga_satuan'];
                            ?>
                            <tr>
                                <th scope="row"><?php echo $urut++; ?></th>
                                <td><?php echo $kode; ?></td>
                                <td><?php echo $nama; ?></td>
                                <td><?php echo $kategori; ?></td>
                                <td><?php echo $stok; ?></td>
                                <td>Rp <?php echo number_format($harga, 0, ',', '.'); ?></td>
                                <td>
                                    <a href="?op=edit&id=<?php echo $id; ?>">
                                        <button type="button" class="btn btn-warning">Edit</button>
                                    </a>
                                    <a href="?op=delete&id=<?php echo $id; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <button type="button" class="btn btn-danger">Delete</button>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                        </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>