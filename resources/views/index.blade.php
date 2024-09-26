<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .price {
            float: right;
            color: grey;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2>Responsive Checkout Form</h2>
        <p>Resize the browser window to see the effect. When the screen is less than 800px wide, the two columns stack
            on top of each other.</p>
        <div class="row">
            <!-- Billing Form -->
            <div class="col-lg-8 col-md-12 mb-4">
                <div class="p-4 border rounded">
                    <form action="/action_page.php">

                        <div class="mb-3">
                            <h3>Billing Address</h3>
                        </div>

                        <div class="form-group">
                            <label for="fname"><i class="fa fa-user"></i> Full Name</label>
                            <input type="text" id="fname" name="firstname" class="form-control"
                                placeholder="John M. Doe">
                        </div>

                        <div class="form-group">
                            <label for="email"><i class="fa fa-envelope"></i> Email</label>
                            <input type="text" id="email" name="email" class="form-control"
                                placeholder="john@example.com">
                        </div>

                        <div class="form-group">
                            <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
                            <input type="text" id="adr" name="address" class="form-control"
                                placeholder="542 W. 15th Street">
                        </div>

                        <div class="form-group">
                            <label for="city"><i class="fa fa-institution"></i> City</label>
                            <input type="text" id="city" name="city" class="form-control"
                                placeholder="New York">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="state">State</label>
                                <input type="text" id="state" name="state" class="form-control"
                                    placeholder="NY">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" id="zip" name="zip" class="form-control"
                                    placeholder="10001">
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="sameadr" name="sameadr" checked>
                            <label class="form-check-label" for="sameadr">Shipping address same as billing</label>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">Continue to checkout</button>
                    </form>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4 col-md-12">
                <div class="p-4 border rounded">
                    <h4>Cart <span class="price" style="color:black"><i class="fa fa-shopping-cart"></i>
                            <b>4</b></span></h4>
                    <p><a href="#">Product 1</a> <span class="price">$15</span></p>
                    <p><a href="#">Product 2</a> <span class="price">$5</span></p>
                    <p><a href="#">Product 3</a> <span class="price">$8</span></p>
                    <p><a href="#">Product 4</a> <span class="price">$2</span></p>
                    <hr>
                    <p>Total <span class="price" style="color:black"><b>$30</b></span></p>
                </div>
                <form class="card p-2 mt-2">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Promo code">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary">Redeem</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
