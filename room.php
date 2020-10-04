<?php
session_start();

include "controller/check_controller.php";
include "database/database.php";
include "controller/time_controller.php";

if (!isset($_SESSION['Customer_Id'])) {
  header("Location: 404.html");
}

$Product_Id = check_input($_GET['product']);

$stmt = $connection->prepare("SELECT 
                                      Product_Name,
                                      Product_Description,
                                      Product_Image,
                                      Transaction_Id,
                                      Starting_Date,
                                      End_Date,
                                      Current_Bid
                                FROM product AS Product
                                JOIN header_transaction AS HTransaction
                                ON HTransaction.Product_Id = Product.Product_Id
                                WHERE Product.Product_Id LIKE :Product_Id LIMIT 1");
$stmt->bindParam("Product_Id", $Product_Id, PDO::PARAM_STR);
$stmt->execute();
$Product_Detail = $stmt->fetch();
$total_Data = $stmt->rowCount();
$stmt = null;

$starting_date = new DateTime($Product_Detail['Starting_Date']);
$end_date = new DateTime($Product_Detail['End_Date']);
$today_date = new DateTime(get_Full_Display_Time());
$result_check_starting_date = $today_date->format('U') - $starting_date->format('U');
$result_check_end_date = $today_date->format('U') - $end_date->format('U');

if ($total_Data == 0) {
  header("Location: 404.html");
} else if ($result_check_starting_date < 0) {
  header("Location: 404.html");
} else if ($result_check_end_date >= 0) {
  header("Location: 404.html");
}


$stmt = $connection->prepare("SELECT Customer_Username,Comment_Text 
                                FROM header_comment AS HC
                                JOIN detail_comment AS DC
                                ON HC.Comment_Id = DC.Comment_Id
                                JOIN customer AS C
                                ON C.Customer_Id = DC.Customer_Id
                                WHERE Product_Id LIKE :Product_Id");
$stmt -> bindParam("Product_Id",$Product_Id,PDO::PARAM_STR);
$stmt -> execute();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Bona Fide Supply</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />

  <link href="css/agency.min.css" rel="stylesheet" />
  <link href="css/shop-homepage.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/custom.css">
</head>
<style>
  body {
    background-image: url("header-bg.jpg");
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

  <div class="row d-flex justify-content-center">
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-auto">
        <img class="card-img-top img_room" src="img/product/<?= $Product_Detail['Product_Image'] ?>?>" alt="" />
        <div class="card-body">
          <h4 class="card-title">
            <a href="room.html"><?= $Product_Detail['Product_Name'] ?></a>
          </h4>
          <h5>Current Price Rp<?= number_format($Product_Detail['Current_Bid'], 0, ",", ".") ?></h5>
          <p class="card-text">
            <?= $Product_Detail['Product_Description'] ?>
          </p>
          <form action="controller/bid.php?type=<?= $Product_Id ?>" method="post">
            <div class="form-group">
              <label for="bidprice">
                Bid Price
              </label>
              <input type="text" class="form-control" id="bidprice" placeholder="<?= $Product_Detail['Current_Bid'] ?>" name="bidprice" />
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
            <button class="w-25 btn btn-dark text-warning">Bid</button>
          </form>
        </div>
        <div class="card-footer">
          <medium class="text-muted">End on <?= $Product_Detail['End_Date'] ?></medium>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 mb-4">
      <div class="card h-100">
        <div class="overflow_chat">
          <?php
          while ($detail = $stmt->fetch()) {
          ?>
            <div class="media">
              <div class="media-body">
                <h5 class="mt-0"><?=$detail['Customer_Username']?></h5>
                <?=$detail['Comment_Text']?>
              </div>
            </div>
            <hr class="hr_chat">
          <?php
          }
          $stmt = null;
          ?>
        </div>
        <div class="d-flex align-items-end w-100">
          <form action="controller/comment.php?product=<?= $Product_Id ?>" method="post" class="w-100">
            <textarea placeholder="Write your comment here!" name="comment" class="pb-cmnt-textarea border-dark"></textarea>
            <?php
            if (isset($_SESSION['error_comment'])) {
            ?>
              <div class="form-group">
                <div class="d-flex justify-content-center text-white bg-danger">
                  <?= $_SESSION['error_comment'] ?>
                </div>
              </div>
            <?php
              unset($_SESSION['error_comment']);
            }
            ?>
            <button type="submit" class="btn btn-dark text-warning w-25">Share</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <style>
    .pb-cmnt-container {
      font-family: Lato;
      margin-top: 100px;
    }

    .pb-cmnt-textarea {
      resize: none;
      padding: 20px;
      height: 130px;
      width: 100%;
    }
  </style>
</body>

</html>