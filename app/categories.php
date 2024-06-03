<?php

require_once('./classes/Users/User.php');
require_once('./classes/Categories/Category.php');

use Users\User as User;
use Categories\Category as Category;

session_start();

$user = new User();
$userData = $user->getByUsername($_SESSION['username']);

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $CategoryTitle = $_POST['title'];

        if (empty($CategoryTitle)) {
            $errorMessage = 'Category title cannot be empty';
        } else {
            $existingCategory = Category::getByTitle($CategoryTitle);

            if ($existingCategory) {
                $errorMessage = 'Category already exists';
            } else {
                $category = new Category();
                $category->setTitle($CategoryTitle);
                $category->store();

                $successMessage = 'Category added successfully';
            }
        }
    }

    if (isset($_POST['edit_category_submit'])) {
        $categoryId = $_POST['edit_category'];
        $editedTitle = $_POST['edit_title'];

        if (empty($editedTitle)) {
            $errorMessage = 'Edited category title cannot be empty';
        } else {
            $editedCategory = new Category();
            $editedCategory->setId($categoryId);
            $editedCategory->setTitle($editedTitle);
            $editedCategory->update();

            $successMessage = 'Category updated successfully';
        }
    }

    if (isset($_POST['delete_category_submit'])) {
        $categoryId = $_POST['delete_category'];

        $deletedCategory = new Category();
        $deletedCategory->setId($categoryId);
        $deletedCategory->delete();

        $successMessage = 'Category deleted successfully';
    }
}

$categories = Category::getAllCategories();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Logo.png" />
    <title>Categories</title>
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
        <div class="container mx-auto mt-1">

            <?php

            if ($successMessage) {
                echo '<div class="alert alert-success">' . $successMessage . '</div>';
            }

            if ($errorMessage) {
                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
            }
            ?>

            <div class="row">
                <div class="col-3">
                    <h2 class="text-2xl font-semibold mb-3">Categories List</h2>

                    <?php

                    if (empty($categories)) {
                    ?>
                        <p>No categories found.</p>
                    <?php } else { ?>
                        <ol>
                            <?php
                            $counter = 1;
                            foreach ($categories as $category) { ?>
                                <li class="p-2">
                                    <?= $counter++ . '. ' . $category['title'] ?>
                                </li>
                            <?php } ?>
                        </ol>
                    <?php } ?>
                </div>

                <div class="col-3">

                    <h2 class="text-2xl font-semibold mb-3">Add Category</h2>

                    <form action="categories.php" method="POST">
                        <input type="hidden" name="add_category" value="1">
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-600">Category Title</label>
                            <input type="text" id="title" name="title" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        </div>

                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add Category</button>
                    </form>

                </div>

                <div class="col-3">
                    <h2 class="text-2xl font-semibold mb-3">Edit Category</h2>

                    <form action="categories.php" method="POST">
                        <div class="mb-4">
                            <label for="edit_category" class="block text-sm font-medium text-gray-600">Select Category to Edit</label>
                            <select id="edit_category" name="edit_category" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                                <?php

                                foreach ($categories as $category) {
                                    echo '<option value="' . $category['id'] . '">' . $category['title'] . '</option>';
                                }

                                ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="edit_title" class="block text-sm font-medium text-gray-600">Edit Category Title</label>
                            <input type="text" id="edit_title" name="edit_title" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        </div>

                        <input type="hidden" name="edit_category_submit" value="1">

                        <button type="submit" class="bg-sky-600 text-white px-4 py-2 rounded">Edit Category</button>
                    </form>
                </div>

                <div class="col-3">
                    <h2 class="text-2xl font-semibold mb-3">Delete Category</h2>

                    <form action="categories.php" method="POST">
                        <div class="mb-4">
                            <label for="delete_category" class="block text-sm font-medium text-gray-600">Select Category to Delete</label>
                            <select id="delete_category" name="delete_category" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                                <?php

                                foreach ($categories as $category) {
                                    echo '<option value="' . $category['id'] . '">' . $category['title'] . '</option>';
                                }

                                ?>
                            </select>
                        </div>

                        <input type="hidden" name="delete_category_submit" value="1">

                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete Category</button>
                    </form>
                </div>

            </div>
        </div>
    </main>

    <script src='./footer.js'></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>