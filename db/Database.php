<?php

namespace google85\phpmvc\db;
use google85\phpmvc\Application;

/**
 * Class Database
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc
 *
 */
class Database {
    public $pdo;                            //public \PDO $pdo;     //pt PHP >=7.4

    public function __construct($config) {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations() {
        
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR.'/migrations');

        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();

            $this->log("Applying migration $migration");
            $instance->up();

            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }
    }

    public function createMigrationsTable() {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;");
    }

    public function getAppliedMigrations() {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations($migrations) {
        // convert migrations from array like "m0001_initial.php", "m0002_something.php" ==> to "('m0001_initial.php')", "('m0002_something.php')"

        //PHP >=7.4 supports array functions:       //$migrations = array_map(fn($m) => "('$m')", $migrations);
        $str = implode(",",
                        array_map(function($m) {      // PHP < 7.4  [https://stitcher.io/blog/short-closures-in-php]
                            return  "('$m')";
                        }, $migrations)
                );

        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
            $str
        ");
        $statement->execute();
    }

    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }

    protected function log($message) {
        echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }
}