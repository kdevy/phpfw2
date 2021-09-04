<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

use Framework\Exception\DBIOException;

class DBIO
{
    /**
     * @var \PDO|null
     */
    public ?\PDO $dbh;

    /**
     * @var \PDOStatement|null
     */
    public ?\PDOStatement $stmt;

    /**
     * @var string
     */
    protected string $dsn;

    /**
     * @var string
     */
    protected string $user;

    /**
     * @var string
     */
    protected string $password;

    /**
     * @var array
     */
    protected static array $configs = [];

    /**
     * @param string $target
     */
    public function __construct(string $target = "default")
    {
        $target = self::$configs["connection"][$target] ?? null;

        if (!isset($target)) {
            throw new DBIOException("Connection name that does not exist.");
        }

        $this->dsn = "mysql:host=" . $target["host"]
            . ";port=" . ($target["port"] ?? 3306)
            . ";dbname=" . $target["dbname"]
            . ";charset=" . ($target["charset"] ?? "utf8mb4");
        $this->user = $target["user"];
        $this->password = $target["password"];

        $this->connect($target);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->$name;
    }

    /**
     * @return void
     */
    public function connect(): void
    {
        $this->close();

        try {
            $this->dbh = new \PDO($this->dsn, $this->user, $this->password);
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            \Framework\Log::error(null, $e);
            exit("Error(900): An error occurred.");
        }
    }

    /**
     * @return boolean
     */
    public function isConnected(bool $with_statement = false): bool
    {
        return isset($this->dbh) && ($with_statement === true ? isset($this->stmt) : true);
    }

    /**
     * @return void
     */
    public function close(): void
    {
        $this->dbh = null;
        $this->stmt = null;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return bool
     */
    public function query(string $sql, array $params = [])
    {
        try {
            if (!$this->isConnected()) {
                \Framework\Log::warning(null, "It was called in a state in which the connection to the database has not been established.");
                return false;
            }
            $this->stmt = $this->dbh->prepare($sql);

            foreach ($params as $key => $value) {
                $this->stmt->bindValue($key + 1, $value);
            }
            $this->stmt->execute();
        } catch (\PDOException $e) {
            \Framework\Log::error(null, $e);
            exit("Error(901): An error occurred.");
        }
    }

    /**
     * @return mixed
     */
    public function fetch()
    {
        try {
            if (!$this->isConnected(true)) {
                \Framework\Log::warning(null, "It was called in a state in which the connection to the database has not been established.");
                return false;
            }
            return $this->stmt->fetch();
        } catch (\PDOException $e) {
            \Framework\Log::error(null, $e);
            exit("Error(902): An error occurred.");
        }
    }

    /**
     * @return mixed
     */
    public function fetchAssoc()
    {
        try {
            if (!$this->isConnected()) {
                \Framework\Log::warning(null, "It was called in a state in which the connection to the database has not been established.");
                return false;
            }
            $result = $this->stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$result) {
                return [];
            } else {
                return $result;
            }
        } catch (\PDOException $e) {
            \Framework\Log::error(null, $e);
            exit("Error(903): An error occurred.");
        }
    }

    /**
     * @return array|bool
     */
    public function getColumns(string $tablename): array
    {
        try {
            if (!$this->isConnected()) {
                \Framework\Log::warning(null, "It was called in a state in which the connection to the database has not been established.");
                return false;
            }
            $stmt = $this->dbh->query("SHOW COLUMNS FROM $tablename");

            return $stmt->fetchAll(\PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            \Framework\Log::error(null, $e);
            exit("Error(904): An error occurred.");
        }
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array|bool
     */
    public function getAll(string $sql, array $params = [])
    {
        try {
            $this->query($sql, $params);
            $result = $this->stmt->fetchAll();
            $this->stmt->closeCursor();

            return $result;
        } catch (\PDOException $e) {
            \Framework\Log::error(null, $e);
            exit("Error(905): An error occurred.");
        }
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array|bool
     */
    public function getAllAssoc(string $sql, array $params = [])
    {
        try {
            $this->query($sql, $params);
            $result = $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
            $this->stmt->closeCursor();

            return $result;
        } catch (\PDOException $e) {
            \Framework\Log::error(null, $e);
            exit("Error(906): An error occurred.");
        }
    }

    /**
     * @param mixed $value
     * @return void
     */
    public function escape($value)
    {
        try {
            if (!$this->isConnected()) {
                \Framework\Log::warning(null, "It was called in a state in which the connection to the database has not been established.");
                return false;
            }
            return $this->dbh->quote($value);
        } catch (\PDOException $e) {
            \Framework\Log::error(null, $e);
            exit("Error(907): An error occurred.");
        }
    }

    /**
     * @param array $configs
     * @return void
     */
    static public function configure(array $configs)
    {
        self::$configs = array_merge(self::$configs, $configs);
    }

    /**
     * @return array
     */
    static public function getConfigure()
    {
        return self::$configs;
    }
}