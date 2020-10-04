<?php
session_start();

include "controller/check_controller.php";


$type = check_input($_GET['type']);
$allow_type = array(1, 2, 3, 4);

if ($type == null) {
  header("Location: 404.html");
}

$check = "false";
foreach ($allow_type as $key => $value) {
  if ($type == $value) {
    $check = "true";
    break;
  }
}

if (!isset($_SESSION['Customer_Id']) && $check == "false") {
  header("Location: 404.html");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Bona Fide Supply</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
  <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>


  <link href="css/agency.min.css" rel="stylesheet">
</head>
<style>
  body {
    background-image: url('header-bg.jpg');
  }
</style>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="home.php">Bona Fide Supply</a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav text-uppercase ml-auto">
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="home.php">Marketplace</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="rules.php">Rules</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="sellyourstuff.php">Sell Your Stuff</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="myaccount.php">My Account</a>
          </li>
          <?php
          if ($_SESSION['Customer_Role'] == "Administration_Worker") {
          ?>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="checkProduct.php">Check Product</a>
            </li>
          <?php
          }
          ?>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="controller/Logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container pt-5">
    <div class="col-lg-12 pt-5">
      <form action="controller/editProfile.php?type=<?= $type ?>" method="POST">

        <?php
        if ($type == 1) {
        ?>
          <div class="form-group">
            <label for="username">
              <font color="white">New Username</font>
            </label>
            <input type="text" class="form-control" id="username" placeholder="New Username" name="username">
          </div>
        <?php
        } else if ($type == 2) {
        ?>
          <div class="form-group">
            <label for="password">
              <font color="white">Old Password</font>
            </label>
            <input type="password" class="form-control" id="old_password" placeholder="Old Password" name="old_password">
          </div>
          <div class="form-group">
            <label for="password">
              <font color="white">New Password</font>
            </label>
            <input type="password" class="form-control" id="new_password" placeholder="New Password" name="new_password">
          </div>
          <div class="form-group">
            <label for="verifypassword">
              <font color="white">Verify New Password</font>
            </label>
            <input type="password" class="form-control" id="verify_password" placeholder="Verify New Password" name="verify_password">
          </div>

        <?php
        } else if ($type == 3) {
        ?>
          <div class="form-group">
            <label for="phoneNumber">
              <font color="white">New Phone Number</font>
            </label>
            <input type="text" class="form-control" id="phoneNumber" placeholder="New Phone Number" name="phoneNumber">
          </div>
        <?php
        } else if ($type == 4) {
        ?>
          <div class="form-group">
            <label for="noktp">
              <font color="white">New Nomor KTP</font>
            </label>
            <input type="text" class="form-control" id="noktp" placeholder="New No KTP" name="noktp">
          </div>
        <?php
        }
        ?>
        <?php
        if (isset($_SESSION['error'])) {
        ?>

          <div class="form-group">
            <div class="d-flex justify-content-center text-white bg-danger">
              <?= $_SESSION['error'] ?>
            </div>
          </div>

        <?php
          unset($_SESSION['error']);
        }
        ?>
        <button type="submit" class="btn btn-danger">Change</button>
      </form>
    </div>
  </div>


</body>

</html>