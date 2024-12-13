<?php
include 'koneksi.php';

// Ambil ID produk dari parameter GET
$produk_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil harga produk dari tabel produk
$query = "SELECT harga FROM produk WHERE id = $produk_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $produk = mysqli_fetch_assoc($result);
    echo json_encode(['harga' => $produk['harga']]);
} else {
    echo json_encode(['harga' => 0]);
}