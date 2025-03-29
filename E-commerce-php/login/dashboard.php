<?php
if (!isset($_COOKIE['user_id']) || $_COOKIE['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}
echo "Welcome, User!";
?>
