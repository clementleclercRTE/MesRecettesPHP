<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class ScrapService {
    private $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function detectAndScrap($url) {
        if (strpos($url, 'marmiton.org') !== false) {
            return $this->scrapMarmiton($url);
        } elseif (strpos($url, 'cuisineaz.com') !== false) {
            return $this->scrapCuisineaz($url);
        } elseif (strpos($url, 'femmeactuelle.fr') !== false) {
            return $this->scrapFemmeActuelle($url);
        } elseif (strpos($url, 'journaldesfemmes') !== false) {
            return $this->scrapJournalDesFemmes($url);
        } else {
            throw new \InvalidArgumentException("URL non prise en charge. Veuillez entrer une URL de Marmiton, CuisineAZ, Femme Actuelle ou Journal des femmes.");
        }
    }

    public function scrapMarmiton($url) {
        return $this->scrapRecette($url, "span .ingredient-name", "//*[@id=\"recipe-media-viewer-main-picture\"]", "data-src");
    }

    public function scrapCuisineaz($url) {
        return $this->scrapRecette($url, ".ingredient_label", "//*[@id=\"main_content\"]/section[1]/picture/img", "src");
    }

    public function scrapFemmeActuelle($url) {
        return $this->scrapRecette($url, "[data-block=\"recipe-ingredientLabel\"]", "//*[@id=\"corps\"]/div[1]/div[2]/div[1]/div[3]/div[1]/div[2]/div[3]/div[1]/figure/img", "src");
    }

    public function scrapJournalDesFemmes($url) {
        return $this->scrapRecette($url, ".bu_cuisine_pointille_vert", "//*[@id=\"jStickySize\"]/div[2]/div/figure/a/picture/img", "src");
    }

    private function scrapRecette($url, $ingredientSelector, $imageXpath, $imageAttr) {
        $recette = [];
        try {
            $response = $this->client->get($url);
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);

            $recette['name'] = $crawler->filter('h1')->text();
            $recette['ingredients'] = $this->getIngredients($crawler, $ingredientSelector);
            $recette['image'] = $crawler->filterXPath($imageXpath)->attr($imageAttr);
            $recette['url'] = $url;
            $recette['description'] = ''; // À remplir manuellement
            $recette['isFavorite'] = false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
        return $recette;
    }

    private function getIngredients(Crawler $crawler, $selector) {
        $ingredients = [];
        $crawler->filter($selector)->each(function (Crawler $node) use (&$ingredients) {
            $ingredients[] = [
                'name' => $node->text(),
                'quantity' => ''
            ];
        });
        return $ingredients;
    }
}