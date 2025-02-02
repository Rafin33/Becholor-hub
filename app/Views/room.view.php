<?php
require_once '../app/models/RoomPostModel.php';
$roomPostModel = new RoomPostModel();

// Handle room posting
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submitRoom'])) {
    $user_id = $_SESSION['user_id']; // example user ID; you can get this from session or authentication
    $title = $_POST['title'];
    $description = $_POST['description'];
    $rent = $_POST['rent'];
    $condition = $_POST['condition'];
    $room = $_POST['room'];
    $address = $_POST['address'];
    $photo = $_FILES['photo']['name']; // Get the uploaded file name

    // Move the uploaded file to a directory
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file); // Save the file

    $roomPostModel->addRoom($user_id, $title, $description, $rent, $condition, $room, $address, $photo);
}

// Handle room update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateRoom'])) {
    $room_id = $_POST['room_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $rent = $_POST['rent'];
    $condition = $_POST['condition'];
    $room = $_POST['room'];
    $address = $_POST['address'];
    $photo = $_FILES['photo']['name'];

    // Move the uploaded file to a directory (if a new file is uploaded)
    if ($photo) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file); // Save the file
    }

    $roomPostModel->updateRoom($room_id, $title, $description, $rent, $condition, $room, $address, $photo);
}

// Handle room deletion
if (isset($_GET['deleteRoom'])) {
    $room_id = $_GET['deleteRoom'];
    $roomPostModel->deleteRoom($room_id);
    header("Location: room"); // Redirect back to the page
}
$userId = $_SESSION['user_id'];
// Retrieve all rooms for display
if(!isset($_GET['view']))$rooms = $roomPostModel->getAllRooms();
else $rooms = $roomPostModel->getRoomsByUserId($userId);

?>

<?php include('assets/header.php'); ?>
<div class="container">
    <h2 class="mt-4">Post a Room</h2> <a  class="btn btn-information" href="?view"> Show my own post</a>
    <!-- Room Posting Form -->
    <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateRoomForm()">
        <div class="form-group">
            <label for="title">Post Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="rent">Rent</label>
            <input type="number" class="form-control" id="rent" name="rent" required>
        </div>

        <div class="form-group">
            <label for="condition">Condition</label>
            <input type="text" class="form-control" id="condition" name="condition" required>
        </div>

        <div class="form-group">
            <label for="room">Number of Room</label>
            <input type="text" class="form-control" id="room" name="room" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>

        <div class="form-group">
            <label for="photo">Room Photo</label>
            <input type="file" class="form-control-file" id="photo" name="photo" required>
        </div>

        <button type="submit" name="submitRoom" class="btn btn-primary">Submit Room</button>
    </form>

    <h2 class="mt-4">Available Rooms</h2>
    <!-- Display All Rooms -->
    <div class="row">
        <?php foreach ($rooms as $room) : ?>
            <div class="swiper-slide">
                <div class="card shadow border-0 bg-template">
                    <div class="card-body p-0">
                        <div class="room-card">
                            <img src="uploads/<?php echo $room['photo']; ?>" alt="Room Image" class="room-image">
                            <div class="room-info">
                                <h5 class="room-title"><?php echo $room['title']; ?></h5>
                                <div class="room-details">
                                    <div class="owner-info">
                                        <img src="<?php echo $room['profile_photo']; ?>" alt="Profile Image" class="profile-pic">
                                        <span><?php echo $room['fullname']; ?>, <?php echo $room['age']; ?> | <?php echo $room['address']; ?></span>
                                    </div>
                                    <p class="room-price"><?php echo $room['rent']; ?> BDT/Month | <?php echo $room['room']; ?> Bed Rooms</p>
                                </div>
                                <?php if ($_SESSION['user_id'] == $room['user_id']&&isset($_GET['view'])) : ?>
                                    <!-- Edit and Delete buttons for the user's own posts -->
                                    <a href="room?editRoom=<?php echo $room['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="room?deleteRoom=<?php echo $room['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this room post?')">Delete</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php
    // Handle room update form (when edit button is clicked)
    if (isset($_GET['editRoom'])) {
        $room_id = $_GET['editRoom'];
        $room = $roomPostModel->getRoomById($room_id);
        ?>
        <h2 class="mt-4">Edit Room</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">

            <div class="form-group">
                <label for="title">Post Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $room['title']; ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $room['description']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="rent">Rent</label>
                <input type="number" class="form-control" id="rent" name="rent" value="<?php echo $room['rent']; ?>" required>
            </div>

            <div class="form-group">
                <label for="condition">Condition</label>
                <input type="text" class="form-control" id="condition" name="condition" value="<?php echo $room['condition']; ?>" required>
            </div>

            <div class="form-group">
                <label for="room">Number of Room</label>
                <input type="text" class="form-control" id="room" name="room" value="<?php echo $room['room']; ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo $room['address']; ?>" required>
            </div>

            <div class="form-group">
                <label for="photo">Room Photo</label>
                <input type="file" class="form-control-file" id="photo" name="photo">
                <small>If you want to change the photo, upload a new one.</small>
            </div>

            <button type="submit" name="updateRoom" class="btn btn-primary">Update Room</button>
        </form>
    <?php } ?>
   
</div>
<script>
function validateRoomForm() {
    // Get form inputs
    var title = document.getElementById('title').value.trim();
    var description = document.getElementById('description').value.trim();
    var rent = document.getElementById('rent').value.trim();
    var condition = document.getElementById('condition').value.trim();
    var room = document.getElementById('room').value.trim();
    var address = document.getElementById('address').value.trim();
    var photo = document.getElementById('photo').value;

    // Check if title is empty
    if (title === "") {
        alert("Please enter a title.");
        return false;
    }

    // Check if description is empty
    if (description === "") {
        alert("Please enter a description.");
        return false;
    }
    if (description.length < 10) {
        alert('Description must be at least 10 characters long.');
        return false;
    }

    // Check if rent is empty or not a valid number
    if (rent === "" || isNaN(rent) || parseFloat(rent) <= 0) {
        alert("Please enter a valid rent amount.");
        return false;
    }

    // Check if condition is empty
    if (condition === "") {
        alert("Please enter the condition of the room.");
        return false;
    }

    // Check if room is empty
    if (room === "") {
        alert("Please enter the number of rooms.");
        return false;
    }

    // Check if address is empty
    if (address === "") {
        alert("Please enter the address.");
        return false;
    }

    // Check if a photo is uploaded (only for the create form)
    if (document.getElementById('photo').required && photo === "") {
        alert("Please upload a photo of the room.");
        return false;
    }

    // If all validations pass, return true to submit the form
    return true;
}
</script>
<?php include('assets/footer.php'); ?>
