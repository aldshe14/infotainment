-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2019 at 12:01 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `infotainment_system`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getSupplierer` (`istunde` INT, `itag` INT, `iwoche` INT)  begin 
	-- select *, abs(a1.stunde-istunde)
    select a1.lehrer
	from tb_infotainment_unterricht a1
	left join (
		select t1.lehrer
		from  tb_infotainment_unterricht t1
		left join (
		select lehrer
		from tb_infotainment_unterricht
		where stunde = istunde and tag=itag and fach <> 'SU' and fach <> ''
		group by lehrer
		) t2
		on t1.lehrer = t2.lehrer
		where t1.fach <> 'SU' and t1.fach <> '' and t2.lehrer is null and t1.tag=itag
		group by t1.lehrer
	) a2
	on a1.lehrer = a2.lehrer
	left join(
		select b2.supplierer
		from tb_infotainment_unterricht b1
		left join tb_infotainment_supplieren b2
		on b1.u_id = b2.u_id and woche = iwoche
		where tag = itag and b2.supplierer is not null and stunde = istunde
	) a3
	on a1.lehrer = a3.supplierer
	left join (
		SELECT c1.lehrer
		FROM tb_infotainment_unterricht c1
		left join (
		select u_id
		from tb_infotainment_unterricht
		where tag = itag
		group by lehrer
		) c2
		on c1.u_id = c2.u_id
		left join tb_infotainment_fehlendeLehrer c3
		on c1.u_id = c3.u_id and woche = iwoche
		where c1.tag = itag and c1.lehrer <> '' and c2.u_id is not null and c3.u_id is not null
	) a4
	on a1.lehrer = a4.lehrer
    left join (
		SELECT d1.lehrer as lehrer
		from tb_infotainment_unterricht d1
		left join tb_infotainment_supplieren d2
		on d1.lehrer = d2.supplierer and d2.woche = iwoche
		where d1.fach = 'SU' and d1.tag =itag and stunde=istunde and d2.supplierer is null
    ) a5
    on a1.lehrer = a5.lehrer
	where a1.tag= itag and a2.lehrer is not null and a3.supplierer is null and a4.lehrer is null and a5.lehrer is null
	group by a1.lehrer
    order by abs(a1.stunde-istunde) asc;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_display`
--

CREATE TABLE `tb_infotainment_display` (
  `d_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_fehlendelehrer`
--

CREATE TABLE `tb_infotainment_fehlendelehrer` (
  `u_id` int(11) NOT NULL,
  `woche` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `tb_infotainment_fehlendelehrer`
--
DELIMITER $$
CREATE TRIGGER `checkLehrer` BEFORE DELETE ON `tb_infotainment_fehlendelehrer` FOR EACH ROW begin
	declare status int;
    declare ilehrer varchar(3);
    declare itag int;
    select u.lehrer into ilehrer
    from tb_infotainment_unterricht u
    join tb_infotainment_fehlendeLehrer f
    on u.u_id = f.u_id and f.woche = old.woche
    where u.u_id = old.u_id;
    select u.tag into itag
    from tb_infotainment_unterricht u
    join tb_infotainment_fehlendeLehrer f
    on u.u_id = f.u_id and f.woche = old.woche
    where u.u_id = old.u_id;
	select count(*) into status
	from tb_infotainment_unterricht a
	left join(
		select u.lehrer
		from tb_infotainment_unterricht u
		join tb_infotainment_fehlendelehrer f
		on u.u_id = f.u_id and f.woche = old.woche
		where u.lehrer = ilehrer and u.tag= itag
	) a1
	on a.lehrer = a1.lehrer 
	left join tb_infotainment_supplieren s
	on a.u_id = s.u_id
	where a1.lehrer is not null and tag= itag and s.u_id is not null;
    if status>0 
    then 
		signal sqlstate '45000' set message_text = 'Lehrer kann nicht geloescht werden, weil Supplierstunde hat!';
    end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_layout`
--

CREATE TABLE `tb_infotainment_layout` (
  `l_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `beschreibung` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_layout_sections`
--

CREATE TABLE `tb_infotainment_layout_sections` (
  `l_id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_location`
--

CREATE TABLE `tb_infotainment_location` (
  `l_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_roles`
--

CREATE TABLE `tb_infotainment_roles` (
  `r_id` int(11) NOT NULL,
  `r_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_infotainment_roles`
--

INSERT INTO `tb_infotainment_roles` (`r_id`, `r_name`) VALUES
(555, 'Editor'),
(777, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_supplieren`
--

CREATE TABLE `tb_infotainment_supplieren` (
  `u_id` int(11) NOT NULL,
  `woche` int(11) NOT NULL DEFAULT yearweek(cast(current_timestamp() as date),0),
  `supplierer` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `beschreibung` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_unterricht`
--

CREATE TABLE `tb_infotainment_unterricht` (
  `u_id` int(11) NOT NULL,
  `klasse` varchar(10) DEFAULT NULL,
  `lehrer` varchar(10) DEFAULT NULL,
  `fach` varchar(10) DEFAULT NULL,
  `raum` varchar(10) DEFAULT NULL,
  `tag` int(11) DEFAULT NULL,
  `stunde` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_infotainment_unterricht`
--

INSERT INTO `tb_infotainment_unterricht` (`u_id`, `klasse`, `lehrer`, `fach`, `raum`, `tag`, `stunde`) VALUES
(7138, 'SUP', 'KAB', 'SU', '', 3, 4),
(7139, 'SUP', 'KAB', 'SU', '', 4, 6),
(7140, '9a', 'KAB', 'AL', 'K26', 1, 1),
(7141, '9a', 'KAB', 'AL', 'K26', 2, 5),
(7142, '9a', 'KAB', 'AL', 'K26', 3, 3),
(7143, '9a', 'KAB', 'AL', 'K26', 4, 4),
(7144, '9a', 'KAB', 'AL', 'K26', 4, 5),
(7145, '3a', 'MEI', 'AL', 'K23', 2, 2),
(7146, '3a', 'MEI', 'AL', 'K23', 2, 3),
(7147, '3a', 'MEI', 'AL', 'K23', 4, 1),
(7148, '3a', 'MEI', 'AL', 'K23', 5, 5),
(7149, 'SUP', 'MEI', 'SU', '', 1, 4),
(7150, 'SUP', 'MEI', 'SU', '', 4, 6),
(7151, '8a', 'MEI', 'AL', 'K11', 1, 8),
(7152, '8a', 'MEI', 'AL', 'K11', 1, 9),
(7153, '8a', 'MEI', 'AL', 'K11', 3, 2),
(7154, '8a', 'MEI', 'AL', 'K11', 5, 3),
(7155, '8a', 'MEI', 'AL', 'K11', 5, 4),
(7156, '2a', 'KAB', 'AL', 'K28', 1, 8),
(7157, '2a', 'KAB', 'AL', 'K28', 1, 9),
(7158, '2a', 'KAB', 'AL', 'K28', 3, 5),
(7159, '2a', 'KAB', 'AL', 'K28', 3, 6),
(7160, '2b', 'KAB', 'AL', 'K22', 1, 3),
(7161, '2b', 'KAB', 'AL', 'K22', 1, 4),
(7162, '2b', 'KAB', 'AL', 'K22', 3, 1),
(7163, '2b', 'KAB', 'AL', 'K22', 3, 2),
(7164, '4a', 'MEI', 'AL', 'K27', 1, 2),
(7165, '4a', 'MEI', 'AL', 'K27', 2, 1),
(7166, '4b', 'MEI', 'AL', 'K21', 1, 6),
(7167, '4b', 'MEI', 'AL', 'K21', 4, 3),
(7168, '9bf', 'AIT', 'D', 'K14', 1, 3),
(7169, '9bf', 'AIT', 'D', 'K14', 3, 1),
(7170, '9bf', 'AIT', 'D', 'K14', 3, 2),
(7171, '9bf', 'AIT', 'D', 'K14', 5, 1),
(7172, '9bf', 'AIT', 'D', 'K14', 5, 2),
(7173, '9bb', 'AIT', 'D', 'K15', 3, 4),
(7174, '9bb', 'AIT', 'D', 'K15', 3, 5),
(7175, '9bb', 'AIT', 'D', 'K14', 4, 3),
(7176, '9bb', 'AIT', 'D', 'K14', 5, 3),
(7177, '9bb', 'AIT', 'D', 'K14', 5, 4),
(7178, '8a', 'DUE', 'ME', 'BIB', 2, 1),
(7179, '8ay', 'DUE', 'D', 'K13', 4, 7),
(7180, '8ay', 'DUE', 'D', 'K24', 5, 1),
(7181, '8ay', 'DUE', 'D', 'K24', 5, 2),
(7182, '8ax', 'DUE', 'D', 'K26', 2, 3),
(7183, '8ax', 'DUE', 'D', 'K11', 4, 4),
(7184, 'SUP', 'OFM', 'SU', '', 3, 1),
(7185, 'SUP', 'OFM', 'SU', '', 5, 1),
(7186, 'SUP', 'HEK', 'SU', '', 1, 2),
(7187, 'SUP', 'HEK', 'SU', '', 5, 5),
(7188, 'SUP', 'SCR', 'SU', '', 1, 6),
(7189, 'SUP', 'SCR', 'SU', '', 2, 6),
(7190, '2bx', 'HEK', 'D', 'K25', 3, 9),
(7191, '2by', 'HEK', 'D', 'K22', 2, 2),
(7192, 'SUP', 'HÖM', 'SU', '', 3, 2),
(7193, 'SUP', 'HÖM', 'SU', '', 5, 2),
(7194, '4a', 'GIC', 'E', 'K27', 1, 9),
(7195, '4a', 'GIC', 'E', 'K27', 5, 5),
(7196, '4b', 'PEN', 'E', 'K21', 2, 8),
(7197, '4b', 'PEN', 'E', 'K21', 4, 2),
(7198, '9ax', 'LIB', 'E', 'SW2', 1, 3),
(7199, '9ax', 'LIB', 'E', 'SW2', 1, 4),
(7200, '9ax', 'LIB', 'E', 'K26', 2, 2),
(7201, '9ay', 'LIB', 'E', 'K27', 2, 7),
(7202, '9ay', 'LIB', 'E', 'K26', 5, 1),
(7203, '9ay', 'LIB', 'E', 'K26', 5, 2),
(7204, '3b', 'GIC', 'E', 'K25', 1, 8),
(7205, '3b', 'GIC', 'E', 'K25', 3, 4),
(7206, '9bb', 'NED', 'E', 'K14', 1, 8),
(7207, '9bb', 'NED', 'E', 'K14', 3, 7),
(7208, '9bb', 'NED', 'E', 'K14', 4, 4),
(7209, '4a', 'SAL', 'AM', 'K27', 3, 1),
(7210, '4a', 'SAL', 'AM', 'K27', 5, 1),
(7211, '4b', 'SAL', 'AM', 'K21', 1, 4),
(7212, '4b', 'SAL', 'AM', 'K21', 4, 6),
(7213, '3a', 'KAE', 'AM', 'K23', 1, 1),
(7214, '3a', 'KAE', 'AM', 'K23', 2, 1),
(7215, '3b', 'KAE', 'AM', 'K25', 1, 2),
(7216, '3b', 'KAE', 'AM', 'K25', 2, 9),
(7217, '3ax', 'SCC', 'MED2', 'PR2', 4, 5),
(7218, '3ax', 'SCC', 'MED2', 'PR2', 4, 6),
(7219, '3ay', 'OFF', 'MED2', 'MLAB', 4, 5),
(7220, '3ay', 'OFF', 'MED2', 'MLAB', 4, 6),
(7221, '3bx', 'SCC', 'MED2', 'PR2', 4, 3),
(7222, '3bx', 'SCC', 'MED2', 'PR2', 4, 4),
(7223, '3by', 'OFF', 'MED2', 'MLAB', 4, 3),
(7224, '3by', 'OFF', 'MED2', 'MLAB', 4, 4),
(7225, 'SUP', 'PRL', 'SU', '', 1, 4),
(7226, 'SUP', 'PRL', 'SU', '', 4, 3),
(7227, '2a', 'SAL', 'AM', 'K28', 3, 8),
(7228, '2a', 'SAL', 'AM', 'K28', 4, 2),
(7229, '2a', 'SAL', 'AM', 'K28', 5, 4),
(7230, '2b', 'SAL', 'AM', 'K22', 3, 5),
(7231, '2b', 'SAL', 'AM', 'K22', 4, 1),
(7232, '2b', 'SAL', 'AM', 'K22', 5, 2),
(7233, '9a', 'SAL', 'Ph', 'K26', 3, 4),
(7234, '9b', 'SAL', 'Ph', 'K14', 4, 9),
(7235, '8a', 'PRL', 'AM', 'K11', 1, 5),
(7236, '8a', 'PRL', 'AM', 'K11', 2, 2),
(7237, '8a', 'PRL', 'AM', 'K11', 3, 1),
(7238, '8a', 'PRL', 'AM', 'K11', 4, 5),
(7239, '3a', 'PRL', 'AM', 'K23', 2, 9),
(7240, '3a', 'PRL', 'AM', 'K23', 5, 6),
(7241, 'SUP', 'SAL', 'SU', '', 2, 3),
(7242, 'SUP', 'SAL', 'SU', '', 2, 4),
(7243, '4a', 'KAE', 'AM', 'K27', 2, 2),
(7244, '4a', 'KAE', 'AM', 'K27', 4, 9),
(7245, '4b', 'KAE', 'AM', 'K21', 2, 4),
(7246, '4b', 'KAE', 'AM', 'K21', 4, 4),
(7247, 'SUP', 'TAF', 'SU', '', 4, 2),
(7248, 'SUP', 'TAF', 'SU', '', 4, 4),
(7249, '8a', 'NEG', 'H-AL', 'K11', 1, 10),
(7250, '8a', 'NEG', 'H-AL', 'K11', 3, 9),
(7251, '9a', 'TAF', 'H-AL', 'K26', 5, 6),
(7252, '9b', 'NEG', 'H-AL', 'K14', 4, 10),
(7253, '5a', 'TAF', 'H-AL', 'K24', 1, 8),
(7254, '5a', 'TAF', 'H-AL', 'K24', 4, 3),
(7255, '8a', 'TAF', 'GG-AL', 'K11', 1, 4),
(7256, '8a', 'TAF', 'GG-AL', 'K11', 3, 5),
(7257, '9a', 'TAF', 'GG-AL', 'K26', 3, 9),
(7258, '9b', 'TAF', 'GG-AL', 'K14', 5, 5),
(7259, '4a', 'TAF', 'GG-AL', 'K27', 1, 10),
(7260, '4a', 'TAF', 'GG-AL', 'K27', 5, 2),
(7261, '4b', 'TAF', 'GG-AL', 'K21', 1, 3),
(7262, '4b', 'TAF', 'GG-AL', 'K21', 3, 6),
(7263, 'SUP', 'ZHN', 'SU', '', 4, 6),
(7264, 'SUP', 'ZHN', 'SU', '', 5, 3),
(7265, '9av', 'GJA', 'BSPM', 'BSPM', 2, 9),
(7266, '9av', 'GJA', 'BSPM', 'BSPM', 2, 10),
(7267, '9ev', 'GJA', 'BSPM', 'BSPM', 2, 9),
(7268, '9ev', 'GJA', 'BSPM', 'BSPM', 2, 10),
(7269, '9ed', 'ZHN', 'BSPK', 'BSPK', 2, 9),
(7270, '9ed', 'ZHN', 'BSPK', 'BSPK', 2, 10),
(7271, '', '', '', 'BSPM', 2, 9),
(7272, '', '', '', 'BSPM', 2, 10),
(7273, '', '', '', 'BSPK', 2, 9),
(7274, '', '', '', 'BSPK', 2, 10),
(7275, '4b', 'ALB', 'ITP2', 'SW2', 2, 9),
(7276, '4b', 'ALB', 'ITP2', 'SW2', 2, 10),
(7277, '4a', 'ALB', 'ITP2', 'SW2', 2, 5),
(7278, '4a', 'ALB', 'ITP2', 'SW2', 2, 6),
(7279, '3a', 'ALB', 'ITP2', 'SW2', 1, 8),
(7280, '3a', 'ALB', 'ITP2', 'SW2', 1, 9),
(7281, '3b', 'ALB', 'ITP2', 'SW2', 5, 1),
(7282, '3b', 'ALB', 'ITP2', 'SW2', 5, 2),
(7283, '3ax', 'MAM', 'SEW', 'PR4', 3, 3),
(7284, '3ax', 'MAM', 'SEW', 'PR4', 3, 4),
(7285, '3ay', 'MAM', 'SEW', 'PR4', 3, 5),
(7286, '3ay', 'MAM', 'SEW', 'PR4', 3, 6),
(7287, '3a', 'MAM', 'SEW', 'K23', 4, 2),
(7288, '5a', 'LIB', 'E', 'K24', 3, 6),
(7289, '5a', 'LIB', 'E', 'K24', 4, 1),
(7290, '2a', 'ABW', 'SYT', 'K28', 1, 5),
(7291, '2b', 'KAF', 'SYT', 'K22', 2, 6),
(7292, '2ay', 'ABW', 'SYT', 'HLAB', 2, 3),
(7293, '2ay', 'ABW', 'SYT', 'HLAB', 2, 4),
(7294, '2ay', 'ABW', 'SYT', 'HLAB', 4, 5),
(7295, '2ay', 'ABW', 'SYT', 'HLAB', 4, 6),
(7296, '4ax', 'KUA', 'SEW', 'PR3', 3, 5),
(7297, '4ax', 'KUA', 'SEW', 'PR3', 3, 6),
(7298, '4ax', 'KUA', 'SEW', 'PR3', 4, 2),
(7299, '4b', 'KUA', 'SEW', 'K21', 5, 4),
(7300, '3ay', 'OFF', 'ITP2', 'MLAB', 1, 5),
(7301, '3bx', 'OFF', 'ITP2', 'MLAB', 3, 6),
(7302, '3by', 'OFF', 'ITP2', 'MLAB', 1, 6),
(7303, '3a', 'FAJ', 'Ph', 'NWL', 1, 10),
(7304, '3a', 'FAJ', 'Ph', 'NWL', 2, 10),
(7305, '3b', 'FAJ', 'Ph', 'NWL', 3, 8),
(7306, '3b', 'FAJ', 'Ph', 'NWL', 4, 10),
(7307, '3b', 'ABW', 'SYT', 'K25', 2, 8),
(7308, '3b', 'ABW', 'SYT', 'K25', 4, 2),
(7309, '3bx', 'ABW', 'SYT', 'HLAB', 2, 5),
(7310, '3bx', 'ABW', 'SYT', 'HLAB', 2, 6),
(7311, '3by', 'ABW', 'SYT', 'HLAB', 3, 5),
(7312, '3by', 'ABW', 'SYT', 'HLAB', 3, 6),
(7313, '9bv', 'GJA', 'BSPM', 'BSPM', 2, 7),
(7314, '9bv', 'GJA', 'BSPM', 'BSPM', 2, 8),
(7315, '9bd', 'ZHN', 'BSPK', 'BSPK', 2, 7),
(7316, '9bd', 'ZHN', 'BSPK', 'BSPK', 2, 8),
(7317, '', '', '', 'BSPM', 2, 7),
(7318, '', '', '', 'BSPM', 2, 8),
(7319, '', '', '', 'BSPK', 2, 7),
(7320, '', '', '', 'BSPK', 2, 8),
(7321, '2bd', 'ZHN', 'BSPK', 'BSPK', 3, 3),
(7322, '2bd', 'ZHN', 'BSPK', 'BSPK', 3, 4),
(7323, '2bv', 'GJA', 'BSPM', 'BSPM', 3, 3),
(7324, '2bv', 'GJA', 'BSPM', 'BSPM', 3, 4),
(7325, 'SUP', 'GJA', 'SU', '', 2, 6),
(7326, 'SUP', 'GJA', 'SU', '', 5, 3),
(7327, 'SUP', 'NED', 'SU', '', 4, 3),
(7328, 'SUP', 'NED', 'SU', '', 5, 2),
(7329, '8bx', 'SHM', 'D', 'K12', 3, 4),
(7330, '8bx', 'SHM', 'D', 'K12', 3, 5),
(7331, '8b', 'DUE', 'ME', 'BIB', 4, 8),
(7332, '8b', 'MEI', 'AL', 'K12', 1, 3),
(7333, '8b', 'MEI', 'AL', 'K12', 2, 4),
(7334, '8b', 'MEI', 'AL', 'K12', 2, 5),
(7335, '8b', 'MEI', 'AL', 'K12', 4, 2),
(7336, '8b', 'MEI', 'AL', 'K12', 5, 1),
(7337, '3a', 'LIB', 'E', 'K23', 3, 2),
(7338, '3a', 'LIB', 'E', 'K23', 4, 4),
(7339, '8b', 'TAF', 'H-AL', 'K12', 4, 7),
(7340, '8b', 'TAF', 'H-AL', 'K12', 5, 3),
(7341, '8b', 'NEG', 'GG-AL', 'K12', 1, 9),
(7342, '8b', 'NEG', 'GG-AL', 'K12', 2, 9),
(7343, '9b', 'KAB', 'AL', 'K14', 2, 1),
(7344, '9b', 'KAB', 'AL', 'K14', 2, 2),
(7345, '9b', 'KAB', 'AL', 'K14', 3, 9),
(7346, '9b', 'KAB', 'AL', 'K14', 4, 1),
(7347, '9b', 'KAB', 'AL', 'K14', 4, 2),
(7348, '3b', 'MEI', 'AL', 'K25', 3, 3),
(7349, '3b', 'MEI', 'AL', 'K25', 4, 8),
(7350, '3b', 'MEI', 'AL', 'K25', 4, 9),
(7351, '3b', 'MEI', 'AL', 'K25', 5, 6),
(7352, '8by', 'SHM', 'D', 'K12', 1, 7),
(7353, '8by', 'SHM', 'D', 'K27', 3, 2),
(7354, '8by', 'SHM', 'D', 'K27', 3, 3),
(7355, '9bb', 'AIT', 'SOPK', 'K14', 1, 9),
(7356, '8ax', 'SCR', 'D', 'K11', 3, 3),
(7357, '8ax', 'SCR', 'D', 'K11', 3, 4),
(7358, '8ax', 'SCR', 'D', 'K11', 4, 3),
(7359, '8ax', 'SCR', 'D', 'K11', 5, 1),
(7360, '8ax', 'SCR', 'D', 'K11', 5, 2),
(7361, '8ay', 'AIT', 'D', 'K11', 1, 1),
(7362, '8ay', 'AIT', 'D', 'K11', 1, 2),
(7363, '8ay', 'AIT', 'D', 'K11', 2, 3),
(7364, '8ay', 'AIT', 'D', 'K11', 4, 1),
(7365, '8ay', 'AIT', 'D', 'K11', 4, 2),
(7366, '4a', 'SCR', 'D', 'K27', 1, 8),
(7367, '4a', 'SCR', 'D', 'K27', 2, 3),
(7368, '4a', 'SCR', 'D', 'K27', 2, 4),
(7369, '4a', 'SCR', 'D', 'K27', 4, 5),
(7370, '4a', 'SCR', 'D', 'K27', 4, 6),
(7371, '9ay', 'HEK', 'D', 'K26', 1, 3),
(7372, '9ay', 'HEK', 'D', 'K26', 1, 4),
(7373, '9ay', 'HEK', 'D', 'K26', 3, 5),
(7374, '9ay', 'HEK', 'D', 'PR5', 4, 1),
(7375, '9ay', 'HEK', 'D', 'PR5', 4, 2),
(7376, '9ax', 'SCR', 'D', 'K27', 3, 5),
(7377, '9ax', 'SCR', 'D', 'K26', 4, 1),
(7378, '9ax', 'SCR', 'D', 'K26', 4, 2),
(7379, '9ax', 'SCR', 'D', 'K26', 5, 3),
(7380, '9ax', 'SCR', 'D', 'K26', 5, 4),
(7381, '9ax', 'SCR', 'SOPK', 'K26', 2, 7),
(7382, '9ay', 'HEK', 'SOPK', 'K27', 2, 8),
(7383, '9bf', 'AIT', 'SOPK', 'K15', 1, 8),
(7384, '9bf', 'NED', 'E', 'K15', 1, 7),
(7385, '9bf', 'NED', 'E', 'K14', 3, 4),
(7386, '9bf', 'NED', 'E', 'K14', 3, 5),
(7387, '8ax', 'NED', 'E', 'PR5', 1, 1),
(7388, '8ax', 'NED', 'E', 'PR5', 1, 2),
(7389, '8ax', 'NED', 'E', 'K11', 4, 7),
(7390, '8ay', 'NED', 'E', 'K11', 2, 4),
(7391, '8ay', 'NED', 'E', 'K11', 2, 5),
(7392, '8ay', 'NED', 'E', 'K14', 3, 3),
(7393, '8bx', 'GIC', 'E', 'K12', 3, 2),
(7394, '8bx', 'GIC', 'E', 'K12', 3, 3),
(7395, '8bx', 'GIC', 'E', 'K12', 4, 4),
(7396, '3b', 'PRL', 'AM', 'K25', 4, 6),
(7397, '3b', 'PRL', 'AM', 'K25', 5, 5),
(7398, '9a', 'SAL', 'AM', 'K26', 1, 2),
(7399, '9a', 'SAL', 'AM', 'K26', 2, 1),
(7400, '9a', 'SAL', 'AM', 'K26', 3, 2),
(7401, '9a', 'SAL', 'AM', 'K26', 5, 5),
(7402, '9b', 'SAL', 'AM', 'K14', 1, 5),
(7403, '9b', 'SAL', 'AM', 'K14', 2, 5),
(7404, '9b', 'SAL', 'AM', 'K14', 4, 8),
(7405, '9b', 'SAL', 'AM', 'K14', 5, 6),
(7406, '8a', 'CEG', 'Bio', 'NWL', 3, 7),
(7407, '8a', 'CEG', 'Bio', 'NWL', 5, 5),
(7408, '8b', 'CEG', 'Bio', 'NWL', 1, 8),
(7409, '8b', 'CEG', 'Bio', 'NWL', 3, 1),
(7410, '9a', 'CEG', 'Bio', 'NWL', 1, 5),
(7411, '8a', 'CEG', 'BCH', 'K11', 3, 8),
(7412, '8b', 'CEG', 'BCH', 'NWL', 2, 8),
(7413, '9a', 'CEG', 'BCH', 'NWL', 3, 10),
(7414, '9b', 'CEG', 'BCH', 'NWL', 3, 3),
(7415, '2a', 'CEG', 'ACOL', 'NWL', 1, 6),
(7416, '2a', 'CEG', 'ACOL', 'NWL', 5, 3),
(7417, '2b', 'CEG', 'ACOL', 'K22', 2, 10),
(7418, '2b', 'CEG', 'ACOL', 'NWL', 5, 1),
(7419, '2bx', 'KAF', 'SYT', 'HLAB', 1, 5),
(7420, '2bx', 'KAF', 'SYT', 'HLAB', 1, 6),
(7421, '2bx', 'KAF', 'SYT', 'HLAB', 2, 8),
(7422, '2bx', 'KAF', 'SYT', 'HLAB', 2, 9),
(7423, '2by', 'KAF', 'SYT', 'HLAB', 4, 8),
(7424, '2by', 'KAF', 'SYT', 'HLAB', 4, 9),
(7425, '2by', 'KAF', 'SYT', 'HLAB', 5, 5),
(7426, '2by', 'KAF', 'SYT', 'HLAB', 5, 6),
(7427, '4ax', 'NUF', 'ITP2', 'S-LAB', 2, 8),
(7428, '4ax', 'NUF', 'ITP2', 'S-LAB', 2, 9),
(7429, '4ax', 'NUF', 'ITP2', 'S-LAB', 2, 10),
(7430, '9a', 'ABW', 'SYT', 'K26', 4, 3),
(7431, '9b', 'ABW', 'SYT', 'K14', 1, 1),
(7432, '3a', 'NUF', 'NWTK', 'K23', 1, 2),
(7433, '3a', 'NUF', 'NWTK', 'K23', 2, 4),
(7434, '3ax', 'BUB', 'NWTK', 'PR2', 2, 8),
(7435, '3ax', 'BUB', 'NWTK', 'CLAB', 3, 5),
(7436, '3ax', 'BUB', 'NWTK', 'CLAB', 3, 6),
(7437, '3ay', 'BUB', 'NWTK', 'CLAB', 1, 3),
(7438, '3ay', 'BUB', 'NWTK', 'CLAB', 2, 5),
(7439, '3ay', 'BUB', 'NWTK', 'CLAB', 2, 6),
(7440, '2ax', 'ABW', 'SYT', 'HLAB', 1, 3),
(7441, '2ax', 'ABW', 'SYT', 'HLAB', 1, 4),
(7442, 'SUP', 'ALB', 'SU', '', 1, 5),
(7443, 'SUP', 'ALB', 'SU', '', 2, 1),
(7444, 'SUP', 'CEG', 'SU', '', 4, 4),
(7445, 'SUP', 'CEG', 'SU', '', 5, 4),
(7446, '5a', 'ALB', 'QM2', 'K24', 3, 1),
(7447, '2b', 'NUF', 'NWTK', 'K22', 3, 6),
(7448, '2b', 'NUF', 'NWTK', 'K22', 4, 2),
(7449, '2a', 'NUF', 'NWTK', 'K28', 2, 5),
(7450, '2a', 'NUF', 'NWTK', 'K28', 4, 1),
(7451, '4ax', 'SCC', 'INSY', 'PR2', 4, 1),
(7452, '3b', 'MAM', 'SEW', 'K25', 4, 1),
(7453, '3bx', 'MAM', 'SEW', 'PR4', 1, 5),
(7454, '3bx', 'MAM', 'SEW', 'PR4', 1, 6),
(7455, '3by', 'MAM', 'SEW', 'PR4', 3, 1),
(7456, '3by', 'MAM', 'SEW', 'PR4', 3, 2),
(7457, '2bx', 'ALB', 'ITP2', 'HW1', 5, 3),
(7458, '2bx', 'ALB', 'ITP2', 'HW1', 5, 4),
(7459, '3ax', 'OFF', 'ITP2', 'MLAB', 1, 4),
(7460, '9a', 'SCG', 'K', 'K26', 3, 1),
(7461, '9b', 'SCG', 'K', 'K14', 4, 5),
(7462, '8c', 'MEI', 'AL', 'K13', 1, 1),
(7463, '8c', 'MEI', 'AL', 'K13', 3, 1),
(7464, '8c', 'MEI', 'AL', 'K13', 4, 4),
(7465, '8c', 'MEI', 'AL', 'K13', 4, 5),
(7466, '8c', 'MEI', 'AL', 'K13', 5, 2),
(7467, '8by', 'PEN', 'E', 'SW2', 1, 1),
(7468, '8by', 'PEN', 'E', 'SW2', 1, 2),
(7469, '8by', 'PEN', 'E', 'K12', 2, 3),
(7470, '8c', 'PRL', 'Ph', 'K13', 2, 8),
(7471, '8c', 'PRL', 'Ph', 'K13', 3, 8),
(7472, '8b', 'FIO', 'AM', 'K12', 1, 4),
(7473, '8b', 'FIO', 'AM', 'K12', 2, 7),
(7474, '8b', 'FIO', 'AM', 'K12', 4, 3),
(7475, '8b', 'FIO', 'AM', 'K12', 5, 2),
(7476, '8c', 'PRL', 'AM', 'K13', 1, 2),
(7477, '8c', 'PRL', 'AM', 'K13', 2, 3),
(7478, '8c', 'PRL', 'AM', 'K13', 3, 7),
(7479, '8c', 'PRL', 'AM', 'K13', 5, 1),
(7480, '8a', 'PRL', 'Ph', 'K11', 1, 3),
(7481, '8a', 'PRL', 'Ph', 'NWL', 4, 8),
(7482, '8b', 'PRL', 'Ph', 'K12', 4, 1),
(7483, '8b', 'PRL', 'Ph', 'NWL', 5, 4),
(7484, '8c', 'CEG', 'Bio', 'NWL', 1, 7),
(7485, '8c', 'CEG', 'Bio', 'K13', 3, 9),
(7486, '8c', 'CEG', 'BCH', 'NWL', 4, 1),
(7487, '8c', 'NEG', 'H-AL', 'K13', 2, 10),
(7488, '8c', 'NEG', 'H-AL', 'K13', 3, 10),
(7489, '8c', 'TAF', 'GG-AL', 'K13', 1, 5),
(7490, '8c', 'TAF', 'GG-AL', 'K13', 3, 2),
(7491, '2ad', 'ZHN', 'BSPK', 'BSPK', 5, 1),
(7492, '2ad', 'ZHN', 'BSPK', 'BSPK', 5, 2),
(7493, '2av', 'GJA', 'BSPM', 'BSPM', 5, 1),
(7494, '2av', 'GJA', 'BSPM', 'BSPM', 5, 2),
(7495, '2by', 'ALB', 'ITP2', 'HW1', 2, 3),
(7496, '2by', 'ALB', 'ITP2', 'HW1', 2, 4),
(7497, '4a', 'KUA', 'SEW', 'K27', 5, 3),
(7498, '7a', 'SHB', 'AL', 'K01', 2, 7),
(7499, '7a', 'SHB', 'AL', 'K01', 2, 8),
(7500, '7a', 'SHB', 'AL', 'K01', 3, 5),
(7501, '7a', 'SHB', 'AL', 'K01', 4, 7),
(7502, '7a', 'SHB', 'AL', 'K01', 4, 8),
(7503, '7a', 'TAF', 'GG-AL', 'K01', 1, 6),
(7504, '7a', 'TAF', 'GG-AL', 'K01', 3, 3),
(7505, '7a', 'TAF', 'H-AL', 'K01', 2, 4),
(7506, '7a', 'TAF', 'H-AL', 'K01', 3, 4),
(7507, '7a', 'DUE', 'ME', 'BIB', 4, 9),
(7508, '7a', 'FIO', 'AM', 'K01', 1, 2),
(7509, '7a', 'FIO', 'AM', 'K01', 2, 3),
(7510, '7a', 'FIO', 'AM', 'K01', 4, 1),
(7511, '7a', 'FIO', 'AM', 'K01', 5, 3),
(7512, '7a', 'CEG', 'BCH', 'NWL', 1, 1),
(7513, '7a', 'CEG', 'BCH', 'NWL', 4, 2),
(7514, '7ax', 'PEN', 'D', 'K01', 1, 3),
(7515, '7ax', 'PEN', 'D', 'K01', 1, 4),
(7516, '7ax', 'PEN', 'D', 'K01', 3, 7),
(7517, '7ax', 'ABN', 'D', 'PR3', 2, 1),
(7518, '7ax', 'ABN', 'D', 'PR3', 2, 2),
(7519, '7ax', 'ABN', 'D', 'K01', 4, 3),
(7520, '7ax', 'ABN', 'D', 'K01', 4, 4),
(7521, '7ax', 'NED', 'E', 'K01', 1, 5),
(7522, '7ax', 'NED', 'E', 'K01', 3, 1),
(7523, '7ax', 'NED', 'E', 'K01', 3, 2),
(7524, '7ax', 'KAE', 'SYT', 'PR1', 5, 1),
(7525, '7ax', 'KAE', 'SYT', 'PR1', 5, 2),
(7526, '7ay', 'PEN', 'D', 'K01', 2, 1),
(7527, '7ay', 'PEN', 'D', 'K01', 2, 2),
(7528, '7ay', 'PEN', 'D', 'K05', 4, 3),
(7529, '7ay', 'ABN', 'D', 'K23', 1, 3),
(7530, '7ay', 'ABN', 'D', 'K23', 1, 4),
(7531, '7ay', 'ABN', 'D', 'K02', 3, 7),
(7532, '7ay', 'ABN', 'D', 'K02', 3, 8),
(7533, '7ax', 'PEN', 'SBK', 'K01', 3, 8),
(7534, '7ay', 'PEN', 'E', 'K26', 1, 5),
(7535, '7ay', 'PEN', 'E', 'K01', 5, 1),
(7536, '7ay', 'PEN', 'E', 'K01', 5, 2),
(7537, '7ay', 'KAE', 'SYT', 'SW2', 3, 1),
(7538, '7ay', 'KAE', 'SYT', 'SW2', 3, 2),
(7539, '7b', 'SHB', 'AL', 'K02', 1, 1),
(7540, '7b', 'SHB', 'AL', 'K02', 2, 1),
(7541, '7b', 'SHB', 'AL', 'K02', 2, 2),
(7542, '7b', 'SHB', 'AL', 'K02', 4, 5),
(7543, '7b', 'SHB', 'AL', 'K02', 5, 2),
(7544, '7b', 'TAF', 'H-AL', 'K02', 2, 3),
(7545, '7b', 'TAF', 'H-AL', 'K02', 3, 1),
(7546, '7b', 'TAF', 'GG-AL', 'K02', 1, 2),
(7547, '7b', 'TAF', 'GG-AL', 'K02', 5, 4),
(7548, '7b', 'SAL', 'AM', 'K02', 1, 3),
(7549, '7b', 'SAL', 'AM', 'K02', 3, 3),
(7550, '7b', 'SAL', 'AM', 'K02', 4, 3),
(7551, '7b', 'SAL', 'AM', 'K02', 5, 3),
(7552, '7b', 'CEG', 'Bio', 'K02', 2, 9),
(7553, '7b', 'CEG', 'Bio', 'NWL', 5, 6),
(7554, '7bx', 'HÖM', 'D', 'K02', 1, 7),
(7555, '7bx', 'HÖM', 'D', 'K02', 1, 8),
(7556, '7bx', 'HÖM', 'D', 'K27', 4, 2),
(7557, '7bx', 'ABN', 'D', 'K02', 2, 7),
(7558, '7bx', 'ABN', 'D', 'K02', 2, 8),
(7559, '7bx', 'ABN', 'D', 'K05', 3, 4),
(7560, '7bx', 'ABN', 'D', 'K05', 3, 5),
(7561, '7bx', 'HÖM', 'SBK', 'K02', 5, 1),
(7562, '7bx', 'PEN', 'E', 'K13', 2, 4),
(7563, '7bx', 'PEN', 'E', 'K13', 2, 5),
(7564, '7bx', 'PEN', 'E', 'K27', 4, 1),
(7565, '7bx', 'KAE', 'SYT', 'SW2', 4, 7),
(7566, '7bx', 'KAE', 'SYT', 'SW2', 4, 8),
(7567, '7av', 'GJA', 'BSPM', 'BSPM', 4, 5),
(7568, '7av', 'GJA', 'BSPM', 'BSPM', 5, 4),
(7569, '7av', 'GJA', 'BSPM', 'BSPM', 5, 5),
(7570, '7ad', 'ZHN', 'BSPK', 'BSPK', 4, 5),
(7571, '7ad', 'ZHN', 'BSPK', 'BSPK', 5, 4),
(7572, '7ad', 'ZHN', 'BSPK', 'BSPK', 5, 5),
(7573, '', '', '', 'BSPM', 4, 5),
(7574, '', '', '', 'BSPM', 5, 4),
(7575, '', '', '', 'BSPM', 5, 5),
(7576, '', '', '', 'BSPK', 4, 5),
(7577, '', '', '', 'BSPK', 5, 4),
(7578, '', '', '', 'BSPK', 5, 5),
(7579, '8ax', 'SCR', 'SBK', 'K13', 1, 7),
(7580, '8ax', 'KAE', 'SYT', 'PR1', 2, 5),
(7581, '8ax', 'KAE', 'SYT', 'PR1', 4, 1),
(7582, '8ax', 'KAE', 'SYT', 'PR1', 4, 2),
(7583, '8ay', 'AIT', 'SBK', 'K11', 1, 7),
(7584, '8bx', 'SCR', 'D', 'K12', 1, 1),
(7585, '8bx', 'SCR', 'D', 'K12', 1, 2),
(7586, '8bx', 'SCR', 'D', 'PR5', 2, 1),
(7587, '8bx', 'SCR', 'D', 'K02', 5, 5),
(7588, '8bx', 'SCR', 'D', 'K02', 5, 6),
(7589, '8bx', 'SCR', 'SBK', 'PR5', 2, 2),
(7590, '8by', 'HÖM', 'D', 'K12', 2, 1),
(7591, '8by', 'HÖM', 'D', 'K12', 2, 2),
(7592, '8by', 'HÖM', 'D', 'K23', 4, 5),
(7593, '8by', 'HÖM', 'D', 'K12', 5, 5),
(7594, '8by', 'HÖM', 'D', 'K12', 5, 6),
(7595, '8by', 'HÖM', 'SBK', 'K12', 1, 5),
(7596, '9ax', 'KAF', 'SYT', 'PR5', 5, 1),
(7597, '9ax', 'KAF', 'SYT', 'PR5', 5, 2),
(7598, '9ax', 'BUB', 'MED2', 'CLAB', 2, 3),
(7599, '9ax', 'BUB', 'MED2', 'CLAB', 2, 4),
(7600, '9ay', 'KAF', 'SYT', 'PR5', 5, 3),
(7601, '9ay', 'KAF', 'SYT', 'PR5', 5, 4),
(7602, '9ay', 'BUB', 'MED2', 'CLAB', 4, 7),
(7603, '9ay', 'BUB', 'MED2', 'CLAB', 4, 8),
(7604, '9bb', 'MAM', 'MED2', 'PR4', 2, 3),
(7605, '9bb', 'MAM', 'MED2', 'PR4', 2, 4),
(7606, '9bb', 'KAF', 'SYT', 'PR5', 3, 1),
(7607, '9bb', 'KAF', 'SYT', 'PR5', 3, 2),
(7608, '9bf', 'KAF', 'SYT', 'PR5', 3, 7),
(7609, '9bf', 'KAF', 'SYT', 'PR5', 3, 8),
(7610, '9bf', 'MAM', 'MED2', 'SW2', 5, 3),
(7611, '9bf', 'MAM', 'MED2', 'SW2', 5, 4),
(7612, '8cc', 'PEN', 'D', 'K13', 2, 7),
(7613, '8cc', 'PEN', 'D', 'K21', 3, 4),
(7614, '8cc', 'PEN', 'D', 'K21', 3, 5),
(7615, '8cc', 'PEN', 'D', 'K13', 5, 3),
(7616, '8cc', 'PEN', 'D', 'K13', 5, 4),
(7617, '8cc', 'PEN', 'SBK', 'K13', 3, 3),
(7618, '8cc', 'NED', 'E', 'K13', 2, 1),
(7619, '8cc', 'NED', 'E', 'K13', 2, 2),
(7620, '8cc', 'NED', 'E', 'K13', 5, 5),
(7621, '8ce', 'LIB', 'D', 'K24', 2, 5),
(7622, '8ce', 'LIB', 'D', 'K13', 3, 4),
(7623, '8ce', 'LIB', 'D', 'K13', 3, 5),
(7624, '8ce', 'LIB', 'D', 'K13', 4, 2),
(7625, '8ce', 'LIB', 'D', 'K13', 4, 3),
(7626, '8ce', 'LIB', 'SBK', 'K24', 2, 4),
(7627, '8ce', 'NED', 'E', 'K13', 1, 3),
(7628, '8ce', 'NED', 'E', 'K13', 1, 4),
(7629, '8ce', 'NED', 'E', 'K21', 5, 3),
(7630, '2ax', 'OFM', 'D', 'K28', 1, 2),
(7631, '2ax', 'OFM', 'D', 'K28', 3, 3),
(7632, '2ax', 'OFM', 'D', 'K28', 3, 4),
(7633, '2ax', 'OFM', 'D', 'K28', 4, 5),
(7634, '2ax', 'OFM', 'D', 'K28', 4, 6),
(7635, '2ax', 'PLE', 'SEW', 'PR1', 2, 1),
(7636, '2ax', 'PLE', 'SEW', 'PR1', 2, 2),
(7637, '2ax', 'FAJ', 'MED2', 'PR1', 4, 8),
(7638, '2ax', 'FAJ', 'MED2', 'PR1', 4, 9),
(7639, '2ay', 'SCR', 'D', 'K28', 1, 3),
(7640, '2ay', 'SCR', 'D', 'K28', 1, 4),
(7641, '2ay', 'SCR', 'D', 'K28', 3, 1),
(7642, '2ay', 'SCR', 'D', 'K28', 3, 2),
(7643, '2ay', 'SCR', 'D', 'K28', 4, 4),
(7644, '2ay', 'ALB', 'ITP2', 'HW1', 3, 3),
(7645, '2ay', 'ALB', 'ITP2', 'HW1', 3, 4),
(7646, '2ay', 'PLE', 'SEW', 'PR3', 1, 1),
(7647, '2ay', 'PLE', 'SEW', 'PR3', 1, 2),
(7648, '2ay', 'FAJ', 'MED2', 'PR1', 2, 8),
(7649, '2ay', 'FAJ', 'MED2', 'PR1', 2, 9),
(7650, '2b', 'PLE', 'SEW', 'K22', 2, 5),
(7651, '2a', 'PLE', 'SEW', 'K28', 2, 6),
(7652, '2bx', 'PLE', 'SEW', 'PR3', 5, 5),
(7653, '2bx', 'PLE', 'SEW', 'PR3', 5, 6),
(7654, '2bx', 'OFF', 'MED2', 'MLAB', 4, 8),
(7655, '2bx', 'OFF', 'MED2', 'MLAB', 4, 9),
(7656, '2by', 'PLE', 'SEW', 'PR3', 5, 3),
(7657, '2by', 'PLE', 'SEW', 'PR3', 5, 4),
(7658, '2by', 'OFF', 'MED2', 'MLAB', 2, 8),
(7659, '2by', 'OFF', 'MED2', 'MLAB', 2, 9),
(7660, '3a', 'STD', 'SYT', 'K23', 3, 1),
(7661, '3a', 'STD', 'SYT', 'K23', 4, 3),
(7662, '3a', 'MAM', 'INSY', 'K23', 3, 8),
(7663, '3ax', 'STD', 'SYT', 'HLAB', 5, 1),
(7664, '3ax', 'STD', 'SYT', 'HLAB', 5, 2),
(7665, '3ax', 'MAM', 'INSY', 'PR4', 1, 3),
(7666, '3ay', 'STD', 'SYT', 'HLAB', 5, 3),
(7667, '3ay', 'STD', 'SYT', 'HLAB', 5, 4),
(7668, '3ay', 'MAM', 'INSY', 'PR4', 1, 4),
(7669, '3b', 'FAJ', 'INSY', 'K25', 4, 5),
(7670, '3bx', 'FAJ', 'INSY', 'PR1', 3, 5),
(7671, '3by', 'FAJ', 'INSY', 'PR3', 1, 5),
(7672, '4bx', 'OFF', 'ITP2', 'MLAB', 1, 8),
(7673, '4bx', 'OFF', 'ITP2', 'MLAB', 1, 9),
(7674, '4bx', 'OFF', 'ITP2', 'MLAB', 1, 10),
(7675, '4bx', 'SCC', 'INSY', 'PR2', 3, 4),
(7676, '4bx', 'KUA', 'SEW', 'PR3', 3, 1),
(7677, '4bx', 'KUA', 'SEW', 'PR3', 3, 2),
(7678, '4bx', 'KUA', 'SEW', 'PR3', 4, 1),
(7679, '4ay', 'STD', 'ITP2', 'SYTLAB', 2, 8),
(7680, '4ay', 'STD', 'ITP2', 'SYTLAB', 2, 9),
(7681, '4ay', 'STD', 'ITP2', 'SYTLAB', 2, 10),
(7682, '4ay', 'SCC', 'INSY', 'PR2', 1, 3),
(7683, '4ay', 'KUA', 'SEW', 'PR3', 3, 3),
(7684, '4ay', 'KUA', 'SEW', 'PR3', 3, 4),
(7685, '4ay', 'KUA', 'SEW', 'PR3', 4, 3),
(7686, '5a', 'KAB', 'AL', 'K24', 1, 6),
(7687, '5a', 'KAB', 'AL', 'K24', 5, 6),
(7688, '5a', 'OFM', 'D', 'K24', 2, 2),
(7689, '5a', 'OFM', 'D', 'K24', 4, 8),
(7690, '5a', 'OFM', 'D', 'K24', 4, 9),
(7691, '5a', 'PRL', 'AM', 'K24', 1, 1),
(7692, '5a', 'PRL', 'AM', 'K24', 3, 2),
(7693, '5a', 'SCC', 'INSY', 'K24', 2, 1),
(7694, '5a', 'SCC', 'INSY', 'K24', 4, 2),
(7695, '5a', 'KUA', 'SEW', 'K24', 1, 5),
(7696, '5ax', 'SCC', 'INSY', 'PR2', 2, 4),
(7697, '5ax', 'KUA', 'SEW', 'PR3', 2, 5),
(7698, '5ax', 'KUA', 'SEW', 'PR3', 2, 6),
(7699, '5ax', 'KUA', 'SEW', 'PR3', 4, 6),
(7700, '5ay', 'KUA', 'SEW', 'PR3', 1, 9),
(7701, '5ay', 'KUA', 'SEW', 'PR3', 2, 8),
(7702, '5ay', 'KUA', 'SEW', 'PR3', 2, 9),
(7703, '5ay', 'SCC', 'INSY', 'PR2', 3, 5),
(7704, 'SUP', 'KAE', 'SU', '', 5, 3),
(7705, 'SUP', 'KAE', 'SU', '', 5, 4),
(7706, 'SUP', 'PEN', 'SU', '', 1, 6),
(7707, 'SUP', 'PEN', 'SU', '', 4, 5),
(7708, 'SUP', 'AIT', 'SU', '', 2, 5),
(7709, 'SUP', 'AIT', 'SU', '', 3, 6),
(7710, '7b', 'DUE', 'ME', 'BIB', 5, 5),
(7711, 'SUP', 'SCC', 'SU', '', 1, 5),
(7712, 'SUP', 'SCC', 'SU', '', 3, 3),
(7713, 'SUP', 'STD', 'SU', '', 1, 1),
(7714, 'SUP', 'STD', 'SU', '', 1, 3),
(7715, 'SUP', 'NUF', 'SU', '', 1, 1),
(7716, 'SUP', 'NUF', 'SU', '', 2, 3),
(7717, 'SUP', 'KUA', 'SU', '', 1, 4),
(7718, 'SUP', 'KUA', 'SU', '', 5, 5),
(7719, '4b', 'AIT', 'D', 'K21', 1, 5),
(7720, '4b', 'AIT', 'D', 'K21', 2, 1),
(7721, '4b', 'AIT', 'D', 'K21', 2, 2),
(7722, '4b', 'AIT', 'D', 'K21', 3, 3),
(7723, '4b', 'AIT', 'D', 'K21', 5, 5),
(7724, '9e', 'KAB', 'AL', 'K15', 2, 3),
(7725, '9e', 'KAB', 'AL', 'K15', 2, 4),
(7726, '9e', 'KAB', 'AL', 'K15', 3, 8),
(7727, '9e', 'KAB', 'AL', 'K15', 4, 8),
(7728, '9e', 'KAB', 'AL', 'K15', 5, 5),
(7729, '9ex', 'HÖM', 'SBK', 'K14', 1, 2),
(7730, '9e', 'SAL', 'Ph', 'K15', 4, 10),
(7731, '9e', 'SAL', 'AM', 'K15', 1, 1),
(7732, '9e', 'SAL', 'AM', 'K15', 2, 2),
(7733, '9e', 'SAL', 'AM', 'K15', 3, 9),
(7734, '9e', 'SAL', 'AM', 'K15', 4, 5),
(7735, '9e', 'CEG', 'BCH', 'NWL', 1, 3),
(7736, '9e', 'TAF', 'H-AL', 'K15', 2, 5),
(7737, '9e', 'NEG', 'GG-AL', 'K15', 4, 9),
(7738, '9ex', 'KAW', 'SYT', 'HW2', 3, 3),
(7739, '9ex', 'KAW', 'SYT', 'HW2', 3, 4),
(7740, '9ex', 'HÖM', 'D', 'K14', 2, 7),
(7741, '9ex', 'HÖM', 'D', 'K15', 4, 3),
(7742, '9ex', 'HÖM', 'D', 'K15', 4, 4),
(7743, '9ex', 'HÖM', 'D', 'K24', 5, 3),
(7744, '9ex', 'HÖM', 'D', 'K24', 5, 4),
(7745, '9ex', 'GIC', 'E', 'PR4', 4, 1),
(7746, '9ex', 'GIC', 'E', 'PR4', 4, 2),
(7747, '9ex', 'GIC', 'E', 'K15', 5, 1),
(7748, '9ex', 'HÖM', 'SOPK', 'K14', 2, 8),
(7749, '9ex', 'BUB', 'MED2', 'PR1', 1, 4),
(7750, '9ex', 'BUB', 'MED2', 'PR1', 1, 5),
(7751, '9e', 'ABW', 'SYT', 'K15', 2, 1),
(7752, 'SUP', 'SHB', 'SU', '', 4, 3),
(7753, 'SUP', 'SHB', 'SU', '', 5, 4),
(7754, '7by', 'HÖM', 'D', 'K02', 2, 4),
(7755, '7by', 'HÖM', 'D', 'K02', 2, 5),
(7756, '7by', 'HÖM', 'D', 'K02', 3, 4),
(7757, '7by', 'ABN', 'D', 'K01', 1, 7),
(7758, '7by', 'ABN', 'D', 'K01', 1, 8),
(7759, '7by', 'ABN', 'D', 'K02', 4, 7),
(7760, '7by', 'ABN', 'D', 'K02', 4, 8),
(7761, '7by', 'NED', 'E', 'K02', 4, 1),
(7762, '7by', 'NED', 'E', 'K02', 4, 2),
(7763, '7by', 'NED', 'E', 'K21', 5, 1),
(7764, '7by', 'KAE', 'SYT', 'SW2', 2, 7),
(7765, '7by', 'KAE', 'SYT', 'SW2', 2, 8),
(7766, 'SUP', 'KAF', 'SU', '', 4, 1),
(7767, 'SUP', 'KAF', 'SU', '', 4, 2),
(7768, 'SUP', 'OFF', 'SU', '', 3, 2),
(7769, 'SUP', 'OFF', 'SU', '', 5, 5),
(7770, '4b', 'SCC', 'INSY', 'PR2', 5, 3),
(7771, '4a', 'SCC', 'INSY', 'K27', 5, 4),
(7772, '4ax', 'NUF', 'NTSI', 'S-LAB', 1, 3),
(7773, '4ax', 'NUF', 'NTSI', 'S-LAB', 1, 4),
(7774, '4ax', 'NUF', 'NTSI', 'S-LAB', 1, 5),
(7775, '4ax', 'NUF', 'NTSI', 'S-LAB', 1, 6),
(7776, '4bx', 'SCC', 'MTEC', 'PR2', 5, 1),
(7777, '4bx', 'SCC', 'MTEC', 'PR2', 5, 2),
(7778, '4bx', 'SCC', 'MTEC', 'PR2', 1, 1),
(7779, '4bx', 'SCC', 'MTEC', 'PR2', 1, 2),
(7780, '4bx', 'SCC', 'MTEC', 'PR2', 4, 8),
(7781, '4bx', 'SCC', 'MTEC', 'PR2', 4, 9),
(7782, '4bx', 'OFF', 'MTES', 'MLAB', 3, 5),
(7783, '4ay', 'STD', 'ARM', 'SYTLAB', 3, 2),
(7784, '4ay', 'STD', 'ARM', 'SYTLAB', 4, 1),
(7785, '4ay', 'STD', 'ARM', 'SYTLAB', 4, 2),
(7786, '4ay', 'STD', 'LP', 'SYTLAB', 3, 5),
(7787, '4ay', 'STD', 'LP', 'SYTLAB', 3, 6),
(7788, '4ay', 'STD', 'SYE', 'SYTLAB', 1, 4),
(7789, '4ay', 'STD', 'SYE', 'SYTLAB', 1, 5),
(7790, '4ay', 'STD', 'SYE', 'SYTLAB', 1, 6),
(7791, '5a', 'KAE', 'AM', 'K24', 2, 3),
(7792, '5a', 'KAE', 'AM', 'K24', 5, 5),
(7793, '5b', 'TAF', 'H-AL', 'K24', 1, 9),
(7794, '5b', 'TAF', 'H-AL', 'K24', 4, 5),
(7795, '5b', 'LIB', 'E', 'K24', 2, 8),
(7796, '5b', 'LIB', 'E', 'K24', 2, 9),
(7797, '5b', 'ALB', 'QM2', 'K26', 4, 8),
(7798, '5b', 'KAB', 'AL', 'K27', 1, 5),
(7799, '5b', 'KAB', 'AL', 'K27', 4, 3),
(7800, '5b', 'OFM', 'D', 'K11', 2, 1),
(7801, '5b', 'OFM', 'D', 'K24', 3, 8),
(7802, '5b', 'OFM', 'D', 'K24', 3, 9),
(7803, '5b', 'KAE', 'AM', 'K15', 1, 3),
(7804, '5b', 'KAE', 'AM', 'K24', 4, 6),
(7805, '5b', 'SCC', 'INSY', 'PR2', 1, 4),
(7806, '5b', 'SCC', 'INSY', 'PR2', 2, 2),
(7807, '5b', 'SCC', 'INSY', 'PR2', 3, 6),
(7808, '5b', 'PRL', 'AM', 'K24', 3, 5),
(7809, '5b', 'PRL', 'AM', 'K24', 4, 4),
(7810, '5b', 'KUA', 'SEW', 'PR3', 1, 6),
(7811, '5b', 'KUA', 'SEW', 'PR3', 1, 8),
(7812, '5b', 'KUA', 'SEW', 'PR3', 2, 3),
(7813, '5b', 'KUA', 'SEW', 'PR3', 2, 4),
(7814, '5bx', 'OFF', 'MTES', 'MLAB', 1, 1),
(7815, '5bx', 'OFF', 'MTES', 'MLAB', 1, 2),
(7816, '5bx', 'OFF', 'MTES', 'MLAB', 3, 3),
(7817, '9ax', 'BUB', 'CPH', 'HW1', 4, 9),
(7818, '9ax', 'BUB', 'CPH', 'HW1', 4, 10),
(7819, '9ay', 'ALB', 'CPH', 'PR5', 4, 9),
(7820, '9ay', 'ALB', 'CPH', 'PR5', 4, 10),
(7821, '9bf', 'BUB', 'CPH', 'HW1', 1, 9),
(7822, '9bf', 'BUB', 'CPH', 'HW1', 1, 10),
(7823, '9ex', 'KAW', 'CPH', 'HW2', 1, 7),
(7824, '9ex', 'KAW', 'CPH', 'HW2', 1, 8),
(7825, '2ax', 'KAW', 'CPH', 'HW2', 2, 3),
(7826, '2ax', 'KAW', 'CPH', 'HW2', 2, 4),
(7827, '2bx', 'KAW', 'CPH', 'HW2', 4, 5),
(7828, '2bx', 'KAW', 'CPH', 'HW2', 4, 6),
(7829, '2ay', 'MAM', 'CPS', 'PR4', 5, 5),
(7830, '2ay', 'MAM', 'CPS', 'PR4', 5, 6),
(7831, '8c', 'DUE', 'ME', 'BIB', 2, 9),
(7832, '8ce', 'LIB', 'FFD', 'K13', 1, 8),
(7833, '2ax', 'MAM', 'CPS', 'PR4', 4, 3),
(7834, '2ax', 'MAM', 'CPS', 'PR4', 4, 4),
(7835, '2bx', 'MAM', 'CPS', 'PR4', 2, 1),
(7836, '2bx', 'MAM', 'CPS', 'PR4', 2, 2),
(7837, '2by', 'MAM', 'CPS', 'PR4', 1, 1),
(7838, '2by', 'MAM', 'CPS', 'PR4', 1, 2),
(7839, '8cc', 'BAS', 'D', 'K27', 1, 3),
(7840, '8cc', 'BAS', 'D', 'K27', 1, 4),
(7841, '8ce', 'BAS', 'D', 'K24', 2, 7),
(7842, '8ce', 'BAS', 'D', 'K01', 5, 4),
(7843, '8ce', 'BAS', 'D', 'K01', 5, 5),
(7844, '9ex', 'PLE', 'CPS', 'PR1', 3, 1),
(7845, '9ex', 'PLE', 'CPS', 'PR1', 3, 2),
(7846, '9ex', 'BAS', 'D', 'K15', 5, 2),
(7847, '9e', 'SCG', 'K', 'K15', 4, 7),
(7848, '9ax', 'SCR', 'SBK', 'K27', 3, 7),
(7849, '9ax', 'PLE', 'CPS', 'PR3', 4, 7),
(7850, '9ax', 'PLE', 'CPS', 'PR3', 4, 8),
(7851, '9ax', 'BAS', 'D', 'K26', 2, 8),
(7852, '9ay', 'BAS', 'D', 'K25', 2, 2),
(7853, '9ay', 'PLE', 'CPS', 'PR1', 2, 3),
(7854, '9ay', 'PLE', 'CPS', 'PR1', 2, 4),
(7855, '9bb', 'AIT', 'SBK', 'K14', 3, 8),
(7856, '9bb', 'BAS', 'D', 'K14', 1, 7),
(7857, '9bf', 'BAS', 'D', 'K14', 2, 3),
(7858, '3ax', 'HEK', 'D', 'K23', 1, 5),
(7859, '3ax', 'HEK', 'D', 'K23', 2, 5),
(7860, '3ax', 'HEK', 'D', 'K23', 2, 6),
(7861, '3ay', 'HEK', 'D', 'K23', 1, 6),
(7862, '2a', 'FAJ', 'Ph', 'NWL', 3, 9),
(7863, '2ax', 'ALB', 'ITP2', 'SW2', 5, 5),
(7864, '2ax', 'ALB', 'ITP2', 'SW2', 5, 6),
(7865, '2ax', 'GIC', 'E', 'K28', 1, 1),
(7866, '2ax', 'GIC', 'E', 'K28', 2, 8),
(7867, '2ax', 'GIC', 'E', 'K28', 2, 9),
(7868, '2ay', 'GIC', 'E', 'K28', 2, 1),
(7869, '2ay', 'GIC', 'E', 'K28', 2, 2),
(7870, '2ay', 'GIC', 'E', 'K28', 4, 3),
(7871, '2b', 'FAJ', 'Ph', 'K22', 3, 10),
(7872, '2bx', 'LIB', 'E', 'K22', 1, 1),
(7873, '2bx', 'LIB', 'E', 'K22', 1, 2),
(7874, '2bx', 'LIB', 'E', 'K25', 3, 8),
(7875, '2by', 'LIB', 'E', 'K22', 1, 5),
(7876, '2by', 'LIB', 'E', 'K22', 1, 6),
(7877, '2by', 'LIB', 'E', 'K22', 3, 9),
(7878, '9bb', 'PLE', 'CPS', 'PR3', 1, 3),
(7879, '9bb', 'PLE', 'CPS', 'PR3', 1, 4),
(7880, '9bf', 'PLE', 'CPS', 'PR1', 4, 3),
(7881, '9ay', 'HEK', 'SBK', 'K26', 3, 7),
(7882, '9b', 'CEG', 'Bio', 'NWL', 1, 2),
(7883, '9bf', 'AIT', 'SBK', 'K14', 1, 4),
(7884, '4bx', 'SCC', 'MTES', 'PR2', 2, 3),
(7885, 'SUP', 'GIC', 'SU', '', 3, 5),
(7886, 'SUP', 'GIC', 'SU', '', 4, 5),
(7887, 'SUP', 'MAM', 'SU', '', 2, 5),
(7888, 'SUP', 'MAM', 'SU', '', 5, 1),
(7889, 'SUP', 'BUB', 'SU', '', 2, 2),
(7890, 'SUP', 'BUB', 'SU', '', 3, 1),
(7891, 'SUP', 'LIB', 'SU', '', 2, 1),
(7892, 'SUP', 'LIB', 'SU', '', 3, 3),
(7893, '9e', 'CEG', 'Bio', 'NWL', 3, 5),
(7894, '4ad', 'ZHN', 'BSPK', 'BSPK', 5, 6),
(7895, '4bd', 'ZHN', 'BSPK', 'BSPK', 5, 6),
(7896, '4av', 'GJA', 'BSPM', 'BSPM', 5, 6),
(7897, '4bv', 'GJA', 'BSPM', 'BSPM', 5, 6),
(7898, '4ay', 'KUA', 'NWTK', 'PR3', 4, 4),
(7899, '3ax', 'ALB', 'ITP2', 'HW1', 1, 6),
(7900, '3ay', 'ALB', 'ITP2', 'HW1', 2, 8),
(7901, '3bx', 'ALB', 'ITP2', 'HW1', 1, 4),
(7902, '3by', 'ALB', 'ITP2', 'HW1', 2, 2),
(7903, '5bx', 'OFF', 'MTES', 'MLAB', 3, 4),
(7904, '5bx', 'OFF', 'MTES', 'MLAB', 4, 1),
(7905, '5bx', 'OFF', 'MTES', 'MLAB', 4, 2),
(7906, '2ax', 'ABW', 'SYT', 'HLAB', 3, 1),
(7907, '2ax', 'ABW', 'SYT', 'HLAB', 3, 2),
(7908, '8bd', 'ZHN', 'BSPK', 'BSPK', 3, 7),
(7909, '8bd', 'ZHN', 'BSPK', 'BSPK', 3, 8),
(7910, '9ad', 'ZHN', 'BSPK', 'BSPK', 1, 7),
(7911, '9ad', 'ZHN', 'BSPK', 'BSPK', 1, 8),
(7912, '9ey', 'KAW', 'SYT', 'HW1', 4, 1),
(7913, '9ey', 'KAW', 'SYT', 'HW1', 4, 2),
(7914, '9ey', 'BUB', 'MED2', 'CLAB', 5, 1),
(7915, '9ey', 'BUB', 'MED2', 'CLAB', 5, 2),
(7916, '9ey', 'ABW', 'CPS', 'PR1', 3, 3),
(7917, '9ey', 'ABW', 'CPS', 'PR1', 3, 4),
(7918, '9ey', 'BUB', 'CPH', 'HW1', 1, 7),
(7919, '9ey', 'BUB', 'CPH', 'HW1', 1, 8),
(7920, '5a', 'NUF', 'CISCO', 'S-LAB', 5, 10),
(7921, '5b', 'NUF', 'CISCO', 'S-LAB', 5, 10),
(7922, '4a', 'NUF', 'CISCO', 'S-LAB', 5, 10),
(7923, '4b', 'NUF', 'CISCO', 'S-LAB', 5, 10),
(7924, '4a', 'SCG', 'FFITP', '', 5, 8),
(7925, '4a', 'SCG', 'FFITP', '', 5, 9),
(7926, '4b', 'SCG', 'FFITP', '', 5, 8),
(7927, '4b', 'SCG', 'FFITP', '', 5, 9),
(7928, '5a', 'SCG', 'FFITP', '', 5, 8),
(7929, '5a', 'SCG', 'FFITP', '', 5, 9),
(7930, '5b', 'SCG', 'FFITP', '', 5, 8),
(7931, '5b', 'SCG', 'FFITP', '', 5, 9),
(7932, '5bx', 'SCC', 'MTEC', 'MLAB', 5, 5),
(7933, '5bx', 'SCC', 'MTEC', 'MLAB', 5, 6),
(7934, '5ay', 'ABW', 'ITP2', 'SYTLAB', 5, 1),
(7935, '5ay', 'ABW', 'ITP2', 'SYTLAB', 5, 2),
(7936, '5ay', 'ABW', 'ITP2', 'SYTLAB', 5, 3),
(7937, '5ay', 'ABW', 'ITP2', 'SYTLAB', 5, 4),
(7938, '5b', 'OFF', 'ITP2', 'MLAB', 5, 1),
(7939, '5b', 'OFF', 'ITP2', 'MLAB', 5, 2),
(7940, '5b', 'OFF', 'ITP2', 'MLAB', 5, 3),
(7941, '5b', 'OFF', 'ITP2', 'MLAB', 5, 4),
(7942, '5bx', 'SCC', 'MTEC', 'MLAB', 2, 5),
(7943, '5bx', 'SCC', 'MTEC', 'MLAB', 2, 6),
(7944, '5bx', 'SCC', 'MTEC', 'MLAB', 3, 1),
(7945, '5bx', 'SCC', 'MTEC', 'MLAB', 3, 2),
(7946, '5ax', 'KAW', 'NWTK', 'CLAB', 1, 2),
(7947, '5ax', 'KAW', 'NWTK', 'CLAB', 2, 8),
(7948, '5ax', 'KAW', 'NWTK', 'CLAB', 2, 9),
(7949, '5ax', 'KAW', 'NWTK', 'CLAB', 2, 10),
(7950, '5ax', 'NUF', 'NTSI', 'S-LAB', 3, 8),
(7951, '5ax', 'NUF', 'NTSI', 'S-LAB', 3, 9),
(7952, '5ax', 'NUF', 'NTSI', 'S-LAB', 3, 10),
(7953, '5ax', 'NUF', 'SVS', 'S-LAB', 3, 3),
(7954, '5ax', 'NUF', 'SVS', 'S-LAB', 3, 4),
(7955, '5ax', 'NUF', 'SVS', 'S-LAB', 3, 5),
(7956, '5ax', 'NUF', 'SVS', 'S-LAB', 4, 4),
(7957, '5ax', 'NUF', 'SVS', 'S-LAB', 4, 5),
(7958, '5ax', 'NUF', 'ITP2', 'S-LAB', 5, 1),
(7959, '5ax', 'NUF', 'ITP2', 'S-LAB', 5, 2),
(7960, '5ax', 'NUF', 'ITP2', 'S-LAB', 5, 3),
(7961, '5ax', 'NUF', 'ITP2', 'S-LAB', 5, 4),
(7962, '5ay', 'STD', 'IIT', 'SYTLAB', 4, 4),
(7963, '5ay', 'STD', 'IIT', 'SYTLAB', 4, 5),
(7964, '5ay', 'STD', 'IIT', 'SYTLAB', 4, 6),
(7965, '5ay', 'STD', 'BS', 'SYTLAB', 3, 3),
(7966, '5ay', 'STD', 'BS', 'SYTLAB', 3, 4),
(7967, '5ay', 'STD', 'LP', 'SYTLAB', 3, 8),
(7968, '5ay', 'STD', 'LP', 'SYTLAB', 3, 9),
(7969, '5ay', 'STD', 'LP', 'SYTLAB', 3, 10),
(7970, '5ay', 'STD', 'HWP', 'SYTLAB', 2, 5),
(7971, '5ay', 'STD', 'HWP', 'SYTLAB', 2, 6),
(7972, '5ay', 'STD', 'SUS', 'SYTLAB', 1, 2),
(7973, '5ay', 'STD', 'SUS', 'SYTLAB', 2, 4),
(7974, '8ay', 'KAF', 'SYT', 'PR5', 3, 4),
(7975, '8ay', 'KAF', 'SYT', 'PR5', 4, 3),
(7976, '8ay', 'KAF', 'SYT', 'PR5', 4, 4),
(7977, '8by', 'SCG', 'SYT', 'SW2', 3, 4),
(7978, '8by', 'SCG', 'SYT', 'SW2', 3, 5),
(7979, '8by', 'SCG', 'SYT', 'SW2', 4, 4),
(7980, '8bx', 'SCG', 'SYT', 'SW2', 1, 5),
(7981, '8bx', 'SCG', 'SYT', 'SW2', 1, 7),
(7982, '8bx', 'SCG', 'SYT', 'SW2', 2, 3),
(7983, '8cc', 'SCG', 'SYT', 'SW2', 2, 4),
(7984, '8cc', 'SCG', 'SYT', 'SW2', 4, 2),
(7985, '8cc', 'SCG', 'SYT', 'SW2', 4, 3),
(7986, '8ce', 'SCG', 'SYT', 'SW2', 2, 1),
(7987, '8ce', 'SCG', 'SYT', 'SW2', 2, 2),
(7988, '8ce', 'SCG', 'SYT', 'SW2', 3, 3),
(7989, '5b', 'SCG', 'WIR_2', 'SW2', 4, 9),
(7990, '5b', 'SCG', 'WIR_2', 'SW2', 4, 10),
(7991, '5a', 'SCG', 'WIR_2', 'K24', 1, 3),
(7992, '5a', 'SCG', 'WIR_2', 'K24', 1, 4),
(7993, '4a', 'SCG', 'WIR_3', 'K27', 1, 1),
(7994, '4a', 'SCG', 'WIR_3', 'K27', 4, 8),
(7995, '4b', 'SCG', 'WIR_3', 'K21', 2, 5),
(7996, '4b', 'SCG', 'WIR_3', 'K21', 2, 6),
(7997, '4ax', 'BUB', 'NWTK', 'CLAB', 3, 2),
(7998, '4ax', 'BUB', 'NWTK', 'CLAB', 4, 3),
(7999, '4ax', 'BUB', 'NWTK', 'CLAB', 3, 3),
(8000, '4ax', 'BUB', 'NWTK', 'CLAB', 3, 4),
(8001, '4ax', 'BUB', 'NWTK', 'CLAB', 4, 4),
(8002, '4b', 'KUA', 'NWTK', 'PR3', 4, 5),
(8003, '3b', 'KAW', 'NWTK', 'K25', 1, 1),
(8004, '3b', 'KAW', 'NWTK', 'K25', 2, 1),
(8005, '3b', 'OFM', 'D', 'K25', 1, 3),
(8006, '3b', 'OFM', 'D', 'K25', 2, 3),
(8007, '3b', 'OFM', 'D', 'K25', 2, 4),
(8008, '3b', 'OFM', 'D', 'K25', 5, 3),
(8009, '3b', 'OFM', 'D', 'K25', 5, 4),
(8010, '3bx', 'KAW', 'NWTK', 'S-LAB', 2, 2),
(8011, '3bx', 'KAW', 'NWTK', 'S-LAB', 3, 1),
(8012, '3bx', 'KAW', 'NWTK', 'S-LAB', 3, 2),
(8013, '3by', 'KAW', 'NWTK', 'CLAB', 1, 4),
(8014, '3by', 'KAW', 'NWTK', 'S-LAB', 2, 5),
(8015, '3by', 'KAW', 'NWTK', 'S-LAB', 2, 6),
(8016, '2by', 'KAW', 'CPH', 'HW2', 4, 3),
(8017, '2by', 'KAW', 'CPH', 'HW2', 4, 4),
(8018, '2ay', 'KAW', 'CPH', 'HW2', 4, 8),
(8019, '2ay', 'KAW', 'CPH', 'HW2', 4, 9),
(8020, '7a', 'FIO', 'Ph', 'K01', 2, 5),
(8021, '7a', 'FIO', 'Ph', 'K01', 5, 6),
(8022, '7b', 'SAL', 'Ph', 'K02', 3, 6),
(8023, '7b', 'SAL', 'Ph', 'K02', 4, 4),
(8024, '9bb', 'KAW', 'CPH', 'HW2', 5, 1),
(8025, '9bb', 'KAW', 'CPH', 'HW2', 5, 2),
(8026, '9ey', 'OFM', 'D', 'K15', 1, 4),
(8027, '9ey', 'OFM', 'D', 'K15', 1, 5),
(8028, '9ey', 'OFM', 'D', 'K15', 2, 7),
(8029, '9ey', 'OFM', 'D', 'K25', 4, 3),
(8030, '9ey', 'OFM', 'D', 'K25', 4, 4),
(8031, '9ey', 'BAS', 'D', 'K15', 1, 2),
(8032, '9ey', 'OFM', 'SBK', 'K15', 3, 2),
(8033, '9ey', 'OFM', 'SOPK', 'K15', 2, 8),
(8034, '9ey', 'LIB', 'E', 'K15', 3, 1),
(8035, '9ey', 'LIB', 'E', 'K15', 5, 3),
(8036, '9ey', 'LIB', 'E', 'K15', 5, 4),
(8037, 'SUP', 'ABW', 'SU', '', 1, 2),
(8038, 'SUP', 'ABW', 'SU', '', 2, 2),
(8039, 'SUP', 'SCG', 'SU', '', 1, 6),
(8040, 'SUP', 'SCG', 'SU', '', 4, 1),
(8041, '6a', 'SHB', 'AL', 'K03', 1, 4),
(8042, '6a', 'SHB', 'AL', 'K03', 2, 4),
(8043, '6a', 'SHB', 'AL', 'K03', 3, 1),
(8044, '6a', 'SHB', 'AL', 'K03', 5, 5),
(8045, '6a', 'SHB', 'AL', 'K03', 5, 6),
(8046, '6ax', 'ABN', 'D', 'K02', 1, 5),
(8047, '6ax', 'ABN', 'D', 'K03', 4, 1),
(8048, '6ax', 'ABN', 'D', 'K03', 5, 4),
(8049, '6ax', 'HÖM', 'D', 'K15', 3, 3),
(8050, '6ax', 'HÖM', 'D', 'K11', 4, 6),
(8051, '6ay', 'ABN', 'D', 'K02', 2, 6),
(8052, '6ay', 'ABN', 'D', 'K03', 3, 3),
(8053, '6ay', 'ABN', 'D', 'K22', 5, 3),
(8054, '6ay', 'HÖM', 'D', 'K05', 1, 3),
(8055, '6ay', 'HÖM', 'D', 'K15', 4, 1),
(8056, '6ax', 'GIC', 'E', 'K03', 1, 3),
(8057, '6ax', 'GIC', 'E', 'K03', 2, 6),
(8058, '6ax', 'GIC', 'E', 'K03', 5, 3),
(8059, '6ay', 'GIC', 'E', 'K03', 1, 5),
(8060, '6ay', 'GIC', 'E', 'K03', 4, 6),
(8061, '6ay', 'GIC', 'E', 'K22', 5, 4),
(8062, '6a', 'TAF', 'GG-AL', 'K03', 5, 1),
(8063, '6a', 'TAF', 'H-AL', 'K03', 1, 1),
(8064, '6a', 'PRL', 'AM', 'K03', 2, 1),
(8065, '6a', 'PRL', 'AM', 'K03', 3, 4),
(8066, '6a', 'PRL', 'AM', 'K03', 4, 2),
(8067, '6a', 'PRL', 'AM', 'K03', 5, 2),
(8068, '6a', 'SHM', 'ME', 'BIB', 2, 2),
(8069, '6a', 'SHM', 'ME', 'BIB', 4, 5),
(8070, '6ax', 'KAF', 'TEW', 'PR5', 3, 6),
(8071, '6ay', 'ALB', 'TEW', 'HW1', 3, 6),
(8072, '6ax', 'KAF', 'SYT', 'PR5', 3, 5),
(8073, '6ay', 'ALB', 'SYT', 'HW1', 3, 5),
(8074, '6a', 'CEG', 'BCH', 'NWL', 3, 2),
(8075, '6a', 'PRL', 'Ph', 'K03', 2, 5),
(8076, '6a', 'KAB', 'BSP', 'K03', 1, 2),
(8077, '6b', 'SHB', 'AL', 'K04', 1, 3),
(8078, '6b', 'SHB', 'AL', 'K04', 2, 3),
(8079, '6b', 'SHB', 'AL', 'K04', 3, 4),
(8080, '6b', 'SHB', 'AL', 'K04', 4, 4),
(8081, '6b', 'SHB', 'AL', 'K04', 5, 1),
(8082, '6b', 'TAF', 'GG-AL', 'K04', 4, 1),
(8083, '6b', 'TAF', 'H-AL', 'K04', 2, 1),
(8084, '6b', 'FIO', 'AM', 'K04', 1, 1),
(8085, '6b', 'FIO', 'AM', 'K04', 2, 2),
(8086, '6b', 'FIO', 'AM', 'K04', 3, 3),
(8087, '6b', 'FIO', 'AM', 'K04', 5, 4),
(8088, '6b', 'SHM', 'ME', 'BIB', 2, 6),
(8089, '6b', 'SHM', 'ME', 'K04', 5, 5),
(8090, '6b', 'CEG', 'BCH', 'NWL', 4, 3),
(8091, '6b', 'FIO', 'Ph', 'K04', 1, 5),
(8092, '6b', 'KAB', 'BSP', 'K04', 5, 3),
(8093, '6bx', 'ABN', 'D', 'K04', 1, 6),
(8094, '6bx', 'ABN', 'D', 'K04', 2, 4),
(8095, '6bx', 'ABN', 'D', 'K04', 5, 6),
(8096, '6bx', 'OFM', 'D', 'K15', 4, 2),
(8097, '6bx', 'OFM', 'D', 'K21', 5, 2),
(8098, '6bx', 'GIC', 'E', 'K02', 1, 4),
(8099, '6bx', 'GIC', 'E', 'K04', 2, 5),
(8100, '6bx', 'GIC', 'E', 'K04', 3, 1),
(8101, '6bx', 'KAF', 'TEW', 'PR5', 4, 6),
(8102, '6bx', 'KAF', 'SYT', 'PR5', 4, 5),
(8103, '6by', 'ABN', 'D', 'K04', 4, 2),
(8104, '6by', 'ABN', 'D', 'K04', 4, 6),
(8105, '6by', 'ABN', 'D', 'K04', 5, 2),
(8106, '6by', 'HÖM', 'D', 'K04', 1, 4),
(8107, '6by', 'HÖM', 'D', 'K12', 3, 1),
(8108, '6by', 'NED', 'E', 'K02', 1, 6),
(8109, '6by', 'NED', 'E', 'K04', 4, 5),
(8110, '6by', 'NED', 'E', 'K05', 5, 6),
(8111, '6by', 'KAF', 'TEW', 'PR5', 2, 5),
(8112, '6by', 'KAF', 'SYT', 'PR5', 2, 4),
(8113, '6c', 'SHB', 'AL', 'K05', 1, 2),
(8114, '6c', 'SHB', 'AL', 'K05', 2, 6),
(8115, '6c', 'SHB', 'AL', 'K05', 3, 3),
(8116, '6c', 'SHB', 'AL', 'K05', 4, 1),
(8117, '6c', 'SHB', 'AL', 'K05', 5, 3),
(8118, '6c', 'DUE', 'SBK', 'K05', 4, 2),
(8119, '6c', 'TAF', 'GG-AL', 'K05', 4, 6),
(8120, '6c', 'TAF', 'H-AL', 'K05', 2, 2),
(8121, '6c', 'FIO', 'AM', 'K05', 2, 1),
(8122, '6c', 'FIO', 'AM', 'K05', 3, 1),
(8123, '6c', 'FIO', 'AM', 'K05', 4, 5),
(8124, '6c', 'FIO', 'AM', 'K05', 5, 1),
(8125, '6c', 'SHM', 'ME', 'BIB', 1, 5),
(8126, '6c', 'SHM', 'ME', 'BIB', 5, 6),
(8127, '6c', 'CEG', 'BCH', 'NWL', 3, 4),
(8128, '6c', 'FIO', 'Ph', 'K05', 5, 5),
(8129, '6c', 'KAB', 'BSP', 'K05', 5, 4),
(8130, '6c', 'DUE', 'D', 'K05', 2, 4),
(8131, '6c', 'DUE', 'D', 'K05', 2, 5),
(8132, '6c', 'DUE', 'D', 'K03', 3, 5),
(8133, '6c', 'HEK', 'D', 'K05', 1, 1),
(8134, '6c', 'HEK', 'D', 'K05', 3, 2),
(8135, '6c', 'GIC', 'E', 'K05', 2, 3),
(8136, '6c', 'GIC', 'E', 'K05', 3, 6),
(8137, '6c', 'GIC', 'E', 'K05', 5, 2),
(8138, '6c', 'KAF', 'TEW', 'PR5', 1, 4),
(8139, '6c', 'KAF', 'SYT', 'PR5', 1, 3),
(8140, '9a', 'ABW', 'ITSS', 'K26', 3, 8),
(8141, '9b', 'ABW', 'ITSS', 'K14', 4, 7),
(8142, '9e', 'ABW', 'ITSS', 'K15', 3, 7),
(8143, '9bf', 'AIT', 'FFD', 'K14', 2, 4),
(8144, 'SUP', 'ABN', 'SU', '', 1, 2),
(8145, 'SUP', 'ABN', 'SU', '', 3, 6),
(8146, 'SUP', 'KAW', 'SU', '', 1, 3),
(8147, 'SUP', 'KAW', 'SU', '', 3, 5),
(8148, '6ad', 'ZHN', 'BSPK', 'BSPK', 1, 6),
(8149, '6ad', 'ZHN', 'BSPK', 'BSPK', 4, 3),
(8150, '6ad', 'ZHN', 'BSPK', 'BSPK', 4, 4),
(8151, '6cd', 'ZHN', 'BSPK', 'BSPK', 1, 6),
(8152, '6cd', 'ZHN', 'BSPK', 'BSPK', 4, 3),
(8153, '6cd', 'ZHN', 'BSPK', 'BSPK', 4, 4),
(8154, '6av', 'GJA', 'BSPM', 'BSPM', 1, 6),
(8155, '6av', 'GJA', 'BSPM', 'BSPM', 4, 3),
(8156, '6av', 'GJA', 'BSPM', 'BSPM', 4, 4),
(8157, '6cv', 'GJA', 'BSPM', 'BSPM', 1, 6),
(8158, '6cv', 'GJA', 'BSPM', 'BSPM', 4, 3),
(8159, '6cv', 'GJA', 'BSPM', 'BSPM', 4, 4),
(8160, '6bd', 'ZHN', 'BSPK', 'BSPK', 1, 2),
(8161, '6bd', 'ZHN', 'BSPK', 'BSPK', 3, 5),
(8162, '6bd', 'ZHN', 'BSPK', 'BSPK', 3, 6),
(8163, '6bv', 'GJA', 'BSPM', 'BSPM', 1, 2),
(8164, '6bv', 'GJA', 'BSPM', 'BSPM', 3, 5),
(8165, '6bv', 'GJA', 'BSPM', 'BSPM', 3, 6),
(8166, '3av', 'GJA', 'BSPM', 'BSPM', 3, 9),
(8167, '3av', 'GJA', 'BSPM', 'BSPM', 3, 10),
(8168, '3bv', 'GJA', 'BSPM', 'BSPM', 3, 9),
(8169, '3bv', 'GJA', 'BSPM', 'BSPM', 3, 10),
(8170, '3ad', 'ZHN', 'BSPK', 'BSPK', 3, 9),
(8171, '3ad', 'ZHN', 'BSPK', 'BSPK', 3, 10),
(8172, '3bd', 'ZHN', 'BSPK', 'BSPK', 1, 9),
(8173, '3bd', 'ZHN', 'BSPK', 'BSPK', 1, 10),
(8174, '8ad', 'ZHN', 'BSPK', 'BSPK', 4, 9),
(8175, '8ad', 'ZHN', 'BSPK', 'BSPK', 4, 10),
(8176, '8av', 'GJA', 'BSPM', 'BSPM', 4, 9),
(8177, '8av', 'GJA', 'BSPM', 'BSPM', 4, 10),
(8178, '8bv', 'GJA', 'BSPM', 'BSPM', 4, 9),
(8179, '8bv', 'GJA', 'BSPM', 'BSPM', 4, 10),
(8180, '8cd', 'ZHN', 'BSPK', 'BSPK', 4, 7),
(8181, '8cd', 'ZHN', 'BSPK', 'BSPK', 4, 8),
(8182, '8cv', 'GJA', 'BSPM', 'BSPM', 4, 7),
(8183, '8cv', 'GJA', 'BSPM', 'BSPM', 4, 8),
(8184, '7bd', 'ZHN', 'BSPK', 'BSPK', 1, 4),
(8185, '7bd', 'ZHN', 'BSPK', 'BSPK', 1, 5),
(8186, '7bd', 'ZHN', 'BSPK', 'K02', 3, 2),
(8187, '7bv', 'GJA', 'BSPM', 'BSPM', 1, 4),
(8188, '7bv', 'GJA', 'BSPM', 'BSPM', 1, 5),
(8189, '7bv', 'GJA', 'BSPM', 'BSPM', 3, 2),
(8190, 'SUP', 'FIO', 'SU', '', 2, 4),
(8191, 'SUP', 'FIO', 'SU', '', 3, 4),
(8192, '3a', 'STD', 'FFSYT', 'HLAB', 5, 10),
(8193, '3b', 'STD', 'FFSYT', 'HLAB', 5, 10),
(8194, '4a', 'STD', 'FFSYT', 'HLAB', 5, 10),
(8195, '4b', 'STD', 'FFSYT', 'HLAB', 5, 10),
(8196, '5a', 'STD', 'FFSYT', 'HLAB', 5, 10),
(8197, '5b', 'STD', 'FFSYT', 'HLAB', 5, 10),
(8198, '3a', 'KAF', 'FFSYT', 'SYTLAB', 5, 10),
(8199, '3b', 'KAF', 'FFSYT', 'SYTLAB', 5, 10),
(8200, '4a', 'KAF', 'FFSYT', 'SYTLAB', 5, 10),
(8201, '4b', 'KAF', 'FFSYT', 'SYTLAB', 5, 10),
(8202, '5a', 'KAF', 'FFSYT', 'SYTLAB', 5, 10),
(8203, '5b', 'KAF', 'FFSYT', 'SYTLAB', 5, 10),
(8204, '3a', 'STD', 'FFT', 'GYM', 5, 8),
(8205, '3a', 'STD', 'FFT', 'GYM', 5, 9),
(8206, '3b', 'STD', 'FFT', 'GYM', 5, 8),
(8207, '3b', 'STD', 'FFT', 'GYM', 5, 9),
(8208, '4a', 'STD', 'FFT', 'GYM', 5, 8),
(8209, '4a', 'STD', 'FFT', 'GYM', 5, 9),
(8210, '4b', 'STD', 'FFT', 'GYM', 5, 8),
(8211, '4b', 'STD', 'FFT', 'GYM', 5, 9),
(8212, '3a', 'NUF', 'CISCO', 'K23', 5, 8),
(8213, '3b', 'NUF', 'CISCO', 'K23', 5, 8),
(8214, '7ay', 'PEN', 'SBK', 'K03', 4, 4),
(8215, '7by', 'HÖM', 'SBK', 'K02', 3, 5),
(8216, '6b', 'ABN', 'SBK', 'K04', 3, 2),
(8217, '6a', 'ABN', 'SBK', 'K03', 2, 3),
(8218, '3ax', 'HEK', 'D', 'K23', 5, 3),
(8219, '3ay', 'HEK', 'D', 'K23', 3, 3),
(8220, '3ay', 'HEK', 'D', 'K23', 3, 4),
(8221, '3ay', 'HEK', 'D', 'K23', 5, 2),
(8222, '3ay', 'HEK', 'D', 'K23', 5, 1),
(8223, '2bx', 'HEK', 'D', 'K22', 2, 3),
(8224, '2bx', 'HEK', 'D', 'K22', 4, 3),
(8225, '2bx', 'HEK', 'D', 'K22', 4, 4),
(8226, '2bx', 'HEK', 'D', 'K22', 2, 4),
(8227, '2by', 'HEK', 'D', 'K22', 4, 5),
(8228, '2by', 'HEK', 'D', 'K22', 4, 6),
(8229, '2by', 'HEK', 'D', 'K22', 3, 8),
(8230, '2by', 'HEK', 'D', 'K22', 2, 1),
(8231, '3ax', 'HEK', 'D', 'K23', 5, 4),
(8232, '8bx', 'BAS', 'D', 'K12', 4, 5),
(8233, '8cc', 'SHM', 'D', 'K25', 2, 5),
(8234, '8ax', 'SHM', 'D', 'K26', 2, 4),
(8235, '9bf', 'ABW', 'CPS', 'PR1', 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_users`
--

CREATE TABLE `tb_infotainment_users` (
  `u_id` int(11) NOT NULL,
  `u_email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_pswd` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_nickname` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_register` datetime NOT NULL DEFAULT current_timestamp(),
  `u_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_infotainment_users`
--

INSERT INTO `tb_infotainment_users` (`u_id`, `u_email`, `u_pswd`, `u_nickname`, `u_register`, `u_role`) VALUES
(1, 'aldshe14@htl-shkoder.com', '$2y$10$JJbvQi9tniSEAVDG1U9Q2uywEQm4Jg04QjxW6wZPcYdhzalbBobeG', 'Aldo', '2019-10-04 00:00:00', 777),
(2, 'irebal14@htl-shkoder.com', '$2y$10$Nijyopk3WNaPqiI1krP1/eyyCFGb5IUjFyVxrLH4ylpKL6kqQ3VqC', 'Irena', '2019-10-04 00:00:00', 777);

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_weather`
--

CREATE TABLE `tb_infotainment_weather` (
  `w_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `zeit` time NOT NULL,
  `beschreibung` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temp` double NOT NULL,
  `temp_min` double NOT NULL,
  `temp_max` double NOT NULL,
  `humidity` int(3) NOT NULL,
  `sunrise` time NOT NULL,
  `sunset` time NOT NULL,
  `wind_speed` double NOT NULL,
  `wind_deg` int(11) NOT NULL,
  `icon` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_weather_info`
--

CREATE TABLE `tb_infotainment_weather_info` (
  `appid` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_infotainment_website_posts`
--

CREATE TABLE `tb_infotainment_website_posts` (
  `w_id` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_infotainment_display`
--
ALTER TABLE `tb_infotainment_display`
  ADD PRIMARY KEY (`d_id`),
  ADD KEY `fk_display_layout` (`layout_id`),
  ADD KEY `fk_display_location` (`location_id`);

--
-- Indexes for table `tb_infotainment_fehlendelehrer`
--
ALTER TABLE `tb_infotainment_fehlendelehrer`
  ADD PRIMARY KEY (`u_id`,`woche`);

--
-- Indexes for table `tb_infotainment_layout`
--
ALTER TABLE `tb_infotainment_layout`
  ADD PRIMARY KEY (`l_id`);

--
-- Indexes for table `tb_infotainment_layout_sections`
--
ALTER TABLE `tb_infotainment_layout_sections`
  ADD PRIMARY KEY (`l_id`),
  ADD KEY `fk_layout_sections_layout` (`layout_id`);

--
-- Indexes for table `tb_infotainment_location`
--
ALTER TABLE `tb_infotainment_location`
  ADD PRIMARY KEY (`l_id`);

--
-- Indexes for table `tb_infotainment_roles`
--
ALTER TABLE `tb_infotainment_roles`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `tb_infotainment_supplieren`
--
ALTER TABLE `tb_infotainment_supplieren`
  ADD PRIMARY KEY (`u_id`,`woche`);

--
-- Indexes for table `tb_infotainment_unterricht`
--
ALTER TABLE `tb_infotainment_unterricht`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `tb_infotainment_users`
--
ALTER TABLE `tb_infotainment_users`
  ADD PRIMARY KEY (`u_id`),
  ADD KEY `fk_users_roles` (`u_role`);

--
-- Indexes for table `tb_infotainment_weather`
--
ALTER TABLE `tb_infotainment_weather`
  ADD PRIMARY KEY (`w_id`);

--
-- Indexes for table `tb_infotainment_website_posts`
--
ALTER TABLE `tb_infotainment_website_posts`
  ADD PRIMARY KEY (`w_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_infotainment_display`
--
ALTER TABLE `tb_infotainment_display`
  MODIFY `d_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_infotainment_layout`
--
ALTER TABLE `tb_infotainment_layout`
  MODIFY `l_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_infotainment_layout_sections`
--
ALTER TABLE `tb_infotainment_layout_sections`
  MODIFY `l_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_infotainment_location`
--
ALTER TABLE `tb_infotainment_location`
  MODIFY `l_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_infotainment_unterricht`
--
ALTER TABLE `tb_infotainment_unterricht`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9185;

--
-- AUTO_INCREMENT for table `tb_infotainment_users`
--
ALTER TABLE `tb_infotainment_users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_infotainment_weather`
--
ALTER TABLE `tb_infotainment_weather`
  MODIFY `w_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_infotainment_website_posts`
--
ALTER TABLE `tb_infotainment_website_posts`
  MODIFY `w_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_infotainment_display`
--
ALTER TABLE `tb_infotainment_display`
  ADD CONSTRAINT `fk_display_layout` FOREIGN KEY (`layout_id`) REFERENCES `tb_infotainment_layout` (`l_id`),
  ADD CONSTRAINT `fk_display_location` FOREIGN KEY (`location_id`) REFERENCES `tb_infotainment_location` (`l_id`);

--
-- Constraints for table `tb_infotainment_fehlendelehrer`
--
ALTER TABLE `tb_infotainment_fehlendelehrer`
  ADD CONSTRAINT `fk_fehlendeLehrer_unterricht` FOREIGN KEY (`u_id`) REFERENCES `tb_infotainment_unterricht` (`u_id`);

--
-- Constraints for table `tb_infotainment_layout_sections`
--
ALTER TABLE `tb_infotainment_layout_sections`
  ADD CONSTRAINT `fk_layout_sections_layout` FOREIGN KEY (`layout_id`) REFERENCES `tb_infotainment_layout` (`l_id`);

--
-- Constraints for table `tb_infotainment_supplieren`
--
ALTER TABLE `tb_infotainment_supplieren`
  ADD CONSTRAINT `fk_supplieren_unterricht` FOREIGN KEY (`u_id`) REFERENCES `tb_infotainment_unterricht` (`u_id`);

--
-- Constraints for table `tb_infotainment_users`
--
ALTER TABLE `tb_infotainment_users`
  ADD CONSTRAINT `fk_users_roles` FOREIGN KEY (`u_role`) REFERENCES `tb_infotainment_roles` (`r_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
