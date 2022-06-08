<?php

namespace DeboraMind\BuscadorCursosAlura;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

require_once '.\vendor\autoload.php';

class Buscador
{
    private string $loja;

    private string $produto;

    private string $filterNome;

    private string $filterValor;

    private array $lista;


    public function __construct(string $loja, string $produto)
    {
        $this->loja    = $loja;
        $this->produto = $produto;
        $this->busca();
    }


    private function busca(): void
    {
        $url      = $this->url();
        $cliente  = $this->ativacaoClient($url);
        $resposta = $this->ativacaoResposta($cliente, $url);

        $crawler = $this->ativacaoCrawler();
        $html    = $this->ativacaoHtml($resposta);
        $crawler->addHtmlContent($html);

        $this->lista = $this->criaLista($crawler);
    }


    private function url(): string
    {
         return 'https://www.' . $this->loja . '.com.br/';
    }


    private function ativacaoClient(string $url): Client
    {
        return new Client(
            [
                'base_uri' => $url,
                'verify'   => false,
            ]
        );
    }


    private function ativacaoResposta(Client $cliente, string $url)
    {
        $url     = $this->urlCompleta($url);
        $reposta = $cliente->request(
            'GET',
            uri: $url
        );
        return $reposta;
    }


    private function urlCompleta(string $url): string
    {
        switch ($this->loja) {
            case 'colombo':
                // https://www.colombo.com.br/produto/Smartphones/
                $url               = $url . 'produto/' . $this->produto . '/';
                $this->filterNome  = 'div.nm-product-name';
                $this->filterValor = 'span.nm-price';
                break;

            case 'magazineluiza':
                // https://www.magazineluiza.com.br/celulares-e-smartphones/l/te/
                if ($this->produto == 'smartphones') {
                    $url .= 'celulares-e-smartphones/l/te/';
                }

                $this->filterNome = 'h2.fECDmq';
                // div.sc-fezjOJ.jKEGcU > h2
                $this->filterValor = 'p.sc-hKwDye.dWfgMa.sc-cTAIfT.imxxrC';
                break;

            case 'taqi':
                // https://www.taqi.com.br/telefones-e-celulares/smartphone/cat50004
                if ($this->produto == 'smartphones') {
                    $url .= 'telefones-e-celulares/smartphone/cat50004';
                }

                $this->filterNome = 'div.shelf-content';
                // div.shelf-content > p
                $this->filterValor = 'p.price';
                break;

            default:
                $url = '';
                echo 'Url inválida' . PHP_EOL;
                $this->filterNome  = '';
                $this->filterValor = '';
        }

        return $url;
    }


    private function ativacaoCrawler(): Crawler
    {
        return new Crawler();
    }


    private function ativacaoHtml($resposta): string
    {
        return $resposta->getBody();
    }


    private function criaLista(Crawler $crawler): array
    {
        $listaProdutos = $crawler->filter(selector: $this->filterNome);
        $listaValores  = $crawler->filter(selector: $this->filterValor);
        var_dump($listaValores);
        $produtos = ['Nome' => 'Valor'];
        $item     = [];
        $valores  = [];

        foreach ($listaProdutos as $produto) {
            $item[] = trim($produto->textContent);
        }

        foreach ($listaValores as $valor) {
            $valores[] = trim($valor->textContent);
        }

        for ($i = 0; $i < count($listaProdutos); $i++) {
            $produtos[$item[$i]] = $valores[$i];
        }

        return $produtos;
    }


    /**
     * @return array
     */
    public function getLista(): array
    {
        return $this->lista;
    }
}
