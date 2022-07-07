-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           10.6.4-MariaDB - mariadb.org binary distribution
-- SE du serveur:                Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage des données de la table temma.category : ~5 rows (environ)
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` (`id`, `label`, `icon`) VALUES
	(1, 'Factures', 'fa-file-invoice-dollar'),
	(2, 'Devis', 'fa-file-lines'),
	(3, 'Maintenance', 'fa-screwdriver-wrench'),
	(4, 'Données machines', 'fa-database'),
	(5, 'Echanges', 'fa-headset');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Listage des données de la table temma.doctrine_migration_versions : ~7 rows (environ)
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20220629084511', '2022-07-07 11:31:54', 816),
	('DoctrineMigrations\\Version20220630140507', '2022-07-07 11:31:55', 39),
	('DoctrineMigrations\\Version20220630141010', '2022-07-07 11:31:55', 40),
	('DoctrineMigrations\\Version20220701090650', '2022-07-07 11:31:55', 100),
	('DoctrineMigrations\\Version20220705140912', '2022-07-07 11:31:55', 115),
	('DoctrineMigrations\\Version20220705151955', '2022-07-07 11:31:55', 46),
	('DoctrineMigrations\\Version20220706065202', '2022-07-07 11:31:55', 38);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;

-- Listage des données de la table temma.file : ~0 rows (environ)
/*!40000 ALTER TABLE `file` DISABLE KEYS */;
/*!40000 ALTER TABLE `file` ENABLE KEYS */;

-- Listage des données de la table temma.reset_password_request : ~0 rows (environ)
/*!40000 ALTER TABLE `reset_password_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `reset_password_request` ENABLE KEYS */;

-- Listage des données de la table temma.user : ~2 rows (environ)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `email`, `password`, `firstname`, `lastname`, `is_verified`, `enterprise`, `civility`, `login`, `roles`, `is_admin`) VALUES
	(1, 'bob@test.fr', '$2y$13$PndnWy.1vggaDgxE.XhqE.t/M8vVphJk43zWCZgkWcOkZhqNqD5UO', 'bob', 'TestLastname', 1, 'bob', 'Monsieur', 'tesjMsJ', '["ROLE_USER"]', 0),
	(2, 'admin@admin.fr', '$2y$13$H1gM21M7MnMs4JPMfD3QM.UUtA3hCeXtwStp3ERsJJtf2vYFKHHuO', 'Admin', 'Admin', 1, 'Temma', 'Monsieur', 'admD7rJ', '["ROLE_ADMIN"]', 1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
