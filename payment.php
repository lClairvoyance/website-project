<?php

session_start();
include "controller/time_controller.php";
include "controller/check_controller.php";
include "database/database.php";
if (!isset($_SESSION['Customer_Id'])) {
  header("Location: 404.html");
}

$Transaction_Id = check_input($_GET['type']);

$stmt = $connection->prepare("SELECT 
                                      Transaction_Id,
                                      HTransaction.Product_Id,
                                      HTransaction.Customer_Id,
                                      End_Date,
                                      Current_Bid,
                                      Transaction_Status,
                                      Product_Name,
                                      Product_Status
                                FROM header_transaction AS HTransaction
                                JOIN product AS Product
                                WHERE Transaction_Id LIKE :Transaction_Id
                              ");
$stmt->bindParam("Transaction_Id", $Transaction_Id, PDO::PARAM_STR);
$stmt->execute();
$Transaction_Detail = $stmt->fetch();

$End_Date = new DateTime($Transaction_Detail['End_Date']);
$now_date = new DateTime(get_Full_Display_Time());
$result = $now_date->format('U') - $End_Date->format('U');


if ($stmt->rowCount() == 0) {
  header("Location: 404.html");
} else if ($_SESSION['Customer_Id'] != $Transaction_Detail['Customer_Id']) {
  header("Location: 404.html");
} else if ($Transaction_Detail['Product_Status'] != "Finished") {
  header("Location: 404.html");
} else if ($Transaction_Detail['Transaction_Status'] != "Haven't Paid") {
  header("Location: 404.html");
} else if ($result < 0) {
  die();
  header("Location: 404.html");
}

$Year = substr(get_Full_Display_Time(), 0, 4);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Bona Fide Supply - Login</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">




  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
  <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

  <link href="css/agency.min.css" rel="stylesheet">
  <link href="css/payment.css" rel="stylesheet">
</head>
<style>
  body {
    background-image: url('header-bg.jpg');
  }
</style>

<body class="mt-5">

  <div class="container">
    <br>
    <div class="col-lg-12 gap">
      <h1>Payment</h1>
      <h3><?= $Transaction_Detail['Product_Name'] ?> - Rp<?= number_format($Transaction_Detail['Current_Bid'], 0, ",", ".") ?></h3>
    </div>
    <br>
    <div class="col-lg-12 row">
      <div class="col-75">
        <div class="container">
          <form action="controller/payment.php?type=<?=$Transaction_Id?>" method="POST">
            <div class="row">
              <div class="col-50">
                <h3>Billing Address</h3>
                <label for="fname">Full Name</label>
                <input type="text" id="fname" name="fullName" placeholder="Full Name">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="example@example.com">
                <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
                <input type="text" id="adr" name="address" placeholder="Jl. Kebon Jeruk Raya No. 27">
                <label for="city"><i class="fa fa-institution"></i> City</label>
                <input type="text" id="city" name="city" placeholder="Jakarta">

                <div class="row">
                  <div class="col-50">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" placeholder="Jawa Barat">
                  </div>
                  <div class="col-50">
                    <label for="zip">Zip</label>
                    <input type="text" id="zip" name="zip" placeholder="11530">
                  </div>
                </div>
              </div>

              <div class="col-50">
                <h3>Credit Card</h3>
                <label for="cname">Name on Card</label>
                <input type="text" id="cname" name="cardName" placeholder="Name On Card">
                <label for="ccnum">Credit Card Number</label>
                <input type="text" id="ccnum" name="cardNumber" placeholder="">

                <label for="expyear">Expired Card</label>
                <div class="form-inline">
                  <select class="custom-select" name="expiredMonth">
                    <option selected>MM</option>
                    <option value="1">1-January</option>
                    <option value="2">2-February</option>
                    <option value="3">3-March</option>
                    <option value="4">4-April</option>
                    <option value="5">5-May</option>
                    <option value="6">6-June</option>
                    <option value="7">7-July</option>
                    <option value="8">8-August</option>
                    <option value="9">9-September</option>
                    <option value="10">10-October</option>
                    <option value="11">11-November</option>
                    <option value="12">12-December</option>
                  </select>
                  <div class="text-muted ml-3 mr-3 mt-2">
                    <h3>/</h3>
                  </div>
                  <select class="custom-select w-25" name="expiredYear">
                    <option selected>YYYY</option>
                    <?php
                    for ($i = 0; $i < 15; $i++) {
                    ?>
                      <option value="<?= $Year ?>"><?= $Year ?></option>
                    <?php
                      $Year += 1;
                    }
                    ?>
                  </select>
                </div>
                <div class="mt-2">
                  <label for="cvv">CVV</label>
                  <input type="text" class="w-25" id="cvv" name="cvv" placeholder="XXX">
                </div>
              </div>

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
            <input type="submit" value="Continue to checkout" class="btn">
            <button class="btn-dark"><a href="myaccount.html">Cancel</a></button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>