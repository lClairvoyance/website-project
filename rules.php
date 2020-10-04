<?php
session_start();
if (!isset($_SESSION['Customer_Id'])) {
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
  <section class="page-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <span class="border border-dark w-100">
            <div class="p-3 mb-2 bg-light text-dark">
              <h3 class="section-heading text-uppercase">Rules</h3>
              <h4 class="section-subheading text-muted">This is the bidding rules</h4>
              <h5 style="text-align:left">1. No Sniping.</h5>
              <h5 style="text-align:left">2. No Self Bidding.</h5>
              <h5 style="text-align:left">3. When u request to sell an item, we will assess your item and process your request.</h5>
              <h5 style="text-align:left">4. Payment must be resolved in 4x24 hours after winning the bid.</h5>
              <h5 style="text-align:left">5. Not paying for the item in time may result in account penalty or some fee.</h5>
              <h5 style="text-align:left">In the case of two bidders placing the same maximum bid, the bid first received by the system will be deemed the leading bid.</h5>
            </div>
          </span>
        </div>
      </div>

  </section>


</body>

</html>