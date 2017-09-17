<?php

class PostgreSql{

    
    public function connect($host, $userName, $password, $dbName) {
        $dbh = pg_connect(
            "host=".$host." dbname=".$dbName." user=".$userName." password=".$password
        );
        if (!isset($dbh)) {
            throw new Exception(pg_last_error());
        }

        return $dbh;
    }

    public function exec() {
        parent::exec();
        $res = pg_query($this->dbh, $this->query);

        return (!empty($this->select)) ? pg_fetch_all($res) : $res;
    }

}
