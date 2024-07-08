CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `user_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `user_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `user_role` tinyint(1) NOT NULL DEFAULT '2',
  `user_status` tinyint(1) NOT NULL DEFAULT '1',
  `user_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `users` (`user_name`, `user_email`, `user_password`, `user_role`, `user_status`, `user_updated`, `user_created`) VALUES
('superadmin', 'superadmin@admin.com', 'SFU0SkJ0YUlCc1dURVZFZCt5blNKQT09', 0, 1, '2024-07-07 17:52:46', '2024-07-07 17:16:51'),
('admin', 'admin@admin.com', 'S0VNV1lxa25wNW11N2JvS1lyS1BGUT09', 1, 1, '2024-07-07 21:56:58', '2024-07-07 15:41:13'),
('user', 'user@user.com', 'QzJNRnVNVHJwb0lCWGFadTBFYlNjQT09', 2, 1, '2024-07-07 21:58:40', '2024-07-07 19:24:50');
