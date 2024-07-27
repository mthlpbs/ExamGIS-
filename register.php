<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
    header('Location: index.php');
    exit();
}

if(isset($_POST['submitregister'])){

$id = unique_id();
$name = $_POST['name'];
$name = filter_var($name, FILTER_SANITIZE_STRING);
$email = $_POST['email'];
$email = filter_var($email, FILTER_SANITIZE_STRING);
$pass = sha1($_POST['pass']);
$pass = filter_var($pass, FILTER_SANITIZE_STRING);
$cpass = sha1($_POST['cpass']);
$cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

$image = $_FILES['image']['name'];
$image = filter_var($image, FILTER_SANITIZE_STRING);
$ext = pathinfo($image, PATHINFO_EXTENSION);
$rename = unique_id().'.'.$ext;
$image_size = $_FILES['image']['size'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$image_folder = 'uploaded_files/user_thumb/'.$rename;

$select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
$select_user->execute([$email]);

if($select_user->rowCount() > 0){
   $message[] = 'Email is already taken!';
}else{
   if($pass != $cpass){
      $message[] = 'Confirm password is not matched!';
   }else{
      $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
      $insert_user->execute([$id, $name, $email, $cpass, $rename]);
      move_uploaded_file($image_tmp_name, $image_folder);
      
      $verify_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
      $verify_user->execute([$email, $pass]);
      $row = $verify_user->fetch(PDO::FETCH_ASSOC);
      
      if($verify_user->rowCount() > 0){
         setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
         header('location:index.php');
      }
   }
}

}

?>

<!DOCTYPE html>
<html lang="en">
 
    <head>
        <!-- meta data -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="./css/login.css">
        <title>Sign Up on ExamGIS</title>
        <meta name="description" content="ExamGIS is a comprehensive platform designed to support students at Saegis Campus. Whether you’re looking for resources, textbooks, or past papers, ExamGIS has you covered. Our user-friendly interface provides easy access to essential study materials, helping you excel in your academic journey.">

        <!-- Fav-icon -->
        <link rel="apple-touch-icon" sizes="180x180" href="./images/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon/favicon-16x16.png">
        <link rel="manifest" href="./images/site.webmanifest">

        <!-- Facebook Meta Tags -->
        <meta property="og:url" content="https://examgis.rf.gd">
        <meta property="og:type" content="website">
        <meta property="og:title" content="ExamGIS: Your Ultimate Study Companion">
        <meta property="og:description" content="ExamGIS is a comprehensive platform designed to support students at Saegis Campus. Whether you’re looking for resources, textbooks, or past papers, ExamGIS has you covered. Our user-friendly interface provides easy access to essential study materials, helping you excel in your academic journey.">
        <meta property="og:image" content=content="./images/banner.jpg">

        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta property="twitter:domain" content="examgis.rf.gd">
        <meta property="twitter:url" content="https://examgis.rf.gd">
        <meta name="twitter:title" content="ExamGIS: Your Ultimate Study Companion">
        <meta name="twitter:description" content="ExamGIS is a comprehensive platform designed to support students at Saegis Campus. Whether you’re looking for resources, textbooks, or past papers, ExamGIS has you covered. Our user-friendly interface provides easy access to essential study materials, helping you excel in your academic journey.">
        <meta name="twitter:image" content="./images/banner.jpg">
    </head>

    <body>
        <div class="container">
            <div class="header">
                <a href=""><img src="./images/favicon/apple-touch-icon.png" alt="ExamGIS_logo" width="70px" height="70px"><span></span></a>
                <h1>Sign up to ExamGIS</h1>
            </div>
            <form action="#" method="post" enctype="multipart/form-data"  autocomplete="off">
                <div class="form-group">
                    <input type="text" name="name" class="inputField" autocomplete="off" required>
                    <label for="name" class="inputfieldText">Enter your name</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="inputField" autocomplete="off" required>
                    <label for="email">Enter your email</label>
                </div>
                <div class="form-group">
                    <input type="password" name="pass" class="inputField" autocomplete="off" minlength="8" required>
                    <label for="password">Enter a Password</label>
                </div>
                <div class="form-group">
                    <input type="password" name="cpass" class="inputField" autocomplete="off" required>
                    <label for="password">Confirm your password</label>
                </div>
                <div class="form-group">
                    <input type="file" class="inputField" accept="image/*" name="image" class="inputField" autocomplete="off" required>
                    <label for ="img">Image</label>
                </div>
                    
                <button type="submit" name="submitregister" class="submitButton">Sign up</button>
            </form>
            <div class="footer">
                <a href="./login.php">If you already have an account, Sign in</a>
            </div>
        </div>
        <footer class="footer-del">
            <span class="author">
                © 2024  MTC LLC. All rights reserved.
            </span>
        <footer>
    <body>
</html>
