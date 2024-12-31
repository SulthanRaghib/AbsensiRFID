<!DOCTYPE html>
<html lang="en">

<head>

    <?php include "header.php"; ?>
    <title>Tambah Data Karyawan</title>
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

        // baca ID yang akan di edit
        $id = $_GET['id'];

        // baca data dari tabel karyawan berdasarkan id
        $query = "SELECT * FROM karyawan WHERE id = $id";
        $hasil = mysqli_fetch_array(mysqli_query($connect, $query));

        // cek apakah form sudah di submit
        if (isset($_POST['btnSimpan'])) {
            // ambil data dari form
            $no_kartu = $_POST['no_kartu'];
            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];

            // simpan ke database
            $query = "UPDATE karyawan SET no_kartu = '$no_kartu', nama = '$nama', alamat = '$alamat' WHERE id = $id";
            $result = mysqli_query($connect, $query);

            // cek apakah proses simpan ke database berhasil
            if ($result) {
                echo '
                <script>
                    alert("Data berhasil disimpan");
                    location.replace("index.php");
                </script>
                ';
            } else {
                echo '
                <script>
                    alert("Data gagal disimpan");
                    location.replace("edit_karyawan.php?id=' . $id . '");
                </script>
                ';
            }
        }
        ?>

        <form method="POST">
            <div class="mb-3">
                <label for="no_kartu" class="form-label
                ">No. Kartu</label>
                <input type="text" class="form-control" id="no_kartu" name="no_kartu" placeholder="Masukkan No. Kartu RFID" value="<?php echo $hasil['no_kartu']; ?>">
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Karyawan" value="<?php echo $hasil['nama']; ?>">
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat Karyawan"><?php echo $hasil['alamat']; ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary" name="btnSimpan">Simpan</button>
        </form>
    </div>

    <?php include "footer.php"; ?>
</body>

</html>