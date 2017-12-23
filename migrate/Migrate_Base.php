<?php

require_once (CHILD_THEME_PATH.'/migrate/DBService.php');
abstract class Migrate_Base
{
    const ALIEN_HOST = '160.153.128.24';
    const ALIEN_USER = 'seafarersj';
    const ALIEN_PASS = 'mG_kBGf;GgX+';
    const ALIEN_DB   = 'seafarersj_db';
    const ALiEN_PREFIX = '';

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