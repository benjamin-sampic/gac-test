<?php

namespace Tickets\Csv\Validator;

use Tickets\Exception\InvalidDataException as InvalidDataException;

/**
 * Generic validator for tickets' CSV line
 */
class Generic implements \Tickets\Csv\Interfaces\LineColumns
{
    /**
     * Validate data
     * @param  array  $data
     * @return void
     */
    public function validate(array $data): void
    {
        if (empty($data[static::IDX_SUBSCRIBER_NO])) {
            throw new InvalidDataException("Subscriber number is empty", 1);
        }

        if (empty($data[static::IDX_DATE])) {
            throw new InvalidDataException("Date is empty", 1);
        }

        if (empty($data[static::IDX_HOUR])) {
            throw new InvalidDataException("Hour is empty", 1);
        } elseif (!preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/", $data[static::IDX_HOUR])) {
            throw new InvalidDataException("Hour format is invalid", 1);
        }
    }
}
