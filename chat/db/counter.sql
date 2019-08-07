CREATE TABLE `counter` (
  `datetime` timestamp NULL DEFAULT current_timestamp(),
  `ipaddr` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `useragent` text CHARACTER SET utf8 DEFAULT NULL,
  `page` text CHARACTER SET utf8 DEFAULT NULL,
  `username` varchar(32) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;

