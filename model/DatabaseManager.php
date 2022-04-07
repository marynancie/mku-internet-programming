<?php

require_once __DIR__.'/../controller/config.php';
/**
 * Class DB
 */
class DB
{
    // Static Class DB Connection Variables (for write and read)
    /**
     * @var
     */
    private static $writeDBConnection;
    /**
     * @var
     */
    private static $readDBConnection;
    //Database constants
    /**
     * @var string
     */
    private static string $host;
    /**
     * @var string
     */
    private static string $user;
    /**
     * @var string
     */
    private static string $password;
    /**
     * @var string
     */
    private static string$database;
    /**
     * @var
     */
    private static $conn;
    /**
     * @var string
     */
    private static string $port;
    /**
     * @var int
     */
    public int $lastInsertId;
    /**
     * @var int
     */
    public int $affectedRows = 0;
    /**
     * @var string
     */
    public string $last_error = 'Not Initialized';

    /**
     * DB constructor.
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @param string $port
     */
    public function __construct($host = databaseHost, $username = databaseUsername, $password = databasePassword, $database = databaseName, $port = databasePort)
    {

        self::$host = $host;
        self::$user = $username;
        self::$password = $password;
        self::$database = $database;
        self::$port = $port;
    }

    /**
     * @return Mysqli
     */
    public function getConnection()
    {

        return self::connectWriteDB();
    }

    // Static Class Method to connect to DB to perform read only actions (read replicas)
    // handle the PDOException in the controller class to output a json api error

    /**
     * @return Mysqli
     */
    public static function connectWriteDB()
    {
        if (self::$writeDBConnection === null) {
            self::$writeDBConnection = new Mysqli(self::$host, self::$user, self::$password, self::$database, self::$port);
        }

        return self::$writeDBConnection;
    }

    /**
     * @param $query
     * @return array|bool
     */
    function runBaseQuery($query)
    {
        $result = mysqli_query(self::connectReadDB(), $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $resultset[] = $row;
        }
        if (!empty($resultset))
            return $resultset;
        else
            return false;

    }

    //MYSQLI METHODS

    /**
     * @return Mysqli
     */
    public static function connectReadDB()
    {
        if (self::$readDBConnection === null) {

            self::$readDBConnection = new Mysqli(self::$host, self::$user, self::$password, self::$database, self::$port);
        }

        return self::$readDBConnection;
    }

    /**
     * @param string $query
     * @param string $param_type
     * @param array $param_value_array
     * @return array|bool
     */
    function runQuery(string $query, string $param_type, array $param_value_array)
    {

        if (!$sql = self::connectReadDB()->prepare($query)) {
            $this->last_error = (self::connectWriteDB())->error;
            return false;
        }

        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
        $result = $sql->get_result();
        if (isset($result->num_rows)) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $resultset[] = $row;
                }
            }

            if (!empty($resultset)) {
                return $resultset;
            } else {
                return false;
            }
        } else return false;


    }

    /**
     * @param $sql
     * @param string $param_type
     * @param array $param_value_array
     */
    function bindQueryParams( $sql, string $param_type, array $param_value_array): void
    {
        $param_value_reference[] = &$param_type;
        for ($i = 0; $i < count($param_value_array); $i++) {
            $param_value_reference[] = &$param_value_array[$i];
        }

        call_user_func_array(array(
            $sql,
            'bind_param'
        ), $param_value_reference);

    }

    /**
     * @param string $query
     * @param string $param_type
     * @param array $param_value_array
     * @return bool
     */
    function insert(string $query, string $param_type, array $param_value_array): bool
    {

        if (!$sql = self::connectWriteDB()->prepare($query)) {
            $this->last_error = (self::connectWriteDB())->error;
            return false;
        }

        $this->bindQueryParams($sql, $param_type, $param_value_array);

        $sql->execute();
        if ((int)$sql->affected_rows > 0) {
            $this->lastInsertId = $sql->insert_id;
            return true;
        } else
            return false;
    }

    /**
     * @param string $query
     * @param string $param_type
     * @param array $param_value_array
     * @return bool
     */
    function update(string $query, string $param_type, array $param_value_array): bool
    {

        if (!$sql = self::connectWriteDB()->prepare($query)) {
            $this->last_error = (self::connectWriteDB())->error;
            return false;
        }
        $this->bindQueryParams($sql, $param_type, $param_value_array);

        $sql->execute();

        if ((int)$sql->affected_rows > 0) {
            $this->affectedRows = $sql->affected_rows;
            return true;
        } else
            return false;

    }

    /**
     * @param $query
     * @param $param_type
     * @param $param_value_array
     * @return bool
     */
    function delete(string $query, string $param_type, array $param_value_array): bool
    {
        if (!$sql = self::connectWriteDB()->prepare($query)) {
            $this->last_error = (self::connectWriteDB())->error;
            return false;
        }

        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
        if ((int)$sql->affected_rows > 0) {
            $this->affectedRows = $sql->affected_rows;
            return true;
        } else
            return false;

    }

}
