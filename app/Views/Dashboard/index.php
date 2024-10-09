<?php 
session_start();
require '../Components/Header.php';

$successMessage = isset($_SESSION['successMessage']) ? $_SESSION['successMessage'] : "";
