<?php
include "database.php";

// baca nomor kartu dari NodeMCU
$no_kartu = $_GET['no_kartu'];

// kosongkan tabel tmprfid
mysqli_query($connect, "DELETE FROM tmprfid");

// Simpan nomor kartu yang baru ke tabel tmprfid
$simpan = mysqli_query($connect, "INSERT INTO tmprfid(no_kartu) VALUES ('$no_kartu')");

if ($simpan) {
    echo "Berhasil";
} else {
    echo "Gagal";
}
