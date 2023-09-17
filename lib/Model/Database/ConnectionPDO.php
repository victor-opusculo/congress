<?php
namespace Congress\Lib\Model\Database;

require_once __DIR__ . '/../../../vendor/autoload.php';

use \Atk4\Dsql\Connection as DConn;
use Exception;

abstract class ConnectionPDO
{
    private function __construct() { }

    public static ?DConn $conn;

    public static function create() : DConn
    {
        $configs = self::getDatabaseConfig(); 
		
		$serverName = $configs['servername'];
		$userName = $configs['username'];
		$password = $configs['password'];
		$dbname = $configs['dbname'];

        $conn = null;
        try
        {
		    $conn = DConn::connect("mysql:host=$serverName;dbname=$dbname;charset=utf8mb4", $userName, $password, 
            [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);
        }
        catch (\Exception $e)
        {
            die("Connection failed! " . $e->getMessage());
        }

        //$conn->expr("SET NAMES 'utf8';");
		//$conn->set_charset('utf8mb4');
		
        self::$conn = $conn;
		return self::$conn;
    }

    public static function get() : DConn
    {
        try
        {
            if (isset(self::$conn))
            {
                self::$conn->connection()->executeQuery('SELECT 1');
                return self::$conn;
            }
            else
                throw new Exception('');
        }
        catch (\Exception $e)
        {
            return self::create();
        }
    }

    public static function close()
    {
        if (isset(self::$conn) && self::$conn->connection()) self::$conn->connection()->close();
    }

    public static function getCryptoKey() : string
	{
		return !empty(getenv("CRYPTO_KEY")) ? getenv("CRYPTO_KEY") : self::getDatabaseConfig()['crypto'];
	}

    private static function getDatabaseConfig() : array
	{
		$configs = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/congress_config.ini", true);
		putenv("CRYPTO_KEY=" . $configs['database']['crypto']);
		return $configs['database'];
	}

    public static function isId($param) : bool
    {
        return isset($param) && is_numeric($param) && $param > 0;
    }

}