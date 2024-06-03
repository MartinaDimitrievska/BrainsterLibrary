<?php

require_once('./classes/Users/User.php');

use Users\User as User;

$user = new User();

$errors = [];

if ($_POST['first_name'] === 'admin' || $_POST['last_name'] === 'admin' || $_POST['username'] === 'admin') {
    $errors[] = 'Admin registration is not allowed.';
} else {
    if (empty($_POST['first_name'])) {
        $errors['first_name'] = 'First name is required.';
    }

    if (empty($_POST['last_name'])) {
        $errors['last_name'] = 'Last name is required.';
    }

    if (empty($_POST['username'])) {
        $errors['username'] = 'Username is required.';
    } else {
        $getByUsername = $user->getByUsername($_POST['username']);

        if ($getByUsername) {
            $errors['username'] = 'Username already exists. Please choose a different username.';
        }
    }

    if (empty($_POST['email'])) {
        $errors['email'] = 'Email is required.';
    } else {
        $getByEmail = $user->getByEmail($_POST['email']);
        if($getByEmail) {
            $errors['email'] = 'A user with this email already exists. Please choose a different email.';
        } elseif (!strpos($_POST['email'], '@')) {
            $errors['email'] = 'Email must include @ symbol.';
        }
    }

    if (empty($_POST['password'])) {
        $errors['password'] = 'Password is required.';
    } else {
        if (!preg_match('/[0-9]/', $_POST['password']) || !preg_match('/[a-z]/', $_POST['password']) || !preg_match('/[A-Z]/', $_POST['password']) || strlen($_POST['password']) < 5) {
            $errors['password'] = 'Password must contain at least 5 characters, one number, one lowercase letter, one uppercase letter.';
        }
    }
}

if (!empty($errors)) {
    header("Location: register-form.php?errorMessage=" . urlencode(serialize($errors)));
    exit;
}

$user->setFirstName($_POST['first_name']);
$user->setLastName($_POST['last_name']);
$user->setUsername($_POST['username']);
$user->setEmail($_POST['email']);
$user->setPassword($_POST['password']);

$user->store();

header('Location: login-form.php?successMessage=Success.%20You%20can%20now%20log%20in.');
