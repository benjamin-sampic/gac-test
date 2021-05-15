<?php

namespace Tickets\Controller;

class TicketsController extends \Gac\Controller\AbstractController
{
    protected $viewsDir = null;

    public function __construct()
    {
        parent::__construct();
        $this->viewsDir = dirname(__FILE__) . "/../View/";
    }

    public function route(string $route = null)
    {
        switch ($route) {
            case "index":
                $this->index();
            break;
            case "import":
                $this->import();
            break;
            case "import2":
                $this->import2();
            break;
            case "requetes":
                $this->requetes();
            break;
            default:
                $this->error404();
            break;
        }
    }

    public function index()
    {
        $this->fullPageDisplay($this->viewsDir . 'page/index.php', ['route'=>'index']);
    }

    public function import()
    {
        $this->fullPageDisplay($this->viewsDir.'page/import.php', ['route'=>'import']);
    }

    public function import2()
    {
        $ticketsDao = new \Tickets\Dao\TicketsDao();

        $ticketsDao->empty();

        $importer = new \Tickets\Csv\Parser\Generic();
        $results = $importer->import(DATA_PATH . 'tickets_appels_201202.csv');

        echo $this->viewRender($this->viewsDir.'page/import2.php', ['results'=>$results]);
    }

    public function requetes()
    {
        $ticketsDao = new \Tickets\Dao\TicketsDao();

        $phonecallRealDuration = $ticketsDao->getTotalPhonecallDurationFrom15February2012Included();
        $totalSmsSent = $ticketsDao->getTotalSmsSentForFebruary2012();
        $top10DataUsageBySubscriber = $ticketsDao->getTop10DataUsageBySubscriberForFebruary2012();

        $this->fullPageDisplay($this->viewsDir . 'page/requetes.php', [
            'phonecallRealDuration' => $phonecallRealDuration,
            'totalSmsSent' => $totalSmsSent,
            'top10DataUsageBySubscriber' => $top10DataUsageBySubscriber,
            'route'=>'requetes'
        ]);
    }

    public function error404()
    {
        $this->fullPageDisplay('page/error/404.php', [], ['HTTP/1.0 404 Not Found']);
    }

    protected function fullPageDisplay(string $viewPath, array $params = [], array $httpHeaders = [])
    {
        foreach ($httpHeaders as $httpHeader) {
            header($httpHeader);
        }

        $header = $this->viewRender($this->viewsDir . "template/header.php", ['route' => $params['route']]);
        $footer = $this->viewRender($this->viewsDir . "template/footer.php");
        $content = $this->viewRender($viewPath, $params);

        echo $this->viewRender($this->viewsDir . "template/page.php", ['header'=>$header,'content'=>$content,'footer'=>$footer]);
    }
}
