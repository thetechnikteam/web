<?php
require_once 'header.php';
?>
<h1>Song wünschen</h1>
<div class="request">
    <form action="backend/song.post.php" method="post">
        <label class="rqField">
            <i class='bx bx-link' ></i><br><br>
            <span class="rqDes">Füge hier einen Spotify link ein:</span><br>
            <input class="rqInput" type="text" name="link" placeholder="Song Link...">
        </label>
        <?php
        if (isset($_GET["error"]) && $_GET["error"] == "invalidLink") {
            echo("<p style='color: red; font-size: 1.5rem'>Das war kein spotify song link!</p>");
        }
        ?>
    </form>
</div>