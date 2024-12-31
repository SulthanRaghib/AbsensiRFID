<!DOCTYPE html>
<html lang="en">

<head>

    <?php include "header.php"; ?>
    <title>Rekapitulasi Absensi</title>
</head>

<body>
    <?php include "navbar.php"; ?>

    <!-- KONTEN -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Rekap Absen</h1>
            <!-- <a href="tambah_karyawan.php" class="btn btn-primary">Tambah Karyawan</a> -->
        </div>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">No. Kartu</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Jam Masuk</th>
                    <th scope="col">Jam Istirahat</th>
                    <th scope="col">Jam Kembali</th>
                    <th scope="col">Jam Pulang</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // konek ke database
                include "database.php";

                // baca tabel absensi dan relasi dengan tabel karyawan berdasasrkan no_kartu RFID untuk tanggal hari ini

                // Tetapkan zona waktu ke Asia/Jakarta
                date_default_timezone_set('Asia/Jakarta');

                // Ambil tanggal saat ini
                $tanggal = date('Y-m-d');


                // filter absensi berdasarkan tanggal saat ini
                $query = "SELECT b.nama, a.no_kartu, a.tanggal, a.jam_masuk, a.jam_istirahat, a.jam_kembali, a.jam_pulang FROM absensi a, karyawan b WHERE a.no_kartu = b.no_kartu AND a.tanggal = '$tanggal'";
                $result = mysqli_query($connect, $query);

                // cek apakah ada data
                if (mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($result)) {
                ?>
                        <tr>
                            <th scope="row"><?php echo $no++; ?></th>
                            <td><?php echo $data['no_kartu']; ?></td>
                            <td><?php echo $data['nama']; ?></td>
                            <td><?php echo $data['tanggal']; ?></td>
                            <td><?php echo $data['jam_masuk']; ?></td>
                            <td><?php echo $data['jam_istirahat']; ?></td>
                            <td><?php echo $data['jam_kembali']; ?></td>
                            <td><?php echo $data['jam_pulang']; ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='8'>Data tidak ditemukan</td></tr>";
                }
                ?>
            </tbody>
        </table>


    </div>

    <?php include "footer.php"; ?>
</body>

</html>