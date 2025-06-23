<?php require_once ('_header.php'); ?>
<?php
   
    if ($conn) {
        echo "Database connection successful!";
    } else {
        echo "Failed to connect to the database.";
    }
?>
    <h1>Welcome to the E-commerce Site</h1>

    <a href="products.php">products</a>
    <a href="">Customers</a>
    <a href="productscategory.php">Categories</a>
    <a href="productscategory.php?id=">Category</a>

    <form   action="create_product.php" method="POST" class="container" >
    <div class="form-row">
        <!-- <div class="form-group col-md-6">
        <label for="inputEmail4">ID</label>
        <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
        </div> -->
        <div class="form-group col-md-6">
        <label for="inputPassword4">Name</label>
        <input type="text" class="form-control" id="inputPassword4" placeholder="NAME" name="name">
        </div>
    </div>
    <div class="form-group">
        <label for="inputAddress">Brand</label>
        <input type="text" class="form-control" id="inputAddress" placeholder="BRAND" name="brand">
    </div>
    <!-- <div class="form-group">
        <label for="inputAddress2">Address 2</label>
        <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
        <label for="inputCity">City</label>
        <input type="text" class="form-control" id="inputCity">
        </div>
        <div class="form-group col-md-4">
        <label for="inputState">State</label>
        <select id="inputState" class="form-control">
            <option selected>Choose...</option>
            <option>...</option>
        </select>
        </div>
        <div class="form-group col-md-2">
        <label for="inputZip">Zip</label>
        <input type="text" class="form-control" id="inputZip">
        </div>
    </div>
    <div class="form-group">
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="gridCheck">
        <label class="form-check-label" for="gridCheck">
            Check me out
        </label>
        </div>
    </div> -->
    <button type="submit" class="btn btn-primary">CREATE</button>
    </form>

<?php require_once ('_footer.php'); ?>