<?php
session_start();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Member'){
    }else{
        echo "<script>alert('Anda tidak memiliki Hak Akses !');window.location='index.php';</script>"; 
    }
    ;

} else {
    echo "<script>alert('Anda tidak memiliki Hak Akses !');window.location='index.php';</script>";
};
require 'koneksi.php';


$query = $_SESSION['username'];

if ($query != '') {
    $read_sql = "SELECT * FROM transaksi WHERE username LIKE '%" . $query . "%' ";
} 
$result = mysqli_query($conn, $read_sql);

$transaksi = [];
while ($row = mysqli_fetch_assoc($result)) {
    $transaksi[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang IT</title>
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
    <link href="gambar/logo1.png" rel="shorcut icon">
</head>

<body>
    <section class="dash">
        <div class="navigation">
            <ul>
                <li class="list ">
                    <a href="profile.php">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <span class="tittle">Profile</span>
                    </a>
                </li>
                <li class="list active">
                    <a href="riwayat.php">
                        <span class="icon"><i class="fas fa-file"></i></span>
                        <span class="tittle">Riwayat Transaksi</span>
                    </a>
                <li class="list">
                    <a href="logout.php">
                        <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span class="tittle">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="latar">
            <div class="box">
                <div class="tbl">
                    <h1>Riwayat Transaksi ...</h1><br>

                    <table class="table">
                        <thead>

                            <th>NO</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Paket</th>
                            <th>Tanggal</th>


                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <!-- mulai perulangan untuk menampilkan data transaksi -->
                            <?php foreach ($transaksi as $dbtransaksi) : ?>
                            <tr>
                                <td data-label="NO"><?= $i; ?></td>
                                <td data-label="Nama"><?= $dbtransaksi["nama"]; ?></td>
                                <td data-tabel="Email"><?= $dbtransaksi["email"]; ?></td>
                                <td data-label="Paket"> <?= $dbtransaksi["paket"]; ?></td>
                                <td data-label="Tanggal"><?= $dbtransaksi["tanggal"]; ?></td>


                            </tr>

                            <?php $i++; ?>
                            <?php endforeach; ?>
                            <!-- akhir perulangan untuk menampilkan data transaksi -->
                    </table>

                    <form action="" method="POST">
                        <a href="index.php" class="btn-rwt">Home</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>