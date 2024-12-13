<?php
include 'koneksi.php';

// Query untuk menghitung jumlah pesanan selesai
$query_pesanan_selesai = "SELECT COUNT(*) AS jumlah FROM pesanan WHERE status_dikirim = 'diterima'";
$result_pesanan_selesai = mysqli_query($conn, $query_pesanan_selesai);
$row_pesanan_selesai = mysqli_fetch_assoc($result_pesanan_selesai);
$jumlah_pesanan_selesai = $row_pesanan_selesai['jumlah'];

// Query untuk menghitung jumlah pengunjung dari tabel users
$query_pengunjung = "SELECT COUNT(*) AS jumlah FROM users";
$result_pengunjung = mysqli_query($conn, $query_pengunjung);
$row_pengunjung = mysqli_fetch_assoc($result_pengunjung);
$jumlah_pengunjung = $row_pengunjung['jumlah'];

// Query untuk menghitung total penjualan
$query_total_penjualan = "SELECT SUM(hasil) AS total FROM keuangan";
$result_total_penjualan = mysqli_query($conn, $query_total_penjualan);
$row_total_penjualan = mysqli_fetch_assoc($result_total_penjualan);
$total_penjualan = $row_total_penjualan['total'];

// Query untuk mendapatkan data status terbaru
$query_status_pesanan = "
    SELECT
        (SELECT COUNT(*) FROM pesanan WHERE status_dikirim = 'belum_dikirim') AS pesanan_baru,
        (SELECT COUNT(*) FROM pesanan WHERE status_dikirim = 'dikirim') AS pesanan_dikirim,
        (SELECT COUNT(*) FROM pesanan WHERE status_dikirim = 'dibatalkan') AS pembatalan,
        (SELECT COUNT(*) FROM obat WHERE stok = 0) AS stok_habis
";

$result_status_pesanan = mysqli_query($conn, $query_status_pesanan);
$status_pesanan = mysqli_fetch_assoc($result_status_pesanan);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">

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
            <li class="active"><a href="dashboard.php"><i class="fas fa-home"></i><span>Beranda</span></a></li>
            <li><a href="produk.php"><i class="fas fa-box"></i><span>Produk</span></a></li>
            <li><a href="pesanan.php"><i class="fas fa-shopping-cart"></i><span>Pesanan</span></a></li>
            <li><a href="keuangan.php"><i class="fas fa-wallet"></i><span>Keuangan</span></a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i><span>Profile</span></a></li>
            <li><a href="login.php" id="logout-link"><i class="fas fa-sign-out-alt" style="color: #ff0000;"></i><span
                        style="color: #ff0000;">Logout</span></a></li>
        </ul>
    </nav>

    <main>
        <div class="topnav"><a href="#">Dashboard</a></div>

        <ul class="box-info">
            <li>
                <svg class="bx" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px"
                    fill="#0e75ee">
                    <path
                        d="M616-192 447-362l51-51 118 119 221-221 51 51-272 272Zm200-384h-72v-168h-72v120H288v-120h-72v528h216v72H216q-29.7 0-50.85-21.15Q144-186.3 144-216v-528q0-29.7 21.15-50.85Q186.3-816 216-816h171q8-31 33.5-51.5T480-888q34 0 59.5 20.5T573-816h171q29.7 0 50.85 21.15Q816-773.7 816-744v168ZM479.79-744q15.21 0 25.71-10.29t10.5-25.5q0-15.21-10.29-25.71t-25.5-10.5q-15.21 0-25.71 10.29t-10.5 25.5q0 15.21 10.29 25.71t25.5 10.5Z">
                    </path>
                </svg>
                <span class="text">
                    <h3 id="pesanan-selesai"><?= $jumlah_pesanan_selesai ?></h3>
                    <p>Pesanan Selesai</p>
                </span>
            </li>
            <li>
                <svg class="bx" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px"
                    fill="#0e75ee">
                    <path
                        d="M96-192v-92q0-25.78 12.5-47.39T143-366q54-32 114.5-49T384-432q66 0 126.5 17T625-366q22 13 34.5 34.61T672-284v92H96Zm648 0v-92q0-42-19.5-78T672-421q39 8 75.5 21.5T817-366q22 13 34.5 34.67Q864-309.65 864-284v92H744ZM384-480q-60 0-102-42t-42-102q0-60 42-102t102-42q60 0 102 42t42 102q0 60-42 102t-102 42Zm336-144q0 60-42 102t-102 42q-8 0-15-.5t-15-2.5q25-29 39.5-64.5T600-624q0-41-14.5-76.5T546-765q8-2 15-2.5t15-.5q60 0 102 42t42 102ZM168-264h432v-20q0-6.47-3.03-11.76-3.02-5.3-7.97-8.24-47-27-99-41.5T384-360q-54 0-106 14t-99 42q-4.95 2.83-7.98 7.91-3.02 5.09-3.02 12V-264Zm216.21-288Q414-552 435-573.21t21-51Q456-654 434.79-675t-51-21Q354-696 333-674.79t-21 51Q312-594 333.21-573t51 21ZM384-264Zm0-360Z">
                    </path>
                </svg>
                <span class="text">
                    <h3 id="jumlah-pengunjung"><?= $jumlah_pengunjung ?></h3>
                    <p>Pengunjung</p>
                </span>
            </li>
            <li>
                <svg class="bx" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px"
                    fill="#0e75ee">
                    <path
                        d="M444-144v-80q-51-11-87.5-46T305-357l74-30q8 36 40.5 64.5T487-294q39 0 64-20t25-52q0-30-22.5-50T474-456q-78-28-114-61.5T324-604q0-50 32.5-86t87.5-47v-79h72v79q72 12 96.5 55t25.5 45l-70 29q-8-26-32-43t-53-17q-35 0-58 18t-23 44q0 26 25 44.5t93 41.5q70 23 102 60t32 94q0 57-37 96t-101 49v77h-72Z">
                    </path>
                </svg>
                <span class="text">
                    <h3 id="total-penjualan">Rp <?= number_format($total_penjualan, 0, ',', '.') ?></h3>
                    <p>Total Penjualan</p>
                </span>
            </li>
        </ul>


        <div class="produk-dashboard">
            <h1>Status Terbaru</h1>
            <p>Segera kirim jika ada pesanan baru dan pastikan stok terupdate</p>
            <div class="container-pengiriman">
                <div class="status-pesanan">
                    <span id="pesanan-baru"><?= $status_pesanan['pesanan_baru'] ?></span>
                    <label for="span">Pesanan Baru</label>
                </div>
                <div class="status-pesanan">
                    <span id="pesanan-dikirim"><?= $status_pesanan['pesanan_dikirim'] ?></span>
                    <label for="span">Dikirim</label>
                </div>
                <div class="status-pesanan">
                    <span id="pembatalan"><?= $status_pesanan['pembatalan'] ?></span>
                    <label for="span">Pembatalan</label>
                </div>
                <div class="status-pesanan">
                    <span id="stok-habis"><?= $status_pesanan['stok_habis'] ?></span>
                    <label for="span">Stok habis</label>
                </div>
            </div>
        </div>
    </main>

    <script>
    // Auto-refresh data
    setInterval(() => {
        fetch('get_dashboard_data.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('pesanan-selesai').textContent = data.pesanan_selesai;
                document.getElementById('jumlah-pengunjung').textContent = data.pengunjung;
                document.getElementById('total-penjualan').textContent =
                    `Rp ${new Intl.NumberFormat('id-ID').format(data.total_penjualan)}`;
                document.getElementById('pesanan-baru').textContent = data.pesanan_baru;
                document.getElementById('pesanan-dikirim').textContent = data.pesanan_dikirim;
                document.getElementById('pembatalan').textContent = data.pembatalan;
                document.getElementById('stok-habis').textContent = data.stok_habis;
            });
    }, 5000);

    // Logout confirmation
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