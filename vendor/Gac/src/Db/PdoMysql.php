<?php

declare(strict_types=1);

namespace Gac\Db;

use Gac\Exception\DbException;

/**
 * Mysqli connection class.
 */
class PdoMysql implements DbInterface
{
    /**
     * Errors are stored here.
     *
     * @var array
     */
    public $_error = [];
    /**
     * Server to connect to.
     *
     * @var string
     */
    private $_server = '';
    /**
     * Connection's user.
     *
     * @var string
     */
    private $_user = '';
    /**
     * Connection's password.
     *
     * @var string
     */
    private $_password = '';
    /**
     * The database's name.
     *
     * @var string
     */
    private $_databaseName = '';
    /**
     * The database connection's charset
     *
     * @var string
     */
    private $_charset = 'utf-8';
    /**
     * The database selection status.
     *
     * @var bool
     */
    private $_database = false;
    /**
     * The DB link \object.
     *
     * @var \object
     */
    private $_link = null;
    /**
     * The connection status.
     *
     * @var bool
     */
    private $_connected = false;

    /**
     * Constructor.
     *
     */
    public function __construct(string $server, string $user, string $password, string $databaseName, string $charset)
    {
        $this->_server = $server;
        $this->_user = $user;
        $this->_password = $password;
        $this->_databaseName = $databaseName;
        $this->_charset = $charset;
        $this->_error = [
        'ERR_NAME'           => 'ERR_NO',
        'EntryAlreadyExists' => '1062',
        ];

        $this->connect();
    }
    /**
     * Constructor's alias (but in static).
     *
     * @param string $type    Type
     * @param array  $pConfig Configuration
     *
     * @return self
     */
    public static function initialize(string $type, array $pConfig)
    {
        return new self($pConfig);
    }

    /**
     * Execute a query.
     *
     * @param string $pSql   The query
     * @param ?array $params The query parameters
     */
    public function query(string $pSql, ?array $params = [])
    {
        $res = $this->prepareAndExecute($pSql, $params);

        if (false === $this->isStatementCorrect($res)) {
            $errors = $res->errorInfo();
            throw new \Exception('Invalid query : [' . $res->errorCode() . '] ' . $errors[2] . '<br />Query :<br />' . $pSql);
        } elseif (null === $res) {
            throw new \Exception('Connection not opened.');
        }

        return $res;
    }

    /**
     * Returns the last inserted id.
     *
     * @return mixed
     */
    public function insert_id()
    {
        return $this->_link->lastInsertId();
    }

    /**
     * Number of rows.
     *
     * @param $pResource The query's result
     *
     * @return int
     */
    public function num_rows($pResource): int
    {
        return $pResource->rowCount();
    }

    /**
     * Fetch the query results as objects.
     *
     * @param $pResource The query \object
     * @param string $className The class to use for each result
     *
     * @return array Array of objects
     */
    public function fetch_object($pResource, ?string $className = 'stdClass'): array
    {
        $arrReturn = [];
        if (false != $pResource) {
            if ($this->num_rows($pResource) > 0) {
                while ($data = $pResource->fetchObject($className)) {
                    array_push($arrReturn, $data);
                }
            }
        }

        return $arrReturn;
    }

    /**
     * Fetch the query results as array.
     *
     * @param $pResource The query \object
     *
     * @return array Array of arrays
     */
    public function fetch_array($pResource): array
    {
        $arrReturn = [];
        if (false != $pResource) {
            if ($this->num_rows($pResource) > 0) {
                while ($data = $pResource->fetch(\PDO::FETCH_ASSOC)) {
                    array_push($arrReturn, $data);
                }
            }
        }

        return $arrReturn;
    }

    /**
     * Get the query results as array.
     *
     * @param string $pSql   The query string
     * @param ?array $params The query parameters
     *
     * @return array Array of arrays
     */
    public function getAsArray(string $pSql, ?array $params = []): array
    {
        $arrReturn = [];
        $res = $this->prepareAndExecute($pSql, $params);
        $arrReturn = $this->fetch_array($res);

        return $arrReturn;
    }

    /**
     * Get the query results as objects.
     *
     * @param string $pSql      The query string
     * @param ?array $params    The query parameters
     * @param string $className The class to use for each result
     *
     * @return array Array of objects
     */
    public function getAsObjects(string $pSql, ?array $params = [], ?string $className = 'stdClass'): array
    {
        $arrReturn = [];
        $res = $this->prepareAndExecute($pSql, $params);
        $arrReturn = $this->fetch_object($res, $className);

        return $arrReturn;
    }

    /**
     * Establish the connection.
     *
     * @return bool true if the connection has been established
     */
    public function connect(): bool
    {
        $this->_connected = false;

        try {
            $this->_link = new \PDO('mysql:host=' . $this->_server . ';charset='.$this->_charset.'', $this->_user, $this->_password, [\PDO::MYSQL_ATTR_LOCAL_INFILE => true]);
            $this->_connected = true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1, $e);
        }

        try {
            $this->query('USE `' . $this->_databaseName . '`');
            $this->_database = true;
        } catch (\Exception $e) {
            throw new \Exception('Unable to select the database', 1, $e);
        }

        return $this->_connected;
    }

    /**
     * Close the connection.
     *
     * @return bool true if the connection has been closed
     */
    public function close(): bool
    {
        if ($this->_connected) {
            $this->_link = null;
            $this->_database = null;
            $this->_connected = false;
        }

        return $this->_connected;
    }

    public function prepareAndExecute(string $sql, array $params): \PDOStatement
    {
        if (! $this->_connected) {
            $this->connect();
        }
        $PDOStatement = $this->_link->prepare($sql);
        $PDOStatement->execute($params);

        return $PDOStatement;
    }

    public function isStatementCorrect(\PDOStatement $stmt): bool
    {
        return '00000' === $stmt->errorCode();
    }

    /**
     * Starts a named transaction.
     *
     * @param string $transactionName The transaction's name
     *
     * @return bool true if the transaction has been began
     */
    public function beginTransaction(?string $transactionName = 'default'): bool
    {
        if ($this->_connected) {
            return $this->_link->beginTransaction();
        }
        throw new \Exception('Error beginning transaction : connection not opened');
    }

    /**
     * Commit the named transaction.
     *
     * @param string $transactionName The transaction's name
     *
     * @return bool true if the transaction has been commited
     */
    public function commit(?string $transactionName = 'default'): bool
    {
        if ($this->_connected) {
            return $this->_link->commit();
        }
        throw new \Exception('Error commiting transaction : connection not opened');
    }

    /**
     * Rollback a named transaction.
     *
     * @param string $transactionName The transaction's name
     *
     * @return bool true if the transaction has been rollbacked
     */
    public function rollback(?string $transactionName = 'default'): bool
    {
        if ($this->_connected) {
            return $this->_link->rollBack();
        }
        throw new \Exception('Error rolling back transaction : connection not opened');
    }

    /**
     * Return a column type.
     *
     * @param \object $row      A result row
     * @param int     $noColumn The column's index
     *
     * @return int The column type
     */
    public function getFieldType($row, int $noColumn): int
    {
        return ($row->getColumnMeta($noColumn))['pdo_type'];
    }

    /**
     * Return a column name.
     *
     * @param \object $row      A result row
     * @param int     $noColumn The column's index
     *
     * @return string The column name
     */
    public function getFieldName($row, int $noColumn): string
    {
        return ($row->getColumnMeta($noColumn))['name'];
    }

    /**
     * Count the number of columns for a result.
     *
     * @param \object $rows A result row
     *
     * @return int
     */
    public function getNumberOfColumns($rows): int
    {
        return $rows->columnCount();
    }

    /**
     * Escape a string.
     *
     * @param string $pString The raw string
     *
     * @return string The escaped string
     */
    public function escapeString(string $pString): string
    {
        return trim($this->_link->quote($pString), '\'');
    }

    /**
     * Returns the last error's message.
     *
     * @return string
     */
    public function getError(): ?string
    {
        return mysqli_error($this->_link);
    }

    /**
     * Returns the last error's number.
     *
     * @return int
     */
    public function getErrorNumber(): ?int
    {
        return mysqli_errno($this->_link);
    }

    /**
     * Set the server's name.
     *
     * @param string $pServer
     *
     * @return self
     */
    public function setServer(string $pServer)
    {
        $this->_server = $pServer;

        return $this;
    }

    /**
     * Returns the server's name.
     *
     * @return string
     */
    public function getServer(): string
    {
        return $this->_server;
    }

    /**
     * Set the user.
     *
     * @param string $pUser
     *
     * @return self
     */
    public function setUser(string $pUser)
    {
        $this->_user = $pUser;

        return $this;
    }

    /**
     * Get the user.
     *
     * @return string
     */
    public function getUser(): string
    {
        return $this->_user;
    }

    /**
     * Set the password.
     *
     * @param string $pPassword
     *
     * @return self
     */
    public function setPassword(string $pPassword)
    {
        $this->_password = $pPassword;

        return $this;
    }

    /**
     * Returns the password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->_password;
    }

    /**
     * Set the database selection's status.
     *
     * @param bool $pDatabase true to set a selected database
     *
     * @return self
     */
    public function setDatabase(bool $pDatabase)
    {
        $this->_database = $pDatabase;

        return $this;
    }

    /**
     * Verify if a database has been selected.
     *
     * @return bool true if a database is selected
     */
    public function getDatabase(): bool
    {
        return $this->_database;
    }

    /**
     * Set the link to the database.
     *
     * @param \object $pLink
     *
     * @return self
     */
    public function setLink($pLink)
    {
        $this->_link = $pLink;

        return $this;
    }

    /**
     * Get link to database.
     *
     * @return \object
     */
    public function getLink()
    {
        return $this->_link;
    }

    /**
     * Set the connection status.
     *
     * @param bool $pConnected true if connected, false otherwise
     *
     * @return self
     */
    public function setConnected(bool $pConnected)
    {
        $this->_connected = $pConnected;

        return $this;
    }

    /**
     * Get the connection's status.
     *
     * @return bool true if connected, false otherwise
     */
    public function getConnected(): bool
    {
        return $this->_connected;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->_charset;
    }

    /**
     * @param string $_charset
     *
     * @return self
     */
    public function setCharset(string $_charset)
    {
        $this->_charset = $_charset;

        return $this;
    }
}
