<?php

require_once('./classes/Users/User.php');
require_once('./classes/Comments/Comment.php');

use Users\User as User;
use Comments\Comment as Comment;

session_start();

$user = new User();
$userData = $user->getByUsername($_SESSION['username']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comment_id'])) {
        $commentId = $_POST['comment_id'];
        Comment::approveComment($commentId);
    } elseif (isset($_POST['reject_comment_id'])) {
        $rejectCommentId = $_POST['reject_comment_id'];
        Comment::rejectComment($rejectCommentId);
    } elseif (isset($_POST['approve_rejected_comment_id'])) {
        $approveRejectedCommentId = $_POST['approve_rejected_comment_id'];
        Comment::approveRejectedComment($approveRejectedCommentId);
    }

    header("Location: ./comments.php");
    exit();
}

$unapprovedComments = Comment::getUnapprovedComments();
$approvedComments = Comment::getApprovedComments();
$rejectedComments = Comment::getRejectedComments();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Logo.png" />
    <title>Comments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="./style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
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
            <h2 class="font-bold my-2 text-2xl">Unapproved Comments:</h2>
            <ul>
                <?php foreach ($unapprovedComments as $comment) : ?>
                    <li class="card mb-3">
                        <div class="card-body">
                            <strong><?= $comment['username'] ?>:</strong>
                            <?= $comment['comment'] ?>
                            <form action="comments.php" method="post" class="mt-2">
                                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                <button type="submit" class="rounded py-2 px-3 text-white bg-green-500">Approve</button>
                                <button type="submit" name="reject_comment_id" value="<?= $comment['id'] ?>" class="rounded py-2 px-3 text-white bg-red-500">Reject</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <h2 class="font-bold my-2 text-2xl">Approved Comments:</h2>
            <ul>
                <?php foreach ($approvedComments as $comment) { ?>
                    <li class="card mb-3">
                        <div class="card-body">
                            <strong><?= $comment['username'] ?>:</strong>
                            <?= $comment['comment'] ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>

            <h2 class="font-bold my-2 text-2xl">Rejected Comments:</h2>
            <ul>
                <?php foreach ($rejectedComments as $comment) : ?>
                    <li class="card mb-3">
                        <div class="card-body">
                            <strong><?= $comment['username'] ?>:</strong>
                            <?= $comment['comment'] ?>
                            <form action="comments.php" method="post" class="mt-2">
                                <input type="hidden" name="approve_rejected_comment_id" value="<?= $comment['id'] ?>">
                                <button type="submit" class="rounded py-2 px-3 text-white bg-green-500">Approve</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </main>
    <script src='./footer.js'></script>

    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="ha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

    <!-- Latest Compiled Bootstrap 4.6 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rejectAgainButtons = document.querySelectorAll('.reject-again-button');

            rejectAgainButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-comment-id');
                    document.getElementById('rejectCommentId').value = commentId;
                    $('#rejectModal').modal('show');
                });
            });

            $('#confirmRejectButton').click(function() {
                $('#rejectForm').submit();
            });

            const approveButtons = document.querySelectorAll('.approve-rejected-button');

            approveButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-comment-id');
                    document.getElementById('approveRejectedCommentId').value = commentId;
                    $('#approveRejectedModal').modal('show');
                });
            });

            $('#confirmApproveRejectedButton').click(function() {
                $('#approveRejectedForm').submit();
            });
        });
    </script>
</body>

</html>