<?php 
include 'koneksi.php';
session_start();  // Mulai session untuk menyimpan ID pengguna
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Admin SehatWeb</title>
  <link rel="stylesheet" href="css/login.css" />

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
  <div class="wrapper">
    <div class="title"><span>Form Login</span></div>
    <form action="" method="post">
      <div class="row">
        <i class="fas fa-user"></i>
        <input type="text" placeholder="ID" name="id" required />
      </div>
      <div class="row">
        <i class="fas fa-lock"></i>
        <input type="password" placeholder="Password" name="password" required />
      </div>
      
      <div class="row button">
        <input type="submit" value="Login" name="login" />
      </div>
      <div class="signup-link">Belum punya akun? <a href="daftar.php">Daftar disini</a></div>
    </form>
  </div>
</body>

</html>

<?php
if (isset($_POST['login'])) {
  $id = htmlspecialchars($_POST['id']);
  $password = $_POST['password'];

  if (!empty($id) && !empty($password)) {
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();

      if (password_verify($password, $user['password'])) {
        // Simpan ID pengguna dalam session
        $_SESSION['user_id'] = $user['id'];
  

        echo "<script>
                Swal.fire({
                  title: 'Berhasil!',
                  text: 'Anda berhasil login',
                  icon: 'success',
                  confirmButtonColor: '#0e75ee'
                }).then((result) => {
                  if (result.isConfirmed) {
                    window.location.href = 'dashboard.php';
                  }
                });
              </script>";
      } else {
        echo "<script>
                Swal.fire({
                  title: 'Gagal!',
                  text: 'ID atau password salah',
                  icon: 'error',
                  confirmButtonColor: '#0e75ee'
                });
              </script>";
      }
    } else {
      echo "<script>
              Swal.fire({
                title: 'Gagal!',
                text: 'ID tidak ditemukan',
                icon: 'error',
                confirmButtonColor: '#0e75ee'
              });
            </script>";
    }
    $stmt->close();
  } else {
    echo "<script>
            Swal.fire({
              title: 'Peringatan!',
              text: 'Mohon isi semua kolom',
              icon: 'warning',
              confirmButtonColor: '#0e75ee'
            });
          </script>";
  }
}
?>
