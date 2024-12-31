<!DOCTYPE html>
<html lang="en">

<head>

    <?php include "header.php"; ?>
    <title>Data Karyawan</title>
</head>

<body>
    <?php include "navbar.php"; ?>

    <!-- KONTEN -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Data Karyawan</h1>
            <a href="tambah_karyawan.php" class="btn btn-primary">Tambah Karyawan</a>
        </div>


        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">No. Kartu</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Koneksi database -->
                <?php
                // konek ke database
                include "database.php";

                // baca data dari tabel karyawan
                $query = "SELECT * FROM karyawan";
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
                            <td><?php echo $data['alamat']; ?></td>
                            <td>
                                <a href="edit_karyawan.php?id=<?php echo $data['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $data['id']; ?>)" class="btn btn-danger">Hapus</a>
                                <!-- Confirm Delete JavaScript -->
                                <script>
                                    function confirmDelete(id) {
                                        Swal.fire({
                                            title: 'Are you sure?',
                                            text: 'You won\'t be able to revert this!',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Yes, delete it!',
                                            cancelButtonText: 'Cancel'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Redirect to delete_karyawan.php with the selected id
                                                window.location.href = 'delete_karyawan.php?id=' + id;
                                            }
                                        });
                                    }
                                </script>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>Data tidak ditemukan</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

    <?php include "footer.php"; ?>
</body>

</html>