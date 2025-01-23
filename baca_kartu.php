<?php
include "database.php";

// baca tabel status untuk mode absen
$query = "SELECT * FROM status";
$result = mysqli_query($connect, $query);

// Pastikan hasil query tidak kosong
if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_array($result);
    $mode_absen = $data['mode'];
} else {
    $mode_absen = null; // Atur nilai default
}

// mode absen
$mode = "";
if ($mode_absen == "1") {
    $mode = "Masuk";
} else if ($mode_absen == "2") {
    $mode = "Istirahat";
} else if ($mode_absen == "3") {
    $mode = "Kembali";
} else if ($mode_absen == "4") {
    $mode = "Pulang";
}

// baca isi tabel tmprfid
$baca_kartu = "SELECT * FROM tmprfid";
$hasil_query = mysqli_query($connect, $baca_kartu);

// Pastikan hasil query tidak kosong
if ($hasil_query && mysqli_num_rows($hasil_query) > 0) {
    $hasil = mysqli_fetch_array($hasil_query);
    $no_kartu = $hasil['no_kartu'];
} else {
    $no_kartu = ""; // Atur nilai default
}
?>

<div class="container-fluid text-center">
    <?php if ($no_kartu == "") { ?>
        <h1>Absen : <?php echo $mode; ?></h1>
        <h3>Scan Kartu RFID</h3>
        <p>Tempelkan kartu RFID Anda</p>

        <div class="d-flex flex-column justify-content-center align-items-center gap-4">
            <img src="assets/images/rfid.png" alt="img-scan" width="20%">
            <img src="assets/images/animasi2.gif" alt="progres-bar">
        </div>

        <!-- Ganti mode -->
        <div class="d-flex justify-content-center gap-4 mt-4">
            <a href="ganti_mode.php?mode=1" class="btn btn-primary">Masuk</a>
            <a href="ganti_mode.php?mode=2" class="btn btn-warning">Istirahat</a>
            <a href="ganti_mode.php?mode=3" class="btn btn-info">Kembali</a>
            <a href="ganti_mode.php?mode=4" class="btn btn-danger">Pulang</a>
        </div>


    <?php } else {
        // cek apakah no kartu ada di tabel karyawan
        $cari_karyawan = mysqli_query($connect, "SELECT * FROM karyawan WHERE no_kartu = '$no_kartu'");
        $jumlah_data = mysqli_num_rows($cari_karyawan);

        if ($jumlah_data == 0)
            echo "<h1>Maaf, No Kartu Tidak Terdaftar</h1>";
        else {
            // ambil nama karyawan
            $data_karyawan = mysqli_fetch_array($cari_karyawan);
            $nama = $data_karyawan['nama'];

            // tanggal dan waktu saat ini
            date_default_timezone_set('Asia/Jakarta');
            $tanggal = date('Y-m-d');
            $jam = date('H:i:s');

            // cek apakah data absen sudah ada, apabila belum ada maka absen masuk
            $cari_absen = mysqli_query($connect, "SELECT * FROM absensi WHERE no_kartu = '$no_kartu' AND tanggal = '$tanggal'");
            $jumlah_absen = mysqli_num_rows($cari_absen);

            // hitung jumlah absen
            if ($jumlah_absen == 0) {
                echo "
                <script>
                    Swal.fire({
                        title: 'Selamat Datang, Anda Berhasil Absen Masuk',
                        text: '$nama',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                </script>
                ";
                mysqli_query($connect, "INSERT INTO absensi (no_kartu, tanggal, jam_masuk) VALUES ('$no_kartu', '$tanggal', '$jam')");
            } else {
                // update sesuai pilihan mode absen
                if ($mode_absen == 1) {
                    echo "
                    <script>
                        Swal.fire({
                            title: 'Selamat Pagi, Anda Sudah Absen Masuk',
                            text: '$nama',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    </script>
                    ";
                } else if ($mode_absen == 2) {
                    echo "
                    <script>
                        Swal.fire({
                            title: 'Selamat Istirahat',
                            text: '$nama',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    </script>
                    ";
                    mysqli_query($connect, "UPDATE absensi SET jam_istirahat = '$jam' WHERE no_kartu = '$no_kartu' AND tanggal = '$tanggal'");
                } else if ($mode_absen == 3) {
                    echo "
                    <script>
                        Swal.fire({
                            title: 'Selamat Kembali dari Istirahat',
                            text: '$nama',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        </script>
                    ";
                    mysqli_query($connect, "UPDATE absensi SET jam_kembali = '$jam' WHERE no_kartu = '$no_kartu' AND tanggal = '$tanggal'");
                } else if ($mode_absen == 4) {
                    echo "
                    <script>
                        Swal.fire({
                            title: 'Selamat Pulang, Terimakasih',
                            text: '$nama',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    </script>
                    ";
                    mysqli_query($connect, "UPDATE absensi SET jam_pulang = '$jam' WHERE no_kartu = '$no_kartu' AND tanggal = '$tanggal'");
                }
            }
        }

        // hapus data tmprfid
        mysqli_query($connect, "DELETE FROM tmprfid");
    } ?>
</div>