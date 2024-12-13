<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar | Admin SehatWeb</title>
  <link rel="stylesheet" href="css/daftar.css" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    .swal2-popup {
      font-family: 'Poppins', sans-serif !important;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <div class="title"><span>Daftar</span></div>
    <form action="" method="post">

      <div class="row">
        <i class="fas fa-id-card"></i>
        <input type="text" placeholder="ID" name="id" required />
      </div>
      <div class="row">
        <i class="fas fa-envelope"></i>
        <input type="email" placeholder="Email" name="email" required />
      </div>
      <div class="row">
        <i class="fas fa-envelope"></i>
        <input type="text" placeholder="Nomor telepon" name="nomor_telepon" required />
      </div>
      <div class="row">
        <i class="fas fa-envelope"></i>
        <input type="text" placeholder="Nama Karyawan" name="nama_karyawan" required />
      </div>
      <div class="row">
        <i class="fas fa-lock"></i>
        <input type="password" placeholder="Password" name="password" required />
      </div>
      <div class="row">
        <i class="fas fa-lock"></i>
        <input type="password" placeholder="Konfirmasi Password" name="konfirmasi_password" required />
      </div>

      <div class="row button">
        <input type="submit" value="Daftar" name="daftar" />
      </div>
      <div class="signup-link">Sudah punya akun? <a href="login.php">Login disini</a></div>
    </form>
  </div>
</body>

</html>

<?php
if (isset($_POST['daftar'])) {
  // Ambil input tanpa sanitasi
  $id = $_POST['id'];
  $email = $_POST['email'];
  $nomor_telepon = $_POST['nomor_telepon'];
  $nama_karyawan = $_POST['nama_karyawan'];
  $password = $_POST['password'];
  $konfirmasi_password = $_POST['konfirmasi_password'];

  // Validasi format email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
            Swal.fire({
              icon: 'error',
              title: 'Email tidak valid',
              text: 'Ganti email dan coba lagi',
            })
          </script>";
    exit;
  }

  // Validasi kesesuaian password
  if ($password === $konfirmasi_password) {
    // Cek apakah ID sudah ada di database
    $query_cek = "SELECT * FROM users WHERE id = '$id'";
    $result_cek = mysqli_query($conn, $query_cek);

    if (mysqli_num_rows($result_cek) > 0) {
      echo "<script>
              Swal.fire({
                icon: 'error',
                title: 'ID sudah digunakan',
                text: 'Ganti ID dan coba lagi',
              })
            </script>";
      exit;
    } else {
      // Hash password untuk keamanan
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Tambahkan data ke database
      $query_tambah = "INSERT INTO users (id, email, nomor_telepon, nama_karyawan, password) VALUES ('$id', '$email', '$nomor_telepon', '$nama_karyawan', '$hashed_password')";
      if (mysqli_query($conn, $query_tambah)) {
        echo "<script>
                Swal.fire({
                  icon: 'success',
                  title: 'Pendaftaran Berhasil',
                  text: 'Akun Anda berhasil dibuat!',
                });
              </script>";
      } else {
        echo "<script>
                Swal.fire({
                  icon: 'error',
                  title: 'Pendaftaran Gagal',
                  text: 'Terjadi kesalahan saat menyimpan data.',
                });
              </script>";
      }
    }
  } else {
    echo "<script>
            Swal.fire({
              icon: 'error',
              title: 'Pendaftaran Gagal',
              text: 'Password tidak cocok.',
            });
          </script>";
  }
}
?>
