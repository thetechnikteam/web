<?php
function songData($con, $id) {
    $sql = "SELECT * FROM songs WHERE id = ?;";
    return getMyData($con, $sql, $id);
}

function songDataBySpoID($con, $id) {
    $sql = "SELECT * FROM songs WHERE songId = ?;";
    return getMyData($con, $sql, $id);
}

/**
 * @param $con
 * @param string $sql
 * @param $id
 * @return array|false|void
 */
function getMyData($con, string $sql, $id)
{
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return false;
    }
}

//##############################################################################

function insertRequest($con, $link): void {
    $songId = str_replace("https://open.spotify.com/track/", "", $link);
    $songId = explode("?si=", $songId)[0];
    if (count(str_split($songId)) < 10) {
        echo "Invalid string length!";
        exit();
    }

    if (songDataBySpoID($con, $songId) === false) {
        $sql = "INSERT INTO songs (songId) VALUES (?);";
        $stmt = mysqli_stmt_init($con);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../index.php?error=1");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $songId);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("location: ../index.php?error=1");
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        addRqToSong($con, songDataBySpoID($con, $songId)["id"]);
    }
}

//###############################################################################

function addRqToSong($con, $id): void {
    $qry = "UPDATE songs SET `wished`=? WHERE id=?";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ../index.php?error=1");
        exit();
    }

    $count = songData($con, $id)["wished"]+1;

    mysqli_stmt_bind_param($stmt, "ss", $count, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

//###############################################################################

function listSongs() {
    $con = techCon();
    $sql = "SELECT * FROM songs ORDER BY `wished` DESC, `id` ASC LIMIT 5;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: index.php?error=1");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            echo('<iframe style="border-radius:12px" 
src="https://open.spotify.com/embed/track/'.$row["songId"].'?utm_source=generator&theme=0" frameBorder="0" allowfullscreen="" 
allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe> 
<a style="position: relative; top: -66px; font-size: 20px" href="backend/song.post.php?delete='.$row["id"].'">❌</a><br>');
        }
    }

    mysqli_stmt_close($stmt);
}

function listSongsJSON() {
    $con = techCon();
    $sql = "SELECT * FROM songs ORDER BY `wished` DESC, `id` ASC;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: index.php?error=1");
        exit();
    }

    $allSongs = array();

    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $allSongs[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
    return json_encode($allSongs);
}

//##############################################################################

function delSong($id) {
    $con = techCon();
    $qry = "DELETE FROM songs WHERE id=?;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ../?error=1");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function purgeSongs() {
    $con = techCon();
    $qry = "DELETE FROM songs;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $qry)) {
        header("location: ../?error=1");
        exit();
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}