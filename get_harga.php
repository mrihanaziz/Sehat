<?php
include 'koneksi.php';

if (isset($_GET['barang_id'])) {
    $barang_id = mysqli_real_escape_string($conn, $_GET['barang_id']);
    $query = "SELECT harga FROM produk WHERE id = '$barang_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode(['harga' => $data['harga']]);
    } else {
        echo json_encode(['harga' => 0]);
    }
}
?>