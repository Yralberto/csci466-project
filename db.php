<?php
// database connection file(used by all of the pages in order to get MySQL acess)
// Please change 'Z-ID' and 'password' with your z-id and password of PUTTY.
// Otherwise when testing wont work as they are just placeholders
// for the SQL file also replace the placeholder "Z-ID" with your own as well 
try {
    $dsn = "mysql:host=courses;dbname=Z-ID";
    // create PDO connection using username and password
    $pdo = new PDO($dsn, "Z-ID", "Password");
    // enable error reporting for debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    // if connection fails, stop the page and show error message
    die("Connection failed: " . $e->getMessage());
}
?>