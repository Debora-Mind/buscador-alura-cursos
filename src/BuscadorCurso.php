<?php

namespace DeboraMind\BuscadorCursosAlura;

require_once '.\vendor\autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class BuscadorCurso
{
    private Crawler $crawler;

    private string $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function busca(): void
    {
        $cliente = new Client(
            [
                'base_uri' => 'https://www.alura.com.br/',
                'verify'   => false,
            ]
        );

        $resposta = $cliente->request('GET', 'https://www.alura.com.br/' . $this->url);

        $html = $resposta->getBody();

        $this->crawler = new Crawler();
        $this->crawler->addHtmlContent($html);

        $this->crawler = $this->crawler->filter('span.card-curso__nome');
    }

    public function exibeCursos(): void
    {
        foreach ($this->crawler as $curso) {
            echo $curso->textContent . PHP_EOL;
        }
    }
}
