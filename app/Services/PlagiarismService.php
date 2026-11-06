<?php

namespace App\Services;

use App\Models\Revista;

class PlagiarismService
{
    /**
     * Calcula a percentagem de similaridade (Jaccard Index com N-Gramas de 3 palavras)
     */
    public static function calcularSimilaridade(string $textoNovo, int $ignoreId = null): array
    {
        $maxSimilaridade = 0;
        $revistaMaisSimilar = null;

        $ngramsNovo = self::extrairNgrams($textoNovo, 3);

        if (empty($ngramsNovo)) {
            return ['porcentagem' => 0, 'revista' => null];
        }

        // Buscar trabalhos existentes (se for edição, ignora o próprio trabalho)
        $query = Revista::query();
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $revistasExistentes = $query->select('id', 'titulo', 'descricao')->get();

        foreach ($revistasExistentes as $revista) {
            $textoExistente = $revista->titulo . ' ' . $revista->descricao;
            $ngramsExistente = self::extrairNgrams($textoExistente, 3);

            if (empty($ngramsExistente)) {
                continue;
            }

            // Algoritmo de Similaridade de Jaccard
            $intersecao = count(array_intersect($ngramsNovo, $ngramsExistente));
            $uniao = count(array_unique(array_merge($ngramsNovo, $ngramsExistente)));

            if ($uniao > 0) {
                $similaridade = ($intersecao / $uniao) * 100;

                if ($similaridade > $maxSimilaridade) {
                    $maxSimilaridade = $similaridade;
                    $revistaMaisSimilar = $revista;
                }
            }
        }

        return [
            'porcentagem' => round($maxSimilaridade, 2),
            'revista' => $revistaMaisSimilar
        ];
    }

    /**
     * Limpa o texto e quebra em sequências de N palavras (n-grams)
     */
    private static function extrairNgrams(string $texto, int $n = 3): array
    {
        $textoLimpo = mb_strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $texto));
        $palavras = array_filter(explode(' ', $textoLimpo));
        $palavras = array_values($palavras);

        $count = count($palavras);
        $ngrams = [];

        for ($i = 0; $i <= $count - $n; $i++) {
            $ngrams[] = implode(' ', array_slice($palavras, $i, $n));
        }

        return array_unique($ngrams);
    }
}