<?php
session_start();
include "database/database.php";

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

  <link rel="stylesheet" href="./css/custom.css">
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
      <form action="controller/sell_your_stuff.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="itemName">
            <font color="white">Item Name</font>
          </label>
          <input type="text" class="form-control" id="itemName" placeholder="Item Name" name="itemName">
        </div>
        <div class="form-group">
          <label for="startingprice">
            <font color="white">Starting Price</font>
          </label>
          <input type="text" class="form-control" id="startingprice" placeholder="Staring Price" name="startingprice">
        </div>
        <div class="form-group">
          <label for="ItemCategory">
            <font color="white">Item Category</font>
          </label>
          <select class="form-control text-black" id="itemCategory" name="itemCategory">
            <option>Clothes</option>
            <option>Bags</option>
            <option>Outers</option>
            <option>Gadget</option>
            <option>Shoes</option>
          </select>
        </div>
        <div class="form-group">
          <label for="itemDescription">
            <font color="white">Item Description</font>
          </label>
          <input type="text" class="form-control" id="itemDescription" placeholder="Item Description" name="itemDescription">
        </div>
        <div class="form-group">
          <label for="ImageUpload">
            <font color="white">Select image to upload: </font>
          </label>
          <input class="text-white" type="file" name="fileToUpload" id="fileToUpload">
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
        <button type="submit" class="btn btn-danger">Submit Sell Request</button>
      </form>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <span class="border border-dark w-100"></span>
        <div class="p-3 mb-5 bg-light text-dark">
          <h3 class="section-heading text-uppercase">Current Items You Sale</h3>
          <?php
          $stmt = $connection->prepare("SELECT * FROM product WHERE Customer_Id LIKE :id ORDER BY Product_Date");
          $stmt->bindParam("id", $_SESSION['Customer_Id'], PDO::PARAM_STR);
          $stmt->execute();
          if ($stmt->rowCount() != 0) {
          ?>
            <table class="table table-striped table-dark">
              <thead>
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Starting Price</th>
                  <th scope="col">Description</th>
                  <th class="w-25" scope="col">Image</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($product_detail = $stmt->fetch()) {
                ?>
                  <tr>
                    <td class="align-middle"><?= $product_detail['Product_Name'] ?></td>
                    <td class="align-middle">Rp<?= number_format($product_detail['Product_Starting_Price'], 0, ",", ".") ?></td>
                    <td class="align-middle"><?= $product_detail['Product_Description'] ?></td>
                    <td>
                      <img class="img_sell_your_stuff_height w-100" src="img/product/<?= $product_detail['Product_Image'] ?>" alt="<?= $product_detail['Product_Name'] ?>" >
                    </td>
                    <td class="align-middle"><?php
                        if ($product_detail['Product_Status'] == "Waiting") {
                          echo "Waiting For Approval";
                        } else if($product_detail['Product_Status'] == "Accept") {
                          echo "Approve";
                        }else{
                          echo $product_detail['Product_Status'];
                        }
                        ?>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          <?php
          } else {
          ?>
            <h4 class="section-subheading text-muted">You didn't sell any product</h4>
          <?php
          }
          ?>
        </div>
        </span>
      </div>
    </div>
  </div>
</body>

</html>