<?php
class login_view {
    public function login_render()
    {

          ?>
<!DOCTYPE html>        
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="http://localhost/wfm2/modules/login/view/style.css">
</head>
<body>
    <div class="container">
        <p class="title">Login</p>
    <input type="text" class="login user" placeholder=" username">
    <input type="password" class="login pass" placeholder="password ">
    <input type="button" class="btn pluse" value="sign in"> 
    </div>
</body>
</html>


      <?php

    }
}