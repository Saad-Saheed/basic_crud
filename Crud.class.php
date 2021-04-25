<?php
include('DataObject.class.php');

class Crud extends DataObject
{

    public static function insert($query)
    {
        $conn = parent::connect();
        try {

            $res = $conn->query($query);
            parent::disconnect($conn);
            if ($res) {
                return true;
            }
        } catch (mysqli_sql_exception $ex) {
            parent::disconnect($conn);
            die("Query failed: " . $ex->getMessage());
        }
        return false;
    }

    public static function get($query)
    {
        $conn = parent::connect();
        try {
            $row = "";
            $rows = [];
            $res = $conn->query($query);
            if ($res) {
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $rows[] = $row;
                    }
                }
            }
            parent::disconnect($conn);
            if ($rows) {
                return $rows;
            }
        } catch (mysqli_sql_exception $ex) {
            parent::disconnect($conn);
            die("Query failed: " . $ex->getMessage());
        }
        return false;
    }

    public static function update($query)
    {
        $conn = parent::connect();
        try {

            $res = $conn->query($query);
            parent::disconnect($conn);
            if ($res) {
                return true;
            }
            return false;
        } catch (mysqli_sql_exception $ex) {
            parent::disconnect($conn);
            die("Query failed: " . $ex->getMessage());
        }
    }

    public static function delete($query)
    {
        $conn = parent::connect();
        try {

            $res = $conn->query($query);
            parent::disconnect($conn);
            if ($res) {
                return true;
            }
        } catch (mysqli_sql_exception $ex) {
            parent::disconnect($conn);
            die("Query failed: " . $ex->getMessage());
        }
        return false;
    }
}
