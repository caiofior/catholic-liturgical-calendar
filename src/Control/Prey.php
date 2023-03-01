<?php

declare(strict_types=1);

namespace Caiofior\Control;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

/**
 * Parse routes calendar
 */
class Prey {

    /**
     * Parse routes
     * @param RouteCollectorProxy $group
     * @return Response|void
     */
    public static function parse(RouteCollectorProxy $group) {
        $group->any('', function (Request $request, Response $response, $args) {
            $entityManager = $this->get('entity_manager');
            $dateFormatter = $this->get('date_formatter');
            $calendarId = (int)($request->getQueryParams()['calendario'] ?? 0);
            if($calendarId == 0) {
                $option = $entityManager->find('\Caiofior\Core\model\Option', 'default_calendar');
                $calendarId = $option->getValue();
            }
            /** @var \Caiofior\CatholicLiturgical\model\CalendarProperties $calendar */
            $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\model\CalendarProperties', $calendarId);
            
            $today = \DateTime::createFromFormat('Y-m-d', ($request->getQueryParams()['giorno']??''));
            if(!is_object($today)) {
                $today = new \DateTime();
            }
            $previousDay = clone $today;
            $previousDay = $previousDay->sub(new \DateInterval('P1D'));
            $nextDay = clone $today;
            $nextDay = $nextDay->add(new \DateInterval('P1D'));
            
            $theme = ($this->get('settings')['theme'] ?? '');
            $page = 'prey';
            if (!empty($theme)) {
                require __DIR__ . '/../../theme/' . $theme . '/index.php';
            }
            return $response;
        });
        $group->any('/list', function (Request $request, Response $response, $args) {
            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = $this->get('entity_manager');
            
            $today = \DateTime::createFromFormat('Y-m-d', ($request->getQueryParams()['giorno']??''));
            if(!is_object($today)) {
                $today = new \DateTime();
            }
            $calendarId = (int)($request->getQueryParams()['calendario'] ?? 0);
            if($calendarId == 0) {
                $option = $entityManager->find('\Caiofior\Core\model\Option', 'default_calendar');
                $calendarId = $option->getValue();
            }
            /** @var \Caiofior\CatholicLiturgical\model\CalendarProperties $calendar */
            $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\model\CalendarProperties', $calendarId);

            $lithurgicCalendar = new \Caiofior\CatholicLiturgical\Calendar($today->format('Y-m-d'));           
            
            $queryBuilder = $entityManager
                    ->getConnection()
                    ->createQueryBuilder();
            $totalNotFiltered = $queryBuilder
                    ->select('COUNT(*)')
                    ->from('prey')
                    ->fetchFirstColumn();
            $query = $entityManager
                    ->getConnection()
                    ->createQueryBuilder()
                    ->select('COUNT(*)')
                    ->from('prey','p')
                    ->leftJoin(
                    'p',
                    'calendar_properties',
                    'cp',
                    'cp.id = p.calendar_id'
                    )
                    ->leftJoin(
                    'cp',
                    'login',
                    'l',
                    'cp.profile_id = l.profile_id'
                    )
                    ->setFirstResult(($request->getQueryParams()['offset'] ?? 0))
                    ->setMaxResults(($request->getQueryParams()['limit'] ?? 10));
            if($calendar->getData()['lithurgicYear']==true) {
                $query = $query->andWhere($queryBuilder->expr()->eq('p.lithurgic_year', ':lithurgic_year'))
                    ->setParameter('lithurgic_year', $lithurgicCalendar->getLithurgicYear());
            }
            if($calendar->getData()['lithurgicEve']==true) {
                $query = $query->andWhere($queryBuilder->expr()->orX(
                        $queryBuilder->expr()->andX(
                                $queryBuilder->expr()->eq('p.lithurgic_eve', ':lithurgic_eve'),
                                $queryBuilder->expr()->eq('p.lithurgic_week', ':lithurgic_week')
                        ),
                        $queryBuilder->expr()->eq('p.special_fest', ':special_fest')
                        
                ))
                ->setParameter('lithurgic_eve', $lithurgicCalendar->getDateTime()->getTime())
                ->setParameter('lithurgic_week', $lithurgicCalendar->getDateTime()->getWeekTimeNumber())
                ->setParameter('special_fest', $lithurgicCalendar->getSpecialFest());
            }
            
            
            $query = $query->orWhere($queryBuilder->expr()->eq('p.today', ':today'))
                    ->setParameter('today', $today->format('Y-m-d'));
            
            if(!empty($calendar->getData()['id'])) {
            $query = $query
                        ->andWhere(
                                $queryBuilder->expr()->eq('p.calendar_id', ':calendar')
                        )
                        ->setParameter('calendar', $calendar->getData()['id']);
            }
            if (!empty($request->getQueryParams()['search'])) {
                $query = $query
                        ->where(
                                $queryBuilder->expr()->like('p.title', ':search')
                        )
                        ->setParameter('search', '%' . ($request->getQueryParams()['search'] ?? '') . '%');
            }
            
            
            $total = $query->fetchFirstColumn();
            
            $query = $entityManager
                    ->getConnection()
                    ->createQueryBuilder()
                    ->select('p.*', 'l.username')
                    ->from('prey', 'p')
                    ->leftJoin(
                    'p',
                    'calendar_properties',
                    'cp',
                    'cp.id = p.calendar_id'
                    )
                    ->leftJoin(
                    'cp',
                    'login',
                    'l',
                    'cp.profile_id = l.profile_id'
                    )->groupBy('p.id');
            
            if($calendar->getData()['lithurgicYear']==true) {
                $query = $query->andWhere($queryBuilder->expr()->eq('p.lithurgic_year', ':lithurgic_year'))
                    ->setParameter('lithurgic_year', $lithurgicCalendar->getLithurgicYear());
            }
            if($calendar->getData()['lithurgicEve']==true) {
                $query = $query->andWhere($queryBuilder->expr()->orX(
                        $queryBuilder->expr()->andX(
                                $queryBuilder->expr()->eq('p.lithurgic_eve', ':lithurgic_eve'),
                                $queryBuilder->expr()->eq('p.lithurgic_week', ':lithurgic_week')
                        ),
                        $queryBuilder->expr()->eq('p.special_fest', ':special_fest')
                        
                ))
                ->setParameter('lithurgic_eve', $lithurgicCalendar->getDateTime()->getTime())
                ->setParameter('lithurgic_week', $lithurgicCalendar->getDateTime()->getWeekTimeNumber())
                ->setParameter('special_fest', $lithurgicCalendar->getSpecialFest());
                            
            }
            
            
            $query = $query->orWhere($queryBuilder->expr()->eq('p.today', ':today'))
                    ->setParameter('today', $today->format('Y-m-d'));
            
            if(!empty($calendar->getData()['id'])) {
            $query = $query
                        ->andWhere(
                                $queryBuilder->expr()->eq('p.calendar_id', ':calendar')
                        )
                        ->setParameter('calendar', $calendar->getData()['id']);
            }
            if (!empty($request->getQueryParams()['search'])) {
                $query = $query
                        ->where(
                                $queryBuilder->expr()->like('p.title', ':search')
                        )
                        ->setParameter('search', '%' . ($request->getQueryParams()['search'] ?? '') . '%');
            }
            if (!empty($request->getQueryParams()['sort'])) {
                $query = $query
                        ->orderBy($request->getQueryParams()['sort'] ?? '', $request->getQueryParams()['order'] ?? '');
            }
            $query = $query
                    ->setFirstResult(($request->getQueryParams()['offset'] ?? 0))
                    ->setMaxResults(($request->getQueryParams()['limit'] ?? 10));
            
            $results = $query
                    ->fetchAllAssociative();
            $data = array();
            array_walk($results, function ($value, $key) use (&$data) {
                $data[$key] = json_decode(json_encode($value));
                $approved= '';
                if ($data[$key]->approved == true) {
                    $approved = <<<EOT
     <span class="ti-check"></span>
EOT;
                }
                $data[$key]->actions = <<<EOT
<div class="icon-container">
    {$approved}
    <a href="{$this->get('settings')['baseUrl']}/index.php/preghiere/modifica/{$data[$key]->id}">
        <span class="ti-pencil"></span>
    </a>
    <a class="trash" href="{$this->get('settings')['baseUrl']}/index.php/preghiere/cancella/{$data[$key]->id}">
        <span class="ti-trash"></span>  
    </a>
</div>  
EOT;
            });
            return $response->withJson([
                'total' => $total,
                'totalNotFiltered' => $totalNotFiltered,
                'rows' => $data
                    ], 201);
        });
        $group->any('/modifica/[{id}]', function (Request $request, Response $response,$args) {
            
            $entityManager = $this->get('entity_manager');
            $dateFormatter = $this->get('date_formatter');
            /** @var \Caiofior\CatholicLiturgical\model\CalendarProperties $calendar */
            $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\model\CalendarProperties', ($request->getQueryParams()['calendario'] ?? 0));
            /** @var \Caiofior\CatholicLiturgical\Prey $prey */
            $prey = new \Caiofior\CatholicLiturgical\model\Prey();
            if (!empty($args['id'])) {
                $prey = $entityManager->find('\Caiofior\CatholicLiturgical\model\Prey', ($args['id']));
                $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\model\CalendarProperties', ($prey->getData()['calendar_id'] ?? 0));
            }
            if (is_null($calendar)) {
                
                $id = $entityManager
                    ->getConnection()
                    ->createQueryBuilder()
                    ->select('p.*')
                    ->from('calendar_properties', 'p')
                    ->fetchOne();
                
                $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\model\CalendarProperties', $id);
            }
            $today = \DateTime::createFromFormat('Y-m-d', ($request->getQueryParams()['giorno']??''));
            if(!is_object($today)) {
                $today = new \DateTime();
            }
            $previousDay = clone $today;
            $previousDay = $previousDay->sub(new \DateInterval('P1D'));
            $nextDay = clone $today;
            $nextDay = $nextDay->add(new \DateInterval('P1D'));
            /** @var \Caiofior\CatholicLiturgical\Calendar $calendar */
            $catholicCalendar = new \Caiofior\CatholicLiturgical\Calendar($today->format('Y-m-d'));
            $todayEve = $catholicCalendar->getDateTime();
            
            $theme = ($this->get('settings')['theme'] ?? '');
            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = $this->get('entity_manager');
            $message = '';
            $page = 'prey/add';
            /** @var \Caiofior\Core\model\Login $login */
            $login = $entityManager->find('\Caiofior\Core\model\Login', ($_SESSION['username'] ?? ''));
            /** @var \Caiofior\Core\model\Profile $profile */
            $profile = $entityManager->find('\Caiofior\Core\model\Profile', ($login->getProfileId() ?? null));
            /** @var \Caiofior\Core\model\Role $role */
            $role = $entityManager->find('\Caiofior\Core\model\Role', ($profile->getRoleId() ?? null));
            /** @var \Caiofior\Core\model\Option $option */
            $option = $entityManager->find('\Caiofior\Core\model\Option', 'default_calendar');
            if (!is_object($option)) {
                $option = new \Caiofior\Core\Option();
                $option->setOption('default_calendar');
            }
            if (isset($request->getParsedBody()['salva'])) {
                try {
                    $data = $request->getParsedBody();
                    $data['creation']=new \DateTimeImmutable();
                    if(!isset($data['use_lithurgic_eve'])) {
                        unset($data['lithurgic_eve']);
                    }
                    if(!isset($data['use_lithurgic_week'])) {
                        unset($data['lithurgic_week']);
                    }
                    if(!isset($data['use_lithurgic_year'])) {
                        unset($data['lithurgic_year']);
                    }
                    if(!isset($data['use_salter_week'])) {
                        unset($data['salter_week']);
                    }
                    if(!isset($data['use_day_of_week'])) {
                        unset($data['day_of_week']);
                    }
                    if(!isset($data['use_day_of_year'])) {
                        unset($data['day_of_year']);
                    }
                    if(!isset($data['use_today'])) {
                        unset($data['today']);
                    }
                    if(!isset($data['use_special_fest'])) {
                        unset($data['special_fest']);
                    }
                    $prey->setData($data);
                    $entityManager->persist($prey);
                    $entityManager->flush();
                    return $response->withHeader('Location', $this->get('settings')['baseUrl'] . '/index.php/preghiere?calendario='.$request->getParsedBody()['calendar_id'].'&giorno='.$request->getParsedBody()['today'])->withStatus(302);
                } catch (\Exception $e) {
                    throw $e;
                }
            }
            if (!empty($theme)) {
                require __DIR__ . '/../../theme/' . $theme . '/index.php';
            }
            return $response;
        });
        $group->any('/cancella/{id}', function (Request $request, Response $response, $args) {
            $theme = ($this->get('settings')['theme'] ?? '');
            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = $this->get('entity_manager');
            $message = '';
            $page = 'calendar/add';
            $prey = $entityManager->find('\Caiofior\CatholicLiturgical\model\Prey', $args['id']);
            $entityManager->remove($prey);
            $entityManager->flush();
            return $response->withHeader('Location', $this->get('settings')['baseUrl'] . '/index.php/preghiere')->withStatus(302);
        });
    }

}
