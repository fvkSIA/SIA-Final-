<?php 
session_start();

session_destroy();

header('location: landing_jobseeker.php');
?>