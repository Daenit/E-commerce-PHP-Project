<?php
require_once "db.php";
require_once "Product.php";
require_once "Category.php";

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$category = new Category($db);

$products = $product->readAll();
$categories = $category->readAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) {

        $product->name = $_POST["name"];
        $product->price = $_POST["price"];
        $product->qty = $_POST["qty"];
        $product->category_id = $_POST["category_id"];

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $product->image = $target_file;

        if ($product->create()) {
            echo "<p>Product added successfully!</p>";
        } else {
            echo "<p>Error adding product.</p>";
        }
    } elseif (isset($_POST["edit"])) {

        $product->id = $_POST["product_id"];
        $product->name = $_POST["name"];
        $product->price = $_POST["price"];
        $product->qty = $_POST["qty"];
        $product->category_id = $_POST["category_id"];

        if (!empty($_FILES["image"]["name"])) {
            $target_file = "uploads/" . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $product->image = $target_file;
        } else {
            $product->image = $_POST["current_image"];
        }

        if ($product->update()) {
            echo "<p>Product updated successfully!</p>";
        } else {
            echo "<p>Error updating product.</p>";
        }
    } elseif (isset($_POST["delete"])) {

        $product->id = $_POST["product_id"];
        if ($product->delete()) {
            echo "<p>Product deleted successfully!</p>";
        } else {
            echo "<p>Error deleting product.</p>";
        }
    }
}

$products = $product->readAll();
$categories = $category->readAll();
?>

<div class="container">
    <div class="page-inner">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h2 class="card-title">Manage Products</h2>
                <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa fa-plus"></i>Add Product</button>
            </div>
        </div>
        
        <!-- Add Product Modal -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Product</h5>
                            <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control" required>

                            <label>Image:</label>
                            <input type="file" name="image" class="form-control" required>

                            <label>Price:</label>
                            <input type="number" name="price" step="0.01" class="form-control" required>

                            <label>Quantity:</label>
                            <input type="number" name="qty" class="form-control" required>

                            <label>Category:</label>
                            <select name="category_id" class="form-control" required>
                                <?php while ($row = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add" class="btn btn-success">Add Product</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <table class="display table table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th style="width: 15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $row['name'] ?></td>
                        <td><img src="<?= $row['image'] ?>" width="50"></td>
                        <td><?= $row['category_name'] ?></td>
                        <td><?= $row['price'] ?> USD</td>
                        <td><?= $row['qty'] ?></td>
                        <td>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>"><i class="fa fa-edit"></i></button> 
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure?');"> <i class="fa fa-times"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Product Modal -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Product</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">

                                        <label>Name:</label>
                                        <input type="text" name="name" class="form-control" value="<?= $row['name'] ?>" required>

                                        <label>Image:</label>
                                        <input type="file" name="image" class="form-control">
                                        <input type="hidden" name="current_image" value="<?= $row['image'] ?>">
                                        <img src="<?= $row['image'] ?>" width="50">

                                        <label>Price:</label>
                                        <input type="number" name="price" step="0.01" class="form-control" value="<?= $row['price'] ?>" required>

                                        <label>Quantity:</label>
                                        <input type="number" name="qty" class="form-control" value="<?= $row['qty'] ?>" required>

                                        <label>Category:</label>
                                        <select name="category_id" class="form-control" required>
                                            <?php while ($cat = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                                                <option value="<?= $row['id'] ?>" <?= ($reo['id'] == $row['category_id']) ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="edit" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
