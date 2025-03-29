<?php
include 'conn.php';

$query = "SELECT b.id, b.nama, b.email, b.nomor_telp, p.nama_paket, b.date, t.waktu 
          FROM booking b 
          JOIN paket p ON b.paket_id = p.id 
          JOIN time t ON b.time_id = t.id";

$result = $conn->query($query);

if (!$result) {
    die("Query Error: " . $conn->error); // Tampilkan error MySQL
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Nomor Telepon</th>
        <th>Paket</th>
        <th>Tanggal</th>
        <th>Waktu</th>
        <th>Aksi</th> 
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['nomor_telp']; ?></td>
            <td><?= $row['nama_paket']; ?></td>
            <td><?= $row['date']; ?></td>
            <td><?= $row['waktu']; ?></td>
            <td>
                <a href="#" class="btn-edit" data-id="<?= $row['id']; ?>" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="color: black; font-size: 1.2em;"></i>
                </a>
                &nbsp;|&nbsp;
                <a href="db/delete.php?id=<?= $row['id']; ?>" class="btn-delete" title="Hapus">
                    <i class="fa-regular fa-trash-can" style="color: black; font-size: 1.2em;"></i>
                </a>
            </td>
        </tr>
    <?php } ?>
</table>
