<?php
    session_start();
    include ("connexion.php");

    if(isset($_POST['content'])){
        $content_member = mysqli_real_escape_string($conn, $_POST['content']);
        $id_member = (int)mysqli_real_escape_string($conn, $_SESSION['id']);

        $sql = "UPDATE users SET diary = '" . $content_member . "' WHERE id = " . $id_member . ";";

        if(mysqli_query($conn, $sql));


    }

