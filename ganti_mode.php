<?php
include "database.php";

// Get the mode value from the URL (if provided)
if (isset($_GET['mode']) && in_array($_GET['mode'], [1, 2, 3, 4])) {
    $mode_terpilih = (int) $_GET['mode'];
} else {
    // If no valid mode is provided, default to the next mode
    $mode = mysqli_query($connect, "SELECT * FROM status");
    $data_absen = mysqli_fetch_array($mode);
    $mode_absen = $data_absen['mode'];

    // Status terakhir ditambah 1
    $mode_terpilih = $mode_absen + 1;
    if ($mode_terpilih > 4) {
        $mode_terpilih = 1;
    }
}

// Simpan mode absen di tabel status
$simpan = mysqli_query($connect, "UPDATE status SET mode = $mode_terpilih");

if ($simpan) {
    echo "Mode berhasil diupdate";
} else {
    echo "Gagal update mode: " . mysqli_error($connect);
}

// Redirect back to scan.php after mode update
header("Location: scan.php");
exit(); // Ensure no further code runs after redirect
