<?php $this->layout('_base', ['page_title' => 'Login']); ?>

<section class="cta-section theme-bg-light py-5">
    <div class="container text-center single-col-max-width">
        <h2 class="heading">Login Form</h2>
    </div><!--//container-->
</section>


<section class="blog-list px-3 py-5 p-md-5">
    <div class="container single-col-max-width">
        <form action="/security/login" method="post">
            <div>Username: <input type="text" name="email"></div>
            <div>Password: <input type="password" name="password"></div>
            <div><input type="submit" value="Login" /> </div>
        </form>
    </div>
</section>
