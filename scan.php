<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "header.php"; ?>
    <title>Scan Kartu</title>

    <!-- Scanning membaca kartu RFID -->
    <script>
        $(document).ready(function() {
            setInterval(function() {
                $("#cek_kartu").load("baca_kartu.php");
            }, 1000); // Perbarui setiap 1 detik
        });
    </script>
</head>

<body>
    <?php include "navbar.php"; ?>

    <!-- KONTEN -->
    <div class="container">
        <div id="cek_kartu"></div>
    </div>

    <?php include "footer.php"; ?>
</body>

</html>