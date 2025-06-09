<?php
include 'db_connect.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $qty = $_POST['qty'];
    $server = $_POST['server'];
    $user_mode = $_POST['user'];
    $user_length = $_POST['userl'];
    $prefix = $_POST['prefix'];
    $char_type = $_POST['char'];
    $profile = $_POST['profile'];
    $time_limit = $_POST['timelimit'];
    $data_limit = $_POST['datalimit'] * $_POST['mbgb'];
    $agent_email = $_POST['adcomment'];
    $price = $_POST['adcooment']; // Fix: Ensure correct field name

    // SQL query to insert data into the database
    $sql = "INSERT INTO users (qty, server, user_mode, user_length, prefix, char_type, profile, time_limit, data_limit, agent_email, price) 
            VALUES ('$qty', '$server', '$user_mode', '$user_length', '$prefix', '$char_type', '$profile', '$time_limit', '$data_limit', '$agent_email', '$price')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
