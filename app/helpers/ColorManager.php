<?php

namespace App\Helpers;

class ColorManager
{
    // Définir les couleurs sous forme de constantes
    const PRIMARY_COLOR = '#3498db';
    const SECONDARY_COLOR = '#2ecc71';
    const ERROR_COLOR = '#e74c3c';
    const WARNING_COLOR = '#f39c12';
    const SUCCESS_COLOR = '#2ecc71';
    const INFO_COLOR = '#1abc9c';
    const bg_card_header = "bg-orange";
    const text_color_card = "bg-white";

    // Exemple de méthode pour obtenir une couleur spécifique
    public static function getPrimaryColor()
    {
        return self::PRIMARY_COLOR;
    }

    // Ajouter des méthodes similaires pour d'autres couleurs
}
