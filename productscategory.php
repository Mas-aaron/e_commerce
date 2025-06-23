<?php require_once ('_header.php'); ?>

<?php
// Database connection check
/**if ($conn) {
    echo "Database connection successful!";
} else {
    echo "Failed to connect to the database.";
}*/

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle category creation
    if (isset($_POST['btnCategory'])) {
        $categoryName = trim($_POST['category_name'] ?? '');
        
        if (empty($categoryName)) {
            $_SESSION['message'] = ['type' => 'warning', 'text' => 'Category name is required'];
        } else {
            $tablename = 'category';
            $data = ['category_name' => $categoryName];

            try {
                $categorystatus = $dbclassinstance->insert($tablename, $data);
                if ($categorystatus) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Category created successfully!'];
                }
            } catch (Exception $e) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error: ' . $e->getMessage()];
            }
        }
    }
    
    // Handle category update
    if (isset($_POST['btnUpdate'])) {
        $categoryId = (int)$_POST['category_id'];
        $categoryName = trim($_POST['category_name'] ?? '');
        
        if (empty($categoryName)) {
            $_SESSION['message'] = ['type' => 'warning', 'text' => 'Category name is required'];
        } else {
            try {
                $success = $dbclassinstance->update(
                    'category',
                    ['category_name' => $categoryName],
                    ['id' => $categoryId]
                );
                
                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Category updated successfully!'];
                } else {
                    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Failed to update category'];
                }
            } catch (Exception $e) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error: ' . $e->getMessage()];
            }
        }
    }
}

// Handle category deletion
if (isset($_GET['delete'])) {
    $categoryId = (int)$_GET['delete'];
    
    try {
        // First check if category exists
        $category = $dbclassinstance->select('category', ['id' => $categoryId]);
        if (empty($category)) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Category not found'];
        } else {
            // Perform deletion
            $success = $dbclassinstance->delete('category', ['id' => $categoryId]);
            
            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Category deleted successfully!'];
            } else {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Failed to delete category'];
            }
        }
    } catch (Exception $e) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error: ' . $e->getMessage()];
    }
    
    header('Location: productscategory.php');
    exit();
}

// Display any messages
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-{$_SESSION['message']['type']}'>{$_SESSION['message']['text']}</div>";
    unset($_SESSION['message']);
}

// Fetch all categories from the database
try {
    $categories = $dbclassinstance->select('category');
    $editingCategory = null;
    
    // Check if we're editing a category
    if (isset($_GET['edit'])) {
        $categoryId = (int)$_GET['edit'];
        $editingCategory = $dbclassinstance->select('category', ['id' => $categoryId]);
        if (!empty($editingCategory)) {
            $editingCategory = $editingCategory[0];
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Category not found'];
            header('Location: productscategory.php');
            exit();
        }
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error fetching categories: " . $e->getMessage() . "</div>";
    $categories = []; // Empty array if there's an error
}
?>

<div class="breadcrumb">
    <a href="index.php">Home</a>
    <a href="products.php">Products</a>
    <a href="">Customers</a>
</div>

<h3>Product Category</h3>
<div class="row">
    <div class="col">
        <?php if (isset($editingCategory)): ?>
            <!-- Edit Category Form -->
            <form action="" method="POST" class="container">
                <input type="hidden" name="category_id" value="<?php echo $editingCategory['id']; ?>">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputName">Name</label>
                        <input type="text" class="form-control" id="inputName" 
                               placeholder="Category Name" name="category_name" 
                               value="<?php echo htmlspecialchars($editingCategory['category_name']); ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" name="btnUpdate">UPDATE</button>
                <a href="productscategory.php" class="btn btn-secondary">CANCEL</a>
            </form>
        <?php else: ?>
            <!-- Create Category Form -->
            <form action="" method="POST" class="container">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputName">Name</label>
                        <input type="text" class="form-control" id="inputName" 
                               placeholder="Category Name" name="category_name" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" name="btnCategory">CREATE</button>
            </form>
        <?php endif; ?>
    </div>
    <div class="col">
        <table class="table table-striped" border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($category['id'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($category['category_name'] ?? ''); ?></td>
                            <td>
                                <a href="productscategory.php?edit=<?php echo $category['id']; ?>" 
                                   class="btn btn-sm btn-warning">Edit</a>
                                <a href="productscategory.php?delete=<?php echo $category['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                            </td>
                            
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No categories found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '_footer.php'; ?>