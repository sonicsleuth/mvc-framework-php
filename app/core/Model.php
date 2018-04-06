<?php
/**
 * @version 1.0.3 PDO Abstraction for common CRUD statements with auto-column binding.
 *
 * @internal the debug() method imports "model_error.css" for nice error styling. Adjust path to this file accordingly.
 *
 * @update 1.0.3 added error_log() within the __construct() to record connection failures.
 *
 * @update 1.0.4 clarified method comments with regards to calling methods.
 *
 * @update 1.0.5 updated run() function to return last record id on insert, and affected records for update or delete.
 *
 * @update 1.0.7 HTML Entities returned from the database will be decoded by default when calling select() or run() methods.
 */

use PDO;
use PDOException;

class Model extends PDO {

    private $error;
    private $sql;
    private $bind;
    private $errorCallbackFunction;
    private $errorMsgFormat;
    private $errorCssPath = 'model_error.css'; // Styles to pretty up the custom error output.

    public function __construct() {
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION
        );

        try {
            parent::__construct(DB_DRIVER . ':host=' . DB_HOSTNAME . ';dbname=' . DB_DATABASE . '', DB_USERNAME, DB_PASSWORD, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log('DB Model Connection Error: ' . $this->error, 0);
            echo 'DB Connection Error: ' . $this->error;
        }
    }

    /**
     * @param $sql
     * @param string $bind
     * @param bool $entity_decode
     *
     * @return bool|int
     *
     * This method is used to run free-form SQL statements that can't be handled by the included delete, insert, select,
     * or update methods. If no SQL errors are produced, this method will return the number of affected rows for
     * DELETE, INSERT, and UPDATE statements, or an object of results for SELECT, DESCRIBE, and PRAGMA statements.
     *
     * Note: HTML Entities returned from 'select' queries will be decoded by default. Set $entity_decode = false otherwise.
     */
    public function run($sql, $bind = "", $entity_decode = true)
    {
        $this->sql   = trim($sql);
        $this->bind  = $this->cleanup($bind);
        $this->error = "";

        try {
            $pdostmt = $this->prepare($this->sql);
            if ($pdostmt->execute($this->bind) !== false) {
                if (preg_match("/^(" . implode("|", array("select", "describe", "pragma")) . ") /i", $this->sql)) {
                    $results = $pdostmt->fetchAll(PDO::FETCH_ASSOC);
                    if($entity_decode) {
                        array_walk_recursive($results, function (&$item) {
                            $item = htmlspecialchars_decode($item);
                        });
                    }
                    return $results;
                } elseif (preg_match("/^(" . implode("|", array("delete", "update")) . ") /i", $this->sql)) {
                    return $pdostmt->rowCount(); // return records affected.
                } elseif (preg_match("/^(" . implode("|", array("insert")) . ") /i", $this->sql)) {
                    return $this->lastInsertId(); // return new record id.
                }
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $this->debug();
            return false;
        }
    }

    /**
     * @param $table
     * @param string $where
     * @param string $bind
     * @param string $fields
     * @param bool $entity_decode
     *
     * @return bool|int
     *
     * Example #1
     * $results = $this->db->select("mytable");
     *
     * Example #2
     * $results = $this->db->select("mytable", "Gender = 'male'");
     *
     * Example #3 w/Prepared Statement
     * $search = "J";
     * $bind = array(
     *      ":search" => "%$search"
     * );
     * $results = $this->db->select("mytable", "FName LIKE :search", $bind);
     *
     * Note: HTML Entities returned from the database will be decoded by default. Set $entity_decode = false otherwise.
     */
    public function select($table, $where = "", $bind = "", $fields = "*", $entity_decode = true)
    {
        $sql = "SELECT " . $fields . " FROM " . $table;
        if (!empty($where))
            $sql .= " WHERE " . $where;
        $sql .= ";";

        $data = $this->run($sql, $bind);

        if (count($data) > 1) {
            return $data; // return full index of records.
        } else {
            return $data[0]; // return single record.
        }

    }

    /**
     * @param $table
     * @param $info
     *
     * @return bool|int
     *
     * If no SQL errors are produced, this method will return the number of rows affected by the INSERT statement.
     *
     * Example #1:
     * $insert = array(
     *      "FName" => "John",
     *      "LName" => "Doe",
     *      "Age" => 26,
     *      "Gender" => "male"
     * );
     * $this->db->insert("mytable", $insert);
     */
    public function insert($table, $info)
    {
        $fields = $this->filter($table, $info);
        $sql    = "INSERT INTO " . $table . " (" . implode($fields, ", ") . ") VALUES (:" . implode($fields, ", :") . ");";
        $bind   = array();
        foreach ($fields as $field)
            $bind[":$field"] = $info[$field];

        return $this->run($sql, $bind);
    }

    /**
     * @param $table
     * @param $info
     * @param $where
     * @param string $bind
     *
     * If no SQL errors are produced, this method will return the number of rows affected by the UPDATE statement.
     *
     * @return bool|int
     *
     * Example #1
     * $update = array(
     *      "FName" => "Jane",
     *      "Gender" => "female"
     * );
     * $this->db->update("mytable", $update, "FName = 'John'");
     *
     * Example #2 w/Prepared Statement
     * $update = array(
     *      "Age" => 24
     * );
     * $fname = "Jane";
     * $lname = "Doe";
     * $bind = array(
     *      ":fname" => $fname,
     *      ":lname" => $lname
     * );
     * $this->db->update("mytable", $update, "FName = :fname AND LName = :lname", $bind);
     */
    public function update($table, $info, $where, $bind = "")
    {
        $fields    = $this->filter($table, $info);
        $fieldSize = sizeof($fields);

        $sql = "UPDATE " . $table . " SET ";
        for ($f = 0; $f < $fieldSize; ++$f) {
            if ($f > 0)
                $sql .= ", ";
            $sql .= $fields[$f] . " = :update_" . $fields[$f];
        }
        $sql .= " WHERE " . $where . ";";

        $bind = $this->cleanup($bind);
        foreach ($fields as $field)
            $bind[":update_$field"] = $info[$field];

        return $this->run($sql, $bind);
    }

    /**
     * @param $table
     * @param $where
     * @param string $bind
     *
     * If no SQL errors are produced, this method will return the number of rows affected by the DELETE statement.
     *
     * Method #1
     * $this->db->delete("mytable", "Age < 30");
     *
     * Method #2 w/Prepared Statement
     * $lname = "Doe";
     * $bind = array(
     *      ":lname" => $lname
     * )
     * $this->db->delete("mytable", "LName = :lname", $bind);
     */
    public function delete($table, $where, $bind = "")
    {
        $sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
        $this->run($sql, $bind);
    }


    /**
     * @param $table
     * @param $info
     *
     * @return array
     *
     * Automated table binding for MySql or SQLite.
     */
    private function filter($table, $info)
    {
        $driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver == 'sqlite') {
            $sql = "PRAGMA table_info('" . $table . "');";
            $key = "name";
        } elseif ($driver == 'mysqli') { // > php7
            $sql = "DESCRIBE " . $table . ";";
            $key = "Field";
        } elseif ($driver == 'mysql') { // < php7
            $sql = "DESCRIBE " . $table . ";";
            $key = "Field";
        } else {
            $sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
            $key = "column_name";
        }
        if (false !== ($list = $this->run($sql))) {
            foreach ($list as $record) {
                $fields[] = $record[$key];
            }

            return array_values(array_intersect($fields, array_keys($info)));
        }

        return array();
    }

    /**
     * @param $bind
     *
     * @return array
     *
     * Insure we have an array to work with.
     *
     */
    private function cleanup($bind)
    {
        if (!is_array($bind)) {
            if (!empty($bind))
                $bind = array($bind);
            else
                $bind = array();
        }

        return $bind;
    }

    /**
     * The error message can then be displayed, emailed, etc within the callback function.
     *
     * Example:
     *
     * function myErrorHandler($error) {
     * }
     *
     * $db = new db("mysql:host=127.0.0.1;port=0000;dbname=mydb", "dbuser", "dbpasswd");
     * $this->db->setErrorCallbackFunction("myErrorHandler");
     *
     * Text Version
     * $this->db->setErrorCallbackFunction("myErrorHandler", "text");
     *
     * Internal/Built-In PHP Function
     * $this->db->setErrorCallbackFunction("echo");
     *
     * @param $errorCallbackFunction
     * @param string $errorMsgFormat
     */
    public function setErrorCallbackFunction($errorCallbackFunction, $errorMsgFormat = "html")
    {
        if (in_array(strtolower($errorCallbackFunction), array("echo", "print"))) {
            $errorCallbackFunction = "print_r";
        }

        if (method_exists($this, $errorCallbackFunction)) {
            $this->errorCallbackFunction = $errorCallbackFunction;
            if (!in_array(strtolower($errorMsgFormat), array("html", "text"))) {
                $errorMsgFormat = "html";
            }
            $this->errorMsgFormat = $errorMsgFormat;
        }
    }


    /**
     * A better PDO debugger, just because.
     */
    private function debug()
    {
        // If no other error handler is defined, then use this.
        if (!empty($this->errorCallbackFunction)) {

            $error = array("Error" => $this->error);
            if (!empty($this->sql))
                $error["SQL Statement"] = $this->sql;
            if (!empty($this->bind))
                $error["Bind Parameters"] = trim(print_r($this->bind, true));

            $backtrace = debug_backtrace();
            if (!empty($backtrace)) {
                foreach ($backtrace as $info) {
                    if ($info["file"] != __FILE__)
                        $error["Backtrace"] = $info["file"] . " at line " . $info["line"];
                }
            }

            $msg = "";
            if ($this->errorMsgFormat == "html") {
                if (!empty($error["Bind Parameters"]))
                    $error["Bind Parameters"] = "<pre>" . $error["Bind Parameters"] . "</pre>";
                $css = trim(file_get_contents(dirname(__FILE__) . $this->errorCssPath)); // set this path
                $msg .= '<style type="text/css">' . "\n" . $css . "\n</style>";
                $msg .= "\n" . '<div class="db-error">' . "\n\t<h3>SQL Error</h3>";
                foreach ($error as $key => $val)
                    $msg .= "\n\t<label>" . $key . ":</label>" . $val;
                $msg .= "\n\t</div>\n</div>";
            } elseif ($this->errorMsgFormat == "text") {
                $msg .= "SQL Error\n" . str_repeat("-", 50);
                foreach ($error as $key => $val)
                    $msg .= "\n\n$key:\n$val";
            }

            $func = $this->errorCallbackFunction;
            $this->{$func}($msg); // neat little trick to call a variable function.
        }
    }

    /**
     * @param $msg
     *
     * Simple Callback Function.
     */
    public function basicCallbackFunction($msg) {
        print_r($msg);
    }
}