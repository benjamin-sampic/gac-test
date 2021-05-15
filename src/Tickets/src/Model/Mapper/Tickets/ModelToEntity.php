<?php

namespace Tickets\Model\Mapper\Tickets;

use Tickets\Model\TicketsModel;
use Tickets\Entity\TicketsEntity;

class ModelToEntity
{
    public function map(TicketsModel $model): TicketsEntity
    {
        $entity = new TicketsEntity();

        return $entity;
    }
}
