<?php require_once ('_header.php'); ?>

<?php
// Display session messages
if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";
    unset($_SESSION['error_message']);
}
?>

<div class="breadcrumb">
    <a href="index.php">Home</a>
    <a href="products.php">products</a>
    <a href="">Customers</a>
    Our Products
</div>
<div class="container">
    <h1>Products</h1>
    
    <?php
    // Display status messages
    if (isset($_GET['status'])) {
        switch ($_GET['status']) {
            case 'success':
                echo '<div class="alert alert-success">Product created successfully!</div>';
                break;
            case 'updated':
                echo '<div class="alert alert-success">Product updated successfully!</div>';
                break;
            case 'deleted':
                echo '<div class="alert alert-success">Product deleted successfully!</div>';
                break;
            case 'error':
                echo '<div class="alert alert-danger">Error processing your request.</div>';
                break;
            case 'dberror':
                echo '<div class="alert alert-danger">Database error occurred.</div>';
                break;
        }
    }
    ?>
    
    <div class="mb-3">
        <a href="create_product.php" class="btn btn-primary">Add New Product</a>
    </div>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Date/Time Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                // Fetch all products from database
                $stmt = $dbh->query("SELECT * FROM products ORDER BY id ASC");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($products) > 0) {
                    foreach ($products as $product) {
                        echo "<tr>
                                <td>{$product['id']}</td>
                                <td>{$product['name']}</td>
                                <td>{$product['brand']}</td>
                                <td>{$product['created_at']}</td>
                                <td>
                                    <a href="edit_category.php?id=<?php echo $category['id']; ?>" 
                                    class="btn btn-sm btn-warning">Edit</a>
                                    <form action="delete_category.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                                    </form>
                                </td>
</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No products found. Add your first product!</td></tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='5'>Error fetching products: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php require_once '_footer.php'; ?>