<?php

namespace google85\phpmvc\db;
use google85\phpmvc\Application;
use google85\phpmvc\Model;

/**
 * Class DbModel
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc
 * 
 * - for maping user module to database
 *
 */
abstract class DbModel extends Model {

    abstract public function tableName(): string;

    abstract public function attributes(): array;       //return all database columns

    abstract public function primaryKey(): string;

    public function save() {

        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = [];

        //mapings
        $params = array_map(function($attr) {      // PHP < 7.4
            return  ":$attr";
        }, $attributes);
        //$params = array_map(fun($attr) => ":$attr", $attributes);     //PHP >=7.4 supports array functions

        //inserts
        $statement = self::prepare("INSERT INTO $tableName (" . implode(', ', $attributes) . ") 
                VALUES (" . implode(', ', $params) . ") ");

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    public static function prepare($sql) {
        return Application::$app->db->pdo->prepare($sql);
    }

    public function findOne($where) {   // [email => zura@gmail.com, firstname => zura]
        $tableName = static::tableName();       //nu merge cu self:: pt ca e abstract si apartine de o clasa unde va fi definit
        $attributes = array_keys($where);
        // SELECT * FROM $tableName WHERE email = :email AND firstname = :firstname
        $sql = implode("AND ",
                array_map(function($attr) {      // PHP < 7.4
                    return "$attr = :$attr";
                }, $attributes)
            );
        //PHP >=7.4 supports array functions:   //$sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));

        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }
}