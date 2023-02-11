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
            /** @var \Caiofior\CatholicLiturgical\CalendarProperties $calendar */
            $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\CalendarProperties', ($request->getQueryParams()['calendario'] ?? 0));
            if(!is_object($calendar)) {
                $calendar = new \Caiofior\CatholicLiturgical\CalendarProperties();
            }
            
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
            
            /** @var \Caiofior\CatholicLiturgical\CalendarProperties $calendar */
            $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\CalendarProperties', ($request->getQueryParams()['calendario'] ?? 0));
            if(!is_object($calendar)) {
                $calendar = new \Caiofior\CatholicLiturgical\CalendarProperties();
            }
            
            $today = \DateTime::createFromFormat('Y-m-d', ($request->getQueryParams()['giorno']??''));
            if(!is_object($today)) {
                $today = new \DateTime();
            }
            
            $queryBuilder = $entityManager
                    ->getConnection()
                    ->createQueryBuilder();
            $totalNotFiltered = $queryBuilder
                    ->select('COUNT(*)')
                    ->from('prey')
                    ->fetchFirstColumn();
            $total = $entityManager
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
                    ->setMaxResults(($request->getQueryParams()['limit'] ?? 10))
                    ->fetchFirstColumn();
            
            
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
           
            if(!empty($calendar->getData()['id'])) {
            $query = $query
                        ->where(
                                $queryBuilder->expr()->like('p.calendar_id', ':calendar')
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
                if ($data[$key]->approved == true) {
                    $data[$key]->approved = <<<EOT
<div class="icon-container">
     <span class="ti-check"></span>
</div>
EOT;
                } else {
                    $data[$key]->approved = '';
                }
                $data[$key]->actions = <<<EOT
<div class="icon-container">
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
            /** @var \Caiofior\CatholicLiturgical\CalendarProperties $calendar */
            $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\CalendarProperties', ($request->getQueryParams()['calendario'] ?? 0));
            /** @var \Caiofior\CatholicLiturgical\Prey $prey */
            $prey = new \Caiofior\CatholicLiturgical\Prey();
            if (!empty($args['id'])) {
                $prey = $entityManager->find('\Caiofior\CatholicLiturgical\Prey', ($args['id']));
                $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\CalendarProperties', ($prey->getData()['calendar_id'] ?? 0));
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
            /** @var \Caiofior\Core\Login $login */
            $login = $entityManager->find('\Caiofior\Core\Login', ($_SESSION['username'] ?? ''));
            /** @var \Caiofior\Core\Profile $profile */
            $profile = $entityManager->find('\Caiofior\Core\Profile', ($login->getProfileId() ?? null));
            /** @var \Caiofior\Core\Role $role */
            $role = $entityManager->find('\Caiofior\Core\Role', ($profile->getRoleId() ?? null));
            /** @var \Caiofior\Core\Option $option */
            $option = $entityManager->find('\Caiofior\Core\Option', 'default_calendar');
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
                    $prey->setData($data);
                    $entityManager->persist($prey);
                    $entityManager->flush();
                    return $response->withHeader('Location', $this->get('settings')['baseUrl'] . '/index.php/calendari/modifica/'.$data['calendar_id'])->withStatus(302);
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
            $prey = $entityManager->find('\Caiofior\CatholicLiturgical\Prey', $args['id']);
            $entityManager->remove($prey);
            $entityManager->flush();
            return $response->withHeader('Location', $this->get('settings')['baseUrl'] . '/index.php/preghiere')->withStatus(302);
        });
    }

}
