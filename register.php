<?php
session_start();
if (isset($_SESSION['Customer_Id'])) {
  header("Location: home.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Bona Fide Supply - Register</title>
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
      <a class="navbar-brand js-scroll-trigger" href="index.html">Bona Fide Supply</a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav text-uppercase ml-auto">
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="index.html">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="index.html">Goods</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="index.html">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="index.html">Team</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="index.html">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container pt-5">
    <div class="col-lg-12 pt-5">
      <form action="controller/register.php" method="POST">
        <div class="form-group">
          <label for="username">
            <font color="white">Username</font>
          </label>
          <input type="text" class="form-control" id="username" placeholder="Username" name="username">
        </div>
        <div class="form-group">
          <label for="password">
            <font color="white">Password</font>
          </label>
          <input type="password" class="form-control" id="password" placeholder="Password" name="password">
        </div>
        <div class="form-group">
          <label for="verifypassword">
            <font color="white">Verify Password</font>
          </label>
          <input type="password" class="form-control" id="verifypassword" placeholder="Verify Password" name="verifypassword">
        </div>
        <div class="form-group">
          <label for="telephone">
            <font color="white">Telephone Number</font>
          </label>
          <input type="text" class="form-control" id="telephone" placeholder="Telephone" name="telephone">
        </div>
        <div class="form-group">
          <label for="noktp">
            <font color="white">Nomor KTP</font>
          </label>
          <input type="text" class="form-control" id="noktp" placeholder="No KTP" name="noktp">
        </div>
        <div class="form-group">
          <input type="checkbox" id="termsAndCondition" placeholder="termsAndCondition" name="termsAndCondition" value="termsAndCondition">
          <label for="termsAndConditiont">
            <font color="white">I agree with the terms and condition and will follow the rules</font>
          </label>
        </div>
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
        <button type="submit" class="btn btn-danger">Register</button>
        <a href="login.php">Already Have an Account? Login Here! </a>
      </form>
    </div>
  </div>


</body>

</html>