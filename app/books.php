<?php
require_once('./classes/Users/User.php');
require_once('./classes/Books/Book.php');
require_once('./classes/Authors/Author.php');
require_once('./classes/Categories/Category.php');

use Users\User as User;
use Books\Book as Book;
use Authors\Author as Author;
use Categories\Category as Category;

session_start();

$user = new User();
$userData = $user->getByUsername($_SESSION['username']);

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_book'])) {
        if (empty($_POST['add_title'])) {
            $errorMessage = 'Title cannot be empty.';
        } elseif (empty($_POST['add_author'])) {
            $errorMessage = 'Author cannot be empty.';
        } elseif (empty($_POST['add_category'])) {
            $errorMessage = 'Category cannot be empty.';
        } elseif (!preg_match('/^\d{4}$/', $_POST['add_year_published'])) {
            $errorMessage = 'Year must have four digits.';
        } elseif (!is_numeric($_POST['add_number_of_pages'])) {
            $errorMessage = 'Number of pages must be a numeric value.';
        } elseif (empty($_POST['add_image_url']) || !filter_var($_POST['add_image_url'], FILTER_VALIDATE_URL)) {
            $errorMessage = 'Invalid image URL.';
        } else {
            $newBook = new Book();
            $newBook->setTitle($_POST['add_title']);
            $newBook->setAuthorId($_POST['add_author']);
            $newBook->setCategoryId($_POST['add_category']);
            $newBook->setYearPublished($_POST['add_year_published']);
            $newBook->setNumberOfPages($_POST['add_number_of_pages']);
            $newBook->setImageUrl($_POST['add_image_url']);

            $newBook->store();

            $successMessage = 'Book added successfully';
        }
    }

    if (isset($_POST['edit_book_submit'])) {
        if (empty($_POST['edit_title'])) {
            $errorMessage = 'Edited title cannot be empty.';
        } elseif (empty($_POST['edit_author'])) {
            $errorMessage = 'Edited author cannot be empty.';
        } elseif (empty($_POST['edit_category'])) {
            $errorMessage = 'Edited category cannot be empty.';
        } elseif (!preg_match('/^\d{4}$/', $_POST['edit_year_published'])) {
            $errorMessage = 'Edited year must have four digits.';
        } elseif (!is_numeric($_POST['edit_number_of_pages'])) {
            $errorMessage = 'Edited number of pages must be a numeric value.';
        } elseif (empty($_POST['edit_image_url']) || !filter_var($_POST['edit_image_url'], FILTER_VALIDATE_URL)) {
            $errorMessage = 'Invalid editing of image URL.';
        } else {
            $editedBook = new Book();
            $editedBook->setId($_POST['edit_book_id']);
            $editedBook->setTitle($_POST['edit_title']);
            $editedBook->setAuthorId($_POST['edit_author']);
            $editedBook->setCategoryId($_POST['edit_category']);
            $editedBook->setYearPublished($_POST['edit_year_published']);
            $editedBook->setNumberOfPages($_POST['edit_number_of_pages']);
            $editedBook->setImageUrl($_POST['edit_image_url']);

            $editedBook->update();

            $successMessage = 'Book edited successfully';
        }
    }

    // if (isset($_POST['delete_book_submit'])) {
    //     $bookToDelete = new Book();
    //     $bookToDelete->setId($_POST['delete_book']);
    //     $bookToDelete->delete();

    //     $successMessage = 'Book deleted successfully';
    // }

    if (isset($_POST['delete_book_id'])) {
        if ($_POST['sweet_alert_confirmation'] == 1) {
            $deleteBookId = $_POST['delete_book_id'];
            Book::deleteBookAndCommentsAndPrivateNotes($deleteBookId);
            $successMessage = 'Book deleted successfully';
        } else {
            $errorMessage = 'Book deletion cancelled';
        }
    }
}

$books = Book::getAllBooks();
$authors = Author::getAllAuthors();
$categories = Category::getAllCategories();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Logo.png" />
    <title>Books</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="./style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://common.olemiss.edu/_js/sweet-alert/sweet-alert.css">
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
                <!-- <div class="col-3">
                    <h2 class="text-2xl font-semibold mb-3">Books List</h2>

                    <?php
                    // if (empty($books)) {
                    //     echo '<p>No books found.</p>';
                    // } else {
                    //     echo '<ol>';
                    //     foreach ($books as $book) {
                    //         echo '<li class="p-2">' . $book['title'] . '</li>';
                    //     }
                    //     echo '</ol>';
                    // }
                    ?>
                </div> -->

                <div class="col-3">
                    <h2 class="text-2xl font-semibold mb-3">Add Book</h2>

                    <form action="books.php" method="POST">
                        <input type="hidden" name="add_book" value="1">

                        <div class="mb-4">
                            <label for="add_title" class="block text-sm font-medium text-gray-600">Title</label>
                            <input type="text" id="add_title" name="add_title" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        </div>

                        <div class="mb-4">
                            <label for="add_author" class="block text-sm font-medium text-gray-600">Author</label>
                            <select id="add_author" name="add_author" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                                <?php
                                foreach ($authors as $author) {
                                    echo '<option value="' . $author['id'] . '">' . $author['first_name'] . ' ' . $author['last_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="add_category" class="block text-sm font-medium text-gray-600">Category</label>
                            <select id="add_category" name="add_category" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                                <?php
                                foreach ($categories as $category) {
                                    echo '<option value="' . $category['id'] . '">' . $category['title'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="add_year_published" class="block text-sm font-medium text-gray-600">Year of Publication</label>
                            <input type="text" id="add_year_published" name="add_year_published" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        </div>

                        <div class="mb-4">
                            <label for="add_number_of_pages" class="block text-sm font-medium text-gray-600">Number of Pages</label>
                            <input type="text" id="add_number_of_pages" name="add_number_of_pages" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        </div>

                        <div class="mb-4">
                            <label for="add_image_url" class="block text-sm font-medium text-gray-600">Image URL</label>
                            <input type="text" id="add_image_url" name="add_image_url" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        </div>

                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add Book</button>
                    </form>
                </div>

                <div class="col-3">
                    <h2 class="text-2xl font-semibold mb-3">Edit Book</h2>

                    <form action="books.php" method="POST">
                        <div class="mb-4">
                            <label for="edit_book" class="block text-sm font-medium text-gray-600">Select Book to Edit</label>
                            <select id="edit_book" name="edit_book" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                                <?php
                                foreach ($books as $book) {
                                    $selected = ($book['id'] == $_POST['edit_book']) ? 'selected' : '';
                                    echo '<option value="' . $book['id'] . '" ' . $selected . '>' . $book['title'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <input type="hidden" name="select_book" value="1">
                        <button type="submit" name="select_book_submit" class="mb-3 bg-sky-600 text-white px-4 py-2 rounded">Select Book</button>
                    </form>

                    <?php
                    if ((isset($_POST['select_book_submit']) && isset($_POST['edit_book']))) {
                        $selectedBookId = $_POST['edit_book'];
                        $selectedBook = Book::getById($selectedBookId);

                        if ($selectedBook) {
                            echo '<form action="books.php" method="POST">';
                            echo '<input type="hidden" name="edit_book_id" value="' . $selectedBook['id'] . '">';

                            echo '<div class="mb-4">';
                            echo '<label for="edit_title" class="block text-sm font-medium text-gray-600">Title</label>';
                            echo '<input type="text" id="edit_title" name="edit_title" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" value="' . $selectedBook['title'] . '">';
                            echo '</div>';

                            echo '<div class="mb-4">';
                            echo '<label for="edit_author" class="block text-sm font-medium text-gray-600">Author</label>';
                            echo '<select id="edit_author" name="edit_author" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">';
                            foreach ($authors as $author) {
                                $selected = ($author['id'] == $selectedBook['author_id']) ? 'selected' : '';
                                echo '<option value="' . $author['id'] . '" ' . $selected . '>' . $author['first_name'] . ' ' . $author['last_name'] . '</option>';
                            }
                            echo '</select>';
                            echo '</div>';

                            echo '<div class="mb-4">';
                            echo '<label for="edit_category" class="block text-sm font-medium text-gray-600">Category</label>';
                            echo '<select id="edit_category" name="edit_category" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">';
                            foreach ($categories as $category) {
                                $selected = ($category['id'] == $selectedBook['category_id']) ? 'selected' : '';
                                echo '<option value="' . $category['id'] . '" ' . $selected . '>' . $category['title'] . '</option>';
                            }
                            echo '</select>';
                            echo '</div>';

                            echo '<div class="mb-4">';
                            echo '<label for="edit_year_published" class="block text-sm font-medium text-gray-600">Year published</label>';
                            echo '<input type="text" id="edit_year_published" name="edit_year_published" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" value="' . $selectedBook['year_published'] . '">';
                            echo '</div>';

                            echo '<div class="mb-4">';
                            echo '<label for="edit_number_of_pages" class="block text-sm font-medium text-gray-600">Number of pages</label>';
                            echo '<input type="text" id="edit_number_of_pages" name="edit_number_of_pages" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" value="' . $selectedBook['number_of_pages'] . '">';
                            echo '</div>';
                            echo '<div class="mb-4">';
                            echo '<label for="edit_image_url" class="block text-sm font-medium text-gray-600">Image URL</label>
                            <input type="text" id="edit_image_url" name="edit_image_url" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6" value="' . $selectedBook['image_url'] . '">';
                            echo '</div>';

                            echo '<button type="submit" name="edit_book_submit" class="bg-sky-600 text-white px-4 py-2 rounded">Edit Book</button>';
                            echo '</form>';
                        }
                    } else {
                        echo '<p>No book is selected for editing.</p>';
                    }
                    ?>
                </div>
                <div class="col-3">
                    <h2 class="text-2xl font-semibold mb-3">Delete Book</h2>

                    <form action="books.php" method="POST" id="deleteBookForm">
                        <div class="mb-4">
                            <label for="delete_book" class="block text-sm font-medium text-gray-600">Select Book to Delete</label>
                            <select id="delete_book" name="delete_book" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                                <?php
                                foreach ($books as $book) {
                                    echo '<option value="' . $book['id'] . '">' . $book['title'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <input type="hidden" name="delete_book_id" id="delete_book_id" value="">
                        <input type="hidden" name="sweet_alert_confirmation" id="sweet_alert_confirmation" value="0">
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete Book</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <h2 class="text-2xl font-semibold mb-3">All Books</h2>

                <?php
                if (empty($books)) {
                    echo '<p>No books found.</p>';
                } else {
                    foreach ($books as $book) {
                        echo '<div class="col col-3 mb-4">';
                        echo '<div class="card shadow" style="width: 18rem;">';
                        echo '<a href="book-info.php?id=' . $book['id'] . '" class="text-decoration-none text-black">';
                        echo '<img src="' . $book['image_url'] . '" class="card-img-top" alt="' . $book['title'] . '" style="height: 400px;">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title font-bold text-xl">' . $book['title'] . '</h5>';
                        echo '<p class="mb-1">Author: ' . $book['author_first_name'] . ' ' . $book['author_last_name'] . '</p>';
                        echo '<p class="mb-1">Category: ' . $book['category_title'] . '</p>';
                        echo '<p class="mb-1">Publishing year: ' . $book['year_published'] . '</p>';
                        echo '<p class="mb-1">Pages: ' . $book['number_of_pages'] . '</p>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
        </div>
    </main>

    <script src='./footer.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('deleteBookForm').addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: "Are you sure?",
                text: "This action will also delete all comments and notes from the book.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e60000",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    document.getElementById('delete_book_id').value = document.getElementById('delete_book').value;
                    document.getElementById('sweet_alert_confirmation').value = "1";
                    document.getElementById('deleteBookForm').submit();
                } else {
                    Swal.fire("Cancelled", "Book deletion cancelled", "error");
                }
            });
        });
    </script>
</body>

</html>