<?php
class Sql
{
    public $link;

    public function __construct()
    {
        $DbConfig = [
            'host' => 'localhost',
            'port' => 3306,
            'user' => 'dosbot',
            'pwd' => 'xxx',
            'name' => 'dosbot',
            'long' => false,
        ];
        if (empty($DbConfig['port'])) $DbConfig['port'] = 3306;
        $this->link = mysqli_connect($DbConfig['host'], $DbConfig['user'], $DbConfig['pwd'], $DbConfig['name'], $DbConfig['port']);
        for ($x = 0; !$this->link; $x++) {
            if ($x > 3) exit(mysqli_connect_error());
            $this->link = mysqli_connect($DbConfig['host'], $DbConfig['user'], $DbConfig['pwd'], $DbConfig['name'], $DbConfig['port']);
        }
    }

    //链接数据库
    public function conn()
    {
        $this->query('set names utf8');
    }

    public function fetch($q)
    {
        return mysqli_fetch_assoc($q);
    }

    public function count($q)
    {
        $result = mysqli_query($this->link, $q);
        $count = mysqli_fetch_array($result);
        return $count[0];
    }

    public function query($q)
    {
        return mysqli_query($this->link, $q);
    }

    public function escape($str)
    {
        return mysqli_real_escape_string($this->link, $str);
    }

    public function affected()
    {
        return mysqli_affected_rows($this->link);
    }


    //查询数据
    public function get_row($sql)
    {
        $res = $this->query($sql);
        if (!$res) return false;
        return mysqli_fetch_assoc($res);
    }


    //查询全部数据
    public function getAll($sql)
    {
        $res = $this->query($sql);
        while ($row =  mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
        return $data;
    }

    //报错信息
    public function error()
    {
        return mysqli_error($this->link);
    }

    //关闭数据库链接
    public function __destruct()
    {
        return mysqli_close($this->link);
    }
}
