<?php

declare(strict_types=1);

namespace Caiofior\CatholicLiturgical;

/**
 * Searches today prey
 *
 * @author caiofior
 */
class SearchPrey {

    /** @var \Doctrine\ORM\EntityManager $entityManager */
    private $entityManager;

    /** @var \Caiofior\CatholicLiturgical\model\CalendarProperties */
    private $calendar;

    /** @var \DateTime */
    private $today;

    /**
     * 
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \Caiofior\CatholicLiturgical\model\CalendarProperties $calendar
     * @param \DateTime $today
     */
    public function __construct($entityManager, $calendar, $today) {
        $this->entityManager = $entityManager;
        $this->calendar = $calendar;
        $this->today = $today;
    }

    /**
     * Gets today prey
     * @return \Caiofior\CatholicLiturgical\model\Prey
     */
    public function getPrey() {
        $lithurgicCalendar = new \Caiofior\CatholicLiturgical\Calendar($this->today->format('Y-m-d'));
        $queryBuilder = null;
        
        $query = $this->getSelect( $queryBuilder);
        $this->getToday($query, $queryBuilder);
        $results = $query->fetchAllAssociative();
         if(empty($results)) {
            $query = $this->getSelect($queryBuilder);
            $this->getSpecialFest($query, $queryBuilder,$lithurgicCalendar);
            $results = $query->fetchAllAssociative();
        }
        if(empty($results)) {
            $query = $this->getSelect($queryBuilder);
            $this->getLithurgicEve($query, $queryBuilder,$lithurgicCalendar);
            $results = $query->fetchAllAssociative();
        } 
        return $results;
    }
    
    private function getSelect(& $queryBuilder) {
        $queryBuilder = $this->entityManager
                ->getConnection()
                ->createQueryBuilder();
        $query = $queryBuilder
                ->select('p.*')
                ->from('prey', 'p')
                ->leftJoin(
                        'p',
                        'calendar_properties',
                        'cp',
                        'cp.id = p.calendar_id'
                )
                ->groupBy('p.id');
        return $query;
    }
    
    private function getToday(& $query ,$queryBuilder) {
        $query = $query->where($queryBuilder->expr()->eq('p.today', ':today'))
                ->setParameter('today', $this->today->format('Y-m-d'));

        if (!empty($this->calendar->getData()['id'])) {
            $query = $query
                    ->andWhere(
                            $queryBuilder->expr()->eq('p.calendar_id', ':calendar')
                    )
                    ->setParameter('calendar', $this->calendar->getData()['id']);
        }
    }
    
    private function getSpecialFest(& $query ,$queryBuilder,$lithurgicCalendar) {
         
        $query = $query->where(
                                $queryBuilder->expr()->eq('p.special_fest', ':special_fest')
                        )
                ->setParameter('special_fest', $lithurgicCalendar->getSpecialFest());
       

        if (!empty($this->calendar->getData()['id'])) {
            $query = $query
                    ->andWhere(
                            $queryBuilder->expr()->eq('p.calendar_id', ':calendar')
                    )
                    ->setParameter('calendar', $this->calendar->getData()['id']);
        }
    }
    
    private function getLithurgicEve(& $query ,$queryBuilder,$lithurgicCalendar) {
         if ($this->calendar->getData()['lithurgicYear'] == true) {
            $query = $query->andWhere($queryBuilder->expr()->eq('p.lithurgic_year', ':lithurgic_year'))
                    ->setParameter('lithurgic_year', $lithurgicCalendar->getLithurgicYear());
         }
         if ($this->calendar->getData()['lithurgicEve'] == true) {
            $query = $query->where(
                                    $queryBuilder->expr()->andX(
                                            $queryBuilder->expr()->eq('p.lithurgic_eve', ':lithurgic_eve'),
                                            $queryBuilder->expr()->eq('p.lithurgic_week', ':lithurgic_week')
                                    )
                            )
                    ->setParameter('lithurgic_eve', $lithurgicCalendar->getDateTime()->getTime())
                    ->setParameter('lithurgic_week', $lithurgicCalendar->getDateTime()->getWeekTimeNumber());
        } else {
            $query = $query->where('FALSE');
        }

        if (!empty($this->calendar->getData()['id'])) {
            $query = $query
                    ->andWhere(
                            $queryBuilder->expr()->eq('p.calendar_id', ':calendar')
                    )
                    ->setParameter('calendar', $this->calendar->getData()['id']);
        }
    }

}
