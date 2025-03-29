<?php

require_once 'users.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User();
    $email = $_POST['email'];
    $password = $_POST['password'];

    $loginResult = $user->login($email, $password);

    if ($loginResult === 'admin') {
        header("Location: ../admin/index.php");
        exit();
    } elseif ($loginResult === 'user') {
        header("Location: ../index.php");
        exit();
    } else {
        $error = $loginResult;
    }
}

?>

<section class="form-container">
        <form action="" enctype="multipart/form-data" method="POST">
            <h3>Register Now</h3>

            <input type="email" name="email" class="box" placeholder="enter your email" require>

            <input type="password" name="password" class="box" placeholder="enter your password" require>

            <input type="submit" value="Login" class="btn">

            <p>already hae an account? <a href="register.php">login now</a></p>

        </form>
    </section>
    <?= isset($error) ? $error : '' ?>

<style>
    :root{
        --green: #27ae60;
        --orange: #f39c12;
        --red: #e74c3c;
        --black: #333;
        --light-color: #666;
        --white: #fff;
        --light-bg: #f6f6f6;
        --border: .2rem solid var(--black);
        --box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
    }

    *{
        font-family: 'Rubik', sans-serif;
        margin: 0;
        padding: 0;
        outline: none;
        border: none;
        color: var(--black);
    }

    *::selection {
        background-color: var(--green);
        color: var(--white);
    }

    *::-webkit-scrollbar {
        height: .5rem;
        width: 1rem;
    }

    *::-webkit-scrollbar-track {
        background-color: transparent;
    }

    *::-webkit-scrollbar-thumb {
        background-color:  var(--light-color);
    }

    html {

        font-size: 62.5%;
        overflow-x: hidden;
        scroll-behavior: smooth;
        scroll-padding-top: 6.5rem;
    }

    section {
        padding: 3rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .btn,
    .delete-btn,
    .option-btn {
        display: block;
        width: 100%;
        margin-top: 1rem;
        border-radius: .5rem;
        color: var(--white);
        font-size: 2rem;
        padding: 1rem  3rem;
        cursor: pointer;
    }

    .btn {
        background-color: var(--green);
    }

    .delete-btn{
        background-color: var(--red);
    }

    .option-btn {
        background-color: var(--orange);
    }

    .btn:hover,
    .delete-btn:hover,
    .option-btn:hover {
        background-color: var(--black);
    } 

    .form-container{
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-container form {
        width: 50rem;
        background-color: var(--white);
        border-radius: .5rem;
        box-shadow: var(--box-shadow);
        border: var(--border);
        text-align: center;
        padding: 2rem;
    }

    .form-container form h3 {
        font-size: 2.5rem;
        color: var(--black);
        margin-bottom: 1rem;
        text-transform: uppercase;
    }

    .form-container .box {
        width: 100%;
        margin: 1rem 0;
        border-radius: .5rem;
        border: var(--border);
        padding: 1.2rem 1.4rem;
        font-size: 1.8rem;
        color: var(--black);
        background-color: var(--light-bg);
    }

    .form-container form p {
        margin-top: 1.5rem;
        font-size: 2rem;
        color: var(--light-color);
    }

    .form-container form p a {
        color: var(--green);
    }
    
    .form-container form p a:hover {
        text-decoration: underline;
    }

</style>
