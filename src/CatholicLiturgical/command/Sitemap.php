<?php

declare(strict_types=1);

namespace Caiofior\CatholicLiturgical\command;

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Generates a sitemap.
 */
class Sitemap extends Command
{
    private ContainerInterface $containerInterface;

    public function __construct(ContainerInterface $containerInterface)
    {
        parent::__construct();

        $this->containerInterface = $containerInterface;
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('seo:sitemap-generator')
             ->setDescription('Generate the sitemap')
             ->setHelp(<<<'EOT'
Generates the sitemap.
EOT
             );
    }

    /**
     * {@inheritdoc}
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ui = (new SymfonyStyle($input, $output))->getErrorStyle();
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->containerInterface->get('entity_manager');
        
        $siteUrl = ($this->containerInterface->get('settings')['siteUrl']??null);
        $sitenameFilePath = __DIR__.'/../../../sitemap.xml';
        file_put_contents($sitenameFilePath, <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

EOT
);
        $today = new \DateTime();
        
        $option = $entityManager->find('\Caiofior\Core\model\Option', 'default_calendar');
        $calendarId = $option->getValue();
        $allPreys = [];
        
        /** @var \Caiofior\CatholicLiturgical\model\CalendarProperties $calendar */
        $calendar = $entityManager->find('\Caiofior\CatholicLiturgical\model\CalendarProperties', $calendarId);
        
        for($c = 0; $c <100; $c++) {
            
            $lithurgicCalendar = new \Caiofior\CatholicLiturgical\Calendar($today->format('Y-m-d'));

            $queryBuilder = $entityManager
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
                if(!empty($lithurgicCalendar->getSpecialFest())) {
                            $query = $query
                            ->orWhere(
                                    $queryBuilder->expr()->eq('p.special_fest', ':special_fest')
                            )
                            ->setParameter('special_fest', $lithurgicCalendar->getSpecialFest());
                }
                if(!empty($calendar->getData()['id'])) {
                $query = $query
                            ->andWhere(
                                    $queryBuilder->expr()->eq('p.calendar_id', ':calendar')
                            )
                            ->setParameter('calendar', $calendar->getData()['id']);
                }

                $query = $query
                ->setFirstResult(0)
                ->setMaxResults(10);
                $preys = $query
                        ->fetchAllAssociative();
                $isNew = false;
                foreach ($preys as $prey){
                    if (!in_array($prey['id'], $allPreys)) {
                        $allPreys[]=$prey['id'];
                        $isNew = true;
                    }
                }
                if ($isNew === true) {
                    $url = $siteUrl . '?calendario='.$calendar->getData()['id'].'&giorno='.$today->format('Y-m-d');
                    file_put_contents($sitenameFilePath, <<<EOT
  <url>
     <loc>{$url}</loc>
     <lastmod>{$today->format('Y-m-d')}</lastmod>
  </url>

EOT
    ,FILE_APPEND);
                }
                $today->sub( new \DateInterval('P1D'));
        }
        
        file_put_contents($sitenameFilePath, <<<EOT
</urlset>
EOT
,FILE_APPEND);
        
                file_put_contents(__DIR__.'/../../../robot.txt', <<<EOT
User-agent: *
Disallow:
Sitemap: {$siteUrl}/sitemap.xml
EOT
);

        $ui->comment('Generating the sitemap');

        return 0;
    
        
    }
    
    private function getEntityManagerProvider(): EntityManagerProvider
    {
        return $this->entityManagerProvider;
    }
}