<?php
require_once "classes/Punt.php";
require_once __DIR__ . '/../../../wp-load.php';

/** Sets up the WordPress Environment. */
define('WP_USE_THEMES', false); /* Disable WP theme for this file (optional) */


$error = null;
$userID = get_current_user_id();

if (isset($_FILES['imageupload']) && isset($_POST['today']) && isset($_POST['date']) && isset($_FILES['imageupload']['name']) && isset($_FILES['imageupload']['full_path']) && isset($_FILES['imageupload']['type']) && isset($_FILES['imageupload']['tmp_name']) && isset($_FILES['imageupload']['error']) && isset($_FILES['imageupload']['size'])) {

    $date = $_POST['today'] == 1 ? date('Y-m-d') : $_POST['date'];
    $image = $_FILES['imageupload'];
    $temp_path =  plugin_dir_path(__FILE__) . "uploads/tmp/" . $userID . "-" . basename($image["name"]);

    $gotUploaded = facelog_uploadTempImage($_FILES['imageupload'], $temp_path);

    if (!isset($gotUploaded['error'])) {

        $imatgeTractada = treatImage($temp_path, $_FILES['imageupload']);

        if (is_gd_image($imatgeTractada)) {

            $target_filePath =  plugin_dir_path(__FILE__) . "uploads/treated/" . $userID . "-" . $date;
            $target_fileUrl =  plugin_dir_url(__FILE__) . "uploads/treated/" . $userID . "-" . $date;

            //Em posa l'imatge tractada al path especificat, és com un move
            imagejpeg($imatgeTractada, $target_filePath);
            $info = getimagesize($target_filePath);
            if (isset($info[2])) {
                facelog_uploadImageToDB($image['name'], $target_fileUrl, $info[2], $image['size'], $userID, $date);
            } else {
                $error = "Error desconegut (491)";
            }
        } else {
            if (isset($imatgeTractada['error'])) {
                $error = $imatgeTractada['error'];
            } else {
                $error = "Error desconegut (433)";
            }
        }
    } else {
        $error = $gotUploaded['error'];
    }
} else {
    $error = "Camps Buits";
}

if ($error) {
    wp_redirect(get_site_url(null, "facelog_log/?err=$error", null), 303);
} else {
    wp_redirect(get_site_url(null, "/facelog_log/?ok=OK", null), 302);
}


/**
 * Tracta la imatge i li aplica rotació, tallament i escalat
 * @return GdImage si va bé
 * @return Array si va malament, conté l'error
 */
function treatImage(string $target_file): GdImage | array
{
    $options = get_option('facelog_options', [
        'heightDesitjada' => '700',
        'widthDesitjada' => '400',
    ]);
    $widthDesitjada = $options['widthDesitjada'];
    $heightDesitjada = $options['heightDesitjada'];
    $gdImage = imagecreatefromjpeg($target_file);
    $data = getImageDataFromAPI($target_file);

    if (is_gd_image($gdImage)) {
        if ($data) {
            if (!isset($data['message'])) {
                $data = sanitizeData($data);
                if (!isset($data['error'])) {
                    $distanciaEntreUlls = Punt::getDistanciaEntreUlls($data['puntEsquerra'], $data['puntDreta']);
                    $rotationInfo = getRotationInfo($data, imagesx($gdImage), imagesy($gdImage));
                    $rotationAngleDegrees = $rotationInfo['angle'];
                    $ullEsquerraRotat = $rotationInfo['ullEsquerraRotat'];
                    $ullDretaRotat  = $rotationInfo['ullDretaRotat'];
                    $nasRotat  = $rotationInfo['nasRotat'];
                    $puntCentral = Punt::obtenirPuntCentral($ullEsquerraRotat, $ullDretaRotat, $nasRotat);
                    $iniciCropX = ($puntCentral->x - ($distanciaEntreUlls * 1.5));
                    $iniciCropY = ($puntCentral->y - ($distanciaEntreUlls * 2));


                    $gdImage = imagerotate($gdImage, $rotationAngleDegrees, 0);
                    $gdImage = imagecrop($gdImage, ['x' => $iniciCropX, 'y' => $iniciCropY, 'width' => $distanciaEntreUlls * 3, 'height' => $distanciaEntreUlls * 4]);
                    $gdImage = imagescale($gdImage, $widthDesitjada, $heightDesitjada);

                    return $gdImage;
                } else {
                    return ['error' => $data['error']];
                }
            } else {
                return ['error' => $data['message']];
            }
        } else {
            return ['error' => "Face detection API problems"];
        }
    } else {
        return ['error' => "Error inesperat"];
    }
}



/**
 * Troba quin angle he de aplicar per tal de que quedin els ulls rectes, també el retorna un array amb els punts de la cara rotats
 * @see https://www.youtube.com/watch?v=Yzrco3NfxCk
 * @param Array $data Conté els 3 punts de la cara (ulls i nas)
 * @param string $original_width mida de l'imatge abans de ser rotada
 * @param string $original_height altura de l'imatge abans de ser rotada
 * @return Array amb tots els punts amb la nova posició després de ser rotats, i l'angle
 */
function getRotationInfo(array $data, string $original_width, string $original_height): array
{
    $ullEsquerra = $data['puntEsquerra'];
    $ullDreta  = $data['puntDreta'];
    $nas = $data['puntCentral'];

    $puntImaginat = new Punt($ullDreta->x, $ullEsquerra->y);
    $vectorAB = Punt::calcularVector($ullEsquerra, $puntImaginat);
    $vectorAC = Punt::calcularVector($ullEsquerra, $ullDreta);
    $modulVectorAB = Punt::calcularModulVector($vectorAB);
    $modulVectorAC = Punt::calcularModulVector($vectorAC);

    $angleA = rad2deg(acos(Punt::producteEscalar($vectorAB, $vectorAC) / ($modulVectorAB * $modulVectorAC)));
    //Com sé si he de rotar cap a la dreta o l'esquerra per tal que quedi recte? :)
    //Com que ho calculo amb l'acos, he de fer això per tal que em doni l'angle correcte
    $angleA = $ullEsquerra->y < $ullDreta->y ? $angleA : -$angleA;

    $ullEsquerraRotat = Punt::rotarPunt($ullEsquerra, $original_width, $original_height, $angleA);
    $ullDretaRotat = Punt::rotarPunt($ullDreta, $original_width, $original_height, $angleA);
    $nasRotat = Punt::rotarPunt($nas, $original_width, $original_height, $angleA);


    return ['angle' => $angleA, 'nasRotat' => $nasRotat, 'ullEsquerraRotat' => $ullEsquerraRotat, 'ullDretaRotat' => $ullDretaRotat];
}

/**
 * Donades les dades del curl, comprova si són dades vàlides
 * @param Array $data dades del curl
 * @return Array Amb un error si no són dades vàlides o un array amb els punts de la cara si són dades vàlides
 */
function sanitizeData(array $data): array
{
    if (!isset($data["result"][0]["landmarks"]))
        return ['error' => "La api no retorna el resultat esperat"];

    if (count($data["result"]) > 1)
        return ['error' => "Hi ha més d'una persona"];

    $puntets = $data["result"][0]["landmarks"];

    if (count($puntets) != 5)
        return ['error' => "La imatge no té una cara vàlida"];

    $puntEsquerra = new Punt($puntets[0][0], $puntets[0][1]);
    $puntDreta = new Punt($puntets[1][0], $puntets[1][1]);
    $puntCentral = new Punt($puntets[2][0], $puntets[2][1]);

    return array("puntEsquerra" => $puntEsquerra, "puntCentral" => $puntCentral, "puntDreta" => $puntDreta);
}

/**
 * Agafa informació de l'API que detecta cares i la retorna
 * @param string $url
 * @return Array amb les dades de l'imatge
 * @return false En cas d'error de l'API
 */
function getImageDataFromAPI(string $url): array | false
{
    $ch = curl_init("http://coma.boscdelacoma.cat:8000///api/v1/detection/detect?face_plugins=landmarks");

    $photo = makeCurlFile($url);
    $data = array('file' => $photo);
    $apiKey = "3605527e-480c-4e59-a469-c246210d4cd9";
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data', 'x-api-key: ' . $apiKey));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //To not print the curl exec
    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        $result = curl_error($ch);
        return false;
    }
    curl_close($ch);
    return json_decode($result, true, 512, JSON_OBJECT_AS_ARRAY);
}

/**
 * A partir d'una url, fa un CURLFile de l'imatge darrere l'url, ho necessitem per enviar-ho a l'API per POST
 * @param string $url
 * @return CURLFile
 */
function makeCurlFile($url): CURLFile
{
    $mime = mime_content_type($url);
    $info = pathinfo($url);
    $name = $info['basename'];
    $output = new CURLFile($url, $mime, $name);
    return $output;
}


/**
 * Funció que fa un seguit de comprovacions i si tot va bé mou la fotografia a la carpeta temporal i retorna true, 
 * altrament retorna un Array amb l'error
 * @param Array $image
 * @param string $target_file
 * @return Array amb l'error
 * @return bool Si tot va bé
 */
function facelog_uploadTempImage(array $image, string $target_file): array | bool
{

    $check = getimagesize($image["tmp_name"]);
    if ($check === false)
        return ['error' => 'El fitxer no és una imatge'];

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check file size
    if ($image["size"] > 2097152)
        return ['error' => 'El fitxer pesa massa'];


    // Allow certain file formats
    $acceptedFormats = ["jpg" => true, "jpeg" => true];
    if (!isset($acceptedFormats[$imageFileType]))
        return ['error' => 'El fitxer no té l\'extensió adequada (Només admeto JPG, JPEG)'];

    if (!move_uploaded_file($image["tmp_name"], $target_file))
        return ['error' => 'Error al moure el fitxer'];

    return true;
}
