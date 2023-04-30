<?php
require_once "../../config/dbh.php";
$hash = getHash();
if (!isset($_POST["password"])) {
    header("location: ../");
    exit();
}
if (password_verify($_POST["password"], $hash) === false) {
    header("location: ../team.php?error=wrongpw");
} else {
    session_start();
    $_SESSION["team-login"] = true;
    header("location: ../team.php?loggedIn");
}