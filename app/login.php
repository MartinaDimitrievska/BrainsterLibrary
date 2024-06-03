<?php

require_once('./classes/Users/User.php');

use Users\User as User;

if (empty($_POST['username'])) {
    return header('Location: login-form.php?errorMessage=Username%20is%20required.');
}

if (empty($_POST['password'])) {
    return header('Location: login-form.php?errorMessage=Password%20is%20required.');
}

$user = new User();

$user->setUsername($_POST['username']);
$user->setPassword($_POST['password']);

$isAuthenticated = $user->authenticate();

if ($isAuthenticated) {
    session_start();

    $_SESSION['username'] = $_POST['username'];

    if ($_POST['username'] == 'admin') {
        return header('Location: admin-panel.php');
    } else {
        return header('Location: user-panel.php');
    }
}

return header('Location: login-form.php?errorMessage=Invalid%20credentials.');
