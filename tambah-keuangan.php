<?php
include 'koneksi.php';
$id = null;
$data_keuangan = null;

// Cek apakah ada parameter 'ubah' di URL
if (isset($_GET['ubah'])) {
  $id_keuangan = mysqli_real_escape_string($conn, $_GET['ubah']); // Ganti nomor_pesanan dengan id_keuangan
  $query = "SELECT * FROM keuangan WHERE id = '$id_keuangan'"; // Ubah nama tabel dan kolom yang relevan
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $data_keuangan = mysqli_fetch_assoc($result); // Mengambil data keuangan
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Keuangan</title>
    <link rel="stylesheet" href="css/kelola.css" />

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
            <a href="#">Tambah Keuangan</a>
        </div>

        <div class="container">
            <form action="tambah-keuangan.php" method="post" enctype="multipart/form-data">
                <div class="mb-3 row">
                    <label for="id" class="col-sm-2">ID</label>
                    <div class="col-sm-10">
                        <input type="text" name="id" id="id" placeholder="Ex: 12345678"
                            value="<?= isset($data_keuangan['id']) ? htmlspecialchars($data_keuangan['id']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="pemasukan" class="col-sm-2">Pemasukan</label>
                    <div class="col-sm-10">
                        <input type="text" name="pemasukan" id="pemasukan" placeholder="Pemasukan"
                            value="<?= isset($data_keuangan['pemasukan']) ? htmlspecialchars($data_keuangan['pemasukan']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="pengeluaran" class="col-sm-2">Pengeluaran</label>
                    <div class="col-sm-10">
                        <input type="text" name="pengeluaran" id="pengeluaran" placeholder="Pengeluaran"
                            value="<?= isset($data_keuangan['pengeluaran']) ? htmlspecialchars($data_keuangan['pengeluaran']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="catatan" class="col-sm-2">Catatan</label>
                    <div class="col-sm-10">
                        <input type="text" name="catatan" id="catatan" placeholder="Catatan"
                            value="<?= isset($data_keuangan['catatan']) ? htmlspecialchars($data_keuangan['catatan']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="tanggal" class="col-sm-2">Tanggal</label>
                    <div class="col-sm-10">
                        <input type="date" name="tanggal" id="tanggal"
                            value="<?= isset($data_keuangan['tanggal']) ? htmlspecialchars($data_keuangan['tanggal']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <?php if (isset($_GET['ubah'])) { ?>
                    <button type="submit" name="aksi" value="edit" class="btn btn-primary">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>Simpan Perubahan
                    </button>
                    <?php } else { ?>
                    <button type="submit" name="aksi" value="add" class="btn btn-primary" style="margin-right: 1rem;">
                        Tambahkan Keuangan
                    </button>
                    <?php } ?>
                    <button type="button" id="back-btn" class="btn-danger"
                        onclick="window.history.back();">Kembali</button>
                </div>
            </form>
        </div>
    </main>

    <script>
    document.getElementById('back-btn').addEventListener('click', function() {
        window.location.href = 'keuangan.php';
    });

    document.querySelectorAll('.hapus-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const idObat = this.getAttribute('data-id');

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
    });
    </script>
</body>

</html>

<?php
if (isset($_POST['aksi'])) {
  // Ambil data dari form
  $id = $_POST['id'];
  $pemasukan = $_POST['pemasukan'];
  $pengeluaran = $_POST['pengeluaran'];
  $hasil = $_POST['hasil'];
  $catatan = $_POST['catatan'];
  $tanggal = $_POST['tanggal'];

  if ($_POST['aksi'] == 'edit') {
    // Jika aksi adalah edit, lakukan UPDATE
    $query = "UPDATE keuangan SET 
                  pemasukan = '$pemasukan', 
                  pengeluaran = '$pengeluaran', 
                  hasil = '$hasil', 
                  catatan = '$catatan', 
                  tanggal = '$tanggal' 
                WHERE id = '$id'";
  } else {
    // Jika aksi adalah tambah, periksa apakah ID sudah ada
    $check_query = "SELECT id FROM keuangan WHERE id = '$id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
      // Jika ID sudah ada, munculkan peringatan
      echo "<script>
                  Swal.fire({
                      icon: 'warning',
                      title: 'Peringatan',
                      text: 'ID sudah ada di database. Gunakan ID yang berbeda.',
                  }).then(() => {
                      window.history.back();
                  });
                </script>";
      exit; // Hentikan proses jika ID sudah ada
    } else {
      // Jika ID belum ada, lakukan INSERT
      $query = "INSERT INTO keuangan (id, pemasukan, pengeluaran, catatan, tanggal) 
                    VALUES ('$id', '$pemasukan', '$pengeluaran', '$catatan', '$tanggal')";
    }
  }

  // Eksekusi query
  $sql = mysqli_query($conn, $query);

  // Cek hasil eksekusi
  if ($sql) {
    echo "<script>
              Swal.fire({
                  icon: 'success',
                  title: 'Sukses',
                  text: 'Data berhasil ditambahkan atau diperbarui',
              }).then(() => {
                  window.location.href = 'keuangan.php'; // Redirect setelah sukses
              });
            </script>";
  } else {
    echo "<script>
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Data gagal ditambahkan atau diperbarui: " . mysqli_error($conn) . "',
              });
            </script>";
  }

  // Tutup koneksi
  mysqli_close($conn);
}
?>