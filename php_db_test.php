<?php
$dbHost = getenv('DB_HOST');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');
$dbName = getenv('DB_NAME');
// Create connection
echo $dbHost;
echo $dbName;
$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
// Check connection
if ($conn->connect_error) {
    echo "Connected FAILED";
     die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
$conn->close();
?>
