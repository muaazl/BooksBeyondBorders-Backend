-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 22, 2025 at 07:40 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`) VALUES
(1, 1, '2025-02-05 05:03:41'),
(2, 2, '2025-02-09 23:33:26');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cart_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(4, 'Success Secrets'),
(3, 'Vi Keeland Bestsellers'),
(2, 'Newbie Collection'),
(1, 'Featured Books'),
(5, 'Twisted Series'),
(6, 'Dark Verse Series'),
(7, 'Ruthless People Series'),
(8, 'Lauren Asher Book Series'),
(9, 'Novels');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total_amount`, `status`) VALUES
(1, 1, '2025-02-07 17:00:29', 2800.00, 'Pending'),
(2, 1, '2025-02-07 18:05:25', 16200.00, 'Pending'),
(3, 2, '2025-02-10 11:07:47', 9900.00, 'Shipped');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `fk_order_items_orders` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `title`, `author`, `image`) VALUES
(1, 1, 16, 1, 2800.00, 'Piranesi', 'Susanna Clarke', 'images/products/newbie_s/n4.jpg'),
(2, 2, 32, 1, 2600.00, 'Inappropriate', 'Vi Keeland', 'images/products/vi_keeland/n8.jpg'),
(3, 2, 15, 2, 3000.00, 'The Song of Achilles', 'Madeline Miller', 'images/products/newbie_s/n3.jpg'),
(4, 2, 1, 2, 3800.00, 'The Love Hypothesis', 'Ali Hazelwood', 'images/products/featured/f1.jpg'),
(5, 3, 49, 1, 2500.00, 'Twisted Love', 'Ana Huang', 'images/products/twisted_s/n1.jpg'),
(6, 3, 4, 1, 3200.00, 'It Starts with Us', 'Colleen Hoover', 'images/products/featured/f3.jpg'),
(7, 3, 19, 1, 1900.00, 'Roadside Picnic', 'Arkady & Boris Strugatsky', 'images/products/newbie_s/n7.jpg'),
(8, 3, 50, 1, 2300.00, 'Twisted Games', 'Ana Huang', 'images/products/twisted_s/n2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `author`, `price`, `description`, `image`) VALUES
(1, 'The Love Hypothesis', 'Ali Hazelwood', 3800.00, 'A charming rom-com about a scientist who enters a fake relationship with a professor, only to find that real love can be the most unpredictable experiment of all.', 'images/products/featured/f1.jpg'),
(2, 'The Nightingale', 'Kristin Hannah', 4500.00, 'When a German captain requisitions Vianne\'s home, she and her daughter must live with the enemy or lose everything. Without food or money or hope, as danger ...', 'images/products/featured/f2.jpg'),
(3, 'It Ends with Us', 'Colleen Hoover', 3200.00, 'A powerful and emotional story that explores love, resilience, and the difficult choices faced in abusive relationships, with a message of self-empowerment and healing.', 'images/products/featured/f4.jpg'),
(4, 'It Starts with Us', 'Colleen Hoover', 3200.00, 'The sequel to \'It Ends with Us,\' this novel follows Lily as she navigates the complexities of new beginnings, healing, and second chances in love.', 'images/products/featured/f3.jpg'),
(5, 'Beach Read', 'Emily Henry', 3600.00, 'A witty, heartwarming story about two writers with writer’s block who swap genres for the summer, leading to unexpected romance and self-discovery.', 'images/products/featured/f5.jpg'),
(6, 'Book Lovers', 'Emily Henry', 3700.00, 'A refreshing twist on the rom-com genre, this novel tells the story of a literary agent and an editor who clash and then connect in a small-town setting, exploring family, ambition, and romance.', 'images/products/featured/f6.jpg'),
(7, 'Ugly Love', 'Colleen Hoover', 3300.00, 'A touching and complex love story that reveals the darker side of relationships, centered around love, loss, and healing.', 'images/products/featured/f7.jpg'),
(8, 'The Inmate', 'Freida McFadden', 2900.00, 'A thrilling mystery about a woman returning to her hometown to work in a prison where secrets unravel, with dangerous consequences.', 'images/products/featured/f8.jpg'),
(9, 'Counting Miracles', 'Nicholas Sparks', 3100.00, 'A heartwarming story about hope, faith, and the unexpected twists of fate that can change lives forever.', 'images/products/featured/f9.jpg'),
(10, 'Casket Case', 'Lauren Evans', 2800.00, 'A cozy mystery involving a mortician who finds herself at the center of a surprising investigation that turns her life upside down.', 'images/products/featured/f10.jpg'),
(11, 'Yellowface', 'R. F. Kuang', 4200.00, 'A provocative narrative that explores themes of cultural identity, privilege, and the publishing industry through a tale of deceit and ambition.', 'images/products/featured/f11.jpg'),
(12, 'Vita Nuova', 'Dante Alighieri', 2900.00, 'A classic poem capturing the medieval journey of love and spirituality, often seen as a prelude to Dante\'s Divine Comedy.', 'images/products/featured/f12.jpg'),
(13, 'World War Z', 'Max Brooks', 2500.00, 'An oral history of humanity’s epic battle against the zombie apocalypse, capturing the global scale and personal resilience needed to survive the horror.', 'images/products/newbie_s/n1.jpg'),
(14, 'The War of the Worlds', 'H. G. Wells', 1800.00, 'A sci-fi classic that tells the story of a Martian invasion, exploring the resilience of humanity in the face of overwhelming extraterrestrial forces.', 'images/products/newbie_s/n2.jpg'),
(15, 'The Song of Achilles', 'Madeline Miller', 3000.00, 'A retelling of the Iliad from the perspective of Patroclus, capturing his love for Achilles against the backdrop of the Trojan War.', 'images/products/newbie_s/n3.jpg'),
(16, 'Piranesi', 'Susanna Clarke', 2800.00, 'An enchanting tale of mystery and solitude in an endless labyrinth, where a lone man named Piranesi uncovers haunting secrets about his world.', 'images/products/newbie_s/n4.jpg'),
(17, 'Jurassic Park', 'Michael Crichton', 2400.00, 'A thrilling adventure that brings dinosaurs back to life with disastrous consequences, questioning humanity\'s obsession with power and science.', 'images/products/newbie_s/n5.jpg'),
(18, 'The Lost World', 'Michael Crichton', 2600.00, 'A gripping sequel that revisits a prehistoric paradise gone wrong, as scientists return to Isla Sorna to face the dangers of the dinosaur world.', 'images/products/newbie_s/n6.jpg'),
(19, 'Roadside Picnic', 'Arkady & Boris Strugatsky', 1900.00, 'A haunting sci-fi story about alien visitation and its unintended consequences, exploring human fascination with the unknown and forbidden.', 'images/products/newbie_s/n7.jpg'),
(20, 'High Lonesome', 'Louis L\'Amour', 2200.00, 'A classic Western about redemption and survival, following a man on the run who finds purpose and honor in the harsh American frontier.', 'images/products/newbie_s/n8.jpg'),
(21, 'Kings of the Wyld', 'Nicholas Eames', 2700.00, 'A rollicking fantasy adventure that follows a band of retired mercenaries on one last mission, blending humor, action, and heart.', 'images/products/newbie_s/n9.jpg'),
(22, 'Bloody Rose', 'Nicholas Eames', 2800.00, 'A thrilling follow-up to \'Kings of the Wyld,\' centering on Rose, a warrior with something to prove, and her quest for glory in a dangerous world.', 'images/products/newbie_s/n10.jpg'),
(23, 'David Copperfield', 'Charles Dickens', 3000.00, 'A beloved coming-of-age novel that follows David Copperfield’s journey from a troubled childhood to self-discovery, love, and fulfillment.', 'images/products/newbie_s/n11.jpg'),
(24, 'Anna Karenina', 'Leo Tolstoy', 2100.00, 'A masterpiece of Russian literature that explores love, society, and betrayal through the tragic story of Anna and her forbidden romance.', 'images/products/newbie_s/n12.jpg'),
(25, 'Belong To You', 'Vi Keeland', 2500.00, 'A passionate romance novel exploring the journey of love, vulnerability, and self-discovery.', 'images/products/vi_keeland/n1.jpg'),
(26, 'Made For You', 'Vi Keeland', 2800.00, 'An intense romance novel that delves into themes of fate, resilience, and finding true love.', 'images/products/vi_keeland/n2.jpg'),
(27, 'Boss Man', 'Vi Keeland', 2600.00, 'A sizzling office romance where power dynamics and deep emotions collide in unexpected ways.', 'images/products/vi_keeland/n3.jpg'),
(28, 'Stuck-Up Suit', 'Vi Keeland', 2700.00, 'A witty and romantic novel about opposites attracting in the most humorous and heartwarming fashion.', 'images/products/vi_keeland/n4.jpg'),
(29, 'Beautiful Mistake', 'Vi Keeland', 2500.00, 'A romance that follows two souls drawn together by unexpected connections and hidden secrets.', 'images/products/vi_keeland/n5.jpg'),
(30, 'Dirty Letters', 'Vi Keeland', 2800.00, 'A heartfelt story of rediscovered love, where old letters spark a romance long thought lost.', 'images/products/vi_keeland/n6.jpg'),
(31, 'The Rivals', 'Vi Keeland', 2700.00, 'A tale of rivalry and romance between two ambitious characters navigating a whirlwind of emotions.', 'images/products/vi_keeland/n7.jpg'),
(32, 'Inappropriate', 'Vi Keeland', 2600.00, 'A daring story of love that defies conventions, taking readers through a passionate and unexpected journey.', 'images/products/vi_keeland/n8.jpg'),
(33, 'Happily Letter After', 'Vi Keeland', 2400.00, 'A charming romance inspired by heartfelt letters, where dreams and love come together in unexpected ways.', 'images/products/vi_keeland/n9.jpg'),
(34, 'The Invitation', 'Vi Keeland', 2800.00, 'An exciting romance centered on an accidental invitation that sparks an unforgettable relationship.', 'images/products/vi_keeland/n10.jpg'),
(35, 'Hate Notes', 'Vi Keeland', 2700.00, 'A quirky love story fueled by misunderstandings and the discovery of true connection.', 'images/products/vi_keeland/n11.jpg'),
(36, 'The Summer Proposal', 'Vi Keeland', 2500.00, 'A captivating romance set in the heat of summer, where love blossoms against all odds.', 'images/products/vi_keeland/n12.jpg'),
(37, 'Atomic Habits', 'James Clear', 2500.00, 'A groundbreaking guide to building better habits and breaking bad ones for long-term success.', 'images/products/selfhelp/n1.jpg'),
(38, 'The Subtle Art Of Not Giving A F*ck', 'Mark Manson', 2000.00, 'A counterintuitive approach to living a good life by embracing limitations and focusing on what truly matters.', 'images/products/selfhelp/n2.jpg'),
(39, 'How To Win Friends & Influence People', 'Dale Carnegie', 1800.00, 'The timeless principles of interpersonal communication that can lead to personal and professional success.', 'images/products/selfhelp/n3.jpg'),
(40, 'Rich Dad Poor Dad', 'Robert T. Kiyosaki', 2300.00, 'A financial literacy classic that explains the mindset difference between the wealthy and the poor.', 'images/products/selfhelp/n4.jpg'),
(41, 'The Wealth Money Can\'t Buy', 'Robin Sharma', 2200.00, 'A guide to discovering true wealth, which goes beyond material possessions and focuses on purpose and inner growth.', 'images/products/selfhelp/n5.jpg'),
(42, 'Think and Grow Rich', 'Napoleon Hill', 2400.00, 'A renowned self-help book on personal development, outlining the mindset and habits needed to attain wealth and success.', 'images/products/selfhelp/n6.jpg'),
(43, 'The 5am Club', 'Robin Sharma', 2150.00, 'A motivational book that teaches the transformative power of waking up early and maximizing your mornings.', 'images/products/selfhelp/n7.jpg'),
(44, 'The Power of Now', 'Eckhart Tolle', 2100.00, 'A spiritual guide to mindfulness and finding peace by living in the present moment.', 'images/products/selfhelp/n8.jpg'),
(45, 'The Secret', 'Rhonda Byrne', 1750.00, 'A powerful exploration of the law of attraction and how thoughts shape your reality.', 'images/products/selfhelp/n9.jpg'),
(46, 'You are a Badass', 'Jen Sincero', 2350.00, 'An empowering self-help guide that motivates you to take control of your life and pursue your dreams with confidence.', 'images/products/selfhelp/n10.jpg'),
(47, 'The Four Agreements', 'Miguel Ruiz', 2000.00, 'A practical guide to personal freedom, presenting four agreements that can transform your life.', 'images/products/selfhelp/n11.jpg'),
(48, 'Awaken the Giant Within', 'Anthony Robbins', 2100.00, 'A self-improvement book that teaches strategies to take control of your emotions, finances, and life.', 'images/products/selfhelp/n12.jpg'),
(49, 'Twisted Love', 'Ana Huang', 2500.00, 'A sizzling romance that explores love, betrayal, and redemption in unexpected ways.', 'images/products/twisted_s/n1.jpg'),
(50, 'Twisted Games', 'Ana Huang', 2300.00, 'A royal romance where power, passion, and secrets collide in a world of high stakes.', 'images/products/twisted_s/n2.jpg'),
(51, 'Twisted Hate', 'Ana Huang', 2400.00, 'An enemies-to-lovers romance filled with intense emotions, passion, and fiery banter.', 'images/products/twisted_s/n3.jpg'),
(52, 'Twisted Lies', 'Ana Huang', 2350.00, 'A romance full of secrets, deceit, and forbidden love that blurs the line between loyalty and desire.', 'images/products/twisted_s/n4.jpg'),
(53, 'The Predator', 'RuNyx', 2600.00, 'A dark and thrilling tale of obsession, power, and the dangerous allure of forbidden love.', 'images/products/dark_s/n1.jpg'),
(54, 'The Reaper', 'RuNyx', 2700.00, 'A gripping dark romance where love and death walk hand in hand through dangerous paths.', 'images/products/dark_s/n2.jpg'),
(55, 'The Emperor', 'RuNyx', 2800.00, 'A powerful and mysterious love story where passion meets danger in a world of crime.', 'images/products/dark_s/n3.jpg'),
(56, 'The Finisher', 'RuNyx', 2650.00, 'A dark, brooding romance about revenge, power, and the complicated dynamics of love.', 'images/products/dark_s/n4.jpg'),
(57, 'The Annihilator', 'RuNyx', 2750.00, 'A seductive and dangerous love story that explores the boundaries of loyalty, love, and destruction.', 'images/products/dark_s/n5.jpg'),
(58, 'The Syndicater', 'RuNyx', 2800.00, 'A chilling dark romance that dives deep into the underworld, where love and survival clash.', 'images/products/dark_s/n6.jpg'),
(59, 'Declan + Coraline', 'J. J. McAvoy', 2500.00, 'A thrilling love story filled with passion, power, and intrigue, set in the dangerous world of mafia families.', 'images/products/ruthless_s/n1.jpg'),
(60, 'Ruthless People', 'J. J. McAvoy', 2400.00, 'A gripping mafia romance that takes readers through the explosive love and fierce rivalry between two powerful families.', 'images/products/ruthless_s/n2.jpg'),
(61, 'The Untouchables', 'J. J. McAvoy', 2550.00, 'A riveting story of power, love, and loyalty that challenges the boundaries between right and wrong.', 'images/products/ruthless_s/n3.jpg'),
(62, 'American Savages', 'J. J. McAvoy', 2600.00, 'A fierce and explosive finale to the Ruthless People series, where love is the ultimate weapon in a world of savagery.', 'images/products/ruthless_s/n4.jpg'),
(63, 'A Bloody Kingdom', 'J. J. McAvoy', 2700.00, 'A suspenseful and intense continuation of the saga, where loyalties are tested and love is put to the ultimate test.', 'images/products/ruthless_s/n5.jpg'),
(64, 'Throttled', 'Lauren Asher', 2450.00, 'A high-octane romance set in the world of Formula 1 racing, where speed isn’t the only thing making hearts race.', 'images/products/lauren_s/n4.jpg'),
(65, 'Collided', 'Lauren Asher', 2400.00, 'An intense love story set on the racetrack, where emotions collide and winning the race might mean losing your heart.', 'images/products/lauren_s/n5.jpg'),
(66, 'Wrecked', 'Lauren Asher', 2550.00, 'A heartfelt story about finding love in the wreckage of life’s toughest challenges, where healing and hope prevail.', 'images/products/lauren_s/n6.jpg'),
(67, 'Redeemed', 'Lauren Asher', 2500.00, 'A moving romance about redemption and second chances, where love offers the ultimate salvation.', 'images/products/lauren_s/n7.jpg'),
(68, 'Heartless', 'Elsie Silver', 1500.00, 'A gripping romance full of fiery emotions and unexpected twists.', 'images/products/n13.jpg'),
(69, 'Third Girl', 'Agatha Christie', 2000.00, 'A mystery novel featuring Hercule Poirot solving a perplexing crime.', 'images/products/n14.jpg'),
(70, 'Red Thorns', 'Rina Kent', 1750.00, 'A dark and intense romance where love battles against obsession.', 'images/products/n15.jpg'),
(71, 'King of Greed', 'Ana Huang', 2200.00, 'A passionate tale of power, love, and betrayal.', 'images/products/n16.jpg'),
(72, 'Haunting Adeline', 'H. D. Carlton', 2100.00, 'A suspenseful romance that ventures into the dark and dangerous.', 'images/products/n17.jpg'),
(73, 'God of Wrath', 'Rina Kent', 1900.00, 'A tale of raw emotions and devastating secrets that will test the limits of love.', 'images/products/n18.jpg'),
(74, 'Painted Scars', 'Neva Altaj', 1800.00, 'An emotional romance with themes of healing, forgiveness, and redemption.', 'images/products/n19.jpg'),
(75, 'Hooked', 'Emily McIntire', 1950.00, 'A dark reimagining of classic tales, where love turns dangerous and deadly.', 'images/products/n20.jpg'),
(76, 'The Maddest Obsession', 'Danielle Lori', 2000.00, 'An intoxicating romance full of forbidden love and impossible choices.', 'images/products/n21.jpg'),
(77, 'How to Fake it in Hollywood', 'Ava Wilder', 2100.00, 'A glamorous fake dating romance set in the backdrop of Hollywood\'s brightest stars.', 'images/products/n22.jpg'),
(78, 'The Notebook', 'Nicholas Sparks', 1600.00, 'A timeless love story of passion, heartache, and undying commitment.', 'images/products/n23.jpg'),
(79, 'The Inheritance Games', 'Jennifer Lynn Barnes', 1450.00, 'A thrilling YA mystery filled with riddles, inheritance, and unexpected alliances.', 'images/products/n24.jpg'),
(80, 'Someone We Know', 'Shari Lapena', 1200.00, 'A suspenseful thriller where everyone in a small town holds dangerous secrets.', 'images/products/n25.jpg'),
(81, 'The Paris Apartment', 'Lucy Foley', 1800.00, 'A mysterious thriller set in a glamorous Parisian apartment with a dark past.', 'images/products/n26.jpg'),
(82, 'The Pairing', 'Casey McQuiston', 2100.00, 'A heartfelt tale of love, identity, and the courage to embrace one\'s true self.', 'images/products/n28.jpg'),
(83, 'A God in Every Stone', 'Kamila Shamsie', 1750.00, 'An epic historical novel weaving the destinies of its characters across centuries and cultures.', 'images/products/n29.jpg'),
(84, 'I am Thunder', 'Muhammad Khan', 1600.00, 'An empowering story of a young woman finding her voice and standing up for what she believes in.', 'images/products/n40.jpg'),
(85, 'The Sweetest Oblivion', 'Danielle Lori', 1400.00, 'A sizzling romance with passion, tension, and an unforgettable connection between its characters.', 'images/products/n27.jpg'),
(86, 'Icebreaker', 'Hannah Grace', 1950.00, 'A thrilling love story filled with chemistry, humor, and moments of heartwarming intimacy.', 'images/products/n30.jpg'),
(87, 'Wildfire', 'Hannah Grace', 2050.00, 'A tale of fiery passion, where two souls ignite in the face of adversity and emotional turmoil.', 'images/products/n31.jpg'),
(88, 'The Two of Us', 'Taylor Torres', 1300.00, 'A tender romance about the unbreakable bond between two hearts destined to be together.', 'images/products/n32.jpg'),
(89, 'The Book Swap', 'Tessa Bickers', 1400.00, 'A delightful story of friendship, love, and unexpected discoveries through the love of books.', 'images/products/n33.jpg'),
(90, 'The Casanova', 'T. L. Swan', 1500.00, 'A seductive and captivating romance, where love catches even the most unsuspecting hearts.', 'images/products/n34.jpg'),
(91, 'The Summer of Broken Rules', 'K. L. Walther', 1400.00, 'A charming summer romance filled with unforgettable moments, heartbreak, and new beginnings.', 'images/products/n35.jpg'),
(92, 'Maybe Meant To Be', 'K. L. Walther', 1350.00, 'A romantic tale of serendipity and destiny, where love blossoms despite the odds.', 'images/products/n36.jpg'),
(93, 'Soul', 'Olivia Wilson', 1600.00, 'A moving story of self-discovery, love, and the connection between two kindred souls.', 'images/products/n37.jpg'),
(94, 'A Vow of Hate', 'Lylah James', 1500.00, 'A gripping and intense romance where love and hate collide in a battle of emotions.', 'images/products/n38.jpg'),
(95, 'How to Cure a Ghost', 'Fariha Róisín', 1700.00, 'A profound exploration of identity, spirituality, and healing through the power of love.', 'images/products/n39.jpg'),
(96, 'Then She Was Gone', 'Lisa Jewell', 1800.00, 'A psychological thriller with twists and turns, where the past unravels in haunting ways.', 'images/products/n41.jpg'),
(97, 'You and Me on Vacation', 'Emily Henry', 1650.00, 'A delightful summer romance, perfect for anyone who believes in second chances and serendipity.', 'images/products/n42.jpg'),
(98, 'Punk57', 'Penelope Douglas', 1900.00, 'A passionate and intense romance where love is as unpredictable as it is all-consuming.', 'images/products/n43.jpg'),
(99, 'Fictions', 'Jorge Luis Borges', 1250.00, 'A collection of surreal and thought-provoking short stories, delving deep into the human experience.', 'images/products/n44.jpg'),
(100, 'Dangerous Liaisons', 'Pierre Choderlos de Laclos', 1500.00, 'A classic tale of intrigue, manipulation, and passion in the glittering world of French aristocracy.', 'images/products/n45.jpg'),
(101, 'Slaughterhouse-Five', 'Kurt Vonnegut', 1350.00, 'A darkly humorous and profound novel about war, time travel, and the human condition.', 'images/products/n46.jpg'),
(102, 'Their Eyes Were Watching God', 'Zora Neale Hurston', 1800.00, 'A powerful exploration of love, identity, and the struggle for autonomy in the deep South.', 'images/products/n47.jpg'),
(103, 'One Hundred Years of Solitude', 'Gabriel Garcia Marquez', 2000.00, 'A magical realist masterpiece chronicling the rise and fall of the Buendía family.', 'images/products/n48.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE IF NOT EXISTS `product_categories` (
  `product_id` int NOT NULL,
  `category_id` int NOT NULL,
  KEY `product_id` (`product_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 3),
(26, 3),
(27, 3),
(28, 3),
(29, 3),
(30, 3),
(31, 3),
(32, 3),
(33, 3),
(34, 3),
(35, 3),
(36, 3),
(37, 4),
(38, 4),
(39, 4),
(40, 4),
(41, 4),
(42, 4),
(43, 4),
(44, 4),
(45, 4),
(46, 4),
(47, 4),
(48, 4),
(49, 5),
(50, 5),
(51, 5),
(52, 5),
(53, 6),
(54, 6),
(55, 6),
(56, 6),
(57, 6),
(58, 6),
(59, 7),
(60, 7),
(61, 7),
(62, 7),
(63, 7),
(64, 8),
(65, 8),
(66, 8),
(67, 8),
(68, 9),
(69, 9),
(70, 9),
(71, 9),
(72, 9),
(73, 9),
(74, 9),
(75, 9),
(76, 9),
(77, 9),
(78, 9),
(79, 9),
(80, 9),
(81, 9),
(82, 9),
(83, 9),
(84, 9),
(85, 9),
(86, 9),
(87, 9),
(88, 9),
(89, 9),
(90, 9),
(91, 9),
(92, 9),
(93, 9),
(94, 9),
(95, 9),
(96, 9),
(97, 9),
(98, 9),
(99, 9),
(100, 9),
(101, 9),
(102, 9),
(103, 9),
(104, 1),
(105, 3),
(106, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(155) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) DEFAULT 'Customer',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'Muaaz', 'muaazlattif@gmail.com', '$2y$10$cYwaFNHH6eg9kC30.jBtvO3hM48wIQGCUTp0G3e48isam7W.eAOES', 'Admin'),
(2, 'Marcus', 'muaazlattif2606@gmail.com', '$2y$10$GIDWLgcwn27eTOOxcFaXAONTdHu/nylCrsOjthpns1pFXwZU9RKfy', 'Customer');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
