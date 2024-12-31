<?php
include "database.php";

// baca isi tabel tmprfid
$query = "SELECT * FROM tmprfid";
$result = mysqli_fetch_array(mysqli_query($connect, $query));

if ($result) {
    $no_kartu = $result['no_kartu'];
} else {
    $no_kartu = "";
}

// Variabel untuk pesan error
$error_message = "";

// Cek apakah nomor kartu sudah ada di database
if (!empty($no_kartu)) {
    $checkQuery = "SELECT * FROM karyawan WHERE no_kartu = '$no_kartu'";
    $checkResult = mysqli_query($connect, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Jika nomor kartu sudah digunakan
        $error_message = "Nomor kartu RFID sudah digunakan!";
    }
}
?>

<div class="mb-3">
    <label for="no_kartu" class="form-label">No. Kartu</label>
    <input type="text" class="form-control <?php echo !empty($error_message) ? 'is-invalid' : ''; ?>"
        id="no_kartu" name="no_kartu"
        placeholder="Tempelkan Kartu RFID Anda"
        value="<?php echo $no_kartu; ?>" readonly>
    <?php if (!empty($error_message)): ?>
        <span class="text-danger"><?php echo $error_message; ?></span>
    <?php endif; ?>
</div>