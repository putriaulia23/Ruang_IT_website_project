<?php
session_start();

// ini perintah agar member saja yg bisa nampilkan profile
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Member') {
    } else {
        //ini jika bukan member tp lgsng ke profile.php gak bisa
        echo "<script>alert('Anda tidak memiliki Hak Akses !');window.location='index.php';</script>";
    };
} else {
    //ini jika belum login tapi lgsng ke profile.php gak bisa
    echo "<script>alert('Anda tidak memiliki Hak Akses !');window.location='index.php';</script>";
};
require 'koneksi.php';

//editprofile
$username = $_SESSION['username'];
// untuk nampilkan data dari tabel login
$select_sql = mysqli_query($conn, "SELECT * FROM login WHERE username = '$username'");
$login = [];
while ($row = mysqli_fetch_assoc($select_sql)) {
    $login[] = $row;
}

//edit profile nama
if (isset($_POST['nama'])) {
    $nama = htmlspecialchars($_POST['nama']);
    // untuk update nama pengguna pada tabel login
    $sql = "UPDATE login SET nama = '$nama' WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script> alert ('data berhasil diupdate'); document.location.href = 'profile.php' ;</script>";
    } else {
        echo "<script> alert('data gagal diupdate');</script>";
    }
}

//edit profile username
if (isset($_POST['email'])) {
    $email = htmlspecialchars($_POST['email']);
    $sql = "UPDATE login SET Email = '$email' WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script> alert ('data berhasil diupdate'); document.location.href = 'profile.php' ;</script>";
    } else {
        echo "<script> alert('data gagal diupdate');</script>";
    }
}

//edit gambar profile 
if (isset($_POST['Input'])) {
    $gambarr = htmlspecialchars($_FILES["gambar"]['name']);      //untuk mendapatkan nama file yg diupload

    // ini untuk ngatur foto yg dapat dimasukkan hanya png dan jpg
    if ($gambarr != "") {
        $ektensi_gambar = ['png', 'jpg'];
        $x = explode('.', $gambarr);        //untuk penamaan di databasenya
        $ekstensi = strtolower(end($x));    //ubah semua karakter menjadi huruf kecil (nama file gambarnya)
        $file_tmp = $_FILES['gambar']['tmp_name'];   //untuk mendapatkan temporary file yg diupload  
        $angka_acak = rand(1, 999);         //untuk penamaan file gambar dengan menambahkan angka agar tidak sama nama filenya
        $gambar_baru = $angka_acak . '-' . $gambarr;        //untuk format nama file gambar yg akan tersimpan

        if (in_array($ekstensi, $ektensi_gambar) === true) {
            //fungsi move_uploaded_file untuk mengupload_file untuk memindahkan file yang diupload tadi dr temporary file menuju ke folder file
            move_uploaded_file($file_tmp, 'gambar/' . $gambar_baru);

            $query = "UPDATE login SET gambar = '$gambar_baru' ";
            $query .= "WHERE username = '$username'";
            $result = mysqli_query($conn, $query);

            
            if (!$result) {
                die("Query Error : " . mysqli_errno($conn) . " - " . mysqli_error($conn));          //untuk menampilkan erornya dimana
            } else {
                echo "<script>alert('Data Berhasil Diinputkan !');window.location='profile.php';</script>";
            }
        } else {
            echo "<script>alert('Ekstensi Gambar Hanya Bisa jpg dan png!');window.location='profile.php';</script>";
        }
    } else {
        echo "<script> alert('data gagal diupdate');</script>";         //untuk menampilkan alert kalau gagal diupdate atau memastikan kodingan diatas bener
    }
}


//ubah pass
if (isset($_POST["pass"])) {
    //fungsinya untuk mencegah sql injection
    $password = mysqli_real_escape_string($conn, $_POST["newpassword"]);
    $cpassword = mysqli_real_escape_string($conn, $_POST["cpassword"]);

    if ($password === $cpassword) {
        //enkripsi password
        $password = password_hash($password, PASSWORD_DEFAULT);
        //ganti pass
        $query = "UPDATE login SET password ='$password'";
        $query .= "WHERE username = '$username'";               //untuk merapikan syntax sql diatasnya supaya gak panjang ke samping
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query Error : " . mysqli_errno($conn) . " - " . mysqli_error($conn));
        } else {
            echo "<script>alert('Password Berhasil Diganti !');window.location='profile.php';</script>";
        }
    } else {
        echo "<script>alert('Password tidak sama !');window.location='profile.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Profile</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
    <script src="jquery.js"></script>
    <link href="gambar/logo1.png" rel="shorcut icon">
</head>

<body>
    <!-- SIDEBAR Member -->
    <section class="dash">

        <!-- NAVIGASI PROFILE -->
        <div class="navigation">
            <ul>
                <li class="list active">
                    <a href="profile.php">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <span class="tittle">Profile</span>
                    </a>
                </li>
                <li class="list ">
                    <a href="riwayat.php">
                        <span class="icon"><i class="fas fa-file"></i></span>
                        <span class="tittle">Riwayat Transaksi</span>
                    </a>
                </li>
                <li class="list">
                    <a href="logout.php">
                        <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span class="tittle">Logout</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- DATA PROFILE -->
        <div class="wrapper">
            <h2>Profile</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <?php foreach ($login as $user) : ?>
                <div class="input-box">
                    <img src="gambar/<?= $user["gambar"]; ?>">
                    <a href="#" id="bgambar" class="ubah-foto">Ubah</a>
                </div>
                <div class="inputBox">
                    <p>Nama :</p>
                    <span class="tampil"><?= $user['nama']; ?> <a href="#" id="bnama" class="ubah">Ubah</a> </span>

                </div>

                <div class="inputBox">
                    <p>Email :</p>
                    <span class="tampil"><?= $user["Email"]; ?> <a href="#" id="bemail" class="ubah">Ubah</a> </span>

                </div>

                <div class="inputBox">
                    <p>Username :</p>
                    <span class="tampil"><?= $user["username"]; ?> </span>
                </div>

                <button class="btn-home-2"><a href="index.php">Home</a></button>
                <button class="btn-reset"><a href="#" id="bpass" class="ubah">Reset Pass</a></button>

                <?php endforeach ?>
            </form>
        </div>

        <!-- Card Nama -->
        <div id="cardnama" class="cardm">
            <div class="cardm-content">
                <span class="close" id="closenama">&times;</span>
                <h2>Ubah Nama Profile</h2>
                <form action="profile.php" method="POST">
                    <input type="text" name="nama" id="nama" value="<?= $user['nama']; ?>">
                    <button type="submit" class="btn-actn">Update</button>
                </form>
            </div>
        </div>

        <!-- Card Gambar -->
        <div id="cardgambar" class="cardm">
            <div class="cardm-content">
                <span class="close" id="closegambar">&times;</span>
                <h2>Ubah Gambar</h2>
                <form action="profile.php" method="POST" enctype="multipart/form-data">
                    <img src="gambar/<?= $user['gambar']; ?>">
                    <input type="file" name="gambar" id="foto" required><br>
                    <input type="submit" value="Input Data" name="Input" class="btn-act">
                </form>
            </div>
        </div>

        <!-- Card Email -->
        <div id="cardemail" class="cardm">
            <div class="cardm-content">
                <span class="close" id="closeemail">&times;</span>
                <h2>Ubah Email</h2>
                <form action="profile.php" method="POST">
                    <input type="text" name="email" id="email" value="<?= $user['Email']; ?>" required>
                    <button type="submit" class="btn-actn">Update</button>
                </form>
            </div>
        </div>

        <!-- Card ganti pass -->
        <div id="cardpass" class="cardm">
            <div class="cardm-content">
                <span class="close" id="closepass">&times;</span>
                <h2>Ubah Pass</h2>
                <form action="profile.php" method="POST">
                    <input type="password" name="newpassword" id="newpassword" placeholder="Input Password Baru"
                        required>
                    <input type="password" name="cpassword" id="cpassword" placeholder="Input Kembali Password Baru"
                        required>
                    <button type="submit" name="pass" class="btn-actn">Update</button>
                </form>

            </div>
        </div>
    </section>
    <script src="profile.js"></script>
</body>

</html>