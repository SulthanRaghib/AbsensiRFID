<?php
// urutan parameter: host, username, password, nama database
$connect = mysqli_connect('localhost', 'root', '', 'absensi_rfid_iot');

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
