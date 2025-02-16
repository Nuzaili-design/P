<?php
class SQLConnection {
    private static $conn;

    public static function getConnection() {
        if (!self::$conn) {
            try {
                $servername = "localhost";
                $dbname = "qrparking";
                $username = "root";
                $password = "";

                self::$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Success message commented out
                // echo "✅ Database connected successfully!";
            } catch (PDOException $e) {
                die("❌ Connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
 
// Test the connection
SQLConnection::getConnection();
?>
