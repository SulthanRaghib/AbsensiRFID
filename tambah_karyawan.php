<!DOCTYPE html>
<html lang="en">

<head>

    <?php include "header.php"; ?>
    <title>Tambah Data Karyawan</title>

    <!-- Pembaca No Kartu Otomatis -->
    <script>
        $(document).ready(function() {
            setInterval(function() {
                $("#norfid").load("no_kartu.php");
            }, 0);
        })
    </script>
</head>

<body>
    <?php include "navbar.php"; ?>

    <!-- KONTEN -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Tambah Data Karyawan</h1>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </div>

        <!-- Proses Simpan ke Database -->
        <?php
        include "database.php";

        // cek apakah form sudah di submit
        if (isset($_POST['btnSimpan'])) {
            // ambil data dari form
            $no_kartu = $_POST['no_kartu'];
            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];

            // Cek apakah no_kartu sudah ada di database
            $checkQuery = "SELECT * FROM karyawan WHERE no_kartu = '$no_kartu'";
            $checkResult = mysqli_query($connect, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                // Jika no_kartu sudah ada
                echo '
        <script>
            alert("Nomor kartu RFID sudah digunakan!");
            location.replace("tambah_karyawan.php");
        </script>
        ';
            } else {
                // Jika no_kartu belum ada, lanjutkan proses penyimpanan
                $query = "INSERT INTO karyawan (no_kartu, nama, alamat) VALUES ('$no_kartu', '$nama', '$alamat')";
                $result = mysqli_query($connect, $query);

                // cek apakah proses simpan ke database berhasil
                if ($result) {
                    echo '
            <script>
                alert("Data berhasil disimpan");
                $("#getUID").val(""); 
                location.replace("index.php");
            </script>
            ';
                } else {
                    echo '
            <script>
                alert("Data gagal disimpan");
                location.replace("tambah_karyawan.php");
            </script>
            ';
                }
            }
        }

        // kosongkan tabel tmprfid
        mysqli_query($connect, "DELETE FROM tmprfid");
        ?>


        <form method="POST">

            <div id="norfid"></div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Karyawan">
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat Karyawan"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" name="btnSimpan">Simpan</button>
        </form>
    </div>

    <?php include "footer.php"; ?>
</body>

</html>