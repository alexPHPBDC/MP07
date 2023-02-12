<?php

/**
 * Agafa totes les fotos d'un usuari a partir del seu login
 * @param string $user_login
 * @return Array
 * 
*/
function facelog_dbget(string $user_login):Array{
global $wpdb;

$imageTable = $wpdb->base_prefix . "facelog_image";
$usersTable = $wpdb->base_prefix.'users'; 
$sql = $wpdb->prepare("
SELECT relative_path as image, date as 'date' FROM $imageTable AS imatges 
INNER JOIN $usersTable AS usuaris
ON usuaris.ID = imatges.userID AND usuaris.user_login =%s",$user_login);
return $wpdb->get_results($sql);
}

/**
 * Crea la taula on es guardaran les imatges(un cop tractades)
 * @return void;
 */
function facelog_createImageTable():void
{
    global $wpdb;
    $table_name = $wpdb->base_prefix . "facelog_image";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
       `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
       `name` varchar(40) NOT NULL,
       `relative_path` varchar(255) NOT NULL,
       `type` varchar(40) NOT NULL,
       `size` BIGINT(20) UNSIGNED NOT NULL,
       `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       `date` DATE NOT NULL,
       `userID` BIGINT(20) UNSIGNED, 
       UNIQUE (date, userID),
       CONSTRAINT fk_customer FOREIGN KEY (userID) REFERENCES `{$wpdb->base_prefix}users`(ID), 
        PRIMARY KEY  (`id`)
       ) {$charset_collate};";


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * Esborra la taula on es guarden les imatges
 * @return void;
 */
function facelog_dropImageTable():void
{
    global $wpdb;
    $table_name = $wpdb->base_prefix . 'facelog_image';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
}

/**
 * Guarda la URL d'una imatge a la taula d'imatges
 * @param string $name Nom de l'imatge
 * @param string $relative_path url de l'imatge
 * @param string $type tipus d'imatge
 * @param string $size mida de l'imatge
 * @param string $userID ID de l'usuari
 * @return void;
 */
function facelog_uploadImageToDB(string $name, string $relative_path, string $type, string $size, string $userID, string $date):void
{
    global $wpdb;

    $table_name = $wpdb->base_prefix . "facelog_image";

    $sql = $wpdb->prepare("INSERT INTO " . $table_name . " (name, relative_path, type,size,userID,date ) VALUES ( %s, %s, %d, %d, %d,%s) ON DUPLICATE KEY UPDATE name = %s, relative_path = %s, type =%d,size = %d,userID=%d,date = %s " , $name, $relative_path, $type, $size, $userID,$date,$name, $relative_path, $type, $size, $userID,$date);
    $wpdb->query($sql);
}
