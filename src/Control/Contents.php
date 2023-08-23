<?php

declare(strict_types=1);

namespace Caiofior\Control;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

/**
 * Parse routes contents
 */
class Contents {

    /**
     * Parse routes
     * @param RouteCollectorProxy $group
     * @return Response|void
     */
    public static function parse(RouteCollectorProxy $group) {
        $group->any('', function (Request $request, Response $response, $args) {
            $theme = ($this->get('settings')['theme'] ?? '');
            $page = 'content';
            if (!empty($theme)) {
                require __DIR__ . '/../../theme/' . $theme . '/index.php';
            }
            return $response;
        });
        $group->any('/list', function (Request $request, Response $response, $args) {
            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = $this->get('entity_manager');
	    $login = $entityManager->find('\Caiofior\Core\model\Login', ($_SESSION['username'] ?? ''));
	    if(!is_object($login)) {
	        return $response->withHeader('Location', $this->get('settings')['baseUrl'] . '/index.php')->withStatus(302);
	    }
            $queryBuilder = $entityManager
                    ->getConnection()
                    ->createQueryBuilder();
            $totalNotFiltered = $queryBuilder
                    ->select('COUNT(*)')
                    ->from('content')
                    ->fetchFirstColumn();

            $total = $queryBuilder
                    ->select('COUNT(*)')
                    ->from('content')
                    ->setFirstResult(($request->getQueryParams()['offset'] ?? 0))
                    ->setMaxResults(($request->getQueryParams()['limit'] ?? 10))
                    ->fetchFirstColumn();

            $query = $queryBuilder
                    ->select('*')
                    ->from('content');
            if (!empty($request->getQueryParams()['search'])) {
                $query = $query
                        ->where(
                                $queryBuilder->expr()->like('title', ':search')
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
    <a href="{$this->get('settings')['baseUrl']}/index.php/contenuti/modifica/{$data[$key]->id}">
        <span class="ti-pencil"></span>
    </a>
    <a class="trash" href="{$this->get('settings')['baseUrl']}/index.php/contenuti/cancella/{$data[$key]->id}">
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
	    $login = $entityManager->find('\Caiofior\Core\model\Login', ($_SESSION['username'] ?? ''));
	    if(!is_object($login)) {
	        return $response->withHeader('Location', $this->get('settings')['baseUrl'] . '/index.php')->withStatus(302);
	    }
            $message = '';
            $page = 'content/add';
            $content = new \Caiofior\Core\Content();
            if (!empty($args['id'])) {
                $content = $entityManager->find('\Caiofior\Core\Content', $args['id']);
            }
            if (isset($request->getParsedBody()['salva'])) {
                try {
                    $data = $request->getParsedBody();
                    $content->setData($data);
                    $entityManager->persist($content);
                    $entityManager->flush();
                    return $response->withHeader('Location', $this->get('settings')['baseUrl'] . '/index.php/contenuti')->withStatus(302);
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
            $content = $entityManager->find('\Caiofior\Core\Content', $args['id']);
            $entityManager->remove($content);
            $entityManager->flush();
            return $response->withHeader('Location', $this->get('settings')['baseUrl'] . '/index.php/contenuti')->withStatus(302);
        });
    }
}
