<?php

class DBService {
    var $handler;
    function __construct($host,$user,$pass,$db)
    {
        $this->handler = @mysqli_connect($host,$user,$pass,$db);
        //var_dump($this->handler);
        if(!$this->handler)
        {
            $this->Error('connect');
            return false;
        }
        if(!@mysqli_select_db($this->handler,$db))
        {
            $this->Error('selectDB');
            return false;

        }
        @mysqli_query ($this->handler,'SET NAMES UTF8');
        @mysqli_query ($this->handler,'SET COLLATION_CONNECTION=UTF8_GENERAL_CI');
        return true;
    }
    function Error($type, $query = '')
    {
        global $pref;
        $my_error = mysqli_error($this->handler);
        $my_errno = mysqli_errno($this->handler);

        if($my_errno != 1091 && $my_errno != 1050 && $my_errno != 1062)
        {
            echo "MySQL Error! " . "\nQuery\n" .addslashes($query) . "\n\nError\n" . addslashes($my_error) . "\n\nPage\nhttp://$_SERVER[HTTP_HOST]" . "$_SERVER[REQUEST_URI]\n\nIP: $_SERVER[REMOTE_ADDR]";
        }
        return true;

    }

    /**
     * @param $query
     *
     * @return DBResult|void
     */
    function Query( $query ) {
        $res = mysqli_query($this->handler,$query);
        if(!$res)
        {
            $this->Error('query',$query);
            return null;
        }

        return new DBResult($res);
    }

    function RowQuery( $query ) {
        $res = mysqli_fetch_object(mysqli_query($this->handler, $query));
        return $res;
    }

    function ArrQuery( $query ) {
        $res = mysqli_fetch_array(mysqli_query($this->handler,$query));
        return $res;
    }

    function RealEscape( $text ) {
        return mysqli_real_escape_string($this->handler,$text);
    }
    function Escape($text){
        return mysqli_escape_string($this->handler,$text);
    }

    function InsertedID() {
        return mysqli_insert_id($this->handler);
    }

    function BeginTransaction() {
        mysqli_autocommit($this->handler,false);
        mysqli_begin_transaction($this->handler);
    }

    function Commit() {
        mysqli_commit($this->handler);
    }

    function Rollback() {
        mysqli_rollback($this->handler);
    }
}

class DBResult {
    private $result;

    function __construct($result)
    {
        $this->result = $result;
    }

    function FetchArray()
    {
        return mysqli_fetch_array($this->result);
    }
    function dataSeek($offset) {
        return mysqli_data_seek($this->result,$offset);
    }

    function fetchrow_assoc() {
        return mysqli_fetch_assoc($this->result);
    }

    function FetchAssocArray()
    {
        return mysqli_fetch_assoc($this->result);
    }

    function FetchObject()
    {
        return mysqli_fetch_object($this->result);
    }

    function FetchRow()
    {
        return mysqli_fetch_object($this->result);
    }

    function NumRows()
    {
        return mysqli_num_rows($this->result);
    }

    function mysql_version() {
        return  mysqli_get_server_info($this->result);
    }

    function NumFields()
    {
        return mysqli_num_fields($this->result);
    }

    function FieldName($i)
    {
        return mysqli_fetch_field_direct($this->result, $i);
    }

    function FetchAll($type=MYSQLI_ASSOC)
    {
        return mysqli_fetch_all($this->result,$type);
    }

    function close()
    {
        mysqli_free_result($this->result);
        unset($this);
    }
}