<?php

namespace App\Helpers;

class ProfanityFilter
{
    /**
     * Liste de mots vulgaires / insultes en français et en anglais.
     * Les variantes avec chiffres (e=3, a=4, i=1, o=0) sont aussi détectées.
     */
    private static array $blacklist = [
        // Insultes françaises
        'connard', 'connarde', 'conn4rd', 'con', 'conne',
        'merde', 'm3rde', 'merdique',
        'putain', 'put1', 'pute', 'putes',
        'salope', 'sal0pe', 'salopard',
        'batard', 'bâtard', 'bat4rd',
        'enculé', 'enculer', 'encul3',
        'foutre', 'foutue',
        'bordel',
        'fils de pute', 'fdp',
        'va te faire foutre', 'vtff',
        'nique', 'niquer', 'niqué',
        'pd', 'pédé',
        'couille', 'couilles',
        'bite', 'b1te',
        'chier', 'chié',
        'cul',
        'branleur', 'branler',
        'sodomie', 'sodomiser',
        'vaffanculo',
        'saligaud',
        'ordure', 'ordures',
        'débile', 'debile',
        'crétin', 'cretin', 'cretins',
        'abruti', 'abrutie',
        'imbécile', 'imbecile',
        'idiot', 'idiote',
        'raciste', 'racisme',
        'nazi', 'nazisme',

        // Insultes anglaises courantes
        'fuck', 'fucking', 'fucker', 'fck',
        'shit', 'sh1t',
        'bitch', 'b1tch',
        'asshole', 'a$$hole',
        'bastard',
        'cunt',
        'dick', 'd1ck',
        'pussy',
        'nigger', 'nigga',
        'faggot', 'fag',
        'whore',
        'slut',
        'wtf',

        // Caractères spéciaux répétés (spam)
        '!!!', '???', '###', '$$$', '***',
    ];

    /**
     * Vérifie si un texte contient un mot vulgaire.
     * Normalise le texte (minuscules, accents) avant la vérification.
     */
    public static function contains(string $text): bool
    {
        $normalized = self::normalize($text);

        foreach (self::$blacklist as $word) {
            $normalizedWord = self::normalize($word);
            if (str_contains($normalized, $normalizedWord)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalise un texte pour la détection :
     * - Passe en minuscules
     * - Supprime les accents
     * - Remplace les substitutions courantes (3=e, 4=a, 0=o, 1=i)
     */
    private static function normalize(string $text): string
    {
        $text = mb_strtolower($text);

        // Supprimer les accents
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text) ?: $text;

        // Remplacer les substitutions leet speak
        $text = strtr($text, [
            '3' => 'e',
            '4' => 'a',
            '0' => 'o',
            '1' => 'i',
            '@' => 'a',
            '$' => 's',
        ]);

        return $text;
    }
}
