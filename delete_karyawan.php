<?php
include "database.php";

// cek apakah parameter id terkirim dari index.php
$id = $_GET['id'];

// hapus data dari database
$query = "DELETE FROM karyawan WHERE id = $id";

// eksekusi query
$result = mysqli_query($connect, $query);

// cek apakah proses hapus berhasil
if ($result) {
    echo '
    <script>
        alert("Data berhasil dihapus");
        location.replace("index.php");
    </script>
    ';
} else {
    echo '
    <script>
        alert("Data gagal dihapus");
        location.replace("index.php");
    </script>
    ';
}
