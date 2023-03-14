<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use PDO;
use PDOException;


abstract class AbstractModel
{
    protected PDO $conn;

    /**
     * @param array $config
     * @throws ConfigurationException
     * @throws StorageException
     */
    public function __construct(array $config)
    {
        try {
            $this->validateConfig($config);
            $this->createConnection($config);
        } catch (PDOException $e) {
            throw new StorageException('Connection error');
        }
    }

    /**
     * @param array $config
     * @return void
     */
    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        $this->conn = new PDO($dsn, $config['user'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    /**
     * @param array $config
     * @return void
     * @throws ConfigurationException
     */
    private function validateConfig(array $config): void
    {
        if (empty($config['database'])
            || empty($config['host'])
            || empty($config['user'])
        ) {
            throw new ConfigurationException("Configuration error");
        }
    }
}