
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


--
-- Database: `mysqltcs`
--

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `test1` (
  `id` int(11) NOT NULL,
  `value` varchar(50) NOT NULL,
  `value2` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `test1`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `test1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

