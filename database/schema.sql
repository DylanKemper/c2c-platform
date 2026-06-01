Database: `lootly_db`

-----------------------------------------
Table structure for table `admin_log`
-----------------------------------------

CREATE TABLE `admin_log` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action_type` enum('warn','suspend','ban','resolve_report','dismiss_report','flag_listing','remove_listing','clear_flags','release_funds','refund_buyer','request_info','escalate') NOT NULL,
  `target_type` enum('user','listing','report','transaction') NOT NULL,
  `target_id` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-------------------------------------------
Table structure for table `categories`
-------------------------------------------

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


------------------------------------------
Table structure for table `disputes`
------------------------------------------

CREATE TABLE `disputes` (
  `dispute_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `raised_by` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('open','under_review','resolved') DEFAULT 'open',
  `resolution` enum('refund_buyer','refund_seller') DEFAULT NULL,
  `resolution_note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `resolved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


------------------------------------------
Table structure for table `listings`
------------------------------------------

CREATE TABLE `listings` (
  `listing_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `condition` enum('New','Like New','Good','Fair') NOT NULL,
  `status` enum('active','sold','flagged','removed') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `delivery_method` enum('meetup','post','either') NOT NULL DEFAULT 'either',
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


------------------------------------------
Table structure for table `listing_images`
------------------------------------------

CREATE TABLE `listing_images` (
  `image_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


------------------------------------------
Table structure for table `reports`
------------------------------------------

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `report_type` enum('listing','user','transaction') NOT NULL,
  `target_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('open','under_review','resolved') DEFAULT 'open',
  `created_at` datetime DEFAULT current_timestamp(),
  `resolved_at` datetime DEFAULT NULL,
  `resolution_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


------------------------------------------
Table structure for table `reviews`
------------------------------------------

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `reviewee_id` int(11) NOT NULL,
  `reviewee_role` enum('buyer','seller') NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `body` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


------------------------------------------
Table structure for table `transactions`
------------------------------------------

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `status` enum('held','dispatched','received','completed','disputed','cancelled') DEFAULT 'held',
  `created_at` datetime DEFAULT current_timestamp(),
  `dispatched_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


------------------------------------------
Table structure for table `users`
------------------------------------------

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `last_active` datetime DEFAULT NULL,
  `is_banned` tinyint(1) DEFAULT 0,
  `ban_reason` text DEFAULT NULL,
  `is_suspended` tinyint(1) DEFAULT 0,
  `suspended_until` datetime DEFAULT NULL,
  `warning_count` int(11) DEFAULT 0,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;