<?php
class Docker
{
    private array $config;

    public function __construct()
    {
        $this->config = [
            'host' => getenv('MYSQL_HOST') ?: 'mysql',
            'port' => getenv('MYSQL_PORT') ?: '3306',
            'dbname' => getenv('MYSQL_DATABASE') ?: 'app_db',
            'username' => getenv('MYSQL_USER') ?: 'app_user',
            'password' => getenv('MYSQL_PASSWORD') ?: 'secret',
            'charset' => 'utf8mb4',
        ];
    }

    public function getPdo(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $this->config['host'],
            $this->config['port'],
            $this->config['dbname'],
            $this->config['charset']
        );

        return new PDO(
            $dsn,
            $this->config['username'],
            $this->config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
}