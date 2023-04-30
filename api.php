<?php
session_start();
require_once "../config/dbh.php";
require_once "backend/functions.php";
if (!isset($_SESSION["team-login"]) || $_SESSION["team-login"] === false) {
    echo "this is not your place!";
    exit();
}
echo listSongsJSON();