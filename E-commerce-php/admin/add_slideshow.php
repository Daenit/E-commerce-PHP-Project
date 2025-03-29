<?php
require_once "db.php";
require_once "Slideshow.php";

$database = new Database();
$db = $database->getConnection();
$slideshow = new Slideshow($db);

    // Handle form submissions
    // if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //     if (isset($_POST["add"])) {
    //         $slideshow->name = $_POST["name"];
    //         $slideshow->text = $_POST["text"];
    //         $slideshow->button = $_POST["button"];
    //         $slideshow->services = $_POST["services"];

    //         // Handle Image Upload
    //         if (!empty($_FILES["image"]["name"])) {
    //             $target_dir = "uploads/";
    //             $target_file = $target_dir . basename($_FILES["image"]["name"]);
    //             move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    //             $slideshow->image = $target_file;
    //         }

    //         if ($slideshow->create()) {
    //             echo "<script>alert('Slideshow added successfully!'); window.location.href='index.php?p=slideshow';</script>";
    //         } else {
    //             echo "<script>alert('Error adding slideshow.');</script>";
    //         }
    //     }
    // }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["add"])) {
            $slideshow->name = $_POST["name"];
            $slideshow->text = $_POST["text"];
            $slideshow->button = $_POST["button"];
            $slideshow->services = $_POST["services"];
    
            // Handle Image Upload
            if (!empty($_FILES["image"]["name"])) {
                $allowed_types = ["image/jpeg", "image/png"];
                $file_type = mime_content_type($_FILES["image"]["tmp_name"]);
                
                if (in_array($file_type, $allowed_types)) {
                    $target_dir = "uploads/";
                    $file_name = time() . "_" . basename($_FILES["image"]["name"]); // Unique file name
                    $target_file = $target_dir . $file_name;
    
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $slideshow->image = $target_file;
                    } else {
                        echo "<script>alert('Error uploading file.');</script>";
                        exit; // Stop execution if upload fails
                    }
                } else {
                    echo "<script>alert('Invalid file type. Only JPG and PNG allowed.');</script>";
                    exit; // Stop execution if file type is invalid
                }
            }
    
            if ($slideshow->create()) {
                echo "<script>alert('Slideshow added successfully!'); window.location.href='index.php?p=slideshow';</script>";
            } else {
                echo "<script>alert('Error adding slideshow.');</script>";
            }
        }
    }
    
    // Edit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["edit"])) {
            $slideshow->id = $_POST["id"];
            $slideshow->name = $_POST["name"];
            $slideshow->text = $_POST["text"];
            $slideshow->button = $_POST["button"];
            $slideshow->services = $_POST["services"];
    
            // Handle Image Upload
            if (!empty($_FILES["image"]["name"])) {
                $target_file = "uploads/" . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $slideshow->image = $target_file;
            } else {
                $slideshow->image = $_POST["old_image"];
            }
    
            if ($slideshow->update()) {
                echo "<script>alert('Slideshow updated successfully!'); window.location.href='index.php?p=slideshow';</script>";
            } else {
                echo "<script>alert('Error updating slideshow.');</script>";
            }
        }
    }
    
    // Handle delete request
    if (isset($_POST["delete"])) {
        $slideshow->id = $_POST["slideshow_id"];
        if ($slideshow->delete()) {
            echo "<script>alert('Slideshow deleted successfully!'); window.location.href='index.php?p=slideshow';</script>";
        } else {
            echo "<script>alert('Error deleting slideshow.');</script>";
        }
    }
    
// Fetch slideshows
$slideshows = $slideshow->readAll();
?>

<div class="container">
    <div class="page-inner">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h2 class="card-title">Manage Categories</h2>
                <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fa fa-plus"></i> Add Category
                </button>
            </div>
        </div>

        <!-- Add & Edit Modal -->
        <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Slideshow</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Name:</label>
                        <input type="text" name="name" class="form-control" required>
                        <label>Image:</label>
                        <input type="file" name="image" class="form-control">
                        <label>Text:</label>
                        <input type="text" name="text" class="form-control">
                        <label>Button:</label>
                        <input type="text" name="button" class="form-control">
                        <label>Services:</label>
                        <input type="text" name="services" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Image</th>
                <th>Text</th>
                <th>Button</th>
                <th>Services</th>
                <th style="width: 15%">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $slideshows->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><img src="<?= htmlspecialchars($row['image']) ?>" width="50"></td>
                    <td><?= htmlspecialchars($row['text']) ?></td>
                    <td><?= htmlspecialchars($row['button']) ?></td>
                    <td><?= htmlspecialchars($row['services']) ?></td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-primary editBtn"
                            data-id="<?= $row['id'] ?>"
                            data-name="<?= htmlspecialchars($row['name']) ?>"
                            data-image="<?= htmlspecialchars($row['image']) ?>"
                            data-text="<?= htmlspecialchars($row['text']) ?>"
                            data-button="<?= htmlspecialchars($row['button']) ?>"
                            data-services="<?= htmlspecialchars($row['services']) ?>"
                            data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fa fa-edit"></i>
                        </button>

                        <!-- Delete Button -->
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="slideshow_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure?');"> <i class="fa fa-times"></i></button>
                        </form>
                    </td>
                </tr>
                <div class="modal fade" id="editModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Slideshow</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" id="editId">
                                    <input type="hidden" name="old_image" id="oldImage">

                                    <label>Name:</label>
                                    <input type="text" name="name" class="form-control" id="editName" required>

                                    <label>Image:</label>
                                    <input type="file" name="image" class="form-control" >
                                    <img id="editImagePreview" src="" width="50" class="mt-2">

                                    <label>Text:</label>
                                    <input type="text" name="text" class="form-control" id="editText">

                                    <label>Button:</label>
                                    <input type="text" name="button" class="form-control" id="editButton">

                                    <label>Services:</label>
                                    <input type="text" name="services" class="form-control" id="editServices">
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".editBtn").forEach(button => {
            button.addEventListener("click", function() {
                document.getElementById("editId").value = this.dataset.id;
                document.getElementById("editName").value = this.dataset.name;
                document.getElementById("editText").value = this.dataset.text;
                document.getElementById("editButton").value = this.dataset.button;
                document.getElementById("editServices").value = this.dataset.services;
                document.getElementById("oldImage").value = this.dataset.image;
                document.getElementById("editImagePreview").src = this.dataset.image;
            });
        });
    });
</script>

