<?php
/*
Plugin Name: PanoPress Lite
Text Domain: panopress-lite
Plugin URI: https://your-website.com/your_path_in_webspace/
Description: Fügt den Shortcode [pano file="..."] hinzu und zeigt ein responsives iframe-Panorama. Arbeitet mit Marzipano und anderen Panorama-Viewer-Plugins. 
Version: 1.3
Author: Hans Straßgütl
Author URI: https://motorradtouren.de/impressum/
Requires at least: 6.8.2
Tested up to: 6.8.2
Requires PHP: 8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Shortcode handler
function pano_shortcode_handler($atts) {
    // Einstellungen laden
    $options = get_option('panopress_lite_options');
    $base_url    = isset($options['base_url'])    ? trailingslashit(esc_url($options['base_url'])) : '';
    $default_margin_left = $options['margin_left'] ?? 'auto';
    $default_max_width   = $options['max_width'] ?? '100%';
    $default_ratio       = $options['aspect_ratio'] ?? '16:9';

    // Shortcode-Attribute
    $a = shortcode_atts(array(
        'file'        => '',
        'ratio'       => $default_ratio,
        'max_width'   => $default_max_width,
        'margin_left' => $default_margin_left,
    ), $atts);

    if (empty($a['file'])) {
        return '<p style="color:red;">Fehlender Parameter: file</p>';
    }

    // URL zusammensetzen
    $full_url = esc_url($base_url . ltrim($a['file'], '/'));


    // Dynamisch padding-bottom ermitteln
    $padding = '56.25%'; // Default 16:9
    switch ($a['ratio']) {
        case '4:3': $padding = '75%'; break;
        case '2:1': $padding = '50%'; break;
        case '1:1': $padding = '100%'; break;
        case '800px': $padding = '800px'; break;
        default: $padding = '56.25%'; break; // fallback
    }

    // HTML-Ausgabe
    $html  = '<div style="position:relative;';
    $html .= $a['ratio'] === '800px'
        ? 'height:800px;'
        : 'padding-bottom:' . esc_attr($padding) . ';height:0;';
    $html .= 'overflow:hidden;';
    $html .= 'max-width:' . esc_attr($a['max_width']) . ';';
    $html .= 'margin-left:' . esc_attr($a['margin_left']) . ';';
    $html .= 'border:2px solid #ccc;border-radius:8px;box-shadow:5px 5px 5px 5px grey;">';
    $html .= '<iframe src="' . $full_url . '" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" allowfullscreen loading="lazy"></iframe>';
    $html .= '</div>';

    return $html;
}
// Shortcode registrieren
add_shortcode('pano', 'pano_shortcode_handler');


// Admin-Menü hinzufügen
function panopress_lite_admin_menu() {
    add_options_page(
        'PanoPress Lite Einstellungen',
        'PanoPress Lite',
        'manage_options',
        'panopress-lite',
        'panopress_lite_settings_page'
    );
}
add_action('admin_menu', 'panopress_lite_admin_menu');

// Einstellungen registrieren
function panopress_lite_register_settings() {
    register_setting('panopress_lite_options_group', 'panopress_lite_options');
}
add_action('admin_init', 'panopress_lite_register_settings');

// Einstellungsseite HTML
function panopress_lite_settings_page() {
    ?>
<div class="wrap">
    <h1>PanoPress Lite – Einstellungen</h1>
    <p>Hier können Sie die Einstellungen für PanoPress Lite anpassen.</p>
    <p>Die Basis-URL sollte auf den Ordner zeigen, in dem Ihre Panoramen gespeichert sind. Zum Beispiel: <code>https://your-website.com/your_path_in_webspace/</code></p>
    <p>Die Einstellungen werden automatisch auf allen Seiten übernommen, die den Shortcode <code>[pano file="..."]</code> verwenden.</p>   
    <p>Beispiel für die Verwendung des Shortcodes: <code>[pano file="panorama.jpg" ratio="16:9" max_width="100%" margin_left="auto"]</code></p>
    <p>Die Parameter <code>ratio</code>, <code>max_width</code> und <code>margin_left</code> sind optional und können in den Einstellungen angepasst werden.</p>
    <form method="post" action="options.php">
        <?php
            settings_fields('panopress_lite_options_group');
            $options = get_option('panopress_lite_options');
            ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Basis-URL</th>
                <td><input type="url" name="panopress_lite_options[base_url]" value="<?php echo esc_attr($options['base_url'] ?? ''); ?>" style="width: 100%;" placeholder="https://your-website.com/your_path_in_webspace/" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">margin-left ( z. B. auto, 0, 5%, 5vw, 20px) </th>
                <td><input type="text" name="panopress_lite_options[margin_left]" value="<?php echo esc_attr($options['margin_left'] ?? 'auto'); ?>" placeholder="auto, 10px, 5%" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">max-width ( z. B. 100%, 800px, 90vw)</th>
                <td><input type="text" name="panopress_lite_options[max_width]" value="<?php echo esc_attr($options['max_width'] ?? '100%'); ?>" placeholder="100%, 800px, 90vw" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Seitenverhältnis</th>
                <td>
                    <select name="panopress_lite_options[aspect_ratio]">
                        <option value="16:9" <?php selected($options['aspect_ratio'] ?? '', '16:9'); ?>>16:9 (Standard)</option>
                        <option value="4:3" <?php selected($options['aspect_ratio'] ?? '', '4:3'); ?>>4:3</option>
                        <option value="2:1" <?php selected($options['aspect_ratio'] ?? '', '2:1'); ?>>2:1</option>
                        <option value="1:1" <?php selected($options['aspect_ratio'] ?? '', '1:1'); ?>>1:1</option>
                        <option value="800px" <?php selected($options['aspect_ratio'] ?? '', '800px'); ?>>Feste Höhe: 800px</option>
                    </select>
                    <p class="description">Maximale Höhe wird immer auf 800px begrenzt.</p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
<?php
}