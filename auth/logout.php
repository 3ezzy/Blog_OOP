<?php
session_start();
require_once '../db/database.php'; 
require_once '../classes/User.php';
$db = (new Database())->connect();
$logout = (new User($db))->logout(); 

header("location: ../index.php");