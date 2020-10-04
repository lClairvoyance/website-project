<?php
session_start();

include "database/database.php";

if (!isset($_SESSION['Customer_Id'])) {
    header("Location: 404.html");
}

if ($_SESSION['Customer_Role'] != "Administration_Worker") {
    header("Location: 404.html");
}

$status = "Waiting";
$stmt = $connection->prepare("
                            SELECT 
                                Product_Id,
                                Product_Name,
                                Product.Customer_Id,
                                Customer_Username,
                                Product_Starting_Price,
                                Product_Description,
                                Product_Image
                            FROM product AS Product
                            JOIN customer AS Customer
                            ON Customer.Customer_Id = Product.Customer_Id
                            WHERE Product_Status LIKE :Product_Status");
$stmt->bindParam("Product_Status", $status, PDO::PARAM_STR);
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

    <link rel="stylesheet" href="css/custom.css">
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
    <div class="d-flex justify-content-center">
        <section class="page-section">
            <div class="container-fluid text-center">
                <div class="p-3 mb-2 bg-light text-dark">
                    <h3 class="section-heading text-uppercase">Submited Product</h3>
                    <?php
                    if ($stmt->rowCount() == 0) {
                    ?>
                        <h4 class="mt-4 text-muted text-uppercase">There is no Product to check</h4>
                    <?php
                    } else {
                    ?>
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Product Starting Price</th>
                                    <th class="w-25">Product Description</th>
                                    <th class="w-25">Product Image</th>
                                    <th colspan="2">Product Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($Product_Detail = $stmt->fetch()) {
                                    $text = substr($_SESSION['Customer_Id'],0,8);
                                    $text .= "-" . substr($_SESSION['Customer_Id'],-8);
                                    $text .= "-" . substr($_SESSION['Customer_Role'],0,2);
                                ?>
                                    <tr>
                                        <td scope="col" class="align-middle"><?= $Product_Detail['Product_Name'] ?></td>
                                        <td scope="col" class="align-middle"><?= $Product_Detail['Customer_Username'] ?></td>
                                        <td scope="col" class="align-middle">Rp<?= number_format($Product_Detail['Product_Starting_Price'], 0, ",", ".") ?></td>
                                        <td class="w-25 align-middle"><?= $Product_Detail['Product_Description'] ?></td>
                                        <td class="w-25 align-middle"><img class="w-100 img_sell_your_stuff_height" src="img/product/<?= $Product_Detail['Product_Image'] ?>" alt="<?= $Product_Detail['Product_Name'] ?>"></td>
                                        <td scope="col" class="align-middle"><a class="text-success" href="controller/StatusUpdate.php?type=<?=$text?>AC-<?=$Product_Detail['Product_Id']?>">Accept</a></td>
                                        <td scope="col" class="align-middle"><a class="text-danger" href="controller/StatusUpdate.php?type=<?=$text?>RJ-<?=$Product_Detail['Product_Id']?>">Reject</a></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>


</body>

</html>