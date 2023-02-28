<!DOCTYPE html>
<html dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=devide-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>مدرستي | <?php echo $page_title; ?></title>
        <!-- Custom fonts for this template -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <!-- Material Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="<?php echo base_url('public/assets/frontend/css/style.css'); ?>" rel="stylesheet" type="text/css">
    </head>
    <body>

        <?php include('inc/nav_inc.php'); ?>

        <main>
            <!-- Hero -->
            <section class="hero">
                <span class="bg-img" style="background-image: url(<?php echo base_url('public/assets/frontend/img/banner-bg.jpg'); ?>);"></span>
                <div class="container">
                    <div class="main-message">
                        <h3 class="text-rtl">The great outdoor</h3>
                        <h1 class="text-rtl">Adventure</h1>
                        <p class="text-rtl">Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci, laborum id ex nulla perspiciatis itaque quia placeat deserunt atque praesentium, repellendus nostrum culpa eos eveniet qui quasi in dolorum quos?</p>
                        
                        <div class="cta text-rtl">
                            <a href="#" class="btn-main">سجل الآن في صف</a>
                        </div>
                    </div>
                </div>
                <img class="wave bottom" src="<?php echo base_url('public/assets/frontend/img/banner-bottom.png'); ?>">
            </section>

            <!-- Experience Outdoors -->
            <section class="experience-outdoors">
                <div class="container">
                    <div class="title-heading">
                        <h3>Experience</h3>
                        <h1>The thrilling outdoors</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    </div>

                    <div class="activities-grid">
                        <!-- Grid item #1 -->
                        <div class="activities-grid-item star-gazing">
                            <span class="bg-img" style="background-image: url(<?php echo base_url('public/assets/frontend/img/banner-bg.jpg'); ?>);"></span>
                            <i class="star"></i>
                            <h1 class="text-rtl">Star Gazing</h1>
                            <p class="text-rtl">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi id, accusamus non autem molestiae tempora placeat. Repudiandae, culpa? Nihil saepe impedit atque sed tenetur repellat? Ex possimus perspiciatis perferendis libero.</p>
                        </div>
                        <!-- Grid item #2 -->
                        <div class="activities-grid-item hiking">
                            <span class="bg-img" style="background-image: url(<?php echo base_url('public/assets/frontend/img/banner-bg.jpg'); ?>);"></span>
                            <i class="compas"></i>
                            <h1 class="text-rtl">Hiking</h1>
                            <p class="text-rtl">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi id, accusamus non autem molestiae tempora placeat. Repudiandae, culpa? Nihil saepe impedit atque sed tenetur repellat? Ex possimus perspiciatis perferendis libero.</p>
                        </div>
                        <!-- Grid item #3 -->
                        <div class="activities-grid-item camping">
                            <span class="bg-img" style="background-image: url(<?php echo base_url('public/assets/frontend/img/banner-bg.jpg'); ?>);"></span>
                            <i class="bonfire"></i>
                            <h1 class="text-rtl">Camping</h1>
                            <p class="text-rtl">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi id, accusamus non autem molestiae tempora placeat. Repudiandae, culpa? Nihil saepe impedit atque sed tenetur repellat? Ex possimus perspiciatis perferendis libero.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials -->
            <section class="testimonials">
                <span class="bg-img" style="background-image: url(<?php echo base_url('public/assets/frontend/img/banner-bg.jpg'); ?>);"></span>
                <img class="wave top" src="<?php echo base_url('public/assets/frontend/img/banner-bottom.png'); ?>">
                <div class="container">
                    <div class="testimonial">
                        <div class="testimonial-text-box">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius consequatur autem, rerum ipsam repellat quibusdam praesentium facilis molestias odit rem placeat, nisi voluptatibus esse recusandae culpa. Nisi optio iusto fuga!</p>
                            <i class="quote"></i>
                        </div>

                        <div class="testimonial-customer">
                            <img src="https://media.istockphoto.com/videos/be-confident-when-pursuing-success-video-id1130630420?s=640x640" style="" alt="">
                            <h1>Ahmad</h1>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Begin Adventure -->
            <section class="begin-avdenture">
                <div class="container">
                    <div class="title-heading">
                        <h3>Let the</h3>
                        <h1>Adventure Begin</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    </div>

                    <div class="adventure-grid">
                        <!-- Adventure Grid Item #1 -->
                        <div class="adventure-grid-item">
                            <p class="text-rtl">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Neque eveniet, ea, corrupti repudiandae laudantium porro facilis vitae commodi rerum deserunt ratione fugiat quaerat quasi dolores inventore, quia magnam et aliquid?</p>
                        </div>
                        <!-- Adventure Grid Item #2 -->
                        <div class="adventure-grid-item">
                            <p class="text-rtl">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Neque eveniet, ea, corrupti repudiandae laudantium porro facilis vitae commodi rerum deserunt ratione fugiat quaerat quasi dolores inventore, quia magnam et aliquid?</p>
                        </div>
                    </div>

                    <a href="#" class="btn-main"><b>سجل الآن </b></a>
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; 2019 دعوة | جميع الحقوق محفوظة.</p>
        </footer>
    </body>


    <!-- jQuery -->
    <script src="<?php echo base_url('public/assets/backend/material-dashboard/js/core/jquery.min.js'); ?>"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url('public/assets/backend/material-dashboard/js/core/bootstrap-material-design.min.js'); ?>"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>

    <!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    -->

    <script src="<?php echo base_url('public/assets/frontend/js/script.js'); ?>"></script>
</html>
