<?php

namespace Tickets\Csv\Validator;

use Tickets\Csv\Validator\Generic;

/**
 * Validator for tickets' CSV line of type "SMS"
 */
class Sms extends Generic
{
    /**
     * Validate data
     * @param  array  $data
     * @return void
     */
    public function validate(array $data): void
    {
        parent::validate($data);
    }
}
