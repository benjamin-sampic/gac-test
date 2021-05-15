<?php 

namespace Tickets\Csv\Parser;

use Tickets\Exception\InvalidDataException as InvalidDataException;
use Tickets\Csv\Validator\Data as DataValidator;
use Tickets\Csv\Validator\Phonecall as PhonecallValidator;
use Tickets\Csv\Validator\Sms as SmsValidator;

use Tickets\Entity\TicketsEntity;
use Tickets\Dao\TicketsDao;

/**
 *
 */
class Generic implements \Tickets\Csv\Interfaces\LineColumns
{
    /**
     * @var DataValidator
     */
    protected $dataValidator;

    /**
     * @var PhonecallValidator
     */
    protected $phonecallValidator;

    /**
     * @var SmsValidator
     */
    protected $smsValidator;

    /**
     * @var Tickets\Dao\TicketsDao
     */
    protected $ticketsDao;

    public function __construct()
    {
        $this->dataValidator = new DataValidator(static::COLUMNS);
        $this->phonecallValidator = new PhonecallValidator(static::COLUMNS);
        $this->smsValidator = new SmsValidator(static::COLUMNS);
        $this->ticketsDao = new TicketsDao();
    }

    /**
     * [import description]
     * @param  string $filepath [description]
     * @return array
     */
    public function import(string $filepath): array
    {
        $fh = fopen($filepath, 'rb');
        $errors = [];
        $ignored = [];
        $currentLineIndex = 0;
        $nbErrors = 0;
        $nbIgnored = 0;
        while (false !== ($data = fgetcsv($fh, 1000, ';'))) {
            try {
                $this->manageLine($data);
            } catch (InvalidDataException $e) {
                $nbIgnored++;
                $ignored[] = [
                    'index'=>$currentLineIndex,
                    'line'=>$data,
                    'exception'=>$e->getMessage(),
                ];
            } catch (\Gac\Exception\DbException $e) {
                $nbErrors++;
                $errors[] = [
                    'index'=>$currentLineIndex,
                    'line'=>$data,
                    'exception'=>$e->getMessage(),
                ];
            } catch (\Exception $e) {
                $nbErrors++;
                $errors[] = [
                    'index'=>$currentLineIndex,
                    'line'=>$data,
                    'exception'=>$e->getMessage(),
                ];
            }
            ++$currentLineIndex;
        }
        fclose($fh);

        return [
            'total'=>$currentLineIndex,
            'nbErrors'=>$nbErrors,
            'nbIgnored'=>$nbIgnored,
            'nbSuccess'=>($currentLineIndex - ($nbIgnored + $nbErrors)),
            'errors'=>$errors,
            'ignored'=>$ignored,
        ];
    }

    /**
     * [manageLine description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    private function manageLine(array $data)
    {
        // Phone calss
        if ($this->isPhonecall($data[static::IDX_TYPE])) {
            $this->managePhonecall($data);
        }
        // SMS
        elseif ($this->isSms($data[static::IDX_TYPE])) {
            $this->managSms($data);
        }
        // DATA
        elseif ($this->isData($data[static::IDX_TYPE])) {
            $this->manageData($data);
        }
        // Others
        else {
            throw new InvalidDataException('Type "' . $data[static::IDX_TYPE] . '" not handled', 1);
        }
    }

    /**
     * [managePhonecall description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    private function managePhonecall(array $data)
    {
        $this->phonecallValidator->validate($data);
        $ticket = (new TicketsEntity())
        ->setSubscriberNumber((int) $data[static::IDX_SUBSCRIBER_NO])
        ->setDatetime(new \DateTime(substr($data[static::IDX_DATE], 6, 4) . '/' . substr($data[static::IDX_DATE], 3, 2) .'/' .substr($data[static::IDX_DATE], 0, 2) .' ' . $data[static::IDX_HOUR]))
        ->setQuantityReal((int) substr($data[static::IDX_QTY_REAL], 0, 2) * 3600 + (int) substr($data[static::IDX_QTY_REAL], 3, 2) * 60 + (int) substr($data[static::IDX_QTY_REAL], 6, 2))
        ->setQuantityBilled((int) substr($data[static::IDX_QTY_BILLED], 0, 2) * 3600 + (int) substr($data[static::IDX_QTY_BILLED], 3, 2) * 60 + (int) substr($data[static::IDX_QTY_BILLED], 6, 2))
        ->setType(TicketsEntity::TYPE_PHONECALL)
        ->setTypeDetails(utf8_encode($data[static::IDX_TYPE]));
        $this->ticketsDao->insert($ticket);
    }

    /**
     * [managSms description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    private function managSms(array $data)
    {
        $this->smsValidator->validate($data);
        $ticket = (new TicketsEntity())
        ->setSubscriberNumber((int) $data[static::IDX_SUBSCRIBER_NO])
        ->setDatetime(new \DateTime(substr($data[static::IDX_DATE], 6, 4) . '/' . substr($data[static::IDX_DATE], 3, 2) .'/' .substr($data[static::IDX_DATE], 0, 2) .' ' . $data[static::IDX_HOUR]))
        ->setQuantityReal(1)
        ->setQuantityBilled(1)
        ->setType(TicketsEntity::TYPE_SMS)
        ->setTypeDetails(utf8_encode($data[static::IDX_TYPE]));
        $this->ticketsDao->insert($ticket);
    }

    /**
     * [manageData description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    private function manageData(array $data)
    {
        $this->dataValidator->validate($data);
        $ticket = (new TicketsEntity())
        ->setSubscriberNumber((int) $data[static::IDX_SUBSCRIBER_NO])
        ->setDatetime(new \DateTime(substr($data[static::IDX_DATE], 6, 4) . '/' . substr($data[static::IDX_DATE], 3, 2) .'/' .substr($data[static::IDX_DATE], 0, 2) .' ' . $data[static::IDX_HOUR]))
        ->setQuantityReal((int) $data[static::IDX_QTY_REAL])
        ->setQuantityBilled((int) $data[static::IDX_QTY_BILLED])
        ->setType(TicketsEntity::TYPE_DATA)
        ->setTypeDetails(utf8_encode($data[static::IDX_TYPE]));
        $this->ticketsDao->insert($ticket);
    }

    /**
     * [isPhonecall description]
     * @param  string  $type [description]
     * @return boolean       [description]
     */
    private function isPhonecall(string $type): bool
    {
        return false !== strpos($type, 'appel')
                || false !== strpos($type, 'Appel')
                || false !== strpos($type, 'suivi conso')
                || false !== strpos($type, 'consultation messagerie vocale')
        ;
    }

    /**
     * [isSms description]
     * @param  string  $type [description]
     * @return boolean       [description]
     */
    private function isSms(string $type): bool
    {
        return false !== strpos($type, 'sms')
        && false !== strpos($type, 'envoi');
    }

    /**
     * [isData description]
     * @param  string  $type [description]
     * @return boolean       [description]
     */
    private function isData(string $type): bool
    {
        return 'connexion' === substr($type, 0, 9)
                || false !== strpos($type, 'mms')
        ;
    }
}
