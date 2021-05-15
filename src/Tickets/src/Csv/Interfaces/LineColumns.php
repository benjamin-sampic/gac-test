<?php

namespace Tickets\Csv\Interfaces;

interface LineColumns
{
    const IDX_SUBSCRIBER_NO = 2;
    const IDX_DATE = 3;
    const IDX_HOUR = 4;
    const IDX_QTY_REAL = 5;
    const IDX_QTY_BILLED = 6;
    const IDX_TYPE = 7;
    const COLUMNS = [
        'SUSCRIBER_NO' => self::IDX_SUBSCRIBER_NO,
        'DATE' => self::IDX_DATE,
        'HOUR' => self::IDX_HOUR,
        'QTY_REAL' => self::IDX_QTY_REAL,
        'QTY_BILLED' => self::IDX_QTY_BILLED,
        'TYPE' => self::IDX_TYPE,
    ];
}
