<?php 
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'sehatweb';
$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    die('Gagal terhubung :'. mysqli_connect_error());
}
mysqli_select_db($conn, $database);
?>