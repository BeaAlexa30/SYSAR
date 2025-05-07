<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('PICTURES/home_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            padding-top: 40px
        }
        .sk-title {
            font-weight: bold;
            color: red;
        }
        .barangay-title {
            color: blue;
        }
        .content {
            text-align: center;
            margin-top: 10%;
            color: black;
        }
        .content h1 {
            font-weight: bold;
            font-size: 2.5rem;
        }
        .content h3 {
            font-size: 1.8rem;
        }
        .big-logo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .about-section {
            background: rgba(248, 249, 250, 0.79);
            padding: 40px;
            border-radius: 10px;
            margin-top: 50px;
            backdrop-filter: blur(3px);
        }
        .council-section {
            padding: 40px;
            border-radius: 10px;
            margin-top: 50px;
        }
        .council-section img {
            width: 100%;
            height: 300px;
            border-radius: 10px;
        }
        .council-member {
            text-align: center;
            padding: 10px;
        }
        .council-member h5 {
            font-weight: bold;
            margin-top: 10px;
        }
        .gallery-wrapper {
            display: flex;
            align-items: center;
            overflow: hidden;
            position: relative;
            background-color: rgba(248, 249, 250, 0.79);
            backdrop-filter: blur(3px);
            padding: 60px;
            border-radius: 10px;
            gap: 10px; /* Adds spacing between the title and images */
        }
        .event-title {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            color: red;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 2;
        }
        .gallery {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            scroll-behavior: smooth;
            white-space: nowrap;
            padding: 10px;
        }
        .gallery img {
            width: 400px;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
        }
        .gallery img:hover {
            transform: scale(1.1);
        }
        .scroll-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            z-index: 2;
        }
        .scroll-btn.left { left: 0; }
        .scroll-btn.right { right: 0; }
        .event-title-container {
            background: rgba(19, 41, 75, 0.9); /* Dark blue background with slight transparency */
            color: white; /* White text for contrast */
            padding: 15px 20px; /* Adds space inside the background */
            text-align: center; /* Centers the text */
            font-weight: bold;
            width: 100%; /* Ensures the background spans the full width */
        }
    </style>
</head>
<body>
<main>

<!-- Navbar -->
<?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row align-items-center">
            <!-- Left side (Logo and Titles) -->
            <div class="col-md-6 text-center">
                <img src="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" alt="Large Logo" class="big-logo">
                <h1 class="event-title-container">SANGGUNIANG KABATAAN</h1>
                <h3 class="event-title-container">BARANGAY 252 ZONE 23</h3>
            </div>

            <!-- Right side (SK Council) -->
            <div class="col-md-6 d-flex align-items-center council-section">
                <div class="text-center">
                    <h1 style="font-weight: bold;" class="event-title-container">SK COUNCIL</h1>
                    <img src="PICTURES/home_members_pic.png" alt="SK Council Members" class="img-fluid mt-3">
                </div>
            </div>
        </div>
    </div>

    <!-- About Us Section -->
    <div class="container about-section">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 style="color: red; font-weight: bold;">ABOUT US</h1>
                <p>
                    The Sangguniang Kabataan (SK) of Barangay 252 is a youth-led government organization dedicated to empowering young individuals within the community.
                    This council serves as a platform for local youth to actively participate in governance and community-building initiatives,
                    focusing on projects that promote leadership, community involvement, and personal development.
                </p>
                <p>
                    The SK of Barangay 252 aims to foster a positive and supportive environment, encouraging the youth to take part in civic activities,
                    educational programs, and sports initiatives. Through these efforts, the SK contributes to the overall improvement and well-being
                    of Barangay 252, building a strong foundation for future leaders in the community.
                </p>
            </div>
            <div class="col-md-4 text-center">
                <img src="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" alt="SK Logo" class="img-fluid"
                     style="border-radius: 50%; object-fit: cover; margin-left: 10px;">
            </div>
        </div>
    </div>

    <!-- Photo Gallery Section -->
<div class="container mt-5">
    <h1 class="text-center event-title-container" style="font-weight: bold;">EVENTS & PROGRAMS</h1>
    
    <div class="event-section mt-4">
        <div class="gallery-wrapper position-relative">
        <h3 class="text-center event-title">FIRST CLEAN UP</h3><br>
            <button class="scroll-btn left event-title" onclick="scrollGallery('gallery1', -300)">&#10094;</button>
            <div class="gallery" id="gallery1">
                <img src="SKFILES/2_11_24_1st_Clean-up_Drive/TC_00051.JPG" alt="Event Image 1">
                <img src="SKFILES/2_11_24_1st_Clean-up_Drive/TC_00059.JPG" alt="Event Image 2">
                <img src="SKFILES/2_11_24_1st_Clean-up_Drive/TC_00060.JPG" alt="Event Image 3">
                <img src="SKFILES/2_11_24_1st_Clean-up_Drive/TC_00065.JPG" alt="Event Image 4">
                <img src="SKFILES/2_11_24_1st_Clean-up_Drive/TC_00074.JPG" alt="Event Image 5">
                <img src="SKFILES/2_11_24_1st_Clean-up_Drive/TC_00086.JPG" alt="Event Image 6">
                <img src="SKFILES/2_11_24_1st_Clean-up_Drive/TC_00091.JPG" alt="Event Image 7">
                <img src="SKFILES/2_11_24_1st_Clean-up_Drive/TC_00100.JPG" alt="Event Image 8">
                <img src="SKFILES/2_11_24_1st_Clean-up_Drive/TC_00107.JPG" alt="Event Image 9">
                <img src="SKFILES/2_11_24_1st_Clean-up_Drive/TC_00111.JPG" alt="Event Image 10">
            </div>
            <button class="scroll-btn right" onclick="scrollGallery('gallery1', 300)">&#10095;</button>
        </div>

        <div class="event-section mt-4">
        <div class="gallery-wrapper position-relative">
        <h3 class="text-center event-title">SECOND CLEAN UP</h3><br>
            <button class="scroll-btn left event-title" onclick="scrollGallery('gallery2', -300)">&#10094;</button>
            <div class="gallery" id="gallery2">
                <img src="SKFILES/2_25_24_2nd_Clean-up_Drive/TC_00118.JPG" alt="Event Image 1">
                <img src="SKFILES/2_25_24_2nd_Clean-up_Drive/TC_00119.JPG" alt="Event Image 2">
                <img src="SKFILES/2_25_24_2nd_Clean-up_Drive/TC_00125.JPG" alt="Event Image 3">
                <img src="SKFILES/2_25_24_2nd_Clean-up_Drive/TC_00127.JPG" alt="Event Image 4">
                <img src="SKFILES/2_25_24_2nd_Clean-up_Drive/TC_00132.JPG" alt="Event Image 5">
                <img src="SKFILES/2_25_24_2nd_Clean-up_Drive/TC_00133.JPG" alt="Event Image 6">
                <img src="SKFILES/2_25_24_2nd_Clean-up_Drive/TC_00144.JPG" alt="Event Image 7">
                <img src="SKFILES/2_25_24_2nd_Clean-up_Drive/TC_00151.JPG" alt="Event Image 8">
                <img src="SKFILES/2_25_24_2nd_Clean-up_Drive/TC_00155.JPG" alt="Event Image 9">
                <img src="SKFILES/2_25_24_2nd_Clean-up_Drive/TC_00160.JPG" alt="Event Image 10">
            </div>
            <button class="scroll-btn right" onclick="scrollGallery('gallery2', 300)">&#10095;</button>
        </div>

        <div class="event-section mt-4">
        <div class="gallery-wrapper position-relative">
        <h3 class="text-center event-title">FIESTA</h3><br>
            <button class="scroll-btn left event-title" onclick="scrollGallery('gallery3', -300)">&#10094;</button>
            <div class="gallery" id="gallery3">
                <img src="SKFILES/Fiesta/IMG_1484.jpg" alt="Event Image 1">
                <img src="SKFILES/Fiesta/IMG_1499.jpg" alt="Event Image 2">
                <img src="SKFILES/Fiesta/IMG_1586.jpg" alt="Event Image 3">
                <img src="SKFILES/Fiesta/IMG_1604.jpg" alt="Event Image 4">
                <img src="SKFILES/Fiesta/IMG_1634.jpg" alt="Event Image 5">
                <img src="SKFILES/Fiesta/IMG_1654.jpg" alt="Event Image 6">
                <img src="SKFILES/Fiesta/IMG_1682.jpg" alt="Event Image 7">
                <img src="SKFILES/Fiesta/IMG_1707.jpg" alt="Event Image 8">
                <img src="SKFILES/Fiesta/IMG_1719.jpg" alt="Event Image 9">
                <img src="SKFILES/Fiesta/IMG_11775.jpg" alt="Event Image 10">
            </div>
            <button class="scroll-btn right" onclick="scrollGallery('gallery3', 300)">&#10095;</button>
        </div>

        <div class="event-section mt-4">
        <div class="gallery-wrapper position-relative">
        <h3 class="text-center event-title">NEW YEAR - FREE TOROROT</h3><br>
            <button class="scroll-btn left event-title" onclick="scrollGallery('gallery4', -300)">&#10094;</button>
            <div class="gallery" id="gallery1">
                <img src="SKFILES/Free_Torotot_JPG/IMG_8547.jpg" alt="Event Image 1">
                <img src="SKFILES/Free_Torotot_JPG/IMG_8552.jpg" alt="Event Image 2">
                <img src="SKFILES/Free_Torotot_JPG/IMG_8554.jpg" alt="Event Image 3">
                <img src="SKFILES/Free_Torotot_JPG/IMG_8556.jpg" alt="Event Image 4">
                <img src="SKFILES/Free_Torotot_JPG/IMG_8558.jpg" alt="Event Image 5">
                <img src="SKFILES/Free_Torotot_JPG/IMG_8626.jpg" alt="Event Image 6">
                <img src="SKFILES/Free_Torotot_JPG/IMG_8629.jpg" alt="Event Image 7">
            </div>
            <button class="scroll-btn right" onclick="scrollGallery('gallery4', 300)">&#10095;</button>
        </div>

        <div class="event-section mt-4">
        <div class="gallery-wrapper position-relative">
        <h3 class="text-center event-title">KK ASSEMBLY - BALIK ESKWELA</h3><br>
            <button class="scroll-btn left event-title" onclick="scrollGallery('gallery5', -300)">&#10094;</button>
            <div class="gallery" id="gallery5">
                <img src="SKFILES/KK_Assembly/IMG_4690.jpg" alt="Event Image 1">
                <img src="SKFILES/KK_Assembly/IMG_4711.jpg" alt="Event Image 2">
                <img src="SKFILES/KK_Assembly/IMG_4722.jpg" alt="Event Image 3">
                <img src="SKFILES/KK_Assembly/IMG_4752.jpg" alt="Event Image 4">
                <img src="SKFILES/KK_Assembly/IMG_4769.jpg" alt="Event Image 5">
                <img src="SKFILES/KK_Assembly/IMG_4775.jpg" alt="Event Image 6">
                <img src="SKFILES/KK_Assembly/IMG_4781.jpg" alt="Event Image 7">
                <img src="SKFILES/KK_Assembly/IMG_4786.jpg" alt="Event Image 8">
                <img src="SKFILES/KK_Assembly/IMG_4810.jpg" alt="Event Image 9">
                <img src="SKFILES/KK_Assembly/IMG_4826.jpg" alt="Event Image 10">
            </div>
            <button class="scroll-btn right" onclick="scrollGallery('gallery5', 300)">&#10095;</button>
        </div>

        <div class="event-section mt-4">
        <div class="gallery-wrapper position-relative">
        <h3 class="text-center event-title">MOBILE LEGENDS - TOURNAMENT</h3><br>
            <button class="scroll-btn left event-title" onclick="scrollGallery('gallery6', -300)">&#10094;</button>
            <div class="gallery" id="gallery6">
                <img src="SKFILES/ml_tourna/IMG_8788.jpg" alt="Event Image 1">
                <img src="SKFILES/ml_tourna/IMG_8800.jpg" alt="Event Image 2">
                <img src="SKFILES/ml_tourna/IMG_8823.jpg" alt="Event Image 3">
                <img src="SKFILES/ml_tourna/IMG_8833.jpg" alt="Event Image 4">
                <img src="SKFILES/ml_tourna/IMG_8845.jpg" alt="Event Image 5">
            </div>
            <button class="scroll-btn right" onclick="scrollGallery('gallery6', 300)">&#10095;</button>
        </div>

        <div class="event-section mt-4">
        <div class="gallery-wrapper position-relative">
        <h3 class="text-center event-title">CHRISTMAS PARTY 2023</h3><br>
            <button class="scroll-btn left event-title" onclick="scrollGallery('gallery7', -300)">&#10094;</button>
            <div class="gallery" id="gallery7">
                <img src="SKFILES/skevent_xmas_party/IMG_4801.jpg" alt="Event Image 1">
                <img src="SKFILES/skevent_xmas_party/IMG_4808.jpg" alt="Event Image 2">
                <img src="SKFILES/skevent_xmas_party/IMG_4866.jpg" alt="Event Image 3">
                <img src="SKFILES/skevent_xmas_party/IMG_5503.jpg" alt="Event Image 4">
                <img src="SKFILES/skevent_xmas_party/IMG_5605.jpg" alt="Event Image 5">
                <img src="SKFILES/skevent_xmas_party/IMG_5774.jpg" alt="Event Image 6">
                <img src="SKFILES/skevent_xmas_party/IMG_5779.jpg" alt="Event Image 7">
                <img src="SKFILES/skevent_xmas_party/IMG_8194.jpg" alt="Event Image 8">
                <img src="SKFILES/skevent_xmas_party/IMG_8200.jpg" alt="Event Image 9">
            </div>
            <button class="scroll-btn right" onclick="scrollGallery('gallery7', 300)">&#10095;</button>
        </div>

    </div>
    <!-- End of event section -->
</div>

<script>
    function scrollGallery(galleryId, scrollAmount) {
        document.getElementById(galleryId).scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
</script>

</main>

<!-- Footer -->
<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
