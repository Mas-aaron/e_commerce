<?php
// Include the database connection file
require_once 'config/DBClass.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'] ?? '';
    $brand = $_POST['brand'] ?? '';

    // Validate the input
    if (empty($name) || empty($brand)) {
        die("Please fill in all required fields.");
    }

    try {
        // Prepare SQL statement to insert the product
        $stmt = $dbh->prepare("INSERT INTO products (name, brand) VALUES (:name, :brand)");
        
        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':brand', $brand);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back to index.php with success message
            header("Location: index.php?status=success");
            exit();
        } else {
            echo "Error: Could not save the product.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    // If not a POST request, redirect to index
    header("Location: index.php");
    exit();
}
?>