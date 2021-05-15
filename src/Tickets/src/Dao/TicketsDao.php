<?php 

namespace Tickets\Dao;

use Tickets\Entity\TicketsEntity;
use Tickets\Model\Mapper\Tickets\EntityToModel;
use Tickets\Repository\TicketsRepository;

class TicketsDao
{
    private $repository;
    private $entityToModelMapper;

    public function __construct(?TicketsRepository $repository = null)
    {
        $this->repository = $repository ?? new TicketsRepository();
        $this->entityToModelMapper = new EntityToModel();
    }

    public function insert(TicketsEntity $ticket)
    {
        $ticketsModel = $this->entityToModelMapper->map($ticket);
        return $this->repository->insert($ticketsModel);
    }

    public function empty()
    {
        return $this->repository->empty();
    }

    public function getTotalPhonecallDurationFrom15February2012Included()
    {
        return $this->repository->getTotalPhonecallDurationFrom15February2012Included();
    }

    public function getTotalSmsSentForFebruary2012()
    {
        return $this->repository->getTotalSmsSentForFebruary2012();
    }

    public function getTop10DataUsageBySubscriberForFebruary2012()
    {
        return $this->repository->getTop10DataUsageBySubscriberForFebruary2012();
    }
}
