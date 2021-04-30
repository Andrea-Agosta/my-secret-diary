<?php
    session_start();
    $error = "";
    $errReg = "";
    $errPsw = "";
    $registerEmail="";
    $tabLogin = "true";
    $tabRegister = "false";
    $errorLog = "";
    $logEmail = "";
    $errorPswLog = "";

    if ($_POST) {
        include ("connection.php");
        if (array_key_exists("logout", $_GET)) {
            unset($_SESSION);
            setcookie("id", "", time()-60*60);
            $_COOKIE["id"] = "";
        } else if (array_key_exists("id", $_SESSION) AND $_SESSION['id'] OR array_key_exists("id", $_SESSION) OR array_key_exists("id", $_COOKIE) AND $_COOKIE['id']) {
            header("location: myDiary.php");
        }
        if (isset( $_POST['registerButton'])) {
            if ($_POST['registerEmail'] == "") {
            $errReg .= "<uppercase>*</uppercase>insert your email for registration.";
            } else if ($_POST['registerPassword'] == "") {
                if ($_POST['registerEmail'] != ""){
                    $registerEmail = $_POST['registerEmail'];
                    //#### unable to find  solution a this issue ####
                    $tabLogin = '"false"';
                    $tabRegister = '"true"';  
                }
                $errPsw .= "<uppercase>*</uppercase>insert a password for registration.</p>";
            } else {            
                $query = "SELECT `id` FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['registerEmail'])."'";
                $result = mysqli_query($link, $query);
                $id = mysqli_fetch_array($result);
                if (mysqli_num_rows($result) > 0) {
                    $error .= "<p>Thay email address has already been taken.</p>";
                    echo "<p>This email address has already been taken.</p>";
                } else {
                    $hash = password_hash($_POST['registerPassword'], PASSWORD_DEFAULT);
                    $postMail = $_POST['registerEmail'];
                    $query = "INSERT INTO `users` (`email`,`password`) VALUES ('$postMail','$hash')";
                    if (mysqli_query($link, $query)) {
                        $_SESSION['id'] = $id['id'];
                        if ($_POST['stayLoggedInReg'] == 1) {
                            setcookie("user", $id['id'], time() + 60*60*24);
                        }
                        $error = "";
                        header("location: myDiary.php"); 
                    } else {
                        $error .= "<p>There was a problem signing up - please try again later.</p>";
                    }
                }
            }
        }
        if (isset($_POST["logButton"])) {
            if ($_POST['logEmail'] == "") {
                $errorLog .= "<uppercase>*</uppercase>Insert your email for Logging in.";
            } else if ($_POST['logPassword'] == "") {
                if ($_POST['logEmail'] != ""){
                    $logEmail = $_POST['logEmail']; 
                }
                $errorPswLog .= "<uppercase>*</uppercase>Insert your password for Logging in.";
            } else {
                $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['logEmail'])."'";
                $resultLog = mysqli_query($link, $query);
                if (mysqli_num_rows($resultLog) > 0) {
                    $row = mysqli_fetch_array($resultLog);
                    if (password_verify($_POST['logPassword'], $row['password']) ) {
                        $_SESSION["id"] = $row['id'];
                        if (isset($_POST['checkLog'])) {
                            setcookie("user", $row['id'], time() + 60*60*24);
                        }
                    $errorLog = "";
                    header("location: myDiary.php");    
                    } else {
                        $errorLog .= "<p>Insert a correct password.</p>";
                    }                    
                } else {
                    $errorLog .= "<p>You need to insert a valid mail</p>";
                }
            }
        }
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>My Diary</title>
    <link rel="stylesheet" href="./style.css">
  </head>
  <body id="homepage">
    <div  class="container-md">
        <div class="alert-danger"><?php echo $error; ?></div>
    </div>
    <div class="container-sm">
        <div id="box">
            <p class="text-center fs-1 fw-bold"> Secret Diary </p>
            <p class="text-center fs-5"> Store your thoughts permanently and securely.</p>
            <p class="text-center fs-5"> Interested? <span id="br" style="display:none"><br></span>Sign up now.</p>
            <div id="window">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="logInTab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected= <?php echo $tabLogin ?> >Log In!</button>
                        <button class="nav-link" id="registerTab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected= <?php echo $tabRegister ?> >Register</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel">
                        <div class="container">
                            <form method="post">
                                <label for="email">Insert your email:</label>
                                <p style="color:red"> <?php echo $errorLog; ?> </p>
                                <div><input type="email" id="email" name="logEmail" placeholder="Ed. john@john.com" value="<?php echo $logEmail; ?>"></div>
                                <label for="password">Insert your Password:</label> 
                                <p style="color:red"><?php echo $errorPswLog; ?> </p>
                                <div><input type="password" name="logPassword" id="password" placeholder = "************"></div>
                                <div class="row">
                                    <div class="col-4">
                                        <button type="submit" class="btn btn-color" name="logButton">Log In!</button>
                                    </div>
                                    <div class="col-8">
                                        <div class="form-check">
                                            <input class="form-check-input" name="checkLog" type="checkbox" value="1" id="checkLog">
                                            <label class="form-check-label" for="checkLog" id="labChek">Stay logged in</label>
                                        </div>
                                    </div>
                                </div>  
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel">
                        <div class="container">
                            <form method="post">
                                <label for="registerEmail">Insert your email:</label>
                                <p style="color:red"> <?php echo $errReg; ?> </p>
                                <div><input type="email" id="registerEmail" name="registerEmail" placeholder="Ed. john@john.com" value="<?php echo $registerEmail; ?>"></div>
                                <label for="registerPassword">Insert your Password:</label>
                                <p style="color:red"> <?php echo $errPsw; ?> </p>
                                <div><input type="password" name="registerPassword" id="registerPassword" placeholder = "************"></div>
                                <div class="row">
                                    <div class="col-4">
                                        <button type="submit" class="btn btn-color" name="registerButton">Register</button>
                                    </div>
                                    <div class="col-8">
                                        <div class="form-check">
                                            <input class="form-check-input" name="stayLoggedInReg" type="checkbox" value="1" id="checkReg">
                                            <label class="form-check-label" for="checkReg" id="regChek">Stay logged in</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script type="text/javascript">
    </script>
  </body>
</html>
