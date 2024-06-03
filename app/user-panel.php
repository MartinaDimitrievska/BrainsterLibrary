<?php

require_once('./classes/Users/User.php');
require_once('./classes/Books/Book.php');
require_once('./classes/Categories/Category.php');

use Users\User as User;
use Books\Book as Book;
use Categories\Category as Category;

session_start();

$user = new User();
$userData = $user->getByUsername($_SESSION['username']);

$categoryFilter = isset($_GET['category']) ? explode(',', $_GET['category']) : ['all'];

$books = (in_array('all', $categoryFilter)) ? Book::getAllBooks() : Book::getByCategory($categoryFilter);

$categories = Category::getAllCategories();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="../Logo.png" />
  <title>User Panel</title>
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
            <a href="./user-panel.php"><img class="h-8 w-auto" src="../Logo.png" alt="Your Company" /></a>
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

  <main class="container mx-auto mt-8">
    <div class="row">
      <div class="flex items-center mb-4">
        <h3 class="mr-2 font-bold" style="font-size: 23px;">Filter by Category:</h3>
        <?php
        foreach ($categories as $category) {
          echo '<label class="mr-2"><input class="form-check-input" type="checkbox" name="category[]" value="' . $category['id'] . '" ' . (in_array($category['id'], $categoryFilter) ? 'checked' : '') . '> ' . $category['title'] . '</label>';
        }
        ?>
        <button onclick="filterBooks()" class="ml-2 bg-cyan-500 rounded text-white px-3 py-2">Apply Filter</button>
      </div>
    </div>

    <div class="row">
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
          echo '</div>';
          echo '</a>';
          echo '</div>';
          echo '</div>';
        }
      }
      ?>
    </div>
  </main>
  <script src='./footer.js'></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <script>
    function filterBooks() {
      let selectedCheckboxes = document.querySelectorAll('input[name="category[]"]:checked');
      let categoryFilter = Array.from(selectedCheckboxes).map(checkbox => checkbox.value).join(',');

      if (categoryFilter !== '') {
        window.location.href = 'user-panel.php?category=' + categoryFilter;
      }
    }
  </script>
</body>

</html>