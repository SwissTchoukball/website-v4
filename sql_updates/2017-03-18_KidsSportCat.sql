CREATE TABLE `DBDKidsSportCat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nameFr` varchar(255) DEFAULT NULL,
  `nameDe` varchar(255) DEFAULT NULL,
  `nameIt` varchar(255) DEFAULT NULL,
  `nameEn` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`nameFr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `DBDKidsSportCat` WRITE;
/*!40000 ALTER TABLE `DBDKidsSportCat` DISABLE KEYS */;

INSERT INTO `DBDKidsSportCat` (`id`, `nameFr`, `nameDe`, `nameIt`, `nameEn`, `order`)
VALUES
  (1,'vert','Grün','verde','green', 1),
  (2,'bleu','Blau','blu','blue', 2),
  (3,'rouge','Rot','rosso','red', 3),
  (4,'noir','Schwarz','nero','black', 4);

/*!40000 ALTER TABLE `DBDKidsSportCat` ENABLE KEYS */;
UNLOCK TABLES;

ALTER TABLE `DBDPersonne` ADD `kidsSportCatId` INT  NULL  DEFAULT NULL  AFTER `dateAjout`;
