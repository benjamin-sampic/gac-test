<?php 

namespace Gac\Repository;

abstract class AbstractRepository
{
    protected $dbConnection;

    public function __construct(\Gac\Db\DbInterface $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }
}
