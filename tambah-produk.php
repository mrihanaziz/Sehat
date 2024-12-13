<?php
include 'koneksi.php';
$id_obat = null;
$data_obat = null;

if (isset($_GET['ubah'])) {
  $id_obat = mysqli_real_escape_string($conn, $_GET['ubah']);
  $query = "SELECT * FROM obat WHERE id_obat = '$id_obat'";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $data_obat = mysqli_fetch_assoc($result);
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="css/kelola.css" />

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Feather Icon -->
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
            <a href="#">Tambah Obat</a>
        </div>

        <div class="container">
            <form action="tambah-produk.php" method="post" enctype="multipart/form-data">
                <div class="mb-3 row">
                    <label for="id_obat" class="col-sm-2">Kode Obat</label>
                    <div class="col-sm-10">
                        <input type="text" name="id_obat" id="id_obat" placeholder="Ex: 12345678"
                            value="<?= isset($data_obat['id_obat']) ? htmlspecialchars($data_obat['id_obat']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="nama_obat" class="col-sm-2">Nama Obat</label>
                    <div class="col-sm-10">
                        <input type="text" name="nama_obat" id="nama_obat" placeholder="Ex: Paracetamol"
                            value="<?= isset($data_obat['nama_obat']) ? htmlspecialchars($data_obat['nama_obat']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="jenis_obat" class="col-sm-2">Jenis Obat</label>
                    <div class="col-sm-10">
                        <select name="jenis_obat" id="jenis_obat" required>
                            <option value="Tablet"
                                <?= (isset($data_obat['jenis_obat']) && $data_obat['jenis_obat'] == 'Tablet') ? 'selected' : ''; ?>>
                                Tablet</option>
                            <option value="Kapsul"
                                <?= (isset($data_obat['jenis_obat']) && $data_obat['jenis_obat'] == 'Kapsul') ? 'selected' : ''; ?>>
                                Kapsul</option>
                            <option value="Sirup"
                                <?= (isset($data_obat['jenis_obat']) && $data_obat['jenis_obat'] == 'Sirup') ? 'selected' : ''; ?>>
                                Sirup</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="kategori_obat" class="col-sm-2">Kategori Obat</label>
                    <div class="col-sm-10">
                        <select name="kategori_obat" id="kategori_obat" required>
                            <option value="Obat Bebas"
                                <?= (isset($data_obat['kategori_obat']) && $data_obat['kategori_obat'] == 'Obat Bebas') ? 'selected' : ''; ?>>
                                Obat Bebas</option>
                            <option value="Obat Resep"
                                <?= (isset($data_obat['kategori_obat']) && $data_obat['kategori_obat'] == 'Obat Resep') ? 'selected' : ''; ?>>
                                Obat Resep</option>
                            <option value="Vitamin"
                                <?= (isset($data_obat['kategori_obat']) && $data_obat['kategori_obat'] == 'Vitamin') ? 'selected' : ''; ?>>
                                Vitamin</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="harga_beli" class="col-sm-2">Harga Beli</label>
                    <div class="col-sm-10">
                        <input type="text" step="0.01" name="harga_beli" id="harga_beli" placeholder="Harga beli produk"
                            value="<?= isset($data_obat['harga_beli']) ? htmlspecialchars($data_obat['harga_beli']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="harga_jual" class="col-sm-2">Harga Jual</label>
                    <div class="col-sm-10">
                        <input type="text" step="0.01" name="harga_jual" id="harga_jual" placeholder="Harga jual produk"
                            value="<?= isset($data_obat['harga_jual']) ? htmlspecialchars($data_obat['harga_jual']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="stok" class="col-sm-2">Stok</label>
                    <div class="col-sm-10">
                        <input type="text" name="stok" id="stok" placeholder="Jumlah stok"
                            value="<?= isset($data_obat['stok']) ? htmlspecialchars($data_obat['stok']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="lokasi_penyimpanan" class="col-sm-2">Lokasi Penyimpanan</label>
                    <div class="col-sm-10">
                        <select name="lokasi_penyimpanan" id="lokasi_penyimpanan" required>
                            <option value="Gudang A"
                                <?= (isset($data_obat['lokasi_penyimpanan']) && $data_obat['lokasi_penyimpanan'] == 'Gudang A') ? 'selected' : ''; ?>>
                                Gudang A</option>
                            <option value="Gudang B"
                                <?= (isset($data_obat['lokasi_penyimpanan']) && $data_obat['lokasi_penyimpanan'] == 'Gudang B') ? 'selected' : ''; ?>>
                                Gudang B</option>
                            <option value="Gudang C"
                                <?= (isset($data_obat['lokasi_penyimpanan']) && $data_obat['lokasi_penyimpanan'] == 'Gudang C') ? 'selected' : ''; ?>>
                                Gudang C</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="tanggal_kadaluwarsa" class="col-sm-2">Tanggal Kadaluwarsa</label>
                    <div class="col-sm-10">
                        <input type="date" name="tanggal_kadaluwarsa" id="tanggal_kadaluwarsa"
                            value="<?= isset($data_obat['tanggal_kadaluwarsa']) ? htmlspecialchars($data_obat['tanggal_kadaluwarsa']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="ditambahkan_oleh" class="col-sm-2">Ditambahkan Oleh</label>
                    <div class="col-sm-10">
                        <select name="ditambahkan_oleh" id="ditambahkan_oleh" required>
                            <option value="Admin"
                                <?= (isset($data_obat['ditambahkan_oleh']) && $data_obat['ditambahkan_oleh'] == 'Admin') ? 'selected' : ''; ?>>
                                Admin</option>
                            <option value="Supervisor"
                                <?= (isset($data_obat['ditambahkan_oleh']) && $data_obat['ditambahkan_oleh'] == 'Supervisor') ? 'selected' : ''; ?>>
                                Supervisor</option>
                            <option value="Manager"
                                <?= (isset($data_obat['ditambahkan_oleh']) && $data_obat['ditambahkan_oleh'] == 'Manager') ? 'selected' : ''; ?>>
                                Manager</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <?php if (isset($_GET['ubah'])) { ?>
                    <button type="submit" name="aksi" value="edit" class="btn btn-primary">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>Simpan Perubahan
                    </button>
                    <?php } else { ?>
                    <button type="submit" name="aksi" value="add" class="btn btn-primary" style="margin-right: 1rem;">
                        Tambahkan
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
        window.location.href = 'produk.php';
    })

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
  $id_obat = $_POST['id_obat'];
  $nama_obat = $_POST['nama_obat'];
  $jenis_obat = $_POST['jenis_obat'];
  $kategori_obat = $_POST['kategori_obat'];
  $harga_beli = $_POST['harga_beli'];
  $harga_jual = $_POST['harga_jual'];
  $stok = $_POST['stok'];
  $lokasi_penyimpanan = $_POST['lokasi_penyimpanan'];
  $tanggal_kadaluwarsa = $_POST['tanggal_kadaluwarsa'];
  $ditambahkan_oleh = $_POST['ditambahkan_oleh'];

  // Periksa aksi yang dikirim
  if ($_POST['aksi'] === 'edit') {
    // Lakukan UPDATE data
    $query = "UPDATE obat SET
                  nama_obat = '$nama_obat',
                  jenis_obat = '$jenis_obat',
                  kategori_obat = '$kategori_obat',
                  harga_beli = $harga_beli,
                  harga_jual = $harga_jual,
                  stok = $stok,
                  lokasi_penyimpanan = '$lokasi_penyimpanan',
                  tanggal_kadaluwarsa = '$tanggal_kadaluwarsa',
                  ditambahkan_oleh = '$ditambahkan_oleh'
                WHERE id_obat = '$id_obat'";

    $sql = mysqli_query($conn, $query);

    if ($sql) {
      echo "<script>
                      Swal.fire({
                          icon: 'success',
                          title: 'Sukses',
                          text: 'Data berhasil diupdate',
                      }).then(() => {
                          window.location.href = 'produk.php';
                      });
                </script>";
    } else {
      echo "<script>
                      Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: 'Data gagal diupdate: " . mysqli_error($conn) . "',
                      });
                </script>";
    }
  } elseif ($_POST['aksi'] === 'add') {
    // Validasi jika id_obat sudah ada
    $check_query = "SELECT id_obat FROM obat WHERE id_obat = '$id_obat'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
      // ID obat sudah ada, munculkan alert
      echo "<script>
                      Swal.fire({
                          icon: 'warning',
                          title: 'Peringatan',
                          text: 'ID Obat sudah ada di database. Gunakan ID yang berbeda.',
                      }).then(() => {
                          window.history.back();
                      });
                </script>";
    } else {
      // Lakukan INSERT data jika ID belum ada
      $query = "INSERT INTO obat (id_obat, nama_obat, jenis_obat, kategori_obat, harga_beli, harga_jual, stok, lokasi_penyimpanan, tanggal_kadaluwarsa, ditambahkan_oleh) 
                    VALUES ('$id_obat', '$nama_obat', '$jenis_obat', '$kategori_obat', $harga_beli, $harga_jual, $stok, '$lokasi_penyimpanan', '$tanggal_kadaluwarsa', '$ditambahkan_oleh')";

      $sql = mysqli_query($conn, $query);

      if ($sql) {
        echo "<script>
                          Swal.fire({
                              icon: 'success',
                              title: 'Sukses',
                              text: 'Data berhasil ditambahkan',
                          }).then(() => {
                              window.location.href = 'produk.php';
                          });
                    </script>";
      } else {
        echo "<script>
                          Swal.fire({
                              icon: 'error',
                              title: 'Error',
                              text: 'Data gagal ditambahkan: " . mysqli_error($conn) . "',
                          });
                    </script>";
      }
    }
  }
}

?>