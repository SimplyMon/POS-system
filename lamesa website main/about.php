<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="logos/lamesalogo.png" />
    <link rel="stylesheet" href="./css/about.css" />
    <title>LaMesa Inasal | About Us</title>
</head>

<body>
    <div class="about-us-container">
        <nav class="navigation-bar">
            <div class="logo">
                <a class="logo" href="index.php" style="text-decoration: none;">
                    <img src="logos/logo3.png" alt="" style="width: 50px; height: 100%; margin-left: 80px; margin-right:10px;">
                    <h1>La Cocina Inasal</h1>
                </a>
            </div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a style="color: #ddff00" href="#">About Us</a>
                <a href="menu.php">Menu</a>
                <a class="btn" href="order.php">Order Now!</a>
            </div>
        </nav>

        <section class="about-us-content">
            <div class="about-title">
                <h1>
                    LaMesa Inasal Redefines Original Filipino Taste in Every Bite!
                </h1>
                <p>
                    At LaMesa Inasal, we take pride in redefining the original taste of
                    Filipino cuisine in every bite. With a rich heritage rooted in
                    traditional Filipino flavors and a commitment to innovation, we
                    offer a dining experience that celebrates the essence of Filipino
                    culinary heritage while embracing modern tastes.
                </p>
            </div>
            <div class="picture">
                <img src="logos/aboutt.jpg" alt="" style="width: 500px; height: 600px; margin-top: 70px; border-radius: 24px; object-fit: cover;" />
            </div>
        </section>
        <section class="gallery">
            <h1 style="text-align: center; margin-top: 200px; font-size: 50px">
                GALLERY
            </h1>
            <p style="text-align: center; margin-block-end: 40px">
                Hover the image to view
            </p>
            <div class="container-gallery">
                <img src="logos/apat.jpg" alt="" />
                <img src="logos/una.jpg" alt="" />
                <img src="logos/tatlo.jpg" alt="" />
                <img src="logos/lima.jpg" alt="" />
                <img src="logos/dalawa.jpg" alt="" />
            </div>
        </section>
        <section class="map-container" style="margin-bottom: 100px">
            <div class="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d965.3648527787877!2d120.99092250488118!3d14.572878286466011!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9812d12bc5d%3A0xf811aa0273d2647c!2sLeon%20Guinto%20St%20%26%20Remedios%20St%2C%20Malate%2C%20Manila%2C%201004%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1712772827492!5m2!1sen!2sph" width="100%" height="100%" style="border: 0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
    </div>
    <?php
    include 'footer.php';
    ?>
</body>

</html>