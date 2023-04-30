<?php
session_start();
require_once "../../config/dbh.php";
require_once "../backend/functions.php";
if (isset($_POST["link"])) {
    if (str_contains($_POST["link"], "https://open.spotify.com/track/")) {
        insertRequest(techCon(), $_POST["link"]);
        header("location: ../submit.php");
    } else {
        header("location: ../song.php?error=invalidLink");
    }
} elseif (isset($_GET["delete"]) && isset($_SESSION["team-login"]) && $_SESSION["team-login"] === true &&
    songData(techCon(), $_GET["delete"]) !== false) {
    delSong($_GET["delete"]);
    header("location: ../team.php");
}
echo "you have nothing to do here!";