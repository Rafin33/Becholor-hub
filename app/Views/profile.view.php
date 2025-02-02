<?php
include('assets/header.php');
// Include necessary files
require_once '../app/models/UserModel.php';

// Create an instance of the UserModel
$userModel = new UserModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $userId = $_SESSION['user_id']; // Get the logged-in user's ID
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $fullname = $_POST['fullname'];
    $dob = $_POST['dob'];
    $nid = $_POST['nid'];
    
    // Handle file upload for profile photo
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/profile_photos/';
        $fileName = basename($_FILES['profile_photo']['name']);
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $filePath)) {
            $profilePhoto = $filePath;
        } else {
            $error = "Error uploading the file.";
        }
    } else {
        $profilePhoto = null; // If no photo is uploaded, keep it null
    }

    // Update user and user details
    if (empty($username) || empty($phone) || empty($email) || empty($gender) || empty($fullname) || empty($dob) || empty($nid)) {
        $error = "Please fill in all fields.";
    } else {
        // Update user information
        $updateUserInfo = $userModel->updateUser($userId, $username, $phone, $email, $gender);
        // Update user details information
        $updateUserDetails = $userModel->updateUserDetails($userId, $fullname, $dob, $nid, $profilePhoto, 'user'); // Assume 'user' is the default userType
        
        if ($updateUserInfo && $updateUserDetails) {
            $successMessage = "Profile updated successfully!";
        } else {
            $error = "There was an error updating your profile. Please try again.";
        }
    }
}

// Fetch current user data
$userData = $userModel->getUserById($_SESSION['user_id']);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Your Profile</h2>

    <!-- Display success or error message -->
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- User Profile Edit Form -->
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="male" <?php echo ($userData['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo ($userData['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                <option value="other" <?php echo ($userData['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($userData['fullname']); ?>" required>
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($userData['dob']); ?>" required>
        </div>

        <div class="form-group">
            <label for="nid">National ID</label>
            <input type="text" class="form-control" id="nid" name="nid" value="<?php echo htmlspecialchars($userData['NID']); ?>" required>
        </div>

        <div class="form-group">
            <label for="profile_photo">Upload Profile Photo</label>
            <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
    </form>
</div>

<?php include('assets/footer.php'); ?>
