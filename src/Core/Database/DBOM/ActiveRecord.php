<?php

namespace Core\Database\DBOM;

use Core\Property;

class ActiveRecord
{
    protected $db;
    protected static string $table = "";
    protected static string $id_field = "id";
    protected static array $ignored_fields = [];
    protected static array $serialization_fields_ignore = [];

    // This stores any extra data of interest, not always used
    protected array $extras = [];


    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

    public function getID()
    {
        return $this->{get_called_class()::$id_field};
    }

    public function getExtra($key, $type = "string", $default = null)
    {
        return Property::GetValue($this->extras, $key, $type, $default);
    }

    /*
        Internal functions
    */


    /**
     * Populates the properties of the derived class with array of arguments supplied.
     * Property names must match class property names
     * Class properties must be public
     */
    public function fromArgs($args): void
    {
        if(!is_array($args)) $args = (array)$args;
        $fields = $this->getReflection();

        $fieldKeys = array_keys($fields);

        foreach($args as $arg => $value)
        {
            if(!in_array($arg, $fieldKeys)) continue;

            $this->{$arg} = Property::GetValue($args, $arg, $fields[$arg]['type']);
        }
    }

    /**
     * Generates a derived class sensitive SQL UPSERT query (INSERT ON DUPLICATE) using sanitized values
     */
    public function serialize()
    {
        $out = [];
        $fields = $this->getReflection();

        foreach($fields as $key => $info)
        {
            // skip ignored fields
            if(in_array($key, get_called_class()::$serialization_fields_ignore))
                continue;

            switch($info['type'])
            {
                default:
                case 'string':
                case 'int':
                case 'float':
                case 'bool':
                    break;
                case 'DateTime':
                    if(!$info['value']) $info['value'] = 'null';
                    else $info['value'] = $info['value']->format('Y-m-d h:i:s');
                    break;
            }

            $out[$key] = $info['value'];
        }

        return $out;
    }

    /**
     * Generates a derived class sensitive SQL UPSERT query (INSERT ON DUPLICATE) using sanitized values
     */
    public function save()
    {
        $fields = $this->getReflection();
        $tableUpdateList = [];
        $data = [];

        foreach($fields as $key => $info)
        {
            // skip ignored fields
            if(in_array($key, get_called_class()::$ignored_fields))
                continue;

            $sanitizer = null; // string
            $quote = true;

            switch($info['type'])
            {
                default:
                case 'string':
                    break;
                case 'int':
                    $sanitizer = 'int';
                    $quote = false;
                    break;
                case 'float':
                    $sanitizer = 'float';
                    $quote = true;
                    break;
                case 'bool':
                    $sanitizer = 'bool';
                    $quote = false;
                    break;
                case 'DateTime':
                    if(!$info['value']) $info['value'] = 'null';
                    else $info['value'] = $info['value']->format('Y-m-d h:i:s');
                    break;
            }

            $securedData = 'null';
            if($info['value'] !== null && $info['value'] !== 'null')
                $securedData = secure($info['value'], $sanitizer, $quote);

            // if the key is the id field and it's falsy (like we're making a new record) force null. This is a special case
            if($key == get_called_class()::$id_field && !$securedData)
                $securedData = 'null';

            $data[$key] = $securedData;

            if($key == get_called_class()::$id_field)
                continue;

            $tableUpdateList[] = "{$key} = VALUES($key)";
        }

        $query = sprintf("INSERT INTO `".get_called_class()::$table."` (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s",
            implode(', ', array_keys($data)),
            vsprintf(implode(', ', array_fill(0, count($data), '%s')), $data),
            implode(', ', $tableUpdateList)
        );

        self::Log($query);
        $this->db->query($query);
        if($this->db->error)
        {
            self::Log("ERROR: " . $this->db->error, true);
            _error("SQL_ERROR", $this->db->error);
        }


        if(!$this->{get_called_class()::$id_field})
            $this->{get_called_class()::$id_field} = $this->db->insert_id;
    }

    /**
     * Utility function to get this derived classes list of properties, their defined types, and their current values
     */
    private function getReflection()
    {
        // We want to get the intended class property types, not the one's they've accidentally been cooerced too
        $reflection = new ReflectionClass(get_called_class());
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $fields = [];
        foreach($properties as $property)
        {
            $rp = new ReflectionProperty($property->class, $property->name);
            $type = $rp->getType()->getName();
            $fields[$property->name] = ['type' => $type, 'value' => $this->{$property->name}];
        }

        return $fields;
    }

    /*
        Static Callables
    */

    /**
     * Non-instanced interface to build an instance from a list of arguments
     */
    static public function CreateFromArgs($args)
    {
        $derivedClass = get_called_class();

        $item = new $derivedClass();
        $item->fromArgs($args);

        return $item;
    }

    /**
     * Get an instance of derived class based on ID
     */
    static public function Get($id)
    {
        global $db;
        $derivedClass = get_called_class();

        $sql = sprintf("SELECT * FROM `{$derivedClass::$table}` WHERE `{$derivedClass::$id_field}` = %s LIMIT 1", secure($id, "int", false));

        // $query = $db->query($sql) or _error("SQL_ERROR_THROWEN", $db->error);

        self::Log($sql);
        $query = $db->query($sql);
        if($db->error)
        {
            self::Log("ERROR: " . $db->error, true);
            _error("SQL_ERROR", $db->error);
        }

        if($query->num_rows <= 0)
            throw new \Exception(get_called_class() . " with ".get_called_class()::$id_field." = {$id} not found");

        return $derivedClass::CreateFromArgs($query->fetch_assoc());
    }

    /**
     * Get an instance of derived class based on ID
     */
    static public function GetBy($conditions = [])
    {
        global $db;
        $derivedClass = get_called_class();

        $_conditions = "";

        if(empty($conditions))
        {
            throw new Exception("missing condition");
        }
        $_conditions = implode(" AND ", $conditions);

        $sql = sprintf("SELECT * FROM `{$derivedClass::$table}` WHERE %s LIMIT 1", $_conditions);

        self::Log($sql);
        $query = $db->query($sql);
        if($db->error)
        {
            self::Log("ERROR: " . $db->error, true);
            _error("SQL_ERROR", $db->error);
        }

        if($query->num_rows <= 0)
            throw new \Exception(get_called_class() . " not found");

        return $derivedClass::CreateFromArgs($query->fetch_assoc());
    }

    /**
     * Get array of instances of derived class based on conditions
     */
    static public function List($conditions = [], $orderby = [], $limit = 0, $offset = 0)
    {
        global $db;
        $derivedClass = get_called_class();

        $items = [];

        $_orderby = "";
        $_conditions = "";
        $_offset = "";

        $_tmporderby = [];
        foreach($orderby as $key=>$value)
        {
            $sort = $value ? "ASC" : "DESC";
            $_tmporderby[] = "{$key} {$sort}";
        }
        if(!empty($_tmporderby))
        {
            $_orderby = "ORDER BY " . implode(', ', $_tmporderby);
        }

        if($limit)
        {
            $_offset = "LIMIT {$offset},{$limit}";
        }

        if(empty($conditions))
        {
            $conditions[] = "1 = 1";
        }
        $_conditions = implode(" AND ", $conditions);

        $sql = sprintf("SELECT * FROM {$derivedClass::$table} WHERE %s %s %s", $_conditions, $_orderby, $_offset);

        // $query = $db->query($sql) or _error("SQL_ERROR_THROWEN", $db->error);
        self::Log($sql);
        $query = $db->query($sql);
        if($db->error)
        {
            self::Log("ERROR: " . $db->error, true);
            _error("SQL_ERROR", $db->error);
        }

        while($row = $query->fetch_assoc())
        {
            $items[] = $derivedClass::CreateFromArgs($row);
        }

        return $items;
    }


    static public function Log($text, $append = false)
    {
        if(isset($_ENV['ENV']) && $_ENV['ENV'] == 'dev')
        {
            $log = [];
            if(!$append) {
                $log[] = "------";
                $log[] = (new DateTime())->format("Y-m-d h:i:s");
            }

            $log[] = $text;

            $logfile = __DIR__ . "/../logs/queries_". (new DateTime())->format("Y-m-d") .".log";

            file_put_contents($logfile, implode(PHP_EOL, $log) . PHP_EOL, FILE_APPEND);
        }
    }
}
