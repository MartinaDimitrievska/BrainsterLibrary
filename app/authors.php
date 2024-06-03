<?php

require_once('./classes/Users/User.php');
require_once('./classes/Authors/Author.php');

use Users\User as User;
use Authors\Author as Author;

session_start();

$user = new User();
$userData = $user->getByUsername($_SESSION['username']);

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_author'])) {
        $AuthorFirstName = $_POST['first_name'];
        $AuthorLastName = $_POST['last_name'];

        if (empty($AuthorFirstName)) {
            $errorMessage = 'Auhtor\'s first name cannot be empty';
        } elseif (empty($AuthorLastName)) {
            $errorMessage = 'Auhtor\'s last name cannot be empty';
        } elseif (empty($_POST['short_bio'])) {
            $errorMessage = 'Auhtor\'s short bio cannot be empty';
        } else {
            $existingAuthor = Author::getByName($AuthorFirstName, $AuthorLastName);

            if ($existingAuthor) {
                $errorMessage = 'Author already exists';
            } else {
                $author = new Author();
                $author->setFirstName($AuthorFirstName);
                $author->setLastName($AuthorLastName);
                $author->setShortBio($_POST['short_bio']);
                $author->store();

                $successMessage = 'Author added successfully';
            }
        }
    }

    if (isset($_POST['edit_author_submit'])) {
        $authorId = $_POST['edit_author_id'];
        $editedFirstName = $_POST['edit_first_name'];
        $editedLastName = $_POST['edit_last_name'];

        if (empty($editedFirstName)) {
            $errorMessage = 'Edited author\'s first name cannot be empty';
        } elseif (empty($editedLastName)) {
            $errorMessage = 'Edited author\'s last name cannot be empty';
        } elseif (empty($_POST['edit_short_bio'])) {
            $errorMessage = 'Edited author\'s short bio cannot be empty';
        } else {

            $existingAuthor = Author::getByName($editedFirstName, $editedLastName);

            if ($existingAuthor && $existingAuthor['id'] != $authorId) {
                $errorMessage = 'Author already exists';
                exit;
            }

            $editedAuthor = new Author();
            $editedAuthor->setId($authorId);
            $editedAuthor->setFirstName($editedFirstName);
            $editedAuthor->setLastName($editedLastName);
            $editedAuthor->setShortBio($_POST['edit_short_bio']);

            $editedAuthor->update();

            $successMessage = 'Author edited successfully';
        }
    }


    if (isset($_POST['delete_author_submit'])) {

        $deletedAuthor = new Author();
        $deletedAuthor->setId($_POST['delete_author']);
        $deletedAuthor->delete();

        $successMessage = 'Author deleted successfully';
    }
}

$authors = Author::getAllAuthors();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Logo.png" />
    <title>Authors</title>
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
                    <h2 class="text-2xl font-semibold mb-3">Authors List</h2>

                    <?php

                    if (empty($authors)) {
                    ?>
                        <p>No authors found.</p>
                    <?php } else { ?>
                        <ol>
                            <?php
                            $counter = 1;
                            foreach ($authors as $author) : ?>
                                <li class="p-2">
                                    <?= $counter++ . '. ' . $author['first_name'] . ' ' . $author['last_name'] ?>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php } ?>
                </div>

                <div class="col-3">

                    <h2 class="text-2xl font-semibold mb-3">Add Author</h2>

                    <form action="authors.php" method="POST">
                        <input type="hidden" name="add_author" value="1">
                        <div class="mb-4">
                            <label for="first_name" class="block text-sm font-medium text-gray-600">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        </div>

                        <div class="mb-4">
                            <label for="last_name" class="block text-sm font-medium text-gray-600">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        </div>

                        <div class="mb-4">
                            <label for="short_bio" class="block text-sm font-medium text-gray-600">Short Bio</label>
                            <textarea id="short_bio" name="short_bio" rows="4" cols="50" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6"></textarea>
                        </div>

                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add Author</button>
                    </form>

                </div>

                <div class="col-3">
                    <h2 class="text-2xl font-semibold mb-3">Edit Author</h2>

                    <form action="authors.php" method="POST">
                        <div class="mb-4">
                            <label for="edit_author" class="block text-sm font-medium text-gray-600">Select Author to Edit</label>
                            <select id="edit_author" name="edit_author" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                                <?php
                                foreach ($authors as $author) {
                                    $selected = ($author['id'] == $_POST['edit_author']) ? 'selected' : '';
                                    echo '<option value="' . $author['id'] . '" ' . $selected . '>' . $author['first_name'] . ' ' . $author['last_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <input type="hidden" name="select_author" value="1">
                        <button type="submit" name="select_author_submit" class="mb-3 bg-sky-600 text-white px-4 py-2 rounded">Select Author</button>
                    </form>

                    <?php
                    if ((isset($_POST['select_author_submit']) && isset($_POST['edit_author']))) {
                        $selectedBookId = $_POST['edit_author'];
                        $selectedBook = Author::getById($selectedBookId);

                        if ($selectedBook) {
                            echo '<form action="authors.php" method="POST">';
                            echo '<input type="hidden" name="edit_author_id" value="' . $selectedBook['id'] . '">';

                            echo '<div class="mb-4">';
                            echo '<label for="edit_first_name" class="block text-sm font-medium text-gray-600">First Name</label>';
                            echo '<input type="text" id="edit_first_name" name="edit_first_name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" value="' . $selectedBook['first_name'] . '" >';
                            echo '</div>';

                            echo '<div class="mb-4">';
                            echo '<label for="edit_last_name" class="block text-sm font-medium text-gray-600">Last Name</label>';
                            echo '<input type="text" id="edit_last_name" name="edit_last_name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" value="' . $selectedBook['last_name'] . '">';
                            echo '</div>';

                            echo '<div class="mb-4">';
                            echo '<label for="edit_short_bio" class="block text-sm font-medium text-gray-600">Short Bio</label>';
                            echo '<textarea id="edit_short_bio" name="edit_short_bio" rows="4" cols="50" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">' . htmlspecialchars($selectedBook['short_bio']) . '</textarea>';
                            echo '</div>';

                            echo '<button type="submit" name="edit_author_submit" class="bg-sky-600 text-white px-4 py-2 rounded">Edit Author</button>';
                            echo '</form>';
                        }
                    } else {
                        echo '<p>No author is selected for editing.</p>';
                    }
                    ?>
                </div>

                <div class="col-3">
                    <h2 class="text-2xl font-semibold mb-3">Delete Author</h2>

                    <form action="authors.php" method="POST">
                        <div class="mb-4">
                            <label for="delete_author" class="block text-sm font-medium text-gray-600">Select Author to Delete</label>
                            <select id="delete_author" name="delete_author" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                                <?php

                                foreach ($authors as $author) {
                                    echo '<option value="' . $author['id'] . '">' . $author['first_name'] . ' ' . $author['last_name'] . '</option>';
                                }

                                ?>
                            </select>
                        </div>

                        <input type="hidden" name="delete_author_submit" value="1">

                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete Author</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src='./footer.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>