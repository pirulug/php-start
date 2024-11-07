CREATE TABLE `ads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `position` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `ads` (id, title, subtitle, content, status, position) VALUES ('1', 'Header', '(Appears on all pages right under the nav bar)', '&lt;div &gt;\r\n&lt;a href=&quot;#&quot;&gt;\r\n&lt;img src=&quot;https://dummyimage.com/200x400/bababa/ebecf5.jpg&quot;/&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;', '1', 'header');
INSERT INTO `ads` (id, title, subtitle, content, status, position) VALUES ('2', 'Footer', '(Appears on all pages right before the footer)', '&lt;div &gt;\r\n&lt;a href=&quot;#&quot;&gt;\r\n&lt;img src=&quot;https://wicombit.com/demo/banner.jpg&quot;/&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;', '1', 'footer');
INSERT INTO `ads` (id, title, subtitle, content, status, position) VALUES ('3', 'Sidebar', '(Appears on all pages right on left bar)', '&lt;div &gt;\r\n&lt;a href=&quot;#&quot;&gt;\r\n&lt;img src=&quot;https://wicombit.com/demo/sidebar.jpg&quot;/&gt;\r\n&lt;/a&gt;\r\n&lt;/div&gt;', '1', 'sidebar');

CREATE TABLE `brand` (
  `st_favicon` varchar(150) NOT NULL DEFAULT 'favicon.png',
  `st_whitelogo` varchar(150) NOT NULL DEFAULT 'whitelogo.png',
  `st_darklogo` varchar(150) NOT NULL DEFAULT 'darklogo.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=COMPACT;

INSERT INTO `brand` (st_favicon, st_whitelogo, st_darklogo) VALUES ('/uploads/site/st_favicon.png', '/uploads/site/st_whitelogo.png', '/uploads/site/st_darklogo.png');

CREATE TABLE `settings` (
  `st_sitename` varchar(150) DEFAULT NULL,
  `st_facebook` varchar(150) DEFAULT NULL,
  `st_twitter` varchar(150) DEFAULT NULL,
  `st_instagram` varchar(150) DEFAULT NULL,
  `st_youtube` varchar(150) DEFAULT NULL,
  `st_keywords` mediumtext DEFAULT NULL,
  `st_description` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `settings` (st_sitename, st_facebook, st_twitter, st_instagram, st_youtube, st_keywords, st_description) VALUES ('PhpStart - Minifrmawork ', 'https://facebook.com/', 'https://twitter.com/', 'https://www.instagram.com/', 'https://www.youtube.com/', 'phpstart, php, css, js, html, bootstrap', 'PhpStart es un Minifrmawork hecha por Pirulug');

CREATE TABLE `smtp` (
  `st_smtphost` varchar(150) NOT NULL,
  `st_smtpemail` varchar(150) NOT NULL,
  `st_smtppassword` varchar(150) NOT NULL,
  `st_smtpport` varchar(150) NOT NULL,
  `st_smtpencrypt` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=COMPACT;

INSERT INTO `smtp` (st_smtphost, st_smtpemail, st_smtppassword, st_smtpport, st_smtpencrypt) VALUES ('-', '-', '-', '-', '-');

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_role` tinyint(1) NOT NULL DEFAULT 2,
  `user_status` tinyint(1) NOT NULL DEFAULT 1,
  `user_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

