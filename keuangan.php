<?php
include 'koneksi.php';

// Pencarian data
$cari = isset($_POST['cari']) ? $_POST['cari'] : '';
$query = "SELECT * FROM keuangan";

if ($cari != '') {
  $query .= " WHERE catatan LIKE '%$cari%'";
}

$result = mysqli_query($conn, $query);

// Penghapusan data
if (isset($_GET['hapus'])) {
  $id_keuangan = $_GET['hapus'];
  $query = "DELETE FROM keuangan WHERE id = '$id_keuangan'";
  $delete_result = mysqli_query($conn, $query);

  if ($delete_result) {
    header('Location: keuangan.php');
    exit;
  } else {
    echo "<script>alert('Gagal menghapus data');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Keuangan</title>
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
            <li>
                <a href="pesanan.php">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Pesanan</span>
                </a>
            </li>
            <li class="active">
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
            <a href="#">Keuangan</a>
        </div>

        <div class="container">
            <a href="tambah-keuangan.php" class="btn">Tambah Keuangan</a><br>
            <form action="keuangan.php" method="post">
                <input type="text" class="pencarian" name="cari" placeholder="Cari catatan..."
                    value="<?= htmlspecialchars($cari); ?>">
                <input type="submit" class="btn-outline" value="Cari">
                <input type="submit" id="refresh-btn" class="btn-outline" value="Atur Ulang">
            </form>
            <div class="table-scroll">
                <table border="1" class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Pemasukan</th>
                            <th>Pengeluaran</th>
                            <th>Hasil</th>
                            <th>Catatan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            $no = 1;
            if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['id']); ?></td>
                            <td>Rp <?= number_format($row['pemasukan'], 0, ',', '.'); ?></td>
                            <td>Rp <?= number_format($row['pengeluaran'], 0, ',', '.'); ?></td>
                            <td>Rp <?= number_format($row['hasil'], 0, ',', '.'); ?></td>
                            <td><?= htmlspecialchars($row['catatan']); ?></td>
                            <td><?= htmlspecialchars($row['tanggal']); ?></td>
                            <td>
                                <a href="tambah-keuangan.php?ubah=<?= $row['id']; ?>">Edit</a>
                                <a href="#" class="hapus-btn" data-id="<?= $row['id']; ?>">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="8">Tidak ada data ditemukan.</td>
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

            const idKeuangan = this.getAttribute('data-id');

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
                    window.location.href = "keuangan.php?hapus=" + idKeuangan;
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