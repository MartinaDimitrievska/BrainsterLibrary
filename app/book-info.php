<?php
require_once('./classes/Users/User.php');
require_once('./classes/Books/Book.php');
require_once('./classes/Comments/Comment.php');
require_once('./classes/PrivateNotes/PrivateNote.php');

use Users\User as User;
use Books\Book as Book;
use Comments\Comment as Comment;
use PrivateNotes\PrivateNote as PrivateNote;

session_start();

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
  die('Book not found.');
}

$errorMessage = '';
$successMessage = '';

if (isset($_SESSION['username'])) {
  $user = new User();
  $userData = $user->getByUsername($_SESSION['username']);

  if ($userData) {
    $userId = $userData['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      //COMMENTS:
      if (isset($_POST['comment'])) {
        $bookId = $_POST['book_id'];
        $commentText = $_POST['comment'];

        if (!empty($commentText)) {
          $userHasCommented = Comment::userHasCommented($userId, $bookId);

          if (!$userHasCommented) {
            $comment = new Comment();
            $comment->setComment($commentText);
            $comment->setIsDeleted(0);
            $comment->setIsApproved(0);
            $comment->setBookId($bookId);
            $comment->setUserId($userId);

            $comment->store();
          } else {
            $errorMessage = "You have already left a comment on this book.";
          }
        } else {
          $errorMessage = "Comment cannot be empty.";
        }
      } elseif (isset($_POST['delete_comment_id'])) {
        $commentId = $_POST['delete_comment_id'];

        if (Comment::deleteComment($commentId)) {
          $successMessage = "Comment deleted successfully.";
        } else {
          $errorMessage = "Something's wrong";
        }
      }
      //NOTES:
      if (isset($_POST['private_note'])) {
        $bookId = $_POST['book_id'];
        $privateNoteText = $_POST['private_note'];

        $user = new User();
        $userData = $user->getByUsername($_SESSION['username']);

        if ($userData) {
          $userId = $userData['id'];

          $privateNote = new PrivateNote();
          $privateNote->setPrivateNote($privateNoteText);
          $privateNote->setBookId($bookId);
          $privateNote->setUserId($userId);

          $privateNote->store();

          echo json_encode(['status' => 'success', 'private_note' => $privateNoteText, 'id' => $privateNote->getId()]);
          exit;
        } else {
          echo json_encode(['status' => 'error', 'message' => 'User data not found']);
          exit;
        }
      }

      if (isset($_POST['edit_private_note_id']) && isset($_POST['private_note'])) {
        $noteId = $_POST['edit_private_note_id'];
        $privateNoteText = $_POST['private_note'];

        $privateNote = PrivateNote::getById($noteId);

        if ($privateNote !== null) {
          $privateNote->setPrivateNote($privateNoteText);
          $privateNote->update();

          $successMessage = "Private note edited successfully.";
          // header('Content-Type: application/json');
          // echo json_encode(['status' => 'success']);
        } else {
          $errorMessage = "Failed to edit private note.";
          // header('Content-Type: application/json');
          // echo json_encode(['status' => 'error', 'message' => 'An error occurred']);
        }
      }

      if (isset($_POST['delete_private_note_id'])) {
        $noteId = $_POST['delete_private_note_id'];

        $privateNote = new PrivateNote();
        $privateNote->setId($noteId);

        $result = $privateNote->delete();

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
      } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'An error occurred']);
      }
    }
  }
}

$bookDetails = Book::getById($id);
$comments = Comment::getById($id);
$allPrivateNotes = PrivateNote::getAllByBookId($id);

$userId = isset($_SESSION['username']) ? $userData['id'] : null;
$allPrivateNotes = PrivateNote::getAllByBookIdAndUserId($id, $userId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="../Logo.png" />
  <title><?= $bookDetails['title'] ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="./style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
    <div class="mx-auto max-w-8xl px-2 sm:px-6 lg:px-8 flex justify-between">
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
            <a href="
            <?php
            if (isset($_SESSION['username'])) {
              if ($_SESSION['username'] === 'admin') {
                echo './admin-panel.php';
              } else {
                echo './user-panel.php';
              }
            } else {
              echo './homepage.php';
            }
            ?>">
              <img class="h-8 w-auto" src="../Logo.png" alt="Your Company" />
            </a>
          </div>
        </div>
        <div class="hidden sm:ml-6 sm:block">
          <div class="flex space-x-4"></div>
        </div>
      </div>
      <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
        <!-- Profile dropdown -->

        <?php if (isset($_SESSION['username'])) : ?>
          <div class="relative ml-3 flex items-center">
            <p class="mr-3">Welcome, <?= $_SESSION['username'] ?></p>
            <div>
              <a href="./logout.php" class="no-underline rounded-lg bg-rose-700 m-1 px-3 py-2 text-white">
                Logout
              </a>
            </div>
          </div>
        <?php else : ?>
          <div class="relative ml-3">
            <div>
              <a href="./register-form.php" class="rounded-lg bg-fuchsia-600 m-1 px-3 py-2 text-white hover:bg-fuchsia-500">
                Register
              </a>
              <a href="./login-form.php" class="rounded-lg bg-green-600 m-1 px-3 py-2 text-white hover:bg-green-500">
                Login
              </a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
    </div>
    </div>
  </nav>
  <main>
    <div class="container mx-auto mt-8">
      <div class="card mb-3" style="width: 60%; margin:auto;">
        <div class="row g-0">
          <div class="col-md-5">
            <img src="<?= $bookDetails['image_url'] ?>" alt="<?= $bookDetails['title'] ?>" class="img-fluid rounded-start">
          </div>
          <div class="col-md-7 d-flex align-items-center" style="font-size: 25px;">
            <div class="card-body">
              <h1 class="card-title font-bold mb-5" style="font-size: 40px;"><?= $bookDetails['title'] ?></h1>
              <p class="card-text font-bold">Author:</p><span><?= $bookDetails['author_first_name'] . ' ' . $bookDetails['author_last_name'] ?></span>
              <p class="card-text font-bold">Category: </p><span><?= $bookDetails['category_title'] ?></span>
              <p class="card-text font-bold">Published year: </p><span><?= $bookDetails['year_published'] ?></span>
              <p class="card-text font-bold">Number of pages: </p><span><?= $bookDetails['number_of_pages'] ?></span>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <h2 class="font-bold my-2" style="font-size:30px;">Comments:</h2>
      <?php

      if ((isset($_SESSION['username'])) && (!empty($successMessage))) {
        echo '<div class="alert alert-success">' . $successMessage . '</div>';
      }

      if ((isset($_SESSION['username'])) && (!empty($errorMessage))) {
        echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
      }

      if (isset($_SESSION['username']) && $_SESSION['username'] !== 'admin') {
      ?>
        <form action="book-info.php?id=<?= $id ?>" method="POST">
          <div class="flex align-items-center">
            <input type="hidden" name="book_id" value="<?= $id ?>">
            <textarea name="comment" rows="3" cols="50" class="form-control" placeholder="Leave a comment for this book..."></textarea>
            <button type="submit" class="ml-3 bg-teal-500 text-white px-4 py-2 rounded">Add Comment</button>
          </div>
        </form>
      <?php }

      $allComments = Comment::getAllCommentsForUserAndBook(null, $id);

      $userId = isset($_SESSION['username']) ? $userData['id'] : null;
      $userComments = Comment::getAllCommentsForUserAndBook($userId, $id);

      $mergedComments = array_merge($allComments, $userComments);
      $uniqueComments = array_unique($mergedComments, SORT_REGULAR);

      foreach ($uniqueComments as $comment) {
        $statusClass = $comment['is_approved'] ? 'border-teal-500' : 'border-amber-500';

        echo '<div class="card my-3 border-3 ' . $statusClass . '">';
        echo '<div class="card-body">';
        echo '<p>' . ($comment['is_approved'] ? '' : 'Status: Pending') . '</p>';
        echo '<strong>' . $comment['username'] . ':</strong> ' . $comment['comment'];

        if (isset($_SESSION['username']) && $_SESSION['username'] === $comment['username']) {
          echo '<form action="book-info.php?id=' . $id . '" method="POST" class="mt-2">';
          echo '<input type="hidden" name="delete_comment_id" value="' . $comment['id'] . '">';
          echo '<button type="submit" class="bg-red-600 text-white px-2 py-1 rounded">Delete</button>';
          echo '</form>';
        }

        echo '</div>';
        echo '</div>';
      }
      ?>
      <?php if (isset($_SESSION['username']) && $_SESSION['username'] !== 'admin') { ?>
        <h2 class="font-bold my-2" style="font-size:30px;">Private Notes:</h2>
        <form id="add-private-note-form">
          <div class="flex align-items-center mb-3">
            <input type="hidden" id="book-id" value="<?= $id ?>">
            <input type="hidden" name="edit_private_note_id" id="edit_private_note_id" value="<?= $id ?>">
            <textarea name="private_note" rows="3" cols="50" class="form-control" placeholder="Add a private note..."></textarea>
            <button type="submit" class="ml-3 bg-violet-500 text-white px-4 py-2 rounded">Add Private Note</button>
          </div>
        </form>

        <div id="private-notes-container" data-book-id="<?= $id ?>">
        <?php
        foreach ($allPrivateNotes as $note) {
          echo '<div class="card my-3 border-3 border-violet-500">';
          echo '<div class="card-body">';
          echo '<div class="private-note-content mb-2" data-note-id="' . $note['id'] . '">';
          echo '<strong>Your Note:</strong> ' . $note['private_note'];
          echo '</div>';
          echo '<button class="btn btn-sm btn-warning edit-private-note" data-note-id="' . $note['id'] . '">Edit</button>';
          echo '<button class="ml-2 btn btn-sm btn-danger delete-private-note" data-note-id="' . $note['id'] . '">Delete</button>';
          echo '</div>';
          echo '</div>';
        }
      }
        ?>
        </div>
  </main>
  <script src='./footer.js'></script>
  <script src='./private-notes.js'></script>
</body>

</html>