<?php
  session_start();
  $diaryContent = "";
  if (array_key_exists("id", $_COOKIE)) {
    $_SESSION['id'] = $_COOKIE['id'];
  }
  if (array_key_exists("id", $_SESSION) && $_SESSION['id']) {
    include ("connection.php");
    $query = "SELECT diary FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
    $row = mysqli_fetch_array(mysqli_query($link, $query));
    $diaryContent = $row['diary'];
  } else {
      header("location: index.php");
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
  <body id="myDiary">

  <nav class="navbar navbar-expand-lg navbar-light bg-light" style="height:70px;">
    <div class="container-fluid">
      <a class="navbar-brand fs-1 fw-bold" >Secret Diary</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
          </li>
        </ul>
        <form class="d-flex">
        <a class="btn btn-secondary" href='index.php?logout=1'>log out</a></p>
        </form>
      </div>
    </div>
  </nav>
  <div class="container-lg" id="textareaContainer">
    <div class="form-floating">
      <textarea class="form-control" id="textareaID" name="content" style="height: 1000px"><?php echo $diaryContent; ?></textarea>
    </div>
  </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script type="text/javascript">


    $('#textareaID').bind('input propertychange', function() {
      $.ajax({
        method: "POST",
        url: "updateDB.php",
        data: {content: $("#textareaID").val()}
      });
    });

    </script>
  </body>
</html>
