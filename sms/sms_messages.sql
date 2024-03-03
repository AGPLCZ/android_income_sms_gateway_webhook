CREATE TABLE `sms_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `from` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fromName` varchar(255)  COLLATE utf8mb4_general_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_general_ci,
  `sent_stamp` datetime DEFAULT NULL,
  `received_stamp` datetime DEFAULT NULL,
  `sim` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `sms_messages` (`id`, `from`, `fromName`, `text`, `sent_stamp`, `received_stamp`, `sim`) VALUES
(20, '+420123456789', 'Jméno', 'zpráva', '2024-03-02 22:20:44', '2024-03-02 22:20:44', 'sim1');



CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(255) NOT NULL,
    pass VARCHAR(255) NOT NULL
);
