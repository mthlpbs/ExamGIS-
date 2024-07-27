<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('Location: login.php');
   exit();
}

if(isset($_POST['tutor_fetch'])){

   $tutor_email = $_POST['tutor_email'];
   $tutor_email = filter_var($tutor_email, FILTER_SANITIZE_STRING);
   $select_tutor = $conn->prepare('SELECT * FROM `tutors` WHERE email = ?');
   $select_tutor->execute([$tutor_email]);

   $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
   $tutor_id = $fetch_tutor['id'];

   $count_courses = $conn->prepare("SELECT * FROM `course` WHERE tutor_id = ?");
   $count_courses->execute([$tutor_id]);
   $total_courses = $count_courses->rowCount();

   $count_papers = $conn->prepare("SELECT * FROM `paper` WHERE tutor_id = ?");
   $count_papers->execute([$tutor_id]);
   $total_papers = $count_papers->rowCount();

   $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
   $count_likes->execute([$tutor_id]);
   $total_likes = $count_likes->rowCount();

   $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
   $count_comments->execute([$tutor_id]);
   $total_comments = $count_comments->rowCount();

}else{
   header('location:teachers.php');
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
   <title>Tutor's Profile - ExamGIS</title>
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

<!-- teachers profile section starts  -->

<section class="tutor-profile">

   <h1 class="heading">profile details</h1>

   <div class="details">
      <div class="tutor">
         <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
         <h3><?= $fetch_tutor['name']; ?></h3>
         <span><?= $fetch_tutor['profession']; ?></span>
      </div>
      <div class="flex">
         <p>total courses : <span><?= $total_courses; ?></span></p>
         <p>total uploads : <span><?= $total_papers; ?></span></p>
         <p>total likes : <span><?= $total_likes; ?></span></p>
         <p>total comments : <span><?= $total_comments; ?></span></p>
      </div>
   </div>

</section>

<!-- teachers profile section ends -->

<section class="courses">
 
   <h1 class="heading">latest courese</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `course` WHERE tutor_id = ? AND status = ?");
         $select_courses->execute([$tutor_id, 'active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/tutor_thumb/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="course_desc.php?get_id=<?= $course_id; ?>" class="inline-btn">view course</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No courses are added yet!</p>';
      }
      ?>

   </div>

</section>

<!-- courses section ends -->










    

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>