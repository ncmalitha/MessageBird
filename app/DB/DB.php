<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/26/2017
 * Time: 6:06 PM
 */

namespace DB;


class DB
{

    private static $instance = null;
    public $connection = null;

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new DB();
            self::$instance->connect();
        }

        return self::$instance;
    }


    private function __construct()
    {

    }

    private function __clone()
    {
        return false;
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    /**
     * Connect to database
     */
    public function connect(){

        if($this->connection == null) {

            $configs = include dirname(__FILE__).'/../../configs/db.php';
            $dsn     = 'mysql:host=' . $configs['host'] . ';dbname=' . $configs['dbname'];
            $options = array(
                \PDO::ATTR_PERSISTENT         => true,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            );

            try{
                $this->connection = new \PDO($dsn, $configs['username'], $configs['password'], $options);
            }
            catch(\PDOException $e){
                // Log any errors
            }
        }

    }

    /**
     * Disconnect
     */
    public function disconnect()
    {
        if ($this->connection) {
            $this->connection = null;
        }
    }

    /**
     * @param $table
     * @param array|NULL $where
     * @param null $order
     * @param null $group
     * @return mixed
     */
    public function getAll($table, array $where = NULL, $order = NULL, $group = NULL)
    {
        $statement = implode(' ', array(
            "SELECT * FROM {$table}",
            (!empty($where) ? "WHERE " . implode(" AND ", array_map(function($name){
                    return "{$name} = :{$name}";
                }, array_keys($where))) : ""),
            !empty($group) ? "GROUP BY {$group}" : "",
            !empty($order) ? "ORDER BY {$order}" : ""
        ));
        $query = $this->connection->prepare($statement);
        $query->execute($where);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $table
     * @param array|NULL $where
     * @param null $order
     * @param null $group
     * @return mixed
     */
    public function first($table, array $where = NULL, $order = NULL, $group = NULL)
    {
        $statement = implode(' ', array(
            "SELECT * FROM {$table}",
            (!empty($where) ? "WHERE " . implode(" AND ", array_map(function($name){
                    return "{$name} = :{$name}";
                }, array_keys($where))) : ""),
            !empty($group) ? "GROUP BY {$group}" : "",
            !empty($order) ? "ORDER BY {$order}" : ""
        ));
        $query = $this->connection->prepare($statement);
        $query->execute($where);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $table
     * @param array $values
     * @return mixed
     */
    public function insert($table, $values = array())
    {
        $statement = sprintf("INSERT INTO {$table} (%s) VALUES (%s)", implode(', ', array_map(function($name) {
            return "{$name}";
        }, array_keys($values))), implode(', ', array_map(function($name) {
                return ":{$name}";
            }, array_keys($values)))
        );

        $query = $this->connection->prepare($statement);
        $query->execute($values);
        return $this->connection->lastInsertId();
    }

    /**
     * @param $table
     * @param array $values
     * @param array $where
     * @return mixed
     */
    public function update($table, $values = array(), $where = array())
    {
        $statement = sprintf("UPDATE {$table} SET %s %s", implode(', ', array_map(function($name) {
            return "{$name} = :{$name}";
        }, array_keys($values))), !empty($where) ? "WHERE " . implode(' AND ', array_map(function($name) {
                return "{$name} = :{$name}";
            }, array_keys($where))) : "");
        $query = $this->connection->prepare($statement);
        $query->execute(array_merge($values, $where));
        return $query->rowCount();
    }

    /**
     * @param $table
     * @param array $where
     * @return mixed
     */
    public function delete($table, $where = array())
    {
        $statement = sprintf("DELETE FROM {$table} WHERE %s", implode(" AND ", array_map(function($name) {
            return "{$name} = :{$name}";
        }, array_keys($where))));
        $query = $this->connection->prepare($statement);
        $query->execute($where);
        return $query->rowCount();
    }


}