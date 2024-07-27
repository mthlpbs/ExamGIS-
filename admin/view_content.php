<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:contents.php');
}

if(isset($_POST['delete_video'])){

   $delete_id = $_POST['video_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $delete_video_thumb = $conn->prepare("SELECT thumb FROM `content` WHERE id = ? LIMIT 1");
   $delete_video_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_video_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);

   $delete_video = $conn->prepare("SELECT video FROM `content` WHERE id = ? LIMIT 1");
   $delete_video->execute([$delete_id]);
   $fetch_video = $delete_video->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_files/'.$fetch_video['video']);

   $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
   $delete_likes->execute([$delete_id]);
   $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
   $delete_comments->execute([$delete_id]);

   $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
   $delete_content->execute([$delete_id]);
   header('location:contents.php');
    
}

if(isset($_POST['delete_comment'])){

   $delete_id = $_POST['comment_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
   $verify_comment->execute([$delete_id]);

   if($verify_comment->rowCount() > 0){
      $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
      $delete_comment->execute([$delete_id]);
      $message[] = 'comment deleted successfully!';
   }else{
      $message[] = 'comment already deleted!';
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
   <title>View Content - ExamGIS</title>
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
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>


<section class="view-content">

   <?php
      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND tutor_id = ?");
      $select_content->execute([$get_id, $tutor_id]);
      if($select_content->rowCount() > 0){
         while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
            $video_id = $fetch_content['id'];

            $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ? AND content_id = ?");
            $count_likes->execute([$tutor_id, $video_id]);
            $total_likes = $count_likes->rowCount();

            $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ? AND content_id = ?");
            $count_comments->execute([$tutor_id, $video_id]);
            $total_comments = $count_comments->rowCount();
   ?>
   <div class="container">
      <video src="../uploaded_files/<?= $fetch_content['video']; ?>" autoplay controls poster="../uploaded_files/<?= $fetch_content['thumb']; ?>" class="video"></video>
      <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></div>
      <h3 class="title"><?= $fetch_content['title']; ?></h3>
      <div class="flex">
         <div><i class="fas fa-heart"></i><span><?= $total_likes; ?></span></div>
         <div><i class="fas fa-comment"></i><span><?= $total_comments; ?></span></div>
      </div>
      <div class="description"><?= $fetch_content['description']; ?></div>
      <form action="" method="post">
         <div class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="update_content.php?get_id=<?= $video_id; ?>" class="option-btn">update</a>
            <input type="submit" value="delete" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
         </div>
      </form>
   </div>
   <?php
    }
   }else{
      echo '<p class="empty">no contents added yet! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">add videos</a></p>';
   }
      
   ?>

</section>

<section class="comments">

   <h1 class="heading">User Comments</h1>

   
   <div class="show-comments">
      <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ?");
         $select_comments->execute([$get_id]);
         if($select_comments->rowCount() > 0){
            while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){   
               $select_commentor = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_commentor->execute([$fetch_comment['user_id']]);
               $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="user">
            <img src="../uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_commentor['name']; ?></h3>
               <span><?= $fetch_comment['date']; ?></span>
            </div>
         </div>
         <p class="text"><?= $fetch_comment['comment']; ?></p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
            <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('delete this comment?');">delete comment</button>
         </form>
      </div>
      <?php
       }
      }else{
         echo '<p class="empty">no comments added yet!</p>';
      }
      ?>
      </div>
   
</section>












<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>