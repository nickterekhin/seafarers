<?php

require_once (CHILD_THEME_PATH.'/migrate/DBService.php');
require_once (CHILD_THEME_PATH.'/migrate/init.php');
abstract class Migrate_Base
{
    const ALIEN_HOST = HOST;
    const ALIEN_USER = USER;
    const ALIEN_PASS = PASS;
    const ALIEN_DB   = DB;
    const ALiEN_PREFIX = PREFIX;

    protected $alien_db_service;
    protected $db;

    /**
     * Migrate_Base constructor.
     */
    protected function __construct()
    {
        global $db;
        $this->db=$db;
        $this->alien_db_service = new DBService(self::ALIEN_HOST,self::ALIEN_USER,self::ALIEN_PASS,self::ALIEN_DB);
    }


    /*
     $config['dbhost'] = "localhost";
$config['dbuser'] = "seafarersj";
$config['dbpass'] = "mG_kBGf;GgX+";
$config['dbname'] = "seafarers_job";
$config['dbprefix'] = "u";*/
}