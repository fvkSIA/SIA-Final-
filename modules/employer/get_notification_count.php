<?php
session_start();
if (isset($_SESSION['notificationCount'])) {
    $notificationCount = $_SESSION['notificationCount'];
} else {
    $notificationCount = 0;
}
echo $notificationCount;
?>
