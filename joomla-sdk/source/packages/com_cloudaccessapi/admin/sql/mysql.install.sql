CREATE TABLE IF NOT EXISTS `#__demoapi_activation_codes` (
  `code` varchar(86) NOT NULL DEFAULT '' COMMENT 'activation link code',
  `params` text NOT NULL COMMENT 'serialized PHP value',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__demoapi_authentication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refresh_token` varchar(250) NOT NULL,
  `access_token` varchar(250) NOT NULL,
  `expires_in` varchar(250) NOT NULL,
  `token_type` varchar(250) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;