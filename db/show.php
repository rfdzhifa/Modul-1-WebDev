<?php
include 'conn.php';

// Query untuk mengambil data booking
$query = "SELECT b.id, b.nama, b.email, b.nomor_telp, p.nama_paket, b.date, t.waktu 
          FROM booking b 
          JOIN paket p ON b.paket_id = p.id 
          JOIN time t ON b.time_id = t.id";

$result = $conn->query($query);

// Cek apakah query berhasil
if (!$result) {
    die("Query Error: " . $conn->error); // Tampilkan error MySQL
}
?>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Nomor Telepon</th>
        <th>Paket</th>
        <th>Tanggal</th>
        <th>Waktu</th>
        <th>Aksi</th> <!-- Tambahkan kolom aksi -->
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
                <a href="db/edit.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a> | 
                <a href="db/delete.php?id=<?= $row['id']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus booking ini?');">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>
