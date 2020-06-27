<?php
/* ZIBUU ENTERTAINMENT, (C) 2015 - 2020.
 ________   ______   ____     __  __  __  __
/\_____  \ /\__  _\ /\  _`\  /\ \/\ \/\ \/\ \
\/____//'/'\/_/\ \/ \ \ \L\ \\ \ \ \ \ \ \ \ \
     //'/'    \ \ \  \ \  _ <'\ \ \ \ \ \ \ \ \
    //'/'___   \_\ \__\ \ \L\ \\ \ \_\ \ \ \_\ \
    /\_______\ /\_____\\ \____/ \ \_____\ \_____\
    \/_______/ \/_____/ \/___/   \/_____/\/_____/

*/
include 'includes/database.php';


  // Variables
  $mysql_table = 'core_mcusers';
  // Message should be blank
  $message = '';
  // We can freely edit these stuffs if needed
  $success_page = 'http://www.google.com/';
  $page_title = 'Zibuu – Register your account';


// Making sure the form has been submitted
if(isset($_POST['form_status']))
{
    // Fetching data
    $username = $_REQUEST['mcusername'];
    $nickname = strtolower($username);
    $emailadress = $_REQUEST['mcemail'];
    $regadress = $_SERVER['REMOTE_ADDR'];
    $uuid = file_get_contents("https://www.uuidgenerator.net/api/version4");
    $regdate = time();
    $password = $_REQUEST['password'];
    $confirmpassword = $_REQUEST['confirmpassword'];


    // Making sure both, password and confirm password matchs
    if($password != $confirmpassword)
    {
        $message = 'Your password must be the same on both fields, try again';
        header("Location: ".$error);
    }
    elseif($password == $confirmpassword)
    {
    // Hashing our password for extra security
    $hashedpassword = password_hash("$password", PASSWORD_BCRYPT);
    }


    // Verifying our username has no special characters
    if(!preg_match("/^[A-Za-z0-9_!@$]{1,50}$/", $username))
    {
        $message = 'Your username is not valid, try again';
        header("Location: ".$error);
    }


    // Verifying our email adress is a real email adress
    if (!filter_var($emailadress, FILTER_VALIDATE_EMAIL))
    {
        $message = 'You must use a valid e-mail adress in case you lost your password you can recover it through your e-mail adress';
        header("Location: ".$error);
    }


    // Check if there's anyone registered already with that username or email
    if(empty($message))
    {
        $sql = "SELECT username FROM $mysql_table WHERE username = '".$username."'";
        $sql = "SELECT email FROM $mysql_table WHERE email = '".$emailadress."'";
        $result = mysqli_query($db, $sql);
        if (mysqli_num_rows($result)>0) {
        $message = 'Someone is already regitered with your username or e-mail, if you think this is an error, contact support via our Discord';
        header("Location: ".$error);
        }
    }


    // Preparing our form data
    if(empty($message))
    {
    // Securing input data before sending it to the database
    $username = mysqli_real_escape_string($db, $username);
    $nickname = mysqli_real_escape_string($db, $nickname);
    $hashedpassword = mysqli_real_escape_string($db, $hashedpassword);
    $regadress = mysqli_real_escape_string($db, $regadress);
    $regdate = mysqli_real_escape_string($db, $regdate);
    $uuid = mysqli_real_escape_string($db, $uuid);
    $emailadress = mysqli_real_escape_string($db, $emailadress);


    // Inserting data into our database
    $sql = "INSERT INTO $mysql_table (`nickname`, `username`, `password`, `uuid`, `regadress`, `regdate`, `email`) VALUES (\"$nickname\",\"$username\",\"$hashedpassword\",\"$uuid\",\"$regadress\",\"$regdate\",\"$emailadress\")";
    $result = mysqli_query($db, $sql);
    // Redirecting to our success page if no errorres were found
    echo "<script>window.location.replace('$success_page');</script>";
    $message = 'Succesfully registered';
    mysqli_close($db);
    exit;
    // Done!
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scalable=0">
    <title><?php echo $page_title ?></title>
    <meta name="twitter:description" content="A single username and password gets you into everything. Set up your profile and review your character stats on the go">
    <meta name="twitter:image" content="assets/img/webtitle.png?h=7833c8dcc3e6c3c3dc4fea2631b037c6">
    <meta name="author" content="Zibuu Entertainment">
    <meta name="description" content="A single username and password gets you into everything. Set up your profile and review your character stats on the go">
    <meta property="og:image" content="assets/img/webtitle.png?h=7833c8dcc3e6c3c3dc4fea2631b037c6">
    <meta name="twitter:title" content="Zibuu – Register your account">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css?h=587ac2057624923cd5be3eaf8b1158cd">
    <link rel="stylesheet" href="assets/css/styles.css?h=d41d8cd98f00b204e9800998ecf8427e">
</head>

<body>
    <!-- Start of our register form -->
    <div class="login-clean">
        <form method="post" id="signup" name="signup" autocomplete="off" action="" accept-charset="utf-8">
            <input type="hidden" name="form_status" id="form_status" value="1">
            <h2 class="sr-only">Register</h2>
            <div class="illustration"><i class="icon ion-android-add-circle"></i></div>
            <div class="form-group"><input class="form-control" type="email" name="mcemail" placeholder="Email" id="mcemail" autocomplete="false" required="true" maxlength="75"></div>
            <div class="form-group"><input class="form-control" type="text" name="mcusername" placeholder="Minecraft username" id="mcusername" autocomplete="false" required="true" maxlength="20"></div></br>
            <div class="form-group"><small>For extra security, <b>avoid using</b> your Mojang account password.</small></div>
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password" id="password" required="true" maxlength="30"></div>
            <div class="form-group"><input class="form-control" type="password" name="confirmpassword" placeholder="Confirm your password" id="confirmpassword" required="true" maxlength="30"></div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit" name="submit" id="submit">Register</button></div>
            <div class="form-group"><p style="color: red"><?php echo $message ?></p></div>
        </form>
    </div>
    <!-- End of our register form -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.2.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>
