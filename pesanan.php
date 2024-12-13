<?php
include 'koneksi.php';

// Pencarian data
$cari = isset($_POST['cari']) ? $_POST['cari'] : '';
$query = "SELECT * FROM pesanan";

if ($cari != '') {
  $query .= " WHERE nomor_pesanan LIKE '%$cari%' OR nama_customer LIKE '%$cari%'";
}

$result = mysqli_query($conn, $query);

// Penghapusan data
if (isset($_GET['hapus'])) {
  $nomor_pesanan = $_GET['hapus'];
  $query = "DELETE FROM pesanan WHERE nomor_pesanan = '$nomor_pesanan'";
  $delete_result = mysqli_query($conn, $query);

  if ($delete_result) {
    header('Location: pesanan.php');
    exit;
  } else {
    echo "<script>alert('Gagal menghapus data');</script>";
  }
}

// Memproses input tambah/edit pesanan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nomor_pesanan = mysqli_real_escape_string($conn, $_POST['nomor_pesanan']);
    $nama_customer = mysqli_real_escape_string($conn, $_POST['nama_customer']);
    $barang_yang_dibeli = mysqli_real_escape_string($conn, $_POST['obat_yang_dibeli']);
    $qty = (int)$_POST['qty'];
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $status_dikirim = mysqli_real_escape_string($conn, $_POST['status_dikirim']);
    $harga = (float)$_POST['harga'];

    if ($_POST['aksi'] == 'add') {
        // Insert data baru
        $query = "INSERT INTO pesanan (nomor_pesanan, nama_customer, barang_yang_dibeli, qty, alamat, status_dikirim, harga) 
                  VALUES ('$nomor_pesanan', '$nama_customer', '$barang_yang_dibeli', $qty, '$alamat', '$status_dikirim', $harga)";
        if (mysqli_query($conn, $query)) {
            echo "<script>Swal.fire('Berhasil', 'Pesanan berhasil ditambahkan', 'success');</script>";
        } else {
            echo "<script>Swal.fire('Gagal', 'Pesanan gagal ditambahkan: " . mysqli_error($conn) . "', 'error');</script>";
        }
    } elseif ($_POST['aksi'] == 'edit') {
        // Update data yang ada
        $query = "UPDATE pesanan SET nama_customer='$nama_customer', barang_yang_dibeli='$barang_yang_dibeli', qty=$qty, alamat='$alamat', status_dikirim='$status_dikirim', harga=$harga WHERE nomor_pesanan='$nomor_pesanan'";
        if (mysqli_query($conn, $query)) {
            echo "<script>Swal.fire('Berhasil', 'Pesanan berhasil diperbarui', 'success');</script>";
        } else {
            echo "<script>Swal.fire('Gagal', 'Pesanan gagal diperbarui: " . mysqli_error($conn) . "', 'error');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Pesanan</title>
    <link rel="stylesheet" href="css/pesanan.css" />
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <nav id="sidebar">
        <ul>
            <li><span class="logo">SehatWeb</span></li>
            <li><a href="dashboard.php"><i class="fas fa-home"></i><span>Beranda</span></a></li>
            <li><a href="produk.php"><i class="fas fa-box"></i><span>Produk</span></a></li>
            <li class="active"><a href="pesanan.php"><i class="fas fa-shopping-cart"></i><span>Pesanan</span></a></li>
            <li><a href="keuangan.php"><i class="fas fa-wallet"></i><span>Keuangan</span></a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i><span>Profile</span></a></li>
            <li><a href="login.php" id="logout-link"><i class="fas fa-sign-out-alt" style="color: #ff0000;"></i><span
                        style="color: #ff0000;">Logout</span></a></li>
        </ul>
    </nav>

    <main>
        <div class="topnav">
            <a href="#">Pesanan</a>
        </div>

        <div class="container">
            <a href="tambah-pesanan.php" class="btn">Tambah Pesanan</a><br>
            <form action="pesanan.php" method="post">
                <input type="text" class="pencarian" name="cari" placeholder="Cari pesanan..."
                    value="<?= htmlspecialchars($cari); ?>">
                <input type="submit" class="btn-outline" value="Cari">
                <input type="submit" id="refresh-btn" class="btn-outline" value="Atur Ulang">
            </form>
            <div class="table-scroll">
                <table border="1" class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Pesanan</th>
                            <th>Nama Customer</th>
                            <th>Barang yang Dibeli</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Alamat</th>
                            <th>Status Dikirim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['nomor_pesanan']; ?></td>
                            <td><?= $row['nama_customer']; ?></td>
                            <td><?= $row['barang_yang_dibeli']; ?></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td><?= $row['qty']; ?></td>
                            <td><?= $row['alamat']; ?></td>
                            <td><?= $row['status_dikirim']; ?></td>
                            <td>
                                <a href="tambah-pesanan.php?ubah=<?= $row['nomor_pesanan']; ?>">Edit</a>
                                <a href="#" class="hapus-btn" data-id="<?= $row['nomor_pesanan']; ?>">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="9">Tidak ada data ditemukan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
    document.getElementById('refresh-btn').addEventListener('click', function() {
        location.reload();
    });

    document.querySelectorAll('.hapus-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const nomorPesanan = this.getAttribute('data-id');

            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#cacaca",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "pesanan.php?hapus=" + nomorPesanan;
                }
            });
        });
    });

    document.getElementById('logout-link').addEventListener('click', function(event) {
        event.preventDefault();

        Swal.fire({
            title: "Apakah Anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#cacaca",
            confirmButtonText: "Keluar",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "login.php";
            }
        });
    });
    </script>
</body>

</html>