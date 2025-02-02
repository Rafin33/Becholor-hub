
        <!-- Footer -->
        <div class="footer">
            <div class="no-gutters">
                <div class="col-auto mx-auto">
                    <div class="row no-gutters justify-content-center">
                        <div class="col-auto">
                            <a href="home" class="btn btn-link-default active">
                                <i class="material-icons">home</i>
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="looking" class="btn btn-link-default">
                                <i class="material-icons">search</i>
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="room" class="btn btn-link-default">
                                <i class="material-icons">widgets</i>
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="food" class="btn btn-link-default">
                            <i class="material-icons">restaurant</i>
                            </a>
                        </div>
                        
                        <div class="col-auto">
                            <a href="profile" class="btn btn-link-default">
                                <i class="material-icons">account_circle</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let links = document.querySelectorAll(".footer a");
        let currentPage = window.location.pathname.split("/").pop(); // Get the current page filename

        links.forEach(link => {
            if (link.getAttribute("href") === currentPage) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        });
    });
</script>



    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap-4.4.1/js/bootstrap.min.js"></script>
    <script src="vendor/swiper/js/swiper.min.js"></script>
    <script src="vendor/cookie/jquery.cookie.js"></script>
    <script src="js/main.js"></script>
</body>
</html>