<?php
session_start();
include "database/database.php";
include "controller/time_controller.php";

if (!isset($_SESSION['Customer_Id'])) {
  header("Location: 404.html");
}

$stmt = $connection->prepare("SELECT * FROM customer WHERE Customer_Id LIKE :id");
$stmt->bindParam("id", $_SESSION['Customer_Id'], PDO::PARAM_STR);
$stmt->execute();
$Customer_Detail = $stmt->fetch();
$stmt = null;

$Product_Status = "Finished";
$Transaction_Status = "Ongoing";
$stmt = $connection->prepare("SELECT
                                      Product.Product_Id,
                                      Product_Name,
                                      Product_Image,
                                      Product_Status,
                                      Transaction_Id,
                                      HTransaction.Customer_Id,
                                      Current_Bid,
                                      Transaction_Status
                                FROM product AS Product
                                JOIN header_transaction AS HTransaction
                                ON Product.Product_Id = HTransaction.Product_Id
                                WHERE Product_Status = :Product_Status AND
                                Transaction_Status NOT LIKE :Transaction_Status
                                ");
$stmt->bindParam("Product_Status", $Product_Status, PDO::PARAM_STR);
$stmt->bindParam("Transaction_Status", $Transaction_Status, PDO::PARAM_STR);
$stmt->execute();
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
  <link rel="stylesheet" href="css/custom.css">

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
              <h3 class="section-heading text-uppercase">Account information</h3>
              <h4 class="section-subheading text-muted">This displays your account information</h4>
              <table class="table table-striped table-dark">
                <tbody>
                  <tr>
                    <th scope="row">Username</th>
                    <td><?= $Customer_Detail['Customer_Username'] ?></td>
                    <td><a href="editProfile.php?type=1"><button type="button" class="btn btn-light">Edit</button></a></td>
                  </tr>
                  <tr>
                    <th scope="row">Password</th>
                    <td><?= get_LastModified_Time("Customer", $_SESSION['Customer_Id'], 1) ?></td>
                    <td><a href="editProfile.php?type=2"><button type="button" class="btn btn-light">Edit</button></a></td>
                  </tr>
                  <tr class="pr-2">
                    <th scope="row">Phone Number</th>
                    <td><?= $Customer_Detail['Customer_Phone_Number'] ?></td>
                    <td><a href="editProfile.php?type=3"><button type="button" class="btn btn-light">Edit</button></a></td>
                  </tr>
                  <tr class="pr-2">
                    <th scope="row">No KTP</th>
                    <td><?= $Customer_Detail['Customer_Identity_Number'] ?></td>
                    <td><a href="editProfile.php?type=4"><button type="button" class="btn btn-light">Edit</button></a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </span>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <span class="border border-dark w-100">
            <div class="p-3 mb-2 bg-light text-dark">
              <h3 class="text-uppercase">History Transaction</h3>
              <?php
              if ($stmt->rowCount() == 0) {
              ?>
                <h4 class="section-subheading text-muted">You didn't win any auction</h4>
              <?php
                $stmt = null;
              } else {
              ?>
                <h4 class="section-subheading text-muted">This displays your current transaction</h4>
                <table class="table table-striped table-dark">
                  <thead>
                    <tr>
                      <th>Product Name</th>
                      <th>Proudct Image</th>
                      <th>Bidding</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    while ($Transaction_Detail = $stmt->fetch()) {
                    ?>
                      <tr>
                        <td class="align-middle">
                          <h4><?= $Transaction_Detail['Product_Name'] ?></h4>
                        </td>
                        <td class="w-50 align-middle">
                          <img class="w-75 img_my_account_height" src="img/product/<?= $Transaction_Detail['Product_Image'] ?>" alt="<?= $Transaction_Detail['Product_Name'] ?>">
                        </td>
                        <td class="align-middle">
                          <h4>Rp <?= number_format($Transaction_Detail['Current_Bid'], 0, ",", ".") ?></h4>
                        </td>
                        <td class="pb-3 align-middle">
                          <?php
                          if ($Transaction_Detail['Transaction_Status'] == "Haven't Paid") {
                          ?>
                            <h4 class="text-danger pr-3"><?= $Transaction_Detail['Transaction_Status'] ?></h4>
                            <a href="payment.php?type=<?=$Transaction_Detail['Transaction_Id']?>"><button class="btn btn-dark mb-2 text-warning">Pay</button></a>
                          <?php
                          } else {
                          ?>
                            <h4 class="text-success pr-3"><?= $Transaction_Detail['Transaction_Status'] ?></h4>
                          <?php
                          }
                          ?>
                        </td>
                      </tr>
                  <?php
                    }
                    $stmt = null;
                  }
                  ?>
                  </tbody>
                </table>
            </div>
          </span>
        </div>
      </div>
    </div>
  </section>

</body>

</html>