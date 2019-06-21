<?php
    session_start();

    //if the user logout
    if (array_key_exists("logout", $_GET)) {
        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";
        //kill the session
        session_destroy();

    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
        header("Location: loggedinpage.php");
    }


    include ("connexion.php");

    //register variables
    $error_register_message = "";
    $show_register_error = "";
    $success_register_message = "";
    $show_register_success = "";


    //signin variables
    $error_signin_message = "";
    $show_signin_error = "";
    $success_signin_message = "";
    $show_signin_success = "";


if($_POST){

    /* Register  */
    if($_POST['signin_hidden'] == '0'){
        $email_register = mysqli_real_escape_string($conn, $_POST['InputEmailRegister']);
        $password_register = mysqli_real_escape_string($conn, $_POST['InputPasswordRegister']);
        $passwordConfirmation_register = mysqli_real_escape_string($conn, $_POST['InputConfirmPasswordRegister']);


        if(empty($email_register)){
            $error_register_message .= "<strong>Email</strong> field is required!<br>";
        }
        if (!filter_var($email_register, FILTER_VALIDATE_EMAIL)) {
            $error_register_message .= "<strong>Email</strong> is Invalid!<br>";
        }
        if(empty($password_register)){
            $error_register_message .= "<strong>Password</strong> field is required!<br>";
        }
        if(empty($passwordConfirmation_register)){
            $error_register_message .= "<strong>Password confirmation</strong> field is required!<br>";
        }
        if($passwordConfirmation_register != $password_register && !empty($password_register) && !empty($passwordConfirmation_register)){
            $error_register_message .= "<strong>Passwords</strong> is not match!<br>";
        }
        if(strlen($password_register) < 6 && strlen($password_register) != 0){
            $error_register_message .= "<strong>Password</strong> is too short!<br>";
        }


        if(!empty($error_register_message)){
            //the form is incorrect, show the errors
            $show_register_error = '<div class="alert alert-danger" role="alert">
                                    '. $error_register_message .'
                                    </div>';
        }else{
            // the register form is valid
            $show_register_error = "";

            //check the email if it is in the db already
            $sql = "SELECT * FROM users WHERE email = '" . $email_register . "'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                //if the email is already registred

                $show_register_success = "";
                $error_register_message .= "<strong>Already registred</strong> Try to connect whith this email or click to forget password<br>";
                $show_register_error = '<div class="alert alert-warning" role="alert">
                                    '. $error_register_message .'
                                    </div>';
            } else {
                $show_register_error = "";

                //insert the new member in the db

                mysqli_insert_id($conn);
                $sql = "INSERT INTO users (email, password) VALUES ('" . $email_register . "', '" . $password_register . "')";

                if (mysqli_query($conn, $sql)) {
                    //registration success
                    $success_register_message .= "<strong>Registred</strong> Thank you for join us!<br>";

                    //alter the password for a hashed password
                    $sql = "UPDATE users SET password = '" . md5(md5('YoucefMegoura') . $password_register) . "' WHERE email = '" . $email_register . "'";
                    mysqli_query($conn, $sql);


                } else {
                    //there is some error in the database
                    $success_register_message = "";
                    $show_register_error = '<div class="alert alert-info" role="alert">
                                    '. "Error: " . $sql . "<br>" . mysqli_error($conn) .'
                                    </div>';
                }
                $show_register_success = '<div class="alert alert-success" role="alert">
                                    '. $success_register_message .'
                                    </div>';
            }
        }
    }else{
        /* SignIn  */

        //SignIn post variable
        $email_signin = mysqli_real_escape_string($conn, $_POST['InputEmailSignin']);
        $password_signin = mysqli_real_escape_string($conn, $_POST['InputPasswordSignin']);
        $rememberMe_signin = "0";
        if (isset($_POST['CheckSignin'])) {
            mysqli_real_escape_string($conn, $_POST['CheckSignin']);
        }


        if(empty($email_signin)){
            $error_signin_message .= "<strong>Email</strong> field is required!<br>";
        }
        if (!filter_var($email_signin, FILTER_VALIDATE_EMAIL)) {
            $error_signin_message .= "<strong>Email</strong> is Invalid!<br>";
        }

        if(!empty($error_signin_message)){
            //the form is incorrect, show the errors
            $show_signin_error = '<div class="alert alert-danger" role="alert">
                                        '. $error_signin_message .'
                                        </div>';
        }else{
            // the signin form is valid
            $show_signin_error = "";

            $sql = "SELECT * FROM users WHERE email = '" . $email_signin . "' AND password = '" . md5(md5('YoucefMegoura')  . $password_signin) . "'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);

            if (mysqli_num_rows($result) > 0) {
                //if the member with this email/pass exist
                $show_signin_success = "";
                $error_signin_message .= "<strong>Connected</strong> Now you are conncted!<br>";
                $show_signin_error = '<div class="alert alert-success" role="alert">
                                        '. $error_signin_message .'
                                        </div>';

                $_SESSION['id'] = $row['id'];


                //remember me
                if ($rememberMe_signin == '1'){
                    setcookie('id', $row['id'], time()+60*60*24*365);
                }
                header("Location: loggedinpage.php");

            } else {
                $show_signin_error = "";
                //identification field
                $success_signin_message .= "<strong>Wrong</strong> The email or the password is invalid!<br>";

                $show_signin_success = '<div class="alert alert-warning" role="alert">
                                        '. $success_signin_message .'
                                        </div>';
            }
        }
    }
}

    mysqli_close($conn);

?>
<?php include("header.php")?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="title">Secret Diary</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <h1>Sign In</h1>
                <div id="error_signin"><?php echo $show_signin_error;?></div>
                <div id="success_signin"><?php echo $show_signin_success;?></div>
                <form method="post" id="signin">
                    <div class="form-group">
                        <label for="InputEmailSignin">Email address</label>
                        <input type="email" class="form-control" id="InputEmailSignin" name="InputEmailSignin" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="InputPasswordSignin">Password</label>
                        <input type="password" class="form-control" id="InputPasswordSignin" name="InputPasswordSignin" placeholder="Password">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="CheckSignin" name="CheckSignin" value="0">
                        <label class="form-check-label" for="CheckSignin" >Remember me</label>
                    </div>
                    <br>
                    <input type="hidden" name="signin_hidden" value="1">
                    <button type="submit" class="btn btn-success" id="submitSignin" >Sign In</button>
                </form>
            </div>
            <div class="col-sm-7">
                <h1>Register</h1>
                <div id="error_register"><?php echo $show_register_error;?></div>
                <div id="success_register"><?php echo $show_register_success;?></div>
                <form method="post" id="register">
                    <div class="form-group">
                        <label for="InputEmailRegister">Email address</label>
                        <input type="email" class="form-control" id="InputEmailRegister" name="InputEmailRegister" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="InputPasswordRegister">Password</label>
                        <input type="password" class="form-control" id="InputPasswordRegister" name="InputPasswordRegister" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="InputConfirmPasswordRegister">Confirm password</label>
                        <input type="password" class="form-control" id="InputConfirmPasswordRegister" name="InputConfirmPasswordRegister" placeholder="Confirm password">
                    </div>
                    <br>
                    <input type="hidden" name="signin_hidden" value="0">
                    <button type="submit" class="btn btn-primary" id="submitRegister">Register</button>
                </form>
            </div>
        </div>

    </div>
<?php include("footer.php")?>
