<?php
require_once '../app/models/RoomPostModel.php';


// Instantiate the model
$roomModel = new RoomPostModel();

// 1. Add a New Room Post
echo "Adding a new room post...\n";
$newRoom = $roomModel->addRoom(
    1, // user_id
    "Luxury Room", // title
    "A spacious luxury room with all amenities", // description
    25000, // rent
    "New", // condition
    3, // room
    "Dhaka, Bangladesh", // address
    "photo1.jpg" // photo
);
echo $newRoom ? "New room added successfully.\n" : "Failed to add a new room.\n";

// 2. Get All Rooms
echo "Fetching all room posts...\n";
$allRooms = $roomModel->getAllRooms();
if (!empty($allRooms)) {
    foreach ($allRooms as $room) {
        print_r($room);
    }
} else {
    echo "No room posts found.\n";
}

// 3. Get a Room by ID
echo "Fetching room with ID 1...\n";
$roomById = $roomModel->getRoomById(1);
if ($roomById) {
    print_r($roomById);
} else {
    echo "No room found with ID 1.\n";
}

// 4. Update a Room Post
echo "Updating room with ID 1...\n";
$updatedRoom = $roomModel->updateRoom(
    1, // id
    "Updated Luxury Room", // title
    "Updated description with new features", // description
    26000, // rent
    "Like New", // condition
    4, // room
    "Gulshan, Dhaka, Bangladesh", // address
    "photo2.jpg" // photo
);
echo $updatedRoom ? "Room updated successfully.\n" : "Failed to update the room.\n";

// 5. Delete a Room Post
echo "Deleting room with ID 1...\n";
// $deletedRoom = $roomModel->deleteRoom(1);
// echo $deletedRoom ? "Room deleted successfully.\n" : "Failed to delete the room.\n";

?>