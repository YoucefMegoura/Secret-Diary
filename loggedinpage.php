<?php
    session_start();
    include ('connexion.php');
    $diary_content = "";
    if(isset($_SESSION)){
        $id_member = (int)mysqli_real_escape_string($conn, $_SESSION['id']);

        if(array_key_exists('id_member', $_COOKIE)){
            $_SESSION['id'] = $_COOKIE['id'];
        }

        if(array_key_exists('id', $_SESSION)){
            //get content diary from database when logged in

            $sql = "SELECT diary FROM users WHERE id = " . $id_member . ";";
            $result = mysqli_query($conn, $sql);

            $row = mysqli_fetch_array($result);
            $diary_content = $row['diary'];

        }else{
            header("Location: index.php");
        }

    }


?>

<?php include ('header.php'); ?>

<nav class="navbar navbar-light bg-light justify-content-between">
    <a class="navbar-brand mb-0 h1">Secret Diary</a>
    <div class="form-inline">

        <a href="index.php?logout=1" >
            <button type="submit" class="btn btn-outline-danger my-2 my-sm-0">Logout</button>
        </a>
    </div>
</nav>



<div class="container">
    <textarea name="diary" id="diary" class="form-control"><?php echo $diary_content; ?></textarea>

</div>


<?php include ('footer.php'); ?>
