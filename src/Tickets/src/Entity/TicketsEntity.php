<?php 

namespace Tickets\Entity;

use Tickets\Exception\InvalidDataException;

class TicketsEntity
{
    const TYPE_PHONECALL = "call";
    const TYPE_DATA = "data";
    const TYPE_SMS = "sms";
    const TYPES = [
        self::TYPE_PHONECALL,
        self::TYPE_SMS,
        self::TYPE_DATA,
    ];
    /** @var int */
    private $subscriber_number;
    /** @var \DateTime */
    private $datetime;
    /** @var int */
    private $quantity_real;
    /** @var int */
    private $quantity_billed;
    /** @var string */
    private $type;
    /** @var string */
    private $type_details;

    protected function isTypeAllowed(string $type): bool
    {
        return in_array($type, static::TYPES);
    }

    /**
     * @return int
     */
    public function getSubscriberNumber(): int
    {
        return $this->subscriber_number;
    }

    /**
     * @param int $subscriber_number
     *
     * @return self
     */
    public function setSubscriberNumber(int $subscriber_number): self
    {
        $this->subscriber_number = $subscriber_number;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatetime(): \DateTime
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     *
     * @return self
     */
    public function setDatetime(\DateTime $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityReal():int
    {
        return $this->quantity_real;
    }

    /**
     * @param int $quantity_real
     *
     * @return self
     */
    public function setQuantityReal(int $quantity_real): self
    {
        $this->quantity_real = $quantity_real;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityBilled(): int
    {
        return $this->quantity_billed;
    }

    /**
     * @param int $quantity_billed
     *
     * @return self
     */
    public function setQuantityBilled(int $quantity_billed): self
    {
        $this->quantity_billed = $quantity_billed;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        if ($this->isTypeAllowed($type)) {
            $this->type = $type;
        } else {
            throw new InvalidDataException('Le type "'.$type.'" n\'est pas autorisé.', 1);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTypeDetails(): string
    {
        return $this->type_details;
    }

    /**
     * @param string $type_details
     *
     * @return self
     */
    public function setTypeDetails(string $type_details): self
    {
        $this->type_details = $type_details;

        return $this;
    }
}
