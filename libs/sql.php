<?php

abstract class Sql
{
    protected $db = null;
    protected $driver;
    protected $host;
    protected $userName;
    protected $password;
    protected $dbName;
    protected $select;
    protected $from;
    protected $where = "";
    protected $dbh;
    protected $update;
    protected $query;
    protected $insert;

    public function __construct($driver, $host, $userName, $password, $dbName){
        $this->driver = $driver;
        $this->host = $host;
        $this->userName = $userName;
        $this->password = $password;
        $this->dbName = $dbName;
        if ($this->driver == 'mysql') {
            $this->db = new Mysql;
        } else {
            $this->db = new PostgreSql;
        }

        $this->dbh = $this->db->connect($this->host, $this->userName, $this->password, $this->dbName);
    }

    public function select($str){
        $this->select = $str;
        return $this;
    }

    public function from($str){
        $this->from = $str;
        return $this;
    }

    public function where($str){
        $this->where = $str;
        return $this;
    }

    public function delete(){
        $this->delete = true;
        return $this;
    }

    public function insert($arrayFields, $arrayValues) {
        $fields = implode(',', $arrayFields);
        $values = "'" . implode("','", $arrayValues) . "'";
        $this->insert = '(' . $fields . ') values (' . $values . ')';
        return $this;
    }

    public function update($arrayFields, $arrayValues) {
        $fields = "";
        for ($i = 0; $i < count($arrayFields); $i++) {
            if (strlen($fields) != 0) {
                $fields .= ',';
            }
            $fields .= $arrayFields[$i] . " = '" . $arrayValues[$i] . "'";
        }
        $this->update = $fields;
        return $this;
    }

    public function exec(){
        if (empty($this->from)) {
            throw new Exception("not set");
        }
        if ($this->delete) {
            $this->query = "delete from {$this->from}";
            return $this->result;
        } elseif (!empty($this->insert)) {
            $this->query = "insert into {$this->from} $this->insert";
        } elseif (!empty($this->update)) {
            $this->query = "update  {$this->from} set $this->update";
            $this->query .= (!empty($this->where)) ? " where {$this->where};" : '';
        } else {
            if (empty($this->select)) {
                throw new Exception("not select set");
            }
            $this->query = "select {$this->select} from {$this->from}";
            $this->query .= (!empty($this->where)) ? " where {$this->where};" : '';
        }
    }

    public function getQuery(){
        return $this->query;
    }
}
