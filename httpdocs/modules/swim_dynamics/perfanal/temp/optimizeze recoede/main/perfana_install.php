<?php
function perfanal_install()
{
switch ($GLOBALS['db_type']) {
    case 'mysql':
    case 'mysqli':
    db_query("CREATE TABLE `{perfanal_db}` (`id` int(11) NOT NULL auto_increment, `fiter` text NOT NULL,  `display_name` text NOT NULL,  `enabled` tinyint(3) NOT NULL default '0',PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;");
      break;
}
}
?>