<?php

namespace Caiofior\CatholicLiturgical;

/**
 * Liturgich Week
 *
 * @author caiofior
 */
class Week {
    /**
     * Liturgic time
     * @var string
     */
    private string $time;
    /**
     * Week time number
     * @var int
     */
    private int $weekTimeNumber;
    /**
     * Salter number
     * @var int
     */
    private int $weekPsalterNumber;
    /**
     * Sets lithurgic time
     * @param string $time
     */
    public function setTime (string $time ) {
        $this->time = $time;
    }
    /**
     * Sets week time number
     * @param int $weekTimeNumber
     */
    public function setWeekTimeNumber (int $weekTimeNumber ) {
        $this->weekTimeNumber = $weekTimeNumber;
    }
    /**
     * Sets week Psalter number
     * @param int $weekPsalterNumber
     */
    public function setWeekPsalterNumber (int $weekPsalterNumber ) {
        $this->weekPsalterNumber = $weekPsalterNumber;
    }
    /**
     * Gest time of lithurgic year
     * @return string
     */
    public function getTime () : string  {
        return $this->time;
    }
    /**
     * Gets week time number
     * @return int
     */
    public function getWeekTimeNumber () : int  {
        return $this->weekTimeNumber;
    }
    /**
     * Gets psalter number
     * @return int
     */
    public function getWeekPsalterNumber () : int {
        return $this->weekPsalterNumber;
    }
}
