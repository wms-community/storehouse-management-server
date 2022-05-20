-- 玉桂库房管理系统
-- 数据库安装文件

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__class`
--

CREATE TABLE `__PREFIX__class` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `warehouse` int(11) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `__PREFIX__class`
--

INSERT INTO `__PREFIX__class` (`id`, `name`, `warehouse`, `user`) VALUES
(1, 'default', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__config`
--

CREATE TABLE `__PREFIX__config` (
  `id` int(11) NOT NULL,
  `sitename` text NOT NULL,
  `siteurl` text NOT NULL,
  `sitetail` text NOT NULL,
  `sitedes` text NOT NULL,
  `sitekey` text NOT NULL,
  `beian` text NOT NULL,
  `support` text NOT NULL,
  `repo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `__PREFIX__config`
--

INSERT INTO `__PREFIX__config` (`id`, `sitename`, `siteurl`, `sitetail`, `sitedes`, `sitekey`, `beian`, `support`, `repo`) VALUES
(1, '玉桂库房管理系统', '', '好用的库房管理系统', '库房管理，库房管理系统，简单易用、功能强大的控制面板。管理您的库房，我们是专业的!', '库房管理,库房管理系统,玉桂库房管理系统', '', 'support@mojy.xyz', 'https://github.com/wms-community/storehouse-management-server');

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__record`
--

CREATE TABLE `__PREFIX__record` (
  `id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `goods_num` text NOT NULL,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `num` int(11) NOT NULL,
  `date` date NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `__PREFIX__record`
--

INSERT INTO `__PREFIX__record` (`id`, `goods_id`, `goods_num`, `name`, `type`, `num`, `date`, `user`) VALUES
(1, 1, '1', 'default', 'default', 1, '2012-01-01', 1);

-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__user`
--

CREATE TABLE `__PREFIX__user` (
  `id` int(11) NOT NULL,
  `username` tinytext NOT NULL,
  `password` tinytext NOT NULL,
  `nickname` tinytext NOT NULL,
  `vxid` char(50) NOT NULL,
  `mail` tinytext NOT NULL,
  `phone` tinytext NOT NULL,
  `admin` int(11) NOT NULL,
  `regdate` date NOT NULL,
  `logdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `__PREFIX__user`
--

INSERT INTO `kfgl_user` (`id`, `username`, `password`, `nickname`, `vxid`, `mail`, `phone`, `admin`, `regdate`, `logdate`) VALUES
(1, 'admin', '', 'admin', '', 'support@mojy.xyz', '', 1, '2012-01-01', '2012-01-01');
-- --------------------------------------------------------

--
-- 表的结构 `__PREFIX__warehouse`
--

CREATE TABLE `__PREFIX__warehouse` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `__PREFIX__warehouse`
--

INSERT INTO `__PREFIX__warehouse` (`id`, `name`, `user`) VALUES
(1, 'default', 1);

--
-- 转储表的索引
--

--
-- 表的索引 `__PREFIX__class`
--
ALTER TABLE `__PREFIX__class`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `user` (`user`),
  ADD UNIQUE KEY `warehouse` (`warehouse`);

--
-- 表的索引 `__PREFIX__config`
--
ALTER TABLE `__PREFIX__config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- 表的索引 `__PREFIX__record`
--
ALTER TABLE `__PREFIX__record`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `goods_id` (`goods_id`),
  ADD UNIQUE KEY `user` (`user`);

--
-- 表的索引 `__PREFIX__user`
--
ALTER TABLE `__PREFIX__user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- 表的索引 `__PREFIX__warehouse`
--
ALTER TABLE `__PREFIX__warehouse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `user` (`user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
