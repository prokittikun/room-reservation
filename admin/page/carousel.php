<?php

// Include database configuration
require_once '../config/config_db.php';

// Get database connection
try {
    $conn = connect_db();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to sanitize user inputs (not needed with PDO prepared statements but good practice)
function sanitize($input)
{
    return htmlspecialchars(trim($input));
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new carousel image
    if (isset($_POST['add_carousel'])) {
        $title = sanitize($_POST['title']);
        $description = sanitize($_POST['description']);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $order_num = (int)$_POST['order_num'];

        // Handle file upload
        $target_dir = "../assets/images/carousel/";

        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $file_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . '_' . $file_name;
        $upload_success = false;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Check file size (limit to 5MB)
            if ($_FILES["image"]["size"] < 5000000*4) {
                // Allow certain file formats
                if (in_array($image_file_type, ["jpg", "jpeg", "png", "gif"])) {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $upload_success = true;
                    } else {
                        $_SESSION['error'] = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $_SESSION['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }
            } else {
                $_SESSION['error'] = "Sorry, your file is too large. Max size is 20MB.";
            }
        } else {
            $_SESSION['error'] = "File is not an image.";
        }

        if ($upload_success) {
            try {
                $sql = "INSERT INTO carousel (title, description, image_path, is_active, order_num, created_at) 
                        VALUES (:title, :description, :image_path, :is_active, :order_num, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':image_path', $target_file);
                $stmt->bindParam(':is_active', $is_active, PDO::PARAM_INT);
                $stmt->bindParam(':order_num', $order_num, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Carousel image added successfully.";
                    echo "<script>window.location.href='../admin/index.php?r=carousel';</script>";
                    exit();
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
        }
    }

    // Delete carousel image
    if (isset($_POST['delete_carousel'])) {
        $id = (int)$_POST['id'];

        try {
            // Get image path first to delete the file
            $sql = "SELECT image_path FROM carousel WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Delete file if it exists
                if (file_exists($row['image_path'])) {
                    unlink($row['image_path']);
                }

                // Delete record from database
                $sql = "DELETE FROM carousel WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Carousel image deleted successfully.";
                } else {
                    $_SESSION['error'] = "Error deleting carousel image.";
                }
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }

        echo "<script>window.location.href='../admin/index.php?r=carousel';</script>";
        exit();
    }

    // Update is_active status
    if (isset($_POST['toggle_active'])) {
        $id = (int)$_POST['id'];
        $is_active = (int)$_POST['active_status'] ? 0 : 1; // Toggle current status

        try {
            $sql = "UPDATE carousel SET is_active = :is_active WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':is_active', $is_active, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Carousel status updated successfully.";
            } else {
                $_SESSION['error'] = "Error updating carousel status.";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }

        echo "<script>window.location.href='../admin/index.php?r=carousel';</script>";
        exit();
    }

    // Update order
    if (isset($_POST['update_order'])) {
        $id = (int)$_POST['id'];
        $new_order = (int)$_POST['new_order'];

        try {
            $sql = "UPDATE carousel SET order_num = :order_num WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':order_num', $new_order, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Carousel order updated successfully.";
            } else {
                $_SESSION['error'] = "Error updating carousel order.";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }

        echo "<script>window.location.href='../admin/index.php?r=carousel';</script>";
        exit();
    }
}

// Get all carousel images
$carousel_items = [];
try {
    $sql = "SELECT * FROM carousel ORDER BY order_num ASC";
    $stmt = $conn->query($sql);
    $carousel_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
}
?>

<style>
    .preview-image {
        width: 150px;
        height: 80px;
        object-fit: cover;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .action-buttons form {
        display: inline;
    }
</style>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">จัดการรูปภาพสไลด์</h1>

        <!-- Display messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Add New Carousel Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="col-md-6">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                            <small class="text-muted">Recommended size: 1920x800px. Max: 20MB</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="col-md-3">
                            <label for="order_num" class="form-label">Display Order</label>
                            <input type="number" class="form-control" id="order_num" name="order_num" min="1" value="1">
                        </div>
                        <div class="col-md-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" name="add_carousel" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Add Carousel Image
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Carousel Images List -->
        <div class="card">
            <div class="card-header">
                <h5>Manage Carousel Images</h5>
            </div>
            <div class="card-body">
                <?php if (empty($carousel_items)): ?>
                    <div class="alert alert-info">No carousel images found. Add your first one above!</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Preview</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($carousel_items as $item): ?>
                                    <tr>
                                        <td><?php echo $item['id']; ?></td>
                                        <td>
                                            <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['title']; ?>" class="preview-image">
                                        </td>
                                        <td><?php echo $item['title']; ?></td>
                                        <td>
                                            <?php
                                            echo strlen($item['description']) > 50 ?
                                                substr($item['description'], 0, 50) . '...' :
                                                $item['description'];
                                            ?>
                                        </td>
                                        <td>
                                            <form action="" method="POST" class="d-flex align-items-center">
                                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                <input type="number" name="new_order" class="form-control form-control-sm" value="<?php echo $item['order_num']; ?>" min="1" style="width: 60px">
                                                <button type="submit" name="update_order" class="btn btn-sm btn-outline-secondary ms-2">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="" method="POST">
                                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                <input type="hidden" name="active_status" value="<?php echo $item['is_active']; ?>">
                                                <button type="submit" name="toggle_active" class="btn btn-sm <?php echo $item['is_active'] ? 'btn-success' : 'btn-secondary'; ?>">
                                                    <?php echo $item['is_active'] ? 'Active' : 'Inactive'; ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
                                        <td class="action-buttons">
                                            <!-- <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#previewModal<?php echo $item['id']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button> -->
                                            <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this carousel image?');">
                                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" name="delete_carousel" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Preview Modal -->
                                    <div class="modal fade" id="previewModal<?php echo $item['id']; ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Preview: <?php echo $item['title']; ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-3">
                                                        <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['title']; ?>" class="img-fluid">
                                                    </div>
                                                    <div class="mb-3">
                                                        <h5>Title</h5>
                                                        <p><?php echo $item['title']; ?></p>
                                                    </div>
                                                    <div>
                                                        <h5>Description</h5>
                                                        <p><?php echo $item['description']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>