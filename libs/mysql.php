<?php

class MySQL{

    public function connect($host, $userName, $password, $dbName) {
        $dbh = mysql_connect(
                    $host,
                    $userName,
                    $password);
        $dbConnect = mysql_select_db($dbName, $dbh);
        if (!$dbConnect) {
            throw new Exception('Failed to select base' . $dbName . ': ' . mysql_error());
        }
        return $dbh;
    }

    public function exec() {
        parent::exec();
        $res = mysql_query($this->query, $this->dbh);

        return (!empty($this->select)) ? mysql_fetch_array($res) : $res;
    }
}
