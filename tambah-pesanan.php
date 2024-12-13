<?php
include 'koneksi.php';
$data_pesanan = null;

// Cek apakah ada parameter 'ubah' di URL

if (isset($_GET['ubah'])) {
    $nomor_pesanan = mysqli_real_escape_string($conn, $_GET['ubah']); // Ganti nomor_pesanan dengan id_keuangan
    $query = "SELECT * FROM pesanan WHERE nomor_pesanan = '$nomor_pesanan'"; // Ubah nama tabel dan kolom yang relevan
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data_pesanan = mysqli_fetch_assoc($result); // Mengambil data keuangan
    }
}

$query_obat = "SELECT id_obat, nama_obat, harga_jual FROM obat";
$result_obat = mysqli_query($conn, $query_obat);

$obat_options = [];
if ($result_obat && mysqli_num_rows($result_obat) > 0) {
    while ($row = mysqli_fetch_assoc($result_obat)) {
        $obat_options[] = $row;
    }
} else {
    echo "Tidak ada data obat ditemukan.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="css/kelola.css" />
    <script type="text/javascript" src="app.js" defer></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <nav id="sidebar">
        <ul>
            <li>
                <span class="logo">SehatWeb</span>
            </li>
            <li>
                <a href="dashboard.php">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
            </li>
            <li>
                <a href="produk.php">
                    <i class="fas fa-box"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li class="active">
                <a href="pesanan.php">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Pesanan</span>
                </a>
            </li>
            <li>
                <a href="keuangan.php">
                    <i class="fas fa-wallet"></i>
                    <span>Keuangan</span>
                </a>
            </li>
            <li>
                <a href="profile.php">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li>
                <a href="login.php" id="logout-link">
                    <i class="fas fa-sign-out-alt" style="color: #ff0000;"></i>
                    <span style="color: #ff0000;">Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <main>
        <div class="topnav">
            <a href="#">Produk</a>
            <a href="#">Tambah Obat</a>
        </div>

        <div class="container">
            <form action="tambah-pesanan.php" method="post" enctype="multipart/form-data">
                <div class="mb-3 row">
                    <label for="nomor_pesanan" class="col-sm-2">Kode Pesanan</label>
                    <div class="col-sm-10">
                        <input type="text" name="nomor_pesanan" id="nomor_pesanan" placeholder="Ex: 12345678"
                            value="<?= isset($data_pesanan['nomor_pesanan']) ? htmlspecialchars($data_pesanan['nomor_pesanan']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="nama_customer" class="col-sm-2">Nama Pelanggan</label>
                    <div class="col-sm-10">
                        <input type="text" name="nama_customer" id="nama_customer" placeholder="Ex: John Doe"
                            value="<?= isset($data_pesanan['nama_customer']) ? htmlspecialchars($data_pesanan['nama_customer']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="barang_yang_dibeli" class="col-sm-2">Barang yang Dibeli</label>
                    <div class="col-sm-10">
                        <select name="barang_yang_dibeli" id="barang_yang_dibeli" required>
                            <option value="" disabled selected>Pilih Barang</option>
                            <?php foreach ($obat_options as $obat): ?>
                                <option value="<?= htmlspecialchars($obat['id_obat']) ?>"
                                    data-harga="<?= htmlspecialchars($obat['harga_jual']) ?>"
                                    <?= htmlspecialchars($obat['nama_obat']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="qty" class="col-sm-2">Jumlah</label>
                    <div class="col-sm-10">
                        <input type="text" name="qty" id="qty" placeholder="Jumlah yang dipesan"
                            value="<?= isset($data_pesanan['qty']) ? htmlspecialchars($data_pesanan['qty']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="alamat" class="col-sm-2">Alamat Pengiriman</label>
                    <div class="col-sm-10">
                        <input type="text" name="alamat" id="alamat" placeholder="Alamat pengiriman"
                            value="<?= isset($data_pesanan['alamat']) ? htmlspecialchars($data_pesanan['alamat']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="status_dikirim" class="col-sm-2">Status Pengiriman</label>
                    <div class="col-sm-10">
                        <select name="status_dikirim" id="status_dikirim" required>
                            <option value="belum_dikirim"
                                <?= (isset($data_pesanan['status_dikirim']) && $data_pesanan['status_dikirim'] == 'belum_dikirim') ? 'selected' : ''; ?>>
                                Belum Dikirim</option>
                            <option value="dikirim"
                                <?= (isset($data_pesanan['status_dikirim']) && $data_pesanan['status_dikirim'] == 'dikirim') ? 'selected' : ''; ?>>
                                Dikirim</option>
                            <option value="diterima"
                                <?= (isset($data_pesanan['status_dikirim']) && $data_pesanan['status_dikirim'] == 'diterima') ? 'selected' : ''; ?>>
                                Diterima</option>
                            <option value="dibatalkan"
                                <?= (isset($data_pesanan['status_dikirim']) && $data_pesanan['status_dikirim'] == 'dibatalkan') ? 'selected' : ''; ?>>
                                Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="harga" class="col-sm-2">Harga Barang</label>
                    <div class="col-sm-10">
                        <input type="text" name="harga" id="harga" placeholder="Harga barang"
                            value="<?= isset($data_pesanan['harga']) ? htmlspecialchars($data_pesanan['harga']) : ''; ?>"
                            required readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <?php if (isset($_GET['ubah'])) { ?>
                        <button type="submit" name="aksi" value="edit" class="btn btn-primary">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>Simpan Perubahan
                        </button>
                    <?php } else { ?>
                        <button type="submit" name="aksi" value="add" class="btn btn-primary" style="margin-right: 1rem;">
                            Tambahkan Pesanan
                        </button>
                    <?php } ?>
                    <button type="button" id="back-btn" class="btn-danger"
                        onclick="window.history.back();">Kembali</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // AJAX untuk mengambil harga barang
        document.getElementById('barang_yang_dibeli').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga');
            document.getElementById('harga').value = harga ? harga : '';
        });
    </script>

</body>

</html>

<?php
if (isset($_POST['aksi'])) {
    // Ambil data dari form
    $nomor_pesanan = $_POST['nomor_pesanan'];
    $nama_customer = $_POST['nama_customer'];
    $barang_yang_dibeli = $_POST['barang_yang_dibeli'];
    $qty = $_POST['qty'];
    $alamat = $_POST['alamat'];
    $status_dikirim = $_POST['status_dikirim'];
    $harga = $_POST['harga']; // Ambil harga dari form

    // Validasi data
    if (empty($nomor_pesanan) || empty($nama_customer) || empty($barang_yang_dibeli) || empty($qty) || empty($alamat) || empty($status_dikirim) || empty($harga)) {
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Semua kolom harus diisi.',
                }).then(() => {
                    window.history.back();
                });
              </script>";
        exit;
    }

    if (!is_numeric($qty) || !is_numeric($harga)) {
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Jumlah dan harga harus berupa angka valid.',
                }).then(() => {
                    window.history.back();
                });
              </script>";
        exit;
    }

    if ($_POST['aksi'] === 'edit') {
        // Query untuk update data
        $query = "UPDATE pesanan SET 
                    nama_customer = '$nama_customer', 
                    barang_yang_dibeli = '$barang_yang_dibeli', 
                    qty = $qty, 
                    alamat = '$alamat', 
                    status_dikirim = '$status_dikirim',
                    harga = $harga 
                  WHERE nomor_pesanan = '$nomor_pesanan'";
    } else {
        // Periksa apakah nomor pesanan sudah ada
        $check_query = "SELECT nomor_pesanan FROM pesanan WHERE nomor_pesanan = '$nomor_pesanan'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Nomor pesanan sudah ada, munculkan alert
            echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Nomor pesanan sudah ada di database. Gunakan nomor pesanan yang berbeda.',
                    }).then(() => {
                        window.history.back();
                    });
                  </script>";
            exit; // Hentikan proses jika nomor pesanan sudah ada
        } else {
            // Query untuk insert data baru
            $query = "INSERT INTO pesanan (nomor_pesanan, nama_customer, barang_yang_dibeli, qty, alamat, status_dikirim, harga) 
                      VALUES ('$nomor_pesanan', '$nama_customer', '$barang_yang_dibeli', $qty, '$alamat', '$status_dikirim', $harga)";
        }
    }

    // Eksekusi query
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Tampilkan SweetAlert jika berhasil
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Pesanan berhasil disimpan.',
                }).then(() => {
                    window.location.href = 'pesanan.php';
                });
              </script>";
    } else {
        // Tampilkan SweetAlert jika gagal
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                }).then(() => {
                    window.history.back();
                });
              </script>";
    }
}
?>