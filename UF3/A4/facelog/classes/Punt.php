<?php

class Punt
{
    public string $x;
    public string $y;

    function __construct(string $x, string $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Donat un vector, calcula el seu mòdul
     * @param Punt 
     * @return float
     */
    static function calcularModulVector(Punt $vector): float
    {
        $vectorAB = sqrt(pow(($vector->x), 2) + pow(($vector->y), 2));
        return $vectorAB;
    }


    /**
     * Donat dos punts, calcula el seu vector
     * @param Punt $A Primer punt
     * @param Punt $B Segon punt
     * @return Punt
     */
    static function calcularVector(Punt $A, Punt $B): Punt
    {
        $vectorAB = new Punt(($B->x - $A->x), ($B->y - $A->y));

        return $vectorAB;
    }


    /**
     * Donat dos punts, calcula el seu producte escalar
     * @param Punt
     * @param Punt
     * @return float
     */
    static function producteEscalar(Punt $vector1, Punt $vector2): float
    {
        return (($vector1->x * $vector2->x) + ($vector1->y * $vector2->y));
    }

    /**
     * Rota un punt
     * @param Punt $punt a rotar
     * @param int $image_width llargada de l'imatge
     * @param int $image_height llargada de l'imatge
     * @param float $angle a rotar
     * @return Punt
     */
    static function rotarPunt(Punt $punt, int $image_width, int $image_height, float $angle):Punt
    {
        // Calculem quan mesura la imatge rotada
        $rotated = imagerotate(imagecreatetruecolor($image_width, $image_height), $angle, 0);
        $rotated_width = imagesx($rotated);
        $rotated_height = imagesy($rotated);

        // Calculem el centre de l'imatge rotada
        $center_x = $rotated_width / 2;
        $center_y = $rotated_height / 2;

        // Fem que el punt sigui relatiu al centre de l'imatge
        $punt->x -= $image_width / 2;
        $punt->y -= $image_height / 2;

        // Rotem el punt
        $theta = deg2rad(-$angle);
        $rotated_x = $punt->x * cos($theta) - $punt->y * sin($theta);
        $rotated_y = $punt->x * sin($theta) + $punt->y * cos($theta);

        // Fem que el punt sigui relatiu adalt a l'esquerra de l'imatge
        $rotated_x += $center_x;
        $rotated_y += $center_y;

        return new Punt($rotated_x, $rotated_y);
    }

    /**
     * Donat dos punts, calcula la distància entre ells
     * @param Punt
     * @param Punt
     * @return float
     */
    static function getDistanciaEntreUlls(Punt $ullEsquerra,Punt $ullDreta):float{
        return sqrt(pow(abs($ullDreta->x - $ullEsquerra->x), 2) + pow($ullDreta->y - $ullEsquerra->y, 2));
    }

    /**
     * Donat tres punts, troba el punt central
     * @param Punt
     * @param Punt
     * @param Punt
     * @return Punt
     */
    static function obtenirPuntCentral(Punt $ullEsquerra, Punt $ullDreta, Punt $nas):Punt
    {
        $centreX = new Punt((($ullDreta->x - $ullEsquerra->x) / 2), $ullDreta->y);
        $centre = new Punt($ullEsquerra->x + $centreX->x, ($centreX->y + (($nas->y - $centreX->y) / 2)));
        return $centre;
    }

    /**
     * Obté la posició inicial des d'on es farà el crop
     * @param Punt
     * @param float
     * @param float
     * @param float
     * @return Punt
     */
    static function obtenirIniciCrop(Punt $centre, float $escalat, float $widthActual, float $heightActual):Punt
    {
        $x = ($centre->x - ($widthActual / 2) * $escalat);
        $y = ($centre->y - ($heightActual / 2) * $escalat);
        return new Punt($x, $y);
    }
}
