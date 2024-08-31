<?php
$servername = "fdb1031.runhosting.com";
$username = "4417762_focus";
$password = "paginaia2407";
$dbname = "4417762_focus";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}