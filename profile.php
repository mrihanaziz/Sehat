<?php
include "koneksi.php";
session_start();
// Pesan feedback
$message = "";

// Simpan atau edit data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_karyawan = $_POST['id_karyawan'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $foto_profil = isset($_FILES['foto_profil']) ? $_FILES['foto_profil'] : null; // Tetap pakai foto_profil lama jika tidak ada file baru

    // Proses upload file foto profil
    if ($foto_profil && $foto_profil['error'] === 0) {
        // Folder tujuan
        $target_dir = "img/";
        $target_file = $target_dir . time() . '-' . basename($foto_profil["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi file
        if (getimagesize($foto_profil["tmp_name"]) === false) {
            $message = "File yang di-upload bukan gambar.";
        } elseif ($foto_profil["size"] > 1048576) {
            $message = "File terlalu besar, maksimal 1MB.";
        } elseif (!in_array($imageFileType, ["jpg", "jpeg", "png"])) {
            $message = "Hanya file JPG, JPEG, dan PNG yang diizinkan.";
        } else {
            // Upload file
            if (move_uploaded_file($foto_profil["tmp_name"], $target_file)) {
                // Cek apakah ada gambar sebelumnya
                $query_check = "SELECT foto_profil FROM users WHERE id = '$id_karyawan'";
                $result_check = mysqli_query($conn, $query_check);
                if ($result_check && mysqli_num_rows($result_check) > 0) {
                    $row = mysqli_fetch_assoc($result_check);
                    $old_image = $row['foto_profil'];

                    // Hapus gambar lama hanya setelah gambar baru berhasil diupload
                    if (file_exists($old_image) && $old_image != 'img/default-profile.png') {
                        unlink($old_image);
                    }
                }

                // Simpan jalur file ke database
                $foto_profil = $target_file;
            } else {
                $message = "Terjadi kesalahan saat mengunggah file.";
            }
        }
    } else {
        // Tetap menggunakan foto profil lama jika tidak ada gambar baru
        $query_check = "SELECT foto_profil FROM users WHERE id = '$id_karyawan'";
        $result_check = mysqli_query($conn, $query_check);
        if ($result_check && mysqli_num_rows($result_check) > 0) {
            $row = mysqli_fetch_assoc($result_check);
            $foto_profil = $row['foto_profil'];
        }
    }

    if (empty($message)) {
        // Periksa apakah data sudah ada
        $sql_check = "SELECT * FROM users WHERE id = '$id_karyawan'";
        $result = $conn->query($sql_check);

        if ($result->num_rows > 0) {
            // Update data
            $sql_update = "UPDATE users SET 
                    nama_karyawan = '$nama_karyawan', 
                    email = '$email', 
                    nomor_telepon = '$nomor_telepon'";
            if ($foto_profil) {
                $sql_update .= ", foto_profil = '$foto_profil'";
            }
            $sql_update .= " WHERE id = '$id_karyawan'";

            if ($conn->query($sql_update)) {
                $message = "Data berhasil diperbarui.";
                // Redirect untuk merefresh halaman
                header("Location: profile.php");
            } else {
                $message = "Gagal memperbarui data: " . $conn->error;
            }
        } else {
            $message = "Data tidak ditemukan.";
        }
    }
}

if (isset($_SESSION['user_id'])) {
    // Ambil ID pengguna dari session
    $user_id = $_SESSION['user_id'];

    // Query untuk mengambil email pengguna berdasarkan ID
    $query = "SELECT email, nama_karyawan, nomor_telepon, foto_profil FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Ambil data pengguna
        $data_user = mysqli_fetch_assoc($result);
        // Simpan email di session jika diperlukan (opsional)
        $_SESSION['user_email'] = $data_user['email'];
    } else {
        $data_user = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="css/profile.css" />

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
            <li>
                <a href="keuangan.php">
                    <i class="fas fa-wallet"></i>
                    <span>Keuangan</span>
                </a>
            </li>
            <li class="active">
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
            <a href="#">Profile</a>
        </div>

        <div class="container">
            <form action="profile.php" method="post" enctype="multipart/form-data">
                <div class="profile-picture-container">
                    <div class="profile-picture">
                        <!-- Menampilkan gambar profil jika ada -->
                        <img src="<?= isset($data_user['foto_profil']) && !empty($data_user['foto_profil']) ? $data_user['foto_profil'] : 'img/default-profile.png'; ?>"
                            alt="Foto Profil" id="preview">
                    </div>
                    <div class="upload-section">
                        <label for="foto_profil" class="upload-button">Pilih Gambar</label>
                        <input type="file" id="foto_profil" name="foto_profil" accept="image/jpeg, image/png"
                            onchange="previewImage(event)">
                        <p class="instructions">Ukuran gambar: maks. 1 MB<br>Format gambar: .JPEG, .PNG</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="id_karyawan" class="col-sm-2">ID</label>
                    <div class="col-sm-10">
                        <input type="text" name="id_karyawan" id="id_karyawan"
                            value="<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="nama_karyawan" class="col-sm-2">Nama Karyawan</label>
                    <div class="col-sm-10">
                        <input type="text" name="nama_karyawan" id="nama_karyawan" placeholder="Nama lengkap karyawan"
                            value="<?= $data_user['nama_karyawan'] ?? ''; ?>" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="email" class="col-sm-2">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" id="email" placeholder="Email karyawan"
                            value="<?= $data_user['email'] ?? ''; ?>" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="nomor_telepon" class="col-sm-2">Nomor Telepon</label>
                    <div class="col-sm-10">
                        <input type="text" name="nomor_telepon" id="nomor_telepon" placeholder="Nomor telepon karyawan"
                            value="<?= $data_user['nomor_telepon'] ?? ''; ?>" required>
                    </div>
                </div>

                

                <div class="mb-3 row">
                    <button type="submit" value="edit" class="btn btn-primary">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
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

    function previewImage(event) {
        const reader = new FileReader();
        const preview = document.getElementById('preview');

        reader.onload = function() {
            preview.src = reader.result;
        };

        reader.readAsDataURL(event.target.files[0]);
    }
    </script>
</body>

</html>