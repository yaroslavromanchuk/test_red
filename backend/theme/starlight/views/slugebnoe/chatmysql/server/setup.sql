--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `melchat_actions`;
CREATE TABLE `melchat_actions` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `for` int(11) unsigned NOT NULL,
  `action` varchar(64) collate utf8_unicode_ci NOT NULL,
  `params` text collate utf8_unicode_ci NOT NULL,
  `timeout` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

DROP TABLE IF EXISTS `melchat_bans`;
CREATE TABLE `melchat_bans` (
  `nickname` varchar(32) collate utf8_unicode_ci NOT NULL,
  `ip` varchar(15) collate utf8_unicode_ci NOT NULL,
  UNIQUE KEY `nickname` (`nickname`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `melchat_messages`;
CREATE TABLE `melchat_messages` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `time` int(11) NOT NULL,
  `from` varchar(32) collate utf8_unicode_ci NOT NULL,
  `to` varchar(32) collate utf8_unicode_ci NOT NULL,
  `message` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `melchat_settings`;
CREATE TABLE `melchat_settings` (
  `var` varchar(32) collate utf8_unicode_ci NOT NULL,
  `value` text collate utf8_unicode_ci NOT NULL,
  UNIQUE KEY `var` (`var`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `melchat_users`;
CREATE TABLE `melchat_users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `pass` varchar(32) collate utf8_unicode_ci NOT NULL,
  `nickname` varchar(32) collate utf8_unicode_ci NOT NULL,
  `password` varchar(32) collate utf8_unicode_ci NOT NULL,
  `access` int(2) NOT NULL,
  `timeout` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `status_text` varchar(50) collate utf8_unicode_ci NOT NULL,
  `ip` varchar(15) collate utf8_unicode_ci NOT NULL,
  `agent` varchar(128) collate utf8_unicode_ci NOT NULL,
  `conn_time` int(11) unsigned NOT NULL,
  `reg_time` int(11) unsigned NOT NULL,
  `avatar` varchar(16) collate utf8_unicode_ci NOT NULL,
  `email` varchar(64) collate utf8_unicode_ci NOT NULL,
  `reputation` int(11) NOT NULL,
  `title` varchar(64) collate utf8_unicode_ci NOT NULL,
  `silence` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
