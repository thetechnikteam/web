<?php
require_once 'header.php';
if (!isset($_SESSION["team-login"]) || !$_SESSION["team-login"]) {
?>
    <div class="login">
        <form action="backend/login.post.php" method="post">
            <label class="pwField">
                <i class='bx bxs-lock-alt'></i><br><br>
                <input class="pwInput" name="password" type="password" minlength="8" placeholder="Password...">
            </label>
        </form>
    </div>
<?php
} else {
    if (isset($_GET["purge"])) {
        purgeSongs();
        echo"<script>window.location.href='team.php'</script>";
    }
    ?>
    <h1>Song Requests</h1>
    <a style="width: min-content" href="team.php?purge"><h2>Purge list (<span id="count"><?php echo(count(json_decode(listSongsJSON()))) ?></span>)</h2></a>
    <div id="over">
        <div id="songs">
<?php       listSongs();?>
        </div>
    </div>
    <script>
        function updateSongs() {
            let data = JSON.parse(httpGet("api.php"));
            console.log(data)
            let count = document.getElementById("count");
            let over = document.getElementById("over");
            let oldSongDiv = document.getElementById("songs");
            let newSongDiv = document.createElement("div");
            newSongDiv.setAttribute("id", "songs");
            newSongDiv.setAttribute("style", "display: none");
            over.append(newSongDiv);
            for (let i = 0; i < 5 && i < data.lenght; i++) {
                let song = data[i];
                newSongDiv.innerHTML = newSongDiv.innerHTML + '<iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/'+song["songId"]+'?utm_source=generator&theme=0" frameBorder="0" allowfullscreen="" allow="clipboard-write; encrypted-media; picture-in-picture" loading="eager"></iframe> <a style="position: relative; top: -66px; font-size: 20px" href="backend/song.post.php?delete='+song["id"]+'">❌</a><br>'
            }

            setTimeout(function() { // call a 1s setTimeout when the loop is called
                oldSongDiv.remove();
                newSongDiv.removeAttribute("style");
                count.innerText = data.length
                updateSongs();
            }, 10000)
        }
        updateSongs()
    </script>

<?php
}
