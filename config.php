<?php

    // Connect to the MySQL database.
    $db_user = 'blackspa_design-atlas';
    $db_password = '^Lh2ah,(5Y,-';
    $database = 'blackspa_design-atlas';
    $servername = 'localhost';

    $conn = new mysqli($servername, $db_user, $db_password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

?>