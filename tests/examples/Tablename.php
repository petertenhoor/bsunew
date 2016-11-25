<?php
/**
 * Class TableName provides a method for dynamic table names (ideal for unit testing)
 */
class TableName {
    static $keys = array();

    /**
     * @param string $key
     * @return string
     */
    public static function get($key)
    {
        if(isset(self::$keys[$key])) {
            return self::$keys[$key];
        }
        return $key;
    }

    /**
     * @param string $key
     * @param string $name
     */
    public static function set($key, $name)
    {
        self::$keys[$key] = $name;
    }
}

/* Example: */
/*
// A simple SQL query:
echo sprintf('SELECT * FROM `%1$s`;', TableName::get('my_table')) . "\n";

// Change the table name (in a unit test for example):
TableName::set('my_table', 'test_my_table');

// The same SQL query in your code, but a different result:
echo sprintf('SELECT * FROM `%1$s`;', TableName::get('my_table')) . "\n";
*/