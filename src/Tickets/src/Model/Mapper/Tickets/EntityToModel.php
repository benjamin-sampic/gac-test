<?php

namespace Tickets\Model\Mapper\Tickets;

use Tickets\Model\TicketsModel;
use Tickets\Entity\TicketsEntity;

class EntityToModel
{
    public function map(TicketsEntity $entity): TicketsModel
    {
        $model = new TicketsModel();
        $model->setSubscriberNumber($entity->getSubscriberNumber())
            ->setDatetime($entity->getDatetime())
            ->setQuantityReal($entity->getQuantityReal())
            ->setQuantityBilled($entity->getQuantityBilled())
            ->setType($entity->getType())
            ->setTypeDetails($entity->getTypeDetails())
        ;
        return $model;
    }
}
