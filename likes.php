<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('Location: login.php');
   exit();
}

if(isset($_POST['remove'])){
 
   if($user_id != ''){
      $paper_id = $_POST['paper_id'];
      $paper_id = filter_var($paper_id, FILTER_SANITIZE_STRING);

      $verify_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND paper_id = ?");
      $verify_likes->execute([$user_id, $paper_id]);

      if($verify_likes->rowCount() > 0){
         $remove_likes = $conn->prepare("DELETE FROM `likes` WHERE user_id = ? AND paper_id = ?");
         $remove_likes->execute([$user_id, $paper_id]);
         $message[] = 'removed from likes!';
      }
   }else{
      $message[] = 'Please login first!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- primary meta data-->
   <meta http-equiv="
   -Type" charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="keywords" content="Login,Log in,Signin,Sign in" lang="en">
   <meta name="title" content="ExamGIS: Your Ultimate Study Companion">
   <meta name="description" content=" ExamGIS is a user-friendly platform that provides essential study materials for Saegis students. Whether you need resources, textbooks, or past papers, ExamGIS has you covered. It’s designed to support your academic journey and help you excel in your studies">
   <meta name="language" content="English">
   <meta name="author" content="TCM inc">
   <meta name="owner" content="-">

   <!-- meta properties -->
   <title>Liked Papers | ExamGIS</title>
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

<!-- courses section starts  -->

<section class="liked-pdfs">

   <h1 class="heading">liked papers</h1>

   <div class="box-container">

   <?php
      $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
      $select_likes->execute([$user_id]);
      if($select_likes->rowCount() > 0){
         while($fetch_likes = $select_likes->fetch(PDO::FETCH_ASSOC)){

            $select_papers = $conn->prepare("SELECT * FROM `paper` WHERE id = ? ORDER BY date DESC");
            $select_papers->execute([$fetch_likes['paper_id']]);

            if($select_papers->rowCount() > 0){
               while($fetch_papers = $select_papers->fetch(PDO::FETCH_ASSOC)){

               $select_tutors = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutors->execute([$fetch_papers['tutor_id']]);
               $fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC);
   ?>
   <div class="box">
      <div class="tutor">
         <img src="uploaded_files/tutor_thumb/<?= $fetch_tutor['image']; ?>" alt="">
         <div>
            <h3><?= $fetch_tutor['name']; ?></h3>
            <span><?= $fetch_papers['date']; ?></span>
         </div>
      </div>
      <img src="uploaded_files/paper_thumb/<?= $fetch_papers['thumb']; ?>" alt="" class="thumb">
      <h3 class="title"><?= $fetch_papers['title']; ?></h3>
      <form action="" method="post" class="flex-btn">
         <input type="hidden" name="paper_id" value="<?= $fetch_papers['id']; ?>">
         <a href="view_pdf.php?get_id=<?= $fetch_papers['id']; ?>" class="inline-btn">View Paper</a>
         <input type="submit" value="remove" class="inline-delete-btn" name="remove">
      </form>
   </div>
   <?php
            }
         }else{
            echo '<p class="emtpy">Paper was not found!</p>';         
         }
      }
   }else{
      echo '<p class="empty">Nothing added to likes yet!</p>';
   }
   ?>

   </div>

</section>


<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>