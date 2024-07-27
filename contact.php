<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('Location: login.php');
   exit();
}

if(isset($_POST['submit'])){

   $name = $_POST['name']; 
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email']; 
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number']; 
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg']; 
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_contact->execute([$name, $email, $number, $msg]);

   if($select_contact->rowCount() > 0){
      $message[] = 'message sent already!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `contact`(name, email, number, message) VALUES(?,?,?,?)");
      $insert_message->execute([$name, $email, $number, $msg]);
      $message[] = 'message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- primary meta data-->
   <meta http-equiv="Content-Type" charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="keywords" content="Login,Log in,Signin,Sign in" lang="en">
   <meta name="title" content="ExamGIS: Your Ultimate Study Companion">
   <meta name="description" content=" ExamGIS is a user-friendly platform that provides essential study materials for Saegis students. Whether you need resources, textbooks, or past papers, ExamGIS has you covered. It’s designed to support your academic journey and help you excel in your studies">
   <meta name="language" content="English">
   <meta name="author" content="TCM inc">
   <meta name="owner" content="-">

   <!-- meta properties -->
   <title>Contacts | ExamGIS</title>
   <meta name="title" content="ExamGIS: Your Ultimate Study Companion" />
   <meta name="description" content="ExamGIS is a comprehensive platform designed to support students at Saegis Campus. Whether you’re looking for resources, textbooks, or past papers, ExamGIS has you covered. Our user-friendly interface provides easy access to essential study materials, helping you excel in your academic journey." />
   <meta property="og:type" content="website" />
   <meta property="og:url" content="https://examgis.rf.gd" />
   <meta property="og:title" content="ExamGIS: Your Ultimate Study Companion" />
   <meta property="og:description" content="ExamGIS is a comprehensive platform designed to support students at Saegis Campus. Whether you’re looking for resources, textbooks, or past papers, ExamGIS has you covered. Our user-friendly interface provides easy access to essential study materials, helping you excel in your academic journey." />
   <meta property="og:image" content="https://i.imgur.com/WVc46oI.jpeg" />
   <meta property="twitter:card" content="summary_large_image" />
   <meta property="twitter:url" content="https://examgis.rf.gd" />
   <meta property="twitter:title" content="ExamGIS: Your Ultimate Study Companion" />
   <meta property="twitter:description" content="ExamGIS is a comprehensive platform designed to support students at Saegis Campus. Whether you’re looking for resources, textbooks, or past papers, ExamGIS has you covered. Our user-friendly interface provides easy access to essential study materials, helping you excel in your academic journey." />
   <meta property="twitter:image" content="https://i.imgur.com/WVc46oI.jpeg" />

   <!-- Fav-icon -->
   <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-touch-icon.png">
   <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
   <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
   <link rel="manifest" href="/site.webmanifest">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact-us.png" alt="">
      </div>

      <form action="" method="post">
         <h3>Get in touch with us</h3>
         <input type="text" placeholder="Enter your name" required maxlength="100" name="name" class="box">
         <input type="email" placeholder="Enter your email" required maxlength="100" name="email" class="box">
         <input type="number" min="0" max="0712345678" placeholder="enter your number" required maxlength="10" name="number" class="box">
         <textarea name="msg" class="box" placeholder="Enter your message" required cols="30" rows="10" maxlength="1000"></textarea>
         <input type="submit" value="send message" class="inline-btn" name="submit">
      </form>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-phone"></i>
         <h3>Call</h3>
         <a href="tel:0117430000">011 743 0000</a>
      </div>

      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>Mail</h3>
         <a href="mailto:Support@Examgis.com">Support@Examgis.com</a>
      </div>

      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>Address</h3>
         <a href="#">Saegis Campus, 135 S. De S. Jayasinghe Mawatha, Nugegoda</a>
      </div>


   </div>

</section>

<!-- contact section ends -->
   

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>