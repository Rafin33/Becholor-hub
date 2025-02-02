<?php
// Include the models
require_once '../app/models/FoodModel.php';
require_once '../app/models/lookingPostModel.php';
require_once '../app/models/RoomPostModel.php';
require_once '../app/models/UserModel.php';

// Initialize models
$foodModel = new FoodModel();
$lookingPostModel = new lookingPostModel();
$roomPostModel = new RoomPostModel();
$userModel = new UserModel();

// Fetch data from the database
$foodItems = $foodModel->getAllFood();
$lookingPosts = $lookingPostModel->getAllPostsFromAll();
$roomPosts = $roomPostModel->getAllRooms();
$users = $userModel->getAllUsers();
?>

<?php include('assets/header.php');?>

<style>
    /* General Card Styles */
.room-card {
  display: flex;
  flex-direction: column;
  background-color: #ffffff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  margin-bottom: 16px;
}

/* Room Image */
.room-image {
  width: 100%;
  height: 160px;
  object-fit: cover;
}

/* Room Info Section */
.room-info {
  padding: 16px;
  background-color: #f9f9f9;
}

/* Title Styles */
.room-title {
  font-size: 1rem;
  font-weight: bold;
  margin-bottom: 8px;
  color: #333;
}

/* Owner Details Section */
.room-details {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.owner-info {
  display: flex;
  align-items: center;
  gap: 8px;
}

.profile-pic {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #007bff;
}

.owner-info span {
  font-size: 0.9rem;
  color: #555;
}

/* Price Section */
.room-price {
  font-size: 1rem;
  font-weight: bold;
  color: #007bff;
}

/* Responsive Design */
@media (max-width: 768px) {
  .room-image {
    height: 140px;
  }

  .room-title {
    font-size: 0.9rem;
  }

  .profile-pic {
    width: 32px;
    height: 32px;
  }

  .room-price {
    font-size: 0.9rem;
  }
}
</style>

<!-- Food Items Section with Swiper -->
<div class="container">
    <h6 class="subtitle">Food Items</h6>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($foodItems as $food): ?>
                <div class="swiper-slide">
                    <div class="col-md-4 mb-4">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <img src="<?php echo $food['photo']; ?>" alt="Food Image" class="img-fluid">
                                <h5 class="mt-3"><?php echo $food['details']; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Looking Posts Section with Swiper -->
<div class="container">
    <h6 class="subtitle">Looking For</h6>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($lookingPosts as $post): ?>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<!-- Room Posts Section with Swiper -->
<div class="container">
    <h6 class="subtitle">Room Posts</h6>
    <div class="swiper-container offer-slide">
        <div class="swiper-wrapper">
            <?php foreach ($roomPosts as $room): ?>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        spaceBetween: 20,
        slidesPerGroup: 1,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            1200: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 1,
            },
        },
    });
</script>

<?php include('assets/footer.php');?>