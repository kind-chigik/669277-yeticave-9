<?php
session_start();

$is_auth = isset($_SESSION['user']) ? true : false;
$user_name = $is_auth ? strip_tags($_SESSION['user']['name']) : false;