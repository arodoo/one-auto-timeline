-- Create the insurance information table
CREATE TABLE IF NOT EXISTS `membres_insurance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre` int(11) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `contract_number` varchar(50) DEFAULT NULL,
  `green_card_number` varchar(50) DEFAULT NULL,
  `valid_from` int(11) DEFAULT NULL,
  `valid_to` int(11) DEFAULT NULL,
  `agency_name` varchar(100) DEFAULT NULL,
  `agency_address` varchar(200) DEFAULT NULL,  `agency_country` varchar(50) DEFAULT NULL,
  `agency_email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_membre` (`id_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
