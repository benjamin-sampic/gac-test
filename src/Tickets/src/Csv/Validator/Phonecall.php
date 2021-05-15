<?php

namespace Tickets\Csv\Validator;

use Tickets\Csv\Validator\Generic;
use Tickets\Exception\InvalidDataException as InvalidDataException;

/**
 * Validator for tickets' CSV line of type "phone call"
 */
class Phonecall extends Generic
{
    /**
     * Validate data
     * @param  array  $data
     * @return void
     */
    public function validate(array $data): void
    {
        parent::validate($data);
        if (empty($data[static::IDX_DATE])
        || (
            15 > (int) substr($data[static::IDX_DATE], 0, 2)
        )
    ) {
            throw new InvalidDataException("Phone call date not handled (".$data[static::IDX_DATE].")", 1);
        }
    }
}
