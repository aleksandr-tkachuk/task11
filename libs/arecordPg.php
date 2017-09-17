<?php

class ArecordPg extends Sql {

    protected $attributes = [];

    public function __construct() {
        parent::__construct("postgre", HOST, USER_POSTGRE, PASSWORD_POSTGRE, DBNAME_PG);
        $this->getAttributes();
    }

    public function __get($name) {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        } else {
            return null;
        }
    }

    public function __set($name, $value) {        
        $this->attributes[$name] = $value;
    }

    private function getAttributes() {
        $res = pg_query($this->dbh,"select column_name from INFORMATION_SCHEMA.COLUMNS where table_name = '".$this->getTableName()."' ");
        while ($row = pg_fetch_array($res)) {
        $this->attributes[$row[0]] = null;
        }
    }

    public function findAll($where = '', $order = '') {
        $result = $this->
                select('*')->
                from($this->getTableName())->
                where($where)->
                order($order)->
                exec();
        return $result;
    }

    public function findById() {
        $result = $this->
                select('*')->
                from($this->getTableName())->
                where(" id = " . $this->id)->
                exec();
        return $result;
    }

    public function save() {
        if ($this->id == NULL) {
            return $this->add();
        } else {
            return $this->edit();
        }
    }

    public function exec() {
        parent::exec();
        $res = pg_query($this->dbh, $this->query);

        return (!empty($this->select)) ? pg_fetch_all($res) : $res;
    }


    private function add() {
        $fields = array_keys($this->attributes);
        $values = array_values($this->attributes);
        //исключили поле id в таблице
        foreach($fields as $key=>$value){
            if ($value =='id'){
                $id = $key;
                continue;
            }
        }
        unset($fields[$id]);
        unset($values[$id]);

/*        $fields = array_map(function($elm){
            return "'".$elm."'";
        }, $fields);*/

        $result = $this->
        insert($fields, $values)->
        from($this->getTableName())->
        exec();

        echo $this->getQuery();
        return $result;
/*
        $sql = $sql . " ($fields) values ($values)";
        return pg_query($this->dbh,$sql);*/
    }


    private function edit() {
        $sql = "update " . $this->getTableName() . " set {fields} where id = " . $this->id;

        $fields = "";

        foreach ($this->attributes as $attr => $val) {
            if($attr != "dbh") {
                if ($val !== null) {
                    if (strlen($fields) != 0) {
                        $fields .= ", ";
                    }
                    $fields .= $attr . " = '" . $val . "'";
                }
            }
        }

        $sql = str_replace("{fields}", $fields, $sql);

        return pg_query($this->dbh,$sql);
    }

    function delete()
    {
        if ($this->id !=''){
            $sql = 'delete from '. $this->getTableName() .' where id='.$this->id ;
            return pg_query($this->dbh,$sql);
        }
        else{
            return false;
        }
    }

}
