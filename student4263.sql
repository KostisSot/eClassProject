-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 14, 2026 at 04:39 PM
-- Server version: 11.4.10-MariaDB-ubu2404
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student4263`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `date`, `subject`, `content`) VALUES
(1, '2026-01-05 00:00:00', 'Ύλη και Εξέταση', 'Καλησπέρα και καλή χρονιά. Η εξέταση του μαθήματος έχει προγραμματιστεί για τις 22/01/2026. Στην ύλη περιλαμβάνονται όλες οι ενότητες που θα βρείτε στο <a href=https://kssotiriou.webpages.auth.gr/4263partB/frontend/src/pages/documents.php target=\"_blank\">\"Εκπαιδευτικό Υλικό\"</a>.'),
(2, '2025-12-15 00:00:00', 'Τελευταία Διάλεξη του Μαθήματος', 'Σας ενημερώνω πως την ερχόμενη εβδομάδα θα πραγματοποιηθούν οι τελευταίες δύο διαλέξεις για το εξάμηνο. Καλές γιορτές σε όλους!'),
(3, '2025-12-10 00:00:00', 'Ανακοίνωση Πέμπτης Εργασίας', 'Έχει ανακοινωθεί η εκφώνηση της πέμπτης Εργασίας του μαθήματος. Θα την βρείτε στην ενότητα: <a href=https://kssotiriou.webpages.auth.gr/4263partB/frontend/src/pages/homework.php target=\"_blank\">\"Εργασίες\"</a>.'),
(4, '2025-11-19 00:00:00', 'Ανακοίνωση Τέταρτης Εργασίας', 'Έχει ανακοινωθεί η εκφώνηση της τέταρτης Εργασίας του μαθήματος. Θα την βρείτε στην ενότητα: <a href=https://kssotiriou.webpages.auth.gr/4263partB/frontend/src/pages/homework.php target=\"_blank\">\"Εργασίες\"</a>.'),
(5, '2025-11-05 00:00:00', 'Αναπλήρωση Μαθήματος', 'Όπως είπαμε και στο σημερινό μάθημα, η αναπλήρωση του μαθήματος θα πραγματοποιηθεί δια ζώσης την Δευτέρα 10/11/2025, στο Αμφιθέατρο.'),
(6, '2025-10-29 00:00:00', 'Βοηθητικό Υλικό', 'Σε περίπτωση που χρειαστείτε επιπλέον βοηθητικό υλικό, μπορείτε να αναζητήσετε στον ιστότοπο: <a href=https://www.w3schools.com target=\"_blank\">W3Schools</a>.'),
(7, '2025-10-29 00:00:00', 'Ανάρτηση Τρίτης Εργασίας', 'Έχει ανακοινωθεί η εκφώνηση της τρίτης Εργασίας του μαθήματος. Θα την βρείτε στην ενότητα: <a href=https://kssotiriou.webpages.auth.gr/4263partB/frontend/src/pages/homework.php target=\"_blank\">\"Εργασίες\"</a>.'),
(8, '2025-10-27 00:00:00', 'Αναβολή Μαθήματος', 'Δυστυχώς το αυριανό μάθημα αναβάλλεται λόγω αδυναμίας του διδάσκοντα. Θα ενημερωθείτε εγκαίρως για την αναπλήρωσή του.'),
(9, '2025-10-22 00:00:00', 'Ανάρτηση Δεύτερης Εργασίας', 'Έχει ανακοινωθεί η εκφώνηση της δεύτερης Εργασίας του μαθήματος. Θα την βρείτε στην ενότητα:<a href=https://kssotiriou.webpages.auth.gr/4263partB/frontend/src/pages/homework.php target=\"_blank\">\"Εργασίες\"</a>.'),
(10, '2025-10-15 00:00:00', 'Ανάρτηση Πρώτης Εργασίας', 'Έχει ανακοινωθεί η εκφώνηση της πρώτης Εργασίας του μαθήματος. Θα την βρείτε στην ενότητα: <a href=https://kssotiriou.webpages.auth.gr/4263partB/frontend/src/pages/homework.php target=\"_blank\">\"Εργασίες\"</a>.'),
(11, '2025-10-13 00:00:00', 'Πρόγραμμα Μαθημάτων', 'Οι διαλέξεις του μαθήματος θα πραγματοποιούνται κάθε Τρίτη και Τετάρτη, 13.00-15.00 στο Αμφιθέατρο.'),
(12, '2025-10-12 00:00:00', 'Έναρξη μαθημάτων', 'Καλησπέρα σας και καλή χρονιά! Η πρώτη διάλεξη θα πραγματοποιηθεί την Τρίτη 14/10/2025, στο Αμφιθέατρο.');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `title`, `description`, `filename`, `filepath`, `uploaded_at`) VALUES
(7, 'Εισαγωγή στην JavaScript', 'Υλικό από τα πρώτα εισαγωγικά μαθήματα.', '1771027449_JavaScript-1.pdf', '../../assets/docs/1771027449_JavaScript-1.pdf', '2026-02-14 02:04:09'),
(8, 'Συναρτήσεις και Frameworks', 'Συναρτήσεις, lambdas, this, Angular.', '1771027474_JavaScript-2.pdf', '../../assets/docs/1771027474_JavaScript-2.pdf', '2026-02-14 02:04:34'),
(9, 'Ασύγχρονη JS και επικοινωνία με servers', 'Promises, Fetch API, AJAX, Χρονισμός, REST APIs, CORS.', '1771027497_JavaScript-3.pdf', '../../assets/docs/1771027497_JavaScript-3.pdf', '2026-02-14 02:04:57');

-- --------------------------------------------------------

--
-- Table structure for table `homework`
--

CREATE TABLE `homework` (
  `id` int(11) NOT NULL,
  `goals` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `deliverables` text NOT NULL,
  `due_date` date NOT NULL,
  `posted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homework`
--

INSERT INTO `homework` (`id`, `goals`, `filename`, `deliverables`, `due_date`, `posted_at`) VALUES
(1, 'Κατανόηση συναρτήσεων της JS.\r\nΚατανόνηση events.\r\nΕκτέλεση μαθηματικών πράξεων.', '1771025522_Assignment-1.pdf', 'index.html\r\nscript.js\r\nstyle.css', '2025-10-22', '2026-02-14 01:32:02'),
(2, 'Χειρισμός φορμών.\r\nΧρήση Regular Expressions.', '1771025611_Assignment-2.pdf', 'Το αρχείο html με ενσωματωμένη την JavaScript.', '2025-10-29', '2026-02-14 01:33:31'),
(3, 'Προσθήκη και διαγραφή στοιχείων από DOM.', '1771025823_Assignment-3.pdf', 'Ο πλήρης φάκελος του έργου και μια σύντομη τεχνική αναφορά.', '2025-11-06', '2026-02-14 01:37:03'),
(4, 'Δομές ελέγχου.\r\nΠαραγωγή τυχαίων αριθμών.', '1771026753_Assignment-4.pdf', 'Το αρχείο JS και μια σύντομη τεχνική αναφορά.', '2025-11-28', '2026-02-14 01:52:33'),
(5, 'Χρήση Arrays.\r\nΧρήση Objects.\r\nΧρήση LocalStorage.', '1771027074_Assignment-5.pdf', 'Ο κώδικας και μια σύντομη τεχνική αναφορά.', '2026-01-05', '2026-02-14 01:57:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Tutor','Student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `role`) VALUES
(1, 'Κωνσταντίνος', 'Σωτηρίου', 'kostis.sotiriou@gmail.com', '$2y$10$eEAletUBNBP8G8InZqHPXeDeZuczjCi/onamMfjliZVg7ddfzLpaO', 'Tutor'),
(3, 'Menios', 'Trambalis', 'menios@measae.gr', '$2y$10$jprHx/q8nxJeiFvvWpt1QOH5mf75zyhEhZQIDk45RHxkRmzxG0UeG', 'Student'),
(4, 'Asimakis', 'Trambalis', 'asimakis@measae.gr', '$2y$10$eyi/duBBeFqMdZsZdNA1iu7Hr9Vi5c6Me6NsrHTtC36uvYl69ax4S', 'Student'),
(5, 'Kostas', 'Grimbilas', 'grimbilas@hotel.gr', '$2y$10$iTKKyEDtiyFLXarbouPTL.zZj5OQaW5ZPFrHN/xvQKV.I4vCyWapG', 'Student'),
(10, 'Filotas', 'Karras', 'filotas@mail.gr', '$2y$10$aPPxv6OA15Ph.YYnMMoKF.UwHcP14jOgTpPtGOCpPn71ICraHpNRW', 'Tutor'),
(14, 'Lambrini', 'Karra-Trambali', 'lambrini@mail.gr', '$2y$10$effdeqNv/34RRU7mIGS6QuQy4Wh6c53LET/E.UzJoWjIuOHnYeX4S', 'Student'),
(15, 'Student', 'TestingUser', 'student@csd.auth.gr', '$2y$10$Lat21OJFbCZMxIOm4/6fAumgZLW1oYHe0hBwXaU0xBwNmdzB8aKBq', 'Student'),
(16, 'Tutor', 'TestingUser', 'tutor@csd.auth.gr', '$2y$10$hbaJsGRF6ndaINnCwsttlerHlgwLchA2J5Vq/pQGOLZkTbI6GyFNq', 'Tutor'),
(18, 'Gerasimos', 'Savvidis', 'gerasimos@measae.gr', '$2y$10$9xq5AxLdM0App1O9mm51xOS67YWtZs27aEIlt0sDtfJoZ.HHF0DpW', 'Student'),
(25, 'Βασιλης', 'Σαραντιδης', 'bilsarantidis@gmail.com', '$2y$10$oi3rzsdXXMnB8JfZgqZATOIPeWezpxa.AhupEn85hV2vuIwy6qaQ.', 'Student'),
(26, 'Olga', 'Giannoglou', 'olga.giannoglou@gmail.com', '$2y$10$D5DZSLtodgAQ1w5vFtVuVulSdzRZ.sJd3txXSou3/y1UE/QYMcHPy', 'Student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homework`
--
ALTER TABLE `homework`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `homework`
--
ALTER TABLE `homework`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
