<?php
session_start();
include 'function.php';

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Member') {
    } else {
        echo "<script>alert('Anda tidak memiliki Hak Akses !');window.location='index.php';</script>";
    };
} else {
    echo "<script>alert('Anda tidak memiliki Hak Akses !');window.location='index.php';</script>";
};
require 'koneksi.php';

$id = $_GET["id"];      //get diambil dari tombol kursus

$select_sql = "SELECT * FROM paket WHERE id_paket = '$id'";
$result = mysqli_query($conn, $select_sql);

$paket = [];

while ($row = mysqli_fetch_assoc($result)) {
    $paket[] = $row;
}

$paket = $paket[0];                 //dibaca dari array ke 0

$username = $_SESSION['username'];

$select_sql = mysqli_query($conn, "SELECT * FROM login WHERE username = '$username'");
$login = [];
while ($row = mysqli_fetch_assoc($select_sql)) {
    $login[] = $row;
}

if (isset($_POST["Input"])) {
    $username = htmlspecialchars($_POST["username"]);
    $nama = htmlspecialchars($_POST["nama"]);
    $email = htmlspecialchars($_POST["email"]);
    $paket = htmlspecialchars($_POST["nama_paket"]);
    $harga = htmlspecialchars($_POST["harga"]);
    $tanggal = htmlspecialchars(date("Y-m-d "));        //untuk menampilkan tanggal hari ini secara otomatis

    // perintah untuk menambahkan data ke tabel transaksi pada database
    $create_sql = "INSERT INTO transaksi VALUES ('','$username','$nama','$email','$paket','$harga','$tanggal')";
    $result = mysqli_query($conn, $create_sql);

    if ($result) {
        echo "<script>
            alert('Transaksi Berhasil!');
            document.location.href = 'hasil.php?nama=$nama&email=$email&paket=$paket&harga=$harga&tanggal=$tanggal';
        </script>";
    } else {
        echo "<script>
            alert('Transaksi gagal!');
            document.location.href = 'transaksi.php';
        </script>";
    }
}
?>

<html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beli Paket</title>
    <link rel="stylesheet" href="style2.css">
    <link href="gambar/logo1.png" rel="shorcut icon">
</head>

<body>
    <!-- FORM TRANSAKSI -->
    <section class="latar-2">
        <div class="container-2">
            <div class="content-2">
                <h1>Transaksi</h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <!-- mulai perulangan untuk menampilkan data pelanggan pada tabel login -->
                    <!-- jadi username, nama, dan email akan otomatis tampil jadi gak perlu isi lagi -->
                    <?php foreach ($login as $user) : ?>

                    <label class="lb">Username</label><br>
                    <input type="text" placeholder="Masukkan Username Anda" name="username" class="inp-bayar" readonly
                        value="<?= $user['username']; ?>"><br><br>

                    <label class="lb">Nama</label><br>
                    <input type="text" placeholder="Masukkan Nama Anda" name="nama" class="inp-bayar" readonly
                        value="<?= $user['nama']; ?>"><br><br>

                    <label class="lb">Email </label><br>
                    <input type="text" placeholder="Masukkan Email Anda" name="email" class="inp-bayar" readonly
                        value="<?= $user['Email']; ?>"><br><br>
                    <?php endforeach ?>
                    <!-- akhir perulangan data pelanggan -->

                    <label class="lb">Nama Paket</label><br>
                    <input type="text" placeholder="Masukkan Nama Paket" name="nama_paket" class="inp-bayar" readonly
                        value="<?= $paket["nama_paket"]; ?>"><br><br>
                    <input type="hidden" name="id_paket" value="<?= $paket["id_paket"]; ?>">
                    <!-- fungsi hidden disini supaya data paket gak bisa diubah-ubah -->

                    <label class="lb">Harga</label><br>
                    <input type="text" placeholder="Masukkan Harga Paket" name="harga" class="inp-bayar" readonly
                        value="<?= buatRp($paket["harga"]); ?>"><br><br>
                    <input type="hidden" name="id_paket" value="<?= $paket["id_paket"]; ?>">

                    <label class="lb">Tanggal</label><br>
                    <input type="text" name="tanggal" class="inp-bayar" readonly value="<?=date('l, d-m-Y')?>"><br><br>
                    <button class="btn-bayar" type="submit" value="Konfirmasi" name="Input">Konfirmasi</button>
                </form>
                <button class="btn-bayar-2"><a href="kursus.php">Back</a></button>
            </div>
        </div>
    </section>


</body>




</html>