<?php

declare(strict_types=1);

namespace Caiofior\Control;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

/**
 * Parse routes calendar
 */
class CalendarProperties {

    /**
     * Parse routes
     * @param RouteCollectorProxy $group
     * @return Response|void
     */
    public static function parse(RouteCollectorProxy $group) {
        $group->any('', function (Request $request, Response $response, $args) {
            $theme = ($this->get('settings')['theme'] ?? '');
            $page = 'calendar';
            if (!empty($theme)) {
                require __DIR__ . '/../../theme/' . $theme . '/index.php';
            }
            return $response;
        });
        $group->any('/list', function (Request $request, Response $response, $args) {
            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = $this->get('entity_manager');
            $queryBuilder = $entityManager
                    ->getConnection()
                    ->createQueryBuilder();
            $totalNotFiltered = $queryBuilder
                    ->select('COUNT(*)')
                    ->from('calendar_properties')
                    ->fetchFirstColumn();

            $total = $queryBuilder
                    ->select('COUNT(*)')
                    ->from('calendar_properties')
                    ->setFirstResult(($request->getQueryParams()['offset'] ?? 0))
                    ->setMaxResults(($request->getQueryParams()['limit'] ?? 10))
                    ->fetchFirstColumn();

            $query = $queryBuilder
                    ->select('cp.*', 'l.username')
                    ->from('calendar_properties', 'cp')
                    ->join(
                    'cp',
                    'login',
                    'l',
                    'cp.profile_id = l.profile_id'
            );
            if (!empty($request->getQueryParams()['search'])) {
                $query = $query
                        ->where(
                                $queryBuilder->expr()->like('cp.name', ':search')
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
                if ($data[$key]->public == true) {
                    $data[$key]->public = <<<EOT
<div class="icon-container">
     <span class="ti-check"></span>
</div>
EOT;
                } else {
                    $data[$key]->public = '';
                }
                $data[$key]->actions = <<<EOT
<div class="icon-container">
    <a href="{$this->get('settings')['baseUrl']}/index.php/calendari/modifica/{$data[$key]->id}">
        <span class="ti-pencil"></span>
    </a>
    <a class="trash" href="{$this->get('settings')['baseUrl']}/index.php/calendari/cancella/{$data[$key]->id}">
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
        $group->any('/modifica[/{id}]', function (Request $request, Response $response, $args) {
            $theme = ($this->get('settings')['theme'] ?? '');
            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = $this->get('entity_manager');
            $message = '';
            $page = 'calendar/add';
            /** @var \Caiofior\CatholicLiturgical\model\CalendarProperties $calendar */
            $calendar = new \Caiofior\CatholicLiturgical\model\CalendarProperties();
            if (!empty($args['id'])) {
                $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\model\CalendarProperties', $args['id']);
            }
            /** @var \Caiofior\Core\model\Login $login */
            $login = $entityManager->find('\Caiofior\Core\Login', ($_SESSION['username'] ?? ''));
            /** @var \Caiofior\Core\model\Profile $profile */
            $profile = $entityManager->find('\Caiofior\Core\Profile', ($login->getProfileId() ?? null));
            /** @var \Caiofior\Core\model\Role $role */
            $role = $entityManager->find('\Caiofior\Core\Role', ($profile->getRoleId() ?? null));
            /** @var \Caiofior\Core\model\Option $option */
            $option = $entityManager->find('\Caiofior\Core\model\Option', 'default_calendar');
            if (!is_object($option)) {
                $option = new \Caiofior\Core\model\Option();
                $option->setOption('default_calendar');
            }
            if (isset($request->getParsedBody()['salva'])) {
                try {
                    $data = $request->getParsedBody();
                    if (empty($calendar->getProfileId())) {
                        $data['profile_id'] = $login->getProfileId();
                    }
                    $calendar->setData($data);
                    $entityManager->persist($calendar);
                    $entityManager->flush();
                    if (
                            !empty($data['default'])
                    ) {
                        $option->setValue((string) $calendar->getData()['id']);
                        $entityManager->persist($option);
                        $entityManager->flush();
                    }
                    return $response->withHeader('Location', $this->get('settings')['baseUrl'] . '/index.php/calendari')->withStatus(302);
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
            $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\CalendarProperties', $args['id']);
            $entityManager->remove($calendar);
            $entityManager->flush();
            return $response->withHeader('Location', $this->get('settings')['baseUrl'] . '/index.php/calendari')->withStatus(302);
        });
    }
}
