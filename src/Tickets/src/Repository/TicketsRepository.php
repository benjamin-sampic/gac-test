<?php 

namespace Tickets\Repository;

use Tickets\Model\TicketsModel;

class TicketsRepository extends AbstractRepository
{
    public function insert(TicketsModel $model)
    {
        return $this->dbConnection->query('
    		INSERT INTO `tickets`
    		(`tic_subscriber_number`,`tic_datetime`,`tic_time`,`tic_qty_real`,`tic_qty_billed`,`tic_type`,`tic_type_details`)
    		VALUES
    		(:subscriber_number,:datetime_,:time,:qty_real,:qty_billed,:type,:type_details)
		', [
            // ':tablename'=>'tickets',
            ':subscriber_number'=>$model->getSubscriberNumber(),
            ':datetime_'=>$model->getDatetime()->format('Y-m-d H:i:s'),
            ':time'=>$model->getDatetime()->format('H:i:s'),
            ':qty_real'=>$model->getQuantityReal(),
            ':qty_billed'=>$model->getQuantityBilled(),
            ':type'=>$model->getType(),
            ':type_details'=>$model->getTypeDetails(),
        ]);
    }

    /**
     * Empty table
     * @return [type] [description]
     */
    public function empty(): \PDOStatement
    {
        return $this->dbConnection->query('DELETE FROM `tickets` WHERE 1=1');
    }

    /**
     * get total phone call duration from 15 February 2012 included
     * @return [type] [description]
     */
    public function getTotalPhonecallDurationFrom15February2012Included(): \StdClass
    {
        return  $this->dbConnection->getAsObjects('
            SELECT SUM(`tic_qty_real`) as duration
            FROM `tickets`
            WHERE `tic_type` = :type
            AND `tic_datetime` > :datetime_
        ', [
            'type'=>\Tickets\Entity\TicketsEntity::TYPE_PHONECALL,
            'datetime_'=>'2012-02-15 00:00:00'
        ])[0];
    }

    /**
     * get total SMS sent for february 2012
     * @return StdClass
     */
    public function getTotalSmsSentForFebruary2012(): \StdClass
    {
        return  $this->dbConnection->getAsObjects('
            SELECT SUM(`tic_qty_real`) as total
            FROM `tickets`
            WHERE `tic_type` = :type
            AND `tic_datetime` BETWEEN :datetime_min AND :datetime_max
        ', [
            'type'=>\Tickets\Entity\TicketsEntity::TYPE_SMS,
            'datetime_min'=>'2012-02-01 00:00:00',
            'datetime_max'=>'2012-02-30 23:59:59'
        ])[0];
    }

    /**
     * get top 10 data usage by subscriber for february2012
     * @return array
     */
    public function getTop10DataUsageBySubscriberForFebruary2012(): array
    {
        $this->dbConnection->query('SET @rank := 0;');
        $this->dbConnection->query('SET @current_subscriber_number := 0;');
        return $this->dbConnection->getAsObjects('
            SELECT  subquery.`tic_subscriber_number` as `subscriberNumber`,
                    subquery.`tic_qty_real` as `qty`,
                    subquery.`rank` as `rank`
            FROM (
                SELECT
                    subsubquery.`tic_subscriber_number` as `tic_subscriber_number`,
                    subsubquery.`tic_qty_real` as `tic_qty_real`,
                    @rank := IF(@current_subscriber_number = subsubquery.`tic_subscriber_number`, @rank + 1, 1) as rank,
                    @current_subscriber_number := subsubquery.`tic_subscriber_number`
                FROM (
                    SELECT DISTINCT
                        `tic_subscriber_number`,
                        `tic_qty_real`
                    FROM `tickets`
                    WHERE `tic_type` = :type
                    AND (
                        `tic_time` < :time_min
                        OR
                        `tic_time` > :time_max
                    )
                    ORDER BY `tic_subscriber_number` ASC, `tic_qty_real` DESC
                ) as subsubquery
            ) AS subquery
            WHERE subquery.`rank` <= 10
            ORDER BY subquery.`tic_subscriber_number`,
                    subquery.`tic_qty_real` DESC,
                    subquery.`rank` ASC;
        ', [
            'type'=>\Tickets\Entity\TicketsEntity::TYPE_DATA,
            'time_min'=>"08:00:00",
            'time_max'=>"18:00:00",
        ]);
    }
}
