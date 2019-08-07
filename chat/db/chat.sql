CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `text` text NOT NULL,
  `time` timestamp NULL DEFAULT current_timestamp(),
  `username` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `chat`
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
COMMIT;

