<?php
include 'koneksi.php';

// Pencarian data
$cari = isset($_POST['cari']) ? $_POST['cari'] : '';
$query = "SELECT * FROM obat";

if ($cari != '') {
  $query .= " WHERE id_obat LIKE '%$cari%' 
              OR nama_obat LIKE '%$cari%' 
              OR kategori_obat LIKE '%$cari%'";
}

$result = mysqli_query($conn, $query);

// Penghapusan data
if (isset($_GET['hapus'])) {
  $id_obat = $_GET['hapus'];
  $query = "DELETE FROM obat WHERE id_obat = '$id_obat'";
  $result = mysqli_query($conn, $query);

  if ($result) {
    header('Location: produk.php');
    exit;
  } else {
    echo "Gagal menghapus data";
  }
}

// Penambahan data pesanan
if (isset($_POST['tambah_pesanan'])) {
  $id_obat = $_POST['id_obat'];
  $nama_pemesan = mysqli_real_escape_string($conn, $_POST['nama_pemesan']);
  $qty = (int)$_POST['qty'];
  $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
  $harga = (float)$_POST['harga'];

  $query = "INSERT INTO pesanan (id_obat, nama_pemesan, qty, alamat, harga) 
            VALUES ('$id_obat', '$nama_pemesan', $qty, '$alamat', $harga)";
  $sql = mysqli_query($conn, $query);

  if ($sql) {
    echo "<script>alert('Pesanan berhasil ditambahkan'); window.location.href='pesanan.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan pesanan');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="css/produk.css" />

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
                </button>
            </li>
            <li>
                <a href="dashboard.php">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
            </li>
            <li class="active">
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
        </div>

        <div class="container">
            <a href="tambah-produk.php" class="btn">Tambah Produk</a><br>
            <form action="produk.php" method="post">
                <input type="text" class="pencarian" name="cari" placeholder="Cari obat..."
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
                            <th>Nama Obat</th>
                            <th>Jenis Obat</th>
                            <th>Kategori</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Lokasi</th>
                            <th>Kadaluarsa</th>
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
                            <td><?= htmlspecialchars($row['id_obat']); ?></td>
                            <td><?= htmlspecialchars($row['nama_obat']); ?></td>
                            <td><?= htmlspecialchars($row['jenis_obat']); ?></td>
                            <td><?= htmlspecialchars($row['kategori_obat']); ?></td>
                            <td>Rp <?= number_format($row['harga_beli'], 0, ',', '.'); ?></td>
                            <td>Rp <?= number_format($row['harga_jual'], 0, ',', '.'); ?></td>
                            <td><?= htmlspecialchars($row['stok']); ?></td>
                            <td><?= htmlspecialchars($row['lokasi_penyimpanan']); ?></td>
                            <td><?= htmlspecialchars($row['tanggal_kadaluwarsa']); ?></td>
                            <td>
                                <a href="tambah-produk.php?ubah=<?= $row['id_obat']; ?>">Edit</a>
                                <a href="#" class="hapus-btn" data-id="<?= $row['id_obat']; ?>">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="11">Tidak ada data ditemukan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
    // Refresh halaman
    document.getElementById('refresh-btn').addEventListener('click', function() {
        location.reload();
    });

    document.getElementById('logout-link').addEventListener('click', function(event) {
        event.preventDefault();

        Swal.fire({
            title: "Yakin ga nih???",
            text: "Sebaiknya jangan gegabah",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yakin pisan"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "login.php";
            }
        });
    });

    // Konfirmasi tombol hapus
    document.querySelectorAll('.hapus-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const idObat = this.getAttribute('data-id');

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
                    window.location.href = "produk.php?hapus=" + idObat;
                }
            });
        });
    });

    // Penambahan pesanan dari halaman produk
    document.querySelectorAll('.add-order-btn').forEach(button => {
        button.addEventListener('click', function() {
            const idObat = this.getAttribute('data-id');
            const namaObat = this.getAttribute('data-nama');
            const harga = this.getAttribute('data-harga');

            Swal.fire({
                title: 'Tambah Pesanan',
                html: `
            <form id="order-form">
              <input type="hidden" name="id_obat" value="${idObat}">
              <div class="input-group">
                <label for="nama_pemesan">Nama Pemesan</label>
                <input type="text" id="nama_pemesan" name="nama_pemesan" required>
              </div>
              <div class="input-group">
                <label for="qty">Jumlah</label>
                <input type="number" id="qty" name="qty" value="1" min="1" required>
              </div>
              <div class="input-group">
                <label for="alamat">Alamat</label>
                <input type="text" id="alamat" name="alamat" required>
              </div>
              <div class="input-group">
                <label for="harga">Harga</label>
                <input type="text" id="harga" name="harga" value="${harga}" readonly>
              </div>
            </form>
          `,
                showCancelButton: true,
                confirmButtonText: 'Tambahkan',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const form = document.getElementById('order-form');
                    const formData = new FormData(form);
                    fetch('produk.php', {
                            method: 'POST',
                            body: formData
                        }).then(response => response.text())
                        .then(result => {
                            Swal.fire('Pesanan Ditambahkan!', '', 'success');
                            location.reload();
                        })
                        .catch(error => {
                            Swal.fire('Gagal menambahkan pesanan', '', 'error');
                        });
                }
            });
        });
    });
    </script>
</body>

</html>