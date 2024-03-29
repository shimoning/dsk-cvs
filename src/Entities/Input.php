<?php

namespace Shimoning\DskCvs\Entities;

use Shimoning\DskCvs\Contracts\Entities\PostInput;
use Shimoning\DskCvs\Values\Date;
use Shimoning\DskCvs\Exceptions\ParameterException;

class Input implements PostInput
{
    private string $_userId;
    private string $_pw;
    private ?Date $_fromDate;
    private ?Date $_toDate;

    /**
     * 収納データ取得用入力
     *
     * @param string $userId
     * @param string $pw
     * @param Date|null $fromDate
     * @param Date|null $toDate
     */
    public function __construct(
        string $userId,
        string $pw,
        ?Date $fromDate = null,
        ?Date $toDate = null,
    ) {
        $this->validateDate($fromDate, $toDate);

        $this->_userId = $userId;
        $this->_pw = $pw;
        $this->_fromDate = $fromDate;
        $this->_toDate = $toDate;
    }

    private function validateDate(?Date $fromDate, ?Date $toDate)
    {
        if ((!$fromDate && $toDate) || ($fromDate && !$toDate)) {
            throw new ParameterException('from_date と to_date は片方だけ指定できません。');
        }
        if ($fromDate && $toDate && $fromDate > $toDate) {
            throw new ParameterException('from_date は to_date より過去である必要があります。');
        }
    }

    public function getInput(): array
    {
        return $this->_fromDate && $this->_toDate ?
            [
                'USER_ID' => $this->_userId,
                'PW' => $this->_pw,
                'FROM_DATE' => $this->_fromDate->get(),
                'TO_DATE' => $this->_toDate->get(),
            ] : [
                'USER_ID' => $this->_userId,
                'PW' => $this->_pw,
            ];
    }

    public function setDate(?Date $fromDate, ?Date $toDate)
    {
        $this->validateDate($fromDate, $toDate);
        $this->_fromDate = $fromDate;
        $this->_toDate = $toDate;
    }
}
