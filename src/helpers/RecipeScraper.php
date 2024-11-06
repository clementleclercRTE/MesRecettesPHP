<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use DiDom\Document;

class RecipeScraper {
    public function scrape(string $url) {
        if (strpos($url, 'marmiton.org') !== false) {
            return $this->scrapMarmiton($url);
        } else {
            throw new Exception("URL non prise en charge. Veuillez entrer une URL de Marmiton");
        }
    }

    private function scrapMarmiton(string $url) {
        return $this->scrapRecipe($url, 'span.ingredient-name', '//img[@id="recipe-media-viewer-main-picture"]', 'data-src', '.recipe-step-list p');
    }



    private function scrapRecipe(string $url, string $ingredientSelector, string $imageXPath, string $imageAttr, string $stepSelector = '') {
        try {
            
            $document = new Document(file_get_contents($url), false);
            $name = $document->first('h1')->text();
            $ingredients = $this->getIngredients($document, $ingredientSelector);
            $image = $document->xpath($imageXPath)[0]->getAttribute($imageAttr);
            $steps = [];

            if ($stepSelector) {
                $stepElements = $document->find($stepSelector);
                $num = 1;
                foreach ($stepElements as $element) {
                    $text = $element->text();
                    if ( $text) {
                        $steps[] = [
                            'description' => $text,
                            'num' => $num++
                        ];
                    }
                }
            }

            return [
                'name' => $name,
                'ingredients' => $ingredients,
                'image' => $image,
                'steps' => $steps,
                'url' => $url,
                'description' => '',
                'isFavorite' => false
            ];
        } catch (Exception $e) {
            throw new Exception("Erreur lors du scraping: " . $e->getMessage());
        }
    }

    private function getIngredients(Document $document, string $selector) {
        $elements = $document->find($selector);
        $ingredients = [];

        foreach ($elements as $element) {
            $ingredients[] = [
                'name' => trim($element->text()),
                'quantity' => ''
            ];
        }

        return $ingredients;
    }



}
