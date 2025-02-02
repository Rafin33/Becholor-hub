<?php
// Include necessary files
require_once '../app/models/lookingPostModel.php';

// Create an instance of the model
$lookingPostModel = new lookingPostModel();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'create') {
            // Retrieve form data for creating a post
            $userId = $_SESSION['user_id'];
            $description = $_POST['description'];
            $budget = $_POST['budget'];
            $location = $_POST['location'];

            // Validate form data
            if (empty($description) || empty($budget) || empty($location)) {
                $error = "Please fill in all fields.";
            } else {
                // Call the addPost method to insert the data into the database
                $isAdded = $lookingPostModel->addPost($userId, $description, $budget, $location);

                // Check if the post was added successfully
                if ($isAdded) {
                    $successMessage = "Your post has been created successfully!";
                } else {
                    $error = "There was an error creating your post. Please try again.";
                }
            }
        } elseif ($action === 'update') {
            // Retrieve form data for updating a post
            $postId = $_POST['post_id'];
            $description = $_POST['description'];
            $budget = $_POST['budget'];
            $location = $_POST['location'];

            // Call the updatePost method to update the post
            $isUpdated = $lookingPostModel->updatePost($postId, $description, $budget, $location);

            // Check if the post was updated successfully
            if ($isUpdated) {
                $successMessage = "Your post has been updated successfully!";
            } else {
                $error = "There was an error updating your post. Please try again.";
            }
        } elseif ($action === 'delete') {
            // Retrieve post ID for deletion
            $postId = $_POST['post_id'];

            // Call the deletePost method to delete the post
            $isDeleted = $lookingPostModel->deletePost($postId);

            // Check if the post was deleted successfully
            if ($isDeleted) {
                $successMessage = "Your post has been deleted successfully!";
            } else {
                $error = "There was an error deleting your post. Please try again.";
            }
        }
    }
}

// Fetch posts based on the view
$userId = $_SESSION['user_id']; // Or any other relevant identifier
if (isset($_GET["view"])) {
    $posts = $lookingPostModel->getAllPosts($userId); // Pass the user ID
} else {
    $posts = $lookingPostModel->getAllPostsFromAll();
}
?>

<?php include('assets/header.php'); ?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Create a New Looking For Post</h2>

    <!-- Display success or error message -->
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Post Creation Form -->

    <form action="" method="POST" onsubmit="return validateForm()">
        <input type="hidden" name="action" value="create">
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="budget">Budget</label>
            <input type="number" step="0.01" class="form-control" id="budget" name="budget" required>
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Submit Post</button>
    </form>
    <hr class="mt-5">

    <h2 class="text-center mb-4">All Looking For Posts</h2> <a  class="btn btn-information" href="?view"> Show my own post</a>

    <!-- Display all posts -->
    <div class="row">
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="swiper-slide">
                    <div class="col-md-4 mb-4">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img height="50px" src="<?php echo $post['profile_photo']; ?>" class="rounded-circle" alt="User Image">
                                    </div>
                                    <div class="col">
                                        <small class="mb-0"><?php echo $post['fullname']; ?>,<?php echo $post['age']; ?></small>
                                        <h5 class="mb-0"><?php echo $post['description']; ?></h5>
                                        <p class="mb-0">Budget: <?php echo $post['budget']; ?> BDT</p>
                                        <p class="mb-0">Location: <?php echo $post['location']; ?></p>

                                        <!-- Update and Delete Buttons -->
                                        <?php if ($_SESSION['user_id'] == $post['user_id'] && isset($_GET["view"])): ?>
                                            <div class="mt-2">
                                                <!-- Update Form -->
                                                <form action="" method="POST" style="display:inline;">
                                                    <input type="hidden" name="action" value="update">
                                                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                                    <textarea class="form-control" name="description" rows="2" required><?php echo $post['description']; ?></textarea>
                                                    <input type="number" step="0.01" class="form-control" name="budget" value="<?php echo $post['budget']; ?>" required>
                                                    <input type="text" class="form-control" name="location" value="<?php echo $post['location']; ?>" required>
                                                    <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                                </form>

                                                <!-- Delete Form -->
                                                <form action="" method="POST" style="display:inline;">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No posts available.</p>
        <?php endif; ?>
    </div>
</div>
<script>
function validateForm() {
    // Get form inputs
    var description = document.getElementById('description').value.trim();
    var budget = document.getElementById('budget').value.trim();
    var location = document.getElementById('location').value.trim();

    // Check if description is empty
    if (description === "") {
        alert("Please enter a description.");
        return false;
    }

    // Check if budget is empty or not a valid number
    if (budget === "" || isNaN(budget) || parseFloat(budget) <= 0) {
        alert("Please enter a valid budget.");
        return false;
    }

    // Check if location is empty
    if (location === "") {
        alert("Please enter a location.");
        return false;
    }

    // If all validations pass, return true to submit the form
    return true;
}
</script>
<?php include('assets/footer.php'); ?>