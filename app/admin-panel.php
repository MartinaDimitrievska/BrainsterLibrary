<?php

require_once('./classes/Users/User.php');

use Users\User as User;

session_start();

$user = new User();
$userData = $user->getByUsername($_SESSION['username']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Logo.png" />
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="./style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        footer {
            margin-top: auto;
        }
    </style>
</head>

<body class="bg-yellow-50">
    <nav class="bg-yellow-500">
        <div class="mx-auto max-w-8xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                    <!--
                Icon when menu is closed.
    
                Menu open: "hidden", Menu closed: "block"
              -->
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <!--
                Icon when menu is open.
    
                Menu open: "block", Menu closed: "hidden"
              -->
                    <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex flex-shrink-0 items-center">
                        <a href="./admin-panel.php"><img class="h-8 w-auto" src="../Logo.png" alt="Your Company" /></a>
                    </div>
                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-4"></div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <!-- Profile dropdown -->
                    <div class="relative ml-3 flex items-center">
                        <p class="mr-3">Welcome, <?= $userData['username'] ?></p>
                        <div>
                            <a href="./logout.php" class="no-underline rounded-lg bg-rose-700 m-1 px-3 py-2 text-white">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <div style="position: absolute; translate: -50% -50%; top: 50%; left: 50%; font-size: 30px;">
            <a class="rounded-lg bg-yellow-500 text-black my-5 p-2 no-underline" href="./categories.php">Categories</a>
            <a class="rounded-lg bg-yellow-500 text-black my-5 p-2 no-underline" href="./authors.php">Authors</a>
            <a class="rounded-lg bg-yellow-500 text-black my-5 p-2 no-underline" href="./books.php">Books</a>
            <a class="rounded-lg bg-yellow-500 text-black my-5 p-2 no-underline" href="./comments.php">Comments</a>
        </div>
    </main>

    <script src='./footer.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>