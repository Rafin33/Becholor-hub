<?php
// Include necessary files
require_once '../app/models/FoodModel.php';


// Create an instance of the model
$foodModel = new FoodModel();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}
$userId=$_SESSION['user_id'];

$error = $successMessage = "";

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $details = trim($_POST['details']);
    $photo = null;

    // Handle file upload (if provided)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/food/';
        $fileName = time() . "_" . basename($_FILES['photo']['name']); // Avoid duplicates
        $filePath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Check file type (security measure)
        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                $photo = $filePath;
            } else {
                $error = "Error uploading the file.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    }

    if (empty($details)) {
        $error = "Please fill in the food details.";
    }

    if ($_POST['action'] === 'add') {
        if (empty($photo)) {
            $error = "Please upload a photo.";
        }

        if (!$error) {
            $isAdded = $foodModel->addFood($userId, $details, $photo);
            $successMessage = $isAdded ? "Food post added successfully!" : "Error adding post.";
        }
    } elseif ($_POST['action'] === 'edit' && isset($_POST['food_id'])) {
        $foodId = intval($_POST['food_id']);
        $food = $foodModel->getFoodById($foodId);

        if ($food && $food['user_id'] == $userId) {
            $updatedPhoto = $photo ?? $food['photo'];
            $isUpdated = $foodModel->updateFood($foodId, $details, $updatedPhoto);
            $successMessage = $isUpdated ? "Food post updated successfully!" : "Error updating post.";
        } else {
            $error = "Unauthorized edit attempt.";
        }
    }
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $foodId = intval($_POST['delete_id']);
    $food = $foodModel->getFoodById($foodId);

    if ($food&&$food['user_id'] == $userId) {
        $isDeleted = $foodModel->deleteFood($foodId);
        $successMessage = $isDeleted ? "Food post deleted successfully!" : "Error deleting post.";
    } else {
        $error = "Unauthorized delete attempt.";
    }
}

// Fetch all food posts from the database
$foodPosts = $foodModel->getAllFood();
?>

<?php include('assets/header.php'); ?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Create / Edit Food Post</h2>

    <!-- Display success or error message -->
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Food Post Form -->
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="food_id" id="food_id">
        <input type="hidden" name="action" id="action" value="add">

        <div class="form-group">
            <label for="details">Food Details</label>
            <textarea class="form-control" id="details" name="details" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="photo">Upload Photo</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary btn-block" id="submitBtn">Submit Post</button>
    </form>

    <hr class="mt-5">

    <h2 class="text-center mb-4">All Food Posts</h2><a  class="btn btn-information" href="?view"> Show my own post</a>

    <div class="row">
        <?php if (count($foodPosts) > 0): ?>
            <?php foreach ($foodPosts as $food): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($food['photo']); ?>" class="card-img-top" alt="Food Image">
                        <div class="card-body">
                            <h5 class="card-title">Food Details</h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($food['details'])); ?></p>
                            <?php if ($_SESSION['user_id'] == $food['user_id']&&isset($_GET['view'])) : ?>                            
                            <button class="btn btn-info edit-btn" 
                                    data-id="<?php echo $food['id']; ?>" 
                                    data-details="<?php echo htmlspecialchars($food['details']); ?>" 
                                    data-photo="<?php echo htmlspecialchars($food['photo']); ?>">
                                Edit
                            </button>
                            
                            <form action="" method="POST" class="d-inline">
                                <input type="hidden" name="delete_id" value="<?php echo $food['id']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">
                                    Delete
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No food posts available.</p>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener("submit", function (event) {
        let details = document.getElementById("details").value.trim();
        let photo = document.getElementById("photo");
        let action = document.getElementById("action").value;
        let errorMessages = [];

        // Validate food details length (at least 10 characters)
        if (details.length < 10) {
            errorMessages.push("Food details must be at least 10 characters long.");
        }

        // Validate photo upload only for new posts
        if (action === "add" && photo.files.length === 0) {
            errorMessages.push("Please upload a food photo.");
        }

        // Show error messages if any
        if (errorMessages.length > 0) {
            alert(errorMessages.join("\n"));
            event.preventDefault(); // Prevent form submission
        }
    });

    // Edit button event listener
    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("food_id").value = this.dataset.id;
            document.getElementById("details").value = this.dataset.details;
            document.getElementById("action").value = "edit";
            document.getElementById("submitBtn").textContent = "Update Post";
        });
    });
});

</script>

<?php include('assets/footer.php'); ?>
