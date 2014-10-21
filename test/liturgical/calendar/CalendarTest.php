<?php
namespace liturgical\calendar;
if (!class_exists('liturgical\calendar\Calendar')) {
   require __DIR__.DIRECTORY_SEPARATOR.'..'.
        DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.
        DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'liturgical'.
        DIRECTORY_SEPARATOR.'calendar'.DIRECTORY_SEPARATOR.'Calendar.php'
        ;
}

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-10-21 at 11:54:01.
 */
class CalendarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Calendar
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {

    }
    public function testGetCurrentDay() {
       $this->object = new Calendar(2014);
       $dateTime = $this->object->getCalendar('today');
       $this->assertTrue (is_string($dateTime->time) && $dateTime->time != '');
       $this->assertTrue (is_numeric($dateTime->weekTimeNumber) && $dateTime->weekTimeNumber > 0);
       $this->assertTrue (is_numeric($dateTime->weekPsalterNumber) && $dateTime->weekPsalterNumber > 0);
       var_dump($dateTime);
       $this->setExpectedException(
          'Exception'
        );
       $dateTime = $this->object->getCalendar('dsfgsdg');
    }
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
}
