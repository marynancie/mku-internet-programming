<?php
$start = $_GET['start'] ?? 0;

require_once 'model/ProductsManager.php';
$productMan = new ProductsManager();
$allProducts = $productMan->getAll($start);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WELCOME TO FAVORED ONLINE SHOP</title>
    <link rel="stylesheet" href="libs/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="libs/css/notify-base.css">
    <link rel="stylesheet" href="libs/icons/mdi/css/materialdesignicons.min.css">

    <script src="libs/js/jquery-3.5.1.js"></script>

</head>

<body>

    <div class="container">
        <div class="header m-1">
            <div class="mainHeader  d-flex  align-items-center">
                <div class="float-left">
                    <button id="navToggler" class="btn btn-outline-info">Nav</button>
                </div>
                <h2 class="mx-1">FAVOR'S SHOP</h2>
                <div class="ml-auto">
                    <span class="headerIconWrapper">
                        <i class="mdi mdi-cart-arrow-down"></i>
                        <span class="cartCounter px-1 bg-info text-muted text-bold">0</span>
                    </span>

                    <span class=" mr-2 headerIconWrapper">
                        <i class="mdi mdi-account-circle-outline"></i>
                    </span>
                </div>
            </div>
            <div class="marquee">
                <marquee direction="left">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit illum
                    accusantium quae nam architecto illo nisi harum, quasi, vero fugit dolorum, soluta eaque ut esse
                    accusamus magnam labore sapiente atque.
                </marquee>
            </div>
        </div>
        <div class="mainbody d-flex">
            <Nav class="nav" id="sideNav">
                <ul class="list-unstyled">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="Clothings.php">Clothins</a></li>
                    <li><a href="Shoes.php">Shoes</a></li>
                    <li><a href="Handbags.php">Handbags</a></li>
                    <li><a href="account.php">My Account</a></li>
                    <li><a href="ContactUs.php">Contact Us</a></li>
                    <li><a href="About.php">About Us</a></li>
                </ul>
            </Nav>
            <main class="mainContent flex-grow-1 mx-1">
                <div class="displayCard">


                </div>
                <div class="checkOutSection  my-2">
                    <div class="bg-primary my-3 rounded">
                        <div class="d-flex">
                            <i class="mdi mdi-map-marker-radius "></i>  
                            <i>Delivery Set To <span id="deliveryLocation" class="text-info">Nairobi,CBD</span></i>
                            <span class="text-bold toggleMapView ml-auto mr-2" onclick="toggleMapView()" style="cursor: pointer;">CHANGE NOW</span>
                        </div>
                        <div id="mapView" class="hide">
                           <div class="card my-1">
                               <div class="card-header"> 
                                   <h6 class="text-center">Select Preffed Delivery Location</h6>
                                </div>
                               <div class="card-body">
                                  <div class="mapouter"><div class="gmap_canvas"><iframe width="734" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=mku&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://fmovies-online.net">fmovies</a><br><style>.mapouter{position:relative;text-align:right;height:500px;width:734px;}</style><a href="https://www.embedgooglemap.net">google maps html embed</a><style>.gmap_canvas {overflow:hidden;background:none!important;height:500px;width:734px;}</style></div></div>
                               </div>
                               <div class="card-footer d-flex justify-content-end">
                                    <span class="btn btn-warning">Cancel</span>
                                    <span class="btn btn-success ml-1">Confirm</span>
                               </div>
                           </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div >
                            <div>
                                <span>Total Cost: </span> <span id='totalCosts' class="text-info">500Ksh</span> <span class="ml-3">(Shipping Costs <span id="shippingCostsDisplay" class="text-info text-bold">23Ksh</span> inc.)</span>
                            </div>
                            <div >
                                <span>Wallet Balance: </span> <span class="text-info">1500Ksh</span>
                            </div>
                        </div>
                        <span class="ml-auto btn btn-info">
                            <i class="mdi mdi-cart"></i>
                            <span class=" ml-1 text-bold" onclick="confirmCartDetails()">Checkout Now</span>
                        </span>
                    </div>
                </div>
            </main>
        </div>
        <div class="footer"></div>
    </div>
</body>
<script src="libs/js/notify.js"></script>
<?php
$products = $allProducts ? json_encode($allProducts) : 'false';
echo "<script> let  products=$products </script>";
?>
<script src="assets/js/main.js"></script>
<script>
    /*display products if found*/
    if (products !== undefined) displayItems([products], '.displayCard');
</script>

</html>
