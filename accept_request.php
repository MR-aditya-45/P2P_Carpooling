<?php
include 'connection.php';

$request_id = $_POST['request_id'];

$sql = "UPDATE ride_requests SET status = 'accepted' WHERE request_id = '$request_id'";

if (mysqli_query($conn, $sql)) {
    echo "Request accepted!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
