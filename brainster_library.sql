-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2024 at 10:07 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brainster_library`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `short_bio` varchar(512) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `first_name`, `last_name`, `short_bio`, `is_deleted`) VALUES
(2, 'Lev', 'Nikolayevich Tolstoy', 'Count Lev Nikolayevich Tolstoy, usually referred to in English as Leo Tolstoy, was a Russian writer regarded as one of the greatest authors of all time. He received nominations for the Nobel Prize in Literature every year from 1902 to 1906 and for the Nobel Peace Prize in 1901, 1902, and 1909. Wikipedia', 1),
(3, 'Leo', 'Tolstoy', 'Count Lev Nikolayevich Tolstoy, usually referred to in English as Leo Tolstoy, was a Russian writer regarded as one of the greatest authors of all time. He received nominations for the Nobel Prize in Literature every year from 1902 to 1906 and for the Nobel Peace Prize in 1901, 1902, and 1909.', 0),
(4, 'Harper', 'Lee', 'Nelle Harper Lee was an American novelist who wrote the 1960 novel To Kill a Mockingbird that won the 1961 Pulitzer Prize and became a classic of modern American literature. She assisted her close friend Truman Capote in his research for the book In Cold Blood', 0),
(5, 'Shel', 'Silverstein', 'Sheldon Allan Silverstein was an American writer, poet, cartoonist, singer-songwriter, musician, and playwright. Born and raised in Chicago, Illinois, Silverstein briefly attended university before being drafted into the United States Army.', 0),
(6, 'Jacqueline', 'Susann', 'Jacqueline Susann was an American novelist and actress. Her iconic novel, Valley of the Dolls, is one of the best-selling books in publishing history.', 0),
(7, 'Stephen', 'King', 'Stephen Edwin King is an American author of horror, supernatural fiction, suspense, crime, science-fiction, and fantasy novels. Called the \"King of Horror\", his books have sold more than 350 million copies as of 2006, and many have been adapted into films, television series, miniseries, and comic books.', 1),
(8, 'Antoine', 'Saint-Exupéry', 'Antoine Marie Jean-Baptiste Roger, comte de Saint-Exupéry, known simply as Antoine de Saint-Exupéry, was a French writer, poet, journalist and pioneering aviator.', 0),
(9, 'John', 'Tolkien', 'John Ronald Reuel Tolkien CBE FRSL was an English writer and philologist. He was the author of the high fantasy works The Hobbit and The Lord of the Rings. From 1925 to 1945, Tolkien was the Rawlinson and Bosworth Professor of Anglo-Saxon and a Fellow of Pembroke College, both at the University of Oxford.', 0),
(10, 'Margaret', 'Atwood', 'Margaret Eleanor Atwood CC OOnt CH FRSC FRSL is a Canadian poet, novelist, literary critic, essayist, teacher, environmental activist, and inventor.', 0),
(11, 'Madeleine', 'L’Engle', 'Madeleine L\'Engle was an American writer of fiction, non-fiction, poetry, and young adult fiction, including A Wrinkle in Time and its sequels: A Wind in the Door, A Swiftly Tilting Planet, Many Waters, and An Acceptable Time. Her works reflect both her Christian faith and her strong interest in modern science.', 0),
(12, 'Jane', 'Austen', 'Jane Austen was an English novelist known primarily for her six novels, which implicitly interpret, critique, and comment upon the British landed gentry at the end of the 18th century. Austen\'s plots often explore the dependence of women on marriage for the pursuit of favourable social standing and economic security', 0),
(13, 'Stephen', 'King', 'Stephen Edwin King is an American author of horror, supernatural fiction, suspense, crime, science-fiction, and fantasy novels. Called the \"King of Horror\", his books have sold more than 350 million copies as of 2006, and many have been adapted into films, television series, miniseries, and comic books.\r\n', 0),
(14, 'Bob', 'Woodward', 'Robert Upshur Woodward is an American investigative journalist. He started working for The Washington Post as a reporter in 1971 and now holds the title of associate editor.', 0),
(15, 'Viktor', 'Frankl', 'Viktor Emil Frankl was an Austrian psychiatrist and Holocaust survivor, who founded logotherapy, a school of psychotherapy that describes a search for a life\'s meaning as the central human motivational force. Logotherapy is part of existential and humanistic psychology theories.', 0),
(16, 'Toni', 'Morrison', 'Chloe Anthony Wofford Morrison, known as Toni Morrison, was an American novelist. Her first novel, The Bluest Eye, was published in 1970. The critically acclaimed Song of Solomon brought her national attention and won the National Book Critics Circle Award', 0),
(17, 'Truman', 'Capote', 'Truman Garcia Capote was an American novelist, screenwriter, playwright and actor. Several of his short stories, novels, and plays have been praised as literary classics, including the novella Breakfast at Tiffany\'s and the true crime novel In Cold Blood, which he labeled a \"non-fiction novel\".', 0);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(10) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `title` varchar(32) NOT NULL,
  `year_published` int(11) DEFAULT NULL,
  `number_of_pages` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `author_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `image_url`, `title`, `year_published`, `number_of_pages`, `is_deleted`, `author_id`, `category_id`) VALUES
(14, 'https://miro.medium.com/v2/resize:fit:5120/1*42ebJizcUtZBNIZPmmMZ5Q.jpeg', 'Title', 1998, 350, 1, 6, 9),
(16, 'https://m.media-amazon.com/images/I/91F9WNEThJL._AC_UF1000,1000_QL80_.jpg', 'Anna Karenina', 1878, 864, 0, 3, 8),
(17, 'https://upload.wikimedia.org/wikipedia/commons/4/4f/To_Kill_a_Mockingbird_%28first_edition_cover%29.jpg', 'To Kill a Mockingbird', 1960, 336, 0, 4, 2),
(18, 'https://m.media-amazon.com/images/I/81zRl2vuuLL._AC_UF1000,1000_QL80_.jpg', 'Where the Sidewalk Ends', 1974, 309, 0, 5, 10),
(19, 'https://m.media-amazon.com/images/I/81YS9egzy2L._AC_UF1000,1000_QL80_.jpg', 'Valley of the Dolls', 1966, 442, 0, 6, 8),
(20, 'https://upload.wikimedia.org/wikipedia/commons/0/09/The_Shining_%281977%29_front_cover%2C_first_edition.jpg', 'The Shining', 1977, 447, 0, 13, 12),
(21, 'https://m.media-amazon.com/images/I/71OZY035QKL._AC_UF1000,1000_QL80_.jpg', 'The Little Prince', 1943, 96, 0, 8, 13),
(22, 'https://m.media-amazon.com/images/I/71bocXHoUvL._AC_UF1000,1000_QL80_.jpg', 'The Fellowship of the Ring', 1954, 423, 0, 9, 14),
(23, 'https://m.media-amazon.com/images/I/71mfL5OGNNL._AC_UF1000,1000_QL80_.jpg', 'The Handmaid’s Tale', 1985, 311, 0, 10, 2),
(28, 'https://m.media-amazon.com/images/I/914SzWORucL._AC_UF1000,1000_QL80_.jpg', 'In Cold Blood', 1965, 343, 0, 17, 15),
(29, 'https://m.media-amazon.com/images/I/51Qj9kPD4CL._AC_UF1000,1000_QL80_.jpg', 'Beloved', 1987, 324, 0, 16, 2),
(30, 'https://m.media-amazon.com/images/I/81-Lkj4ekuL._AC_UF1000,1000_QL80_.jpg', 'Man’s Search for Meaning', 1946, 200, 0, 15, 16),
(31, 'https://m.media-amazon.com/images/I/61Ty+ZZTUhL._AC_UF1000,1000_QL80_.jpg', 'All the President’s Men', 1974, 349, 0, 14, 17);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(64) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `is_deleted`) VALUES
(1, 'Comedy', 1),
(2, 'Fiction', 0),
(3, 'Fantasy', 1),
(8, 'Romance', 0),
(9, 'Comedy', 0),
(10, 'Children\'s Poetry', 0),
(12, 'Horror', 0),
(13, 'Fairy Tale', 0),
(14, 'Fantasy', 0),
(15, 'Crime', 0),
(16, 'Autobiography', 0),
(17, 'Biography', 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `comment` varchar(255) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `is_approved` tinyint(4) NOT NULL,
  `book_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `private_notes`
--

CREATE TABLE `private_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `private_note` varchar(255) NOT NULL,
  `book_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `role` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'authenticated user'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `is_deleted`, `role_id`) VALUES
(11, 'admin', 'admin', 'admin', 'admin@gmail.com', '$2y$10$m6XBauQSzdL7YiaoNtKGAOSqwbWMQmdAPrF9vIrXijXZLKHhc0Drq', 0, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `private_notes`
--
ALTER TABLE `private_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `private_notes`
--
ALTER TABLE `private_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `private_notes`
--
ALTER TABLE `private_notes`
  ADD CONSTRAINT `private_notes_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `private_notes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
