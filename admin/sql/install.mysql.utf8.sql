CREATE TABLE IF NOT EXISTS `#__simpledownload_hits` (
	`id`				int(11) unsigned NOT NULL auto_increment,
	`url`				varchar(255)     NOT NULL default '',
	`referrer`			varchar(255)     NOT NULL default '',
	`filepath`			varchar(255)     NOT NULL default '',
	`downloadstatus`	varchar(100)     NOT NULL default '',
	`userid`			int(11) unsigned NOT NULL default 0,
	`name`				varchar(255)     NOT NULL default '',
	`username`			varchar(255)     NOT NULL default '',
	`ip`       varchar(15)      NOT NULL default '',
	`hit_date` datetime         NOT NULL default '0000-00-00 00:00:00',
	PRIMARY KEY  (`id`),
	KEY idx_url				(`url`     ),
	KEY idx_referrer		(`referrer`),
	KEY idx_filepath		(`filepath`),
	KEY idx_downloadstatus	(`downloadstatus`),
	KEY idx_userid			(`userid`  ),
	KEY idx_name			(`name`    ),
	KEY idx_username		(`username`),
	KEY idx_ip				(`ip`      ),
	KEY idx_hit_date		(`hit_date`)
) TYPE=MyISAM;