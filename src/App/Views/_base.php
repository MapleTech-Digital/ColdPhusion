<!DOCTYPE html>
<html lang="en">
<head>
    <title>DevBlog - <?=$this->e($page_title ?? '') ?></title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Blog Template">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="favicon.ico">

    <!-- FontAwesome JS-->
    <script defer src="/assets/fontawesome/js/all.min.js"></script>

    <!-- Theme CSS -->
    <?php if ($this->section('styles')): ?>
        <?=$this->section('styles') ?>
    <?php else: ?>
        <link id="theme-style" rel="stylesheet" href="<?=$this->asset('/assets/css/theme-1.css')?>">
    <?php endif ?>

</head>

<body>

<header class="header text-center">
    <h1 class="blog-name pt-lg-4 mb-0"><a class="no-text-decoration" href="/">Kyle's Blog</a></h1>

    <nav class="navbar navbar-expand-lg navbar-dark" >

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="navigation" class="collapse navbar-collapse flex-column" >
            <div class="profile-section pt-3 pt-lg-0">
                <img class="profile-image mb-3 rounded-circle mx-auto" src="https://avatars.githubusercontent.com/u/1039911?v=4" alt="image" >

                <div class="bio mb-3">Hi, my name is Kyle. I'm a web developer by trade, and a game developer by hobby. I made this to demonstrate a custom framework.</div><!--//bio-->
                <ul class="social-list list-inline py-3 mx-auto">
                    <li class="list-inline-item"><a href="#"><i class="fab fa-twitter fa-fw"></i></a></li>
                    <li class="list-inline-item"><a href="#"><i class="fab fa-linkedin-in fa-fw"></i></a></li>
                    <li class="list-inline-item"><a href="#"><i class="fab fa-github-alt fa-fw"></i></a></li>
                    <li class="list-inline-item"><a href="#"><i class="fab fa-stack-overflow fa-fw"></i></a></li>
                    <li class="list-inline-item"><a href="#"><i class="fab fa-codepen fa-fw"></i></a></li>
                </ul><!--//social-list-->
                <hr>
            </div><!--//profile-section-->

            <ul class="navbar-nav flex-column text-start">
                <li class="nav-item">
                    <a class="nav-link active" href="/"><i class="fas fa-home fa-fw me-2"></i>Blog Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/post/random"><i class="fas fa-bookmark fa-fw me-2"></i>Blog Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about"><i class="fas fa-user fa-fw me-2"></i>About Me</a>
                </li>
            </ul>

            <div class="my-2 my-md-3">
                <a class="btn btn-primary" href="https://themes.3rdwavemedia.com/" target="_blank">Get in Touch</a>
            </div>
        </div>
    </nav>
</header>

<div class="main-wrapper">

    <?=$this->section('content')?>

    <footer class="footer text-center py-2 theme-bg-dark">

        <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
        <small class="copyright">Designed with <span class="sr-only">love</span><i class="fas fa-heart" style="color: #fb866a;"></i> by <a href="https://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small>

    </footer>

</div><!--//main-wrapper-->

<?php if ($this->section('scripts')): ?>
    <?=$this->section('scripts') ?>
<?php else: ?>
    <!-- Javascript -->
    <script src="/assets/plugins/popper.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<?php endif ?>

</body>
</html>

