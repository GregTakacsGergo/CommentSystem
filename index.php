/*Update: - using bootstrap 5.3.2 instead of 4.3.2 bootstrap 
          - bugfixes (comment send event was duplicating the sent comments)*/

<?php
session_start();

    $loggedIn = false;

    if (isset($_SESSION['loggedIn']) && isset($_SESSION['name'])) {
        $loggedIn = true;
    }

    $conn = new mysqli('localhost', 'root', '', 'CommentSystem_db');

    if (isset($_POST['addComment'])) {
      $comment = $conn->real_escape_string($_POST['comment']);

      $conn->query("INSERT INTO comments (comment_ID, comment, creation_datetime) VALUES (DEFAULT,'$comment',NOW())");
      exit('success');
   }

   if (isset($_POST['getComment'])) {
      $comments = array();
      $result = $conn->query("SELECT * FROM comments ORDER BY creation_datetime DESC");

      while ($row = $result->fetch_assoc()) {
         $comments[] = $row;
      }
      exit(json_encode($comments));   
   }
?>
<!doctype html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport"
       content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>R34Style Comment System</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <style>
    .user {
         font-weight: bold;
         color: black;
     }
     .time{
         color: gray;
     }
     .userComment {
         color: #000;
     }
     .replies {
         margin-left: 30px;
     }
     .replies .comment{
         margin-top: 10px;
     }
     
     .user-img {
         width: 50px;
         height: 50px;
         border-radius: 50%;
         object-fit: cover;
         margin-right: 10px;
     }
   </style>
</head>
<body>
   <div class="container" style="margin-top: 50px;">     
      <div class="row">
         <div class="col-md-12" align="middle">
            <textarea class="form-control" id="mainComment" placeholder="Add Comment" cols="20" rows="4"></textarea>    
         </div>    
      </div>
      <div class="row">
         <div class="col-md-12" align="right">
            <button style="float:right" class="btn btn-primary" id="addComment">Send</button>    
         </div>    
      </div>
      <div class="row">
         <div class="col-md-12" align="left">
            <h2><b>2 Comments</b></h2>
            <div class="userComments">
               <div class="comment">
                  <div class="user">Johnny Deep
                     <img class="user-img" src="boomer.jpg" align="left" alt="User Image">
                     <span class="time">2024-01-02</span>
                  </div><br>
                  <div class="userComment">In my opinion...</div>
                  <div class="replies">   
                     <div class="comment">
                        <div class="user">Mac Adam
                           <img class="user-img" src="pickard.jpg" align="left" alt="User Image">
                           <span class="time">2024-01-03</span>
                        </div><br>
                        <div class="userComment">You're right...</div>                                
                     </div>
                     <div class="comment">
                        <div class="user">Cooka la Booka
                           <img class="user-img" src="pickard.jpg" align="left" alt="User Image">
                           <span class="time">2024-01-04</span>
                        </div><br>
                        <div class="userComment">You're not right...</div>                                
                     </div>
                     <div class="comment">
                        <div class="user">Boom Shaka
                           <img class="user-img" src="pickard.jpg" align="left" alt="User Image">
                           <span class="time">2024-01-05</span>
                        </div><br>
                        <div class="userComment">You're almost right...</div>                                
                     </div>
                  </div>
               </div>
               <div class="comment">
                  <div class="user">John Deepa
                     <img class="user-img" src="sir.jpg" align="left" alt="User Image">
                     <span class="time">2024-01-02</span>
                  </div><br>
                  <div class="userComment">In my opinion...</div>
                  <div class="replies">   
                     <div class="comment">
                        <div class="user">Mac Adam
                           <img class="user-img" src="pickard.jpg" align="left" alt="User Image">
                           <span class="time">2024-01-03</span>
                        </div><br>
                        <div class="userComment">You're right...</div>                                
                     </div>
                     <div class="comment">
                        <div class="user">Cook a Booka
                           <img class="user-img" src="pickard.jpg" align="left" alt="User Image">
                           <span class="time">2024-01-04</span>
                        </div><br>
                        <div class="userComment">You're not right...</div>                                
                     </div>
                  </div>
               </div>     
            </div>    
         </div>
      </div>
   </div>
   <script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
   <script type="text/javascript">
      $(document).ready(function () {
         $("#addComment").on('click', function () {

            var comment = $("#mainComment").val();
            
            function getComments() {
               $.ajax({
                  url: 'index.php',
                  method: 'POST',
                  dataType: 'json',
                  data: {
                     getComment: 1,
                  }, 
                  success: function (response) {
                     displayComments(response);
                  }
               });
            };

            if (comment.length > 3) {
               $.ajax({
                  url: 'index.php',
                  method: 'POST',
                  dataType: 'text',
                  data: {
                     addComment: 1,
                     comment: comment
                  },
                  success: function(response){
                     if (response === 'success') {
                        $("#mainComment").val(''); 
                        getComments();
                     }
                     else {
                        console.log(response);
                     }
                  }
               });
            }  
            else {
               alert('Please check your inputs!');
            } 

            function displayComments(comments){
               var commentsCounter = $('.userComments');
               commentsCounter.empty();

               for(var i = 0; i < comments.length; i++){
                  var commentHTML = '<div class="comment">';
                  //commentHTML += '<div class="user">' + comments[i]['username'];
                  //commentHTML += '<img class="user-img" src="' + comments[i]['user_image'] + '" align="left" alt="User Image">';
                  commentHTML += '<div class="userComment">' + comments[i]['comment'] + '</div>';
                  commentHTML += '<div class="commentID">' + comments[i]['comment_ID'] + '&nbsp';'&nbsp';
                  commentHTML += '<span class="time">' + comments[i]['creation_datetime'] + '</span></div><br>';      
                  //commentHTML += '<div class="replies"></div></div>';
                  commentsCounter.append(commentHTML);
               }
            }        
         });    
      });   
   </script>        
</body>
</html>