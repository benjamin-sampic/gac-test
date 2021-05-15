<?php 

namespace Tickets\Repository;

use Tickets\Db\TicketsDb;

class AbstractRepository extends \Gac\Repository\AbstractRepository
{
    public function __construct()
    {
        parent::__construct(new TicketsDb());
    }
}
