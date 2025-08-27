<?php

namespace App;

use mysqli;

class Database {
    private static ?mysqli $connection = null;

    /**
     * Infer mysqli parameter types from the given values.
     *
     * @param array $params
     * @return string
     */
    private static function detectTypes(array $params): string {
        return implode('', array_map(function ($param) {
            return match (true) {
                is_int($param)   => 'i',
                is_float($param) => 'd',
                is_null($param)  => 's', // treat null as string
                is_string($param)=> 's',
                default          => 'b',
            };
        }, $params));
    }

    /**
     * Establishes and returns a single, reusable database connection.
     * This follows the Singleton pattern to prevent multiple connections.
     */
    public static function getConnection(): mysqli {
        if (self::$connection === null) {
            // Retrieve database credentials from environment variables.
            $servername = $_ENV['DB_HOST'];
            $username   = $_ENV['DB_USER'];
            $password   = $_ENV['DB_PASS'];
            $dbname     = $_ENV['DB_NAME'];

            // Establish the database connection.
            $mysqli = new mysqli($servername, $username, $password, $dbname);

            // Terminate the script with a clear error message if the connection fails.
            if ($mysqli->connect_error) {
                error_log('Database Connection Error: ' . $mysqli->connect_error);
                http_response_code(500);
                die('Unable to connect to the database.');
            }

            // Ensure the connection uses the UTF-8 character set.
            $mysqli->set_charset("utf8mb4");

            self::$connection = $mysqli;
        }

        return self::$connection;
    }

    /**
     * Executes a SELECT query using prepared statements and returns results.
     *
     * @param string $sql The SQL query with ? placeholders.
     * @param array $params An array of parameters to bind.
     * @param string $types A string representing the types of the params (e.g., "iss" for integer, string, string).
     *                     If empty, types will be inferred.
     * @return array Returns an array of associative arrays.
     */
    public static function select(string $sql, array $params = [], string $types = ""): array {
        $conn = self::getConnection();
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            error_log('Prepare failed: ' . $conn->error);
            return [];
        }

        if (!empty($params)) {
            if ($types === '') {
                $types = self::detectTypes($params);
            }

            if (!$stmt->bind_param($types, ...$params)) {
                error_log('Bind failed: ' . $stmt->error);
                $stmt->close();
                return [];
            }
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Executes an INSERT, UPDATE, or DELETE query using prepared statements.
     *
     * @param string $sql The SQL query with ? placeholders.
     * @param array $params An array of parameters to bind.
     * @param string $types A string representing the types of the params (e.g., "sdi").
     *                     If empty, types will be inferred.
     * @return bool|int Returns the number of affected rows on success, or false on failure.
     */
    public static function execute(string $sql, array $params = [], string $types = ""): bool|int {
        $conn = self::getConnection();
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            error_log('Prepare failed: ' . $conn->error);
            return false;
        }

        if (!empty($params)) {
            if ($types === '') {
                $types = self::detectTypes($params);
            }

            if (!$stmt->bind_param($types, ...$params)) {
                error_log('Bind failed: ' . $stmt->error);
                $stmt->close();
                return false;
            }
        }

        $success = $stmt->execute();

        if (!$success) {
            error_log('Execute failed: ' . $stmt->error);
            $stmt->close();
            return false;
        }

        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows;
    }
}
