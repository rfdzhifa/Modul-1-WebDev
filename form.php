<?php
// Inisialisasi variabel untuk menyimpan nilai input dan error
$nama = $email = $nomor = $paket = $date = $time = "";
$namaErr = $emailErr = $nomorErr = $paketErr = $dateErr = $timeErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi Nama
    $nama = $_POST["nama"];
    if (empty($nama)) {
        $namaErr = "Nama wajib diisi";
    }

    // Validasi Email
    $email = $_POST["email"];
    if (empty($email)) {
        $emailErr = "Email wajib diisi";
    }

    // Validasi Nomor Telepon
    $nomor = $_POST["nomor"];
    if (empty($nomor)) {
        $nomorErr = "Nomor Telepon wajib diisi";
    } elseif (!ctype_digit($nomor)) {
        $nomorErr = "Nomor Telepon harus berupa angka";
    }

    $date = $_POST["date"];
    if (empty($date)) {
        $dateErr = "date wajib diisi";
    }

    // Menyimpan pilihan mobil tanpa validasi khusus
    $paket = $_POST["paket"];
    $time = $_POST["time"];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Booking Studio</title>
    <link rel="stylesheet" href="styles/form_style.css">
</head>

<body>
    <div class="container">
        <h2>Form Booking Studio</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>">
                <span class="error"><?php echo $namaErr ? "* $namaErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $emailErr ? "* $emailErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="nomor">Nomor Telepon:</label>
                <input type="text" id="nomor" name="nomor" value="<?php echo $nomor; ?>">
                <span class="error"><?php echo $nomorErr ? "* $nomorErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="paket">Pilih Paket:</label>
                <select id="paket" name="paket">
                    <option value="Self Studio" <?php echo ($paket == "Self Studio") ? "selected" : ""; ?>>Self Studio
                    </option>
                    <option value="Prewedding" <?php echo ($paket == "Prewedding") ? "selected" : ""; ?>>Prewedding
                    </option>
                    <option value="Wedding" <?php echo ($paket == "Wedding") ? "selected" : ""; ?>>Wedding</option>
                    <option value="Wisuda" <?php echo ($paket == "Wisuda") ? "selected" : ""; ?>>Wisuda</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Pilih Tanggal:</label>
                <input type="date" id="date" name="date" value="<?php echo $date; ?>">
                <span class="error"><?php echo $dateErr ? "* $dateErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="time">Pilih Waktu:</label>
                <select id="time" name="time">
                    <option value="09.00" <?php echo ($time == "09.00") ? "selected" : ""; ?>>09.00</option>
                    <option value="09.30" <?php echo ($time == "09.30") ? "selected" : ""; ?>>09.30</option>
                    <option value="10.00" <?php echo ($time == "10.00") ? "selected" : ""; ?>>10.00</option>
                    <option value="10.30" <?php echo ($time == "10.30") ? "selected" : ""; ?>>10.30</option>
                </select>
            </div>

            <div class="button-container">
                <button type="submit">Booking</button>
            </div>
        </form>
    </div>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !$namaErr && !$emailErr && !$nomorErr && !$dateErr && !$paketErr && !$timeErr) { ?>
    <div class="container">
        <h3>Data Pembelian:</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="20%">Nama</th>
                        <th width="20%">Email</th>
                        <th width="15%">Nomor Telepon</th>
                        <th width="15%">Paket</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $nama; ?></td>
                        <td><?php echo $email; ?></td>
                        <td><?php echo $nomor; ?></td>
                        <td><?php echo $paket; ?></td>
                        <td><?php echo $date; ?></td>
                        <td><?php echo $time; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>
</body>

</html>