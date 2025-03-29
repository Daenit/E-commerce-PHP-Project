<?php
require_once "db.php";
require_once "Category.php";

$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category->name = $_POST["name"];
    $category->type = $_POST["type"];

    // Handle Image Upload
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $category->image = $target_file;
    } else {
        if (isset($_POST["old_image"])) {
            $category->image = $_POST["old_image"];
        }
    }

    // Update Category
    if (!empty($_POST["id"])) {
        $category->id = $_POST["id"];
        if ($category->update()) {
            echo "<script>alert('Category updated successfully!'); window.location.href='categories.php';</script>";
        } else {
            echo "<script>alert('Error updating category.');</script>";
        }
    } 
    // Add New Category
    else {
        if ($category->create()) {
            echo "<script>alert('Category added successfully!'); window.location.href='categories.php';</script>";
        } else {
            echo "<script>alert('Error adding category.');</script>";
        }
    }
}

// Delete Category
if (isset($_GET["delete"])) {
    $category->id = $_GET["delete"];
    if ($category->delete()) {
        echo "<script>alert('Category deleted successfully!'); window.location.href='categories.php';</script>";
    } else {
        echo "<script>alert('Error deleting category.');</script>";
    }
}

$categories = $category->readAll();
?>

<div class="container">
    <div class="page-inner">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h2 class="card-title">Manage Categories</h2>
                <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#categoryModal">
                    <i class="fa fa-plus"></i> Add Category
                </button>
            </div>
        </div>

        <!-- Add & Edit Modal -->
        <div class="modal fade" id="categoryModal" tabindex="-1">
            <div class="modal-dialog">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Category Form</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="categoryId">
                    <input type="hidden" name="old_image" id="oldImage">

                    <label>Name:</label>
                    <input type="text" name="name" class="form-control" id="categoryName" required>

                    <label>Image:</label>
                    <input type="file" name="image" class="form-control">
                    <img id="previewImage" src="" width="50" class="mt-2">

                    <label>Type:</label>
                    <input type="text" name="type" id="categoryType" class="form-control" required><br>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add" id="saveCategory" class="btn btn-success">Save Category</button>
                    <button type="submit" name="edit" id="editCategory" class="btn btn-success d-none">Update Category</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>

                </div>
            </div>
        </div>

        <!-- Category Table -->
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Type</th>
                    <th style="width: 15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><img src="<?= htmlspecialchars($row['image']) ?>" width="50"></td>
                        <td><?= htmlspecialchars($row['type']) ?></td>
                        <td>
                            <!-- Edit Button -->
                            <button class="btn btn-primary editBtn"
                                data-id="<?= $row['id'] ?>"
                                data-name="<?= htmlspecialchars($row['name']) ?>"
                                data-image="<?= htmlspecialchars($row['image']) ?>"
                                data-type="<?= htmlspecialchars($row['type']) ?>"
                                data-bs-toggle="modal" data-bs-target="#categoryModal"
                                data-edit="true">
                                <i class="fa fa-edit"></i>
                            </button>
                            
                            <!-- Delete Button -->
                            <form method="post" style="display:inline;">
                            <input type="hidden" name="slideshow_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure?');"> <i class="fa fa-times"></i></button>
                        </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>

        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".editBtn").forEach(button => {
        button.addEventListener("click", function() {
            document.getElementById("categoryId").value = this.getAttribute("data-id");
            document.getElementById("categoryName").value = this.getAttribute("data-name");
            document.getElementById("categoryType").value = this.getAttribute("data-type");
            document.getElementById("oldImage").value = this.getAttribute("data-image");
            document.getElementById("previewImage").src = this.getAttribute("data-image");

            // Show Update button and hide Add button
            document.getElementById("saveCategory").classList.add("d-none");
            document.getElementById("editCategory").classList.remove("d-none");
        });
    });

    document.querySelector("[data-bs-target='#categoryModal']").addEventListener("click", function() {
        document.getElementById("categoryId").value = "";
        document.getElementById("categoryName").value = "";
        document.getElementById("categoryType").value = "";
        document.getElementById("oldImage").value = "";
        document.getElementById("previewImage").src = "";

        // Show Add button and hide Update button
        document.getElementById("saveCategory").classList.remove("d-none");
        document.getElementById("editCategory").classList.add("d-none");
    });
});
</script>

