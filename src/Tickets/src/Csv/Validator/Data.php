<?php

namespace Tickets\Csv\Validator;

use Tickets\Csv\Validator\Generic;
use Tickets\Exception\InvalidDataException as InvalidDataException;

/**
 * Validator for tickets' CSV line of type "Data"
 */
class Data extends Generic
{
    /**
     * Validate data
     * @param  array  $data
     * @return void
     */
    public function validate(array $data): void
    {
        parent::validate($data);
        if (empty($data[static::IDX_HOUR])
        || (
            8 < (int) substr($data[static::IDX_HOUR], 0, 2)
            && 18 > (int) substr($data[static::IDX_HOUR], 0, 2)
        )
    ) {
            throw new InvalidDataException("Data consumption date is out of bounds (".$data[static::IDX_HOUR].")", 1);
        }
    }
}
