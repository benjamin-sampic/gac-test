<?php

declare(strict_types=1);

namespace Tickets\Db;

class TicketsDb extends \Gac\Db\PdoMysql implements \Gac\Db\DbInterface
{
    public function __construct()
    {
        parent::__construct(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE, DB_CHARSET);
    }
}
