<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "storyframe";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]));
}
?>
