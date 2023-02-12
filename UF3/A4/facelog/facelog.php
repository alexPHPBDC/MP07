<?php
require_once("includes/custom-pages.php");
/**
 * Plugin Name: FaceLog Plugin
 * Plugin URI: http://boscdelacoma.cat
 * Description: Pràctica MP07.
 * Version: 0.1
 * Author: ALEX
 * Author URI:  http://boscdelacoma.cat
 **/

const FACELOG_DB_VERSION = '1.0';
const FACELOG_VERSION = '1.0';

$facelogDir = plugin_dir_path(__DIR__);

// Allow subscribers to see Private posts and pages
$subRole = get_role('subscriber');
$subRole->add_cap('read_private_posts');
$subRole->add_cap('read_private_pages');
$adminRole = get_role('administrator');
$adminRole->add_cap('manage_options');
add_shortcode('facelog_gallerySC', 'facelog_gallery');
add_shortcode('facelog_logSC', 'facelog_addlog');

register_activation_hook(__FILE__, 'facelog_activation');
register_deactivation_hook(__FILE__, 'facelog_deactivation');
add_action('init', 'register_scripts');
add_action('wp_enqueue_scripts', 'enqueue_style');
add_action('admin_menu', 'facelog_add_settings_page');
add_action('admin_init', 'facelog_register_settings');

$facelog_options = [
   'heightDesitjada' => '700',
   'widthDesitjada' => '400',
   'velocitatGaleria' => '1000',
];

function facelog_add_settings_page():void
{
   add_options_page('Facelog Settings', 'Facelog Menu', 'manage_options', 'facelog_settings', 'facelog_settings_page');
}

function facelog_register_settings():void
{
   register_setting('facelog_options', 'facelog_options', 'facelog_options_validate');
}

function facelog_options_validate($input):Array
{
   global $facelog_options;
   $options = get_option('facelog_options', $facelog_options);
   $options['heightDesitjada'] = sanitize_text_field($input['heightDesitjada']);
   $options['widthDesitjada'] = sanitize_text_field($input['widthDesitjada']);
   $options['velocitatGaleria'] = sanitize_text_field($input['velocitatGaleria']);
   return $options;
}

function register_scripts():void
{
   wp_register_style('new_style', plugins_url('/assets/css/style.css', __FILE__), false, "1.0.0", 'all');
}

function enqueue_style():void
{
   wp_enqueue_style('new_style');
}

function facelog_activation():void
{
   facelog_createPages();
   facelog_createTables();
}

//Faig el mateix que si fessim uninstall
function facelog_deactivation():void
{
   facelog_deletePages();
   facelog_deleteTables();
   facelog_deleteImages();
   facelog_deleteOptions();
}

function facelog_createTables():void
{
   facelog_createImageTable();
}

function facelog_deleteTables():void
{
   facelog_dropImageTable();
}

function facelog_deleteImages():void
{
   $filesTmp = glob(plugin_dir_path(__FILE__) . 'uploads/tmp/*');
   $filesTreated = glob(plugin_dir_path(__FILE__) . 'uploads/treated/*');
   $allFiles = array_merge($filesTmp, $filesTreated);
   array_map('unlink', $allFiles);
}

function facelog_createPages():void
{
   facelog_createGalleryPage();
   facelog_createLogPage();
}

function facelog_deletePages():void
{
   $idFacelogLog = intval(get_option('idFacelogLog', 0));
   $idFacelogGallery = intval(get_option('idFacelogGallery', 0));
   wp_delete_post($idFacelogLog, true);
   wp_delete_post($idFacelogGallery, true);
}

function facelog_deleteOptions():void 
{
   delete_option('facelog_options');
}

function facelog_createGalleryPage():void
{
   $page_title = "facelog_gallery";

   $facelog_gallery = array(
      'post_title'   => $page_title,
      'post_content' => '[facelog_gallerySC]',
      'post_status'  => 'publish',
      'post_type'    => 'page'
   );
   if ($page_id = wp_insert_post($facelog_gallery)) {
      // Only update this option if `wp_insert_post()` was successful
      update_option('idFacelogGallery', $page_id);
   }
}

function facelog_createLogPage():void
{
   $page_title = "facelog_log";

   $facelog_log = array(
      'post_title'   => $page_title,
      'post_content' => '[facelog_logSC]',
      'post_status'  => 'private',
      'post_type'    => 'page'
   );
   if ($page_id = wp_insert_post($facelog_log)) {
      // Only update this option if `wp_insert_post()` was successful
      update_option('idFacelogLog', $page_id);
   }
}

function facelog_settings_page():void
{
   global $facelog_options;

   if (!isset($_REQUEST['settings-updated'])) {
      $_REQUEST['settings-updated'] = false;
   }

   $options = get_option('facelog_options', $facelog_options);

   // Si m'envien el form guardo els options
   if (isset($_REQUEST['submit'])) {
      $options = array(
         'heightDesitjada' => sanitize_text_field($_REQUEST['heightDesitjada']),
         'widthDesitjada' => sanitize_text_field($_REQUEST['widthDesitjada']),
         'velocitatGaleria' => sanitize_text_field($_REQUEST['velocitatGaleria']),
      );
      update_option('facelog_options', $options);
      $_REQUEST['settings-updated'] = true;
   }

   
?>
   <div class="wrap">
      <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

      <?php if ($_REQUEST['settings-updated'] === true) : ?>
         <div class="notice notice-success is-dismissible">
            <p><strong><?php esc_html_e('Settings saved.', 'my-plugin'); ?></strong></p>
         </div>
      <?php endif; ?>

      <form action="options.php" method="post">
         <?php settings_fields('facelog_options'); ?>
         <table class="form-table">
            <tr valign="top">
               <th scope="row"><label for="heightDesitjada"><?php esc_html_e('heightDesitjada', 'my-plugin'); ?></label></th>
               <td><input type="text" id="heightDesitjada" name="facelog_options[heightDesitjada]" value="<?php echo esc_attr($options['heightDesitjada']); ?>" /></td>
            </tr>
            <tr valign="top">
               <th scope="row"><label for="widthDesitjada"><?php esc_html_e('widthDesitjada', 'my-plugin'); ?></label></th>
               <td><input type="text" id="widthDesitjada" name="facelog_options[widthDesitjada]" value="<?php echo esc_attr($options['widthDesitjada']); ?>" /></td>
            </tr>
            <tr valign="top">
               <th scope="row"><label for="velocitatGaleria"><?php esc_html_e('velocitatGaleria', 'my-plugin'); ?></label></th>
               <td><input type="text" id="velocitatGaleria" name="facelog_options[velocitatGaleria]" value="<?php echo esc_attr($options['velocitatGaleria']); ?>" /></td>
            </tr>
         </table>
         <?php submit_button(); ?>
      </form>
   </div>
<?php
}
