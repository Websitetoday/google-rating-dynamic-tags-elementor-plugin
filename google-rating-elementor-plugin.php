<?php
/**
 * Plugin Name:     Google Rating Dynamic Tags Elementor
 * Plugin URI:      https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin
 * GitHub Plugin URI: https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin
 * Description:     Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score, aantal reviews en link naar reviews) als Elementor Dynamic Tag en via shortcode.
 * Version:         3.4.3
 * Author:          Websitetoday.nl
 * Author URI:      https://www.websitetoday.nl/
 * GitHub Branch:   main
 * Requires PHP:    7.4
 * Requires at least: 5.0
 */

if (!defined('ABSPATH')) exit;

// ──────────────────────────────────────────
// Simple GitHub Updater - Lightweight & Stable
// ──────────────────────────────────────────

if (file_exists(__DIR__ . '/includes/simple-github-updater.php')) {
    require_once __DIR__ . '/includes/simple-github-updater.php';

    new SimpleGitHubUpdater(
        __FILE__,
        'https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin'
    );
}

// ───────────────────────────────────────────────
// CONSTANTS
// ───────────────────────────────────────────────
define('GRE_OPT_API_KEY',   'gre_api_key');
define('GRE_OPT_PLACE_ID',  'gre_place_id');
define('GRE_OPT_LAST_DATA', 'gre_last_data');

// ───────────────────────────────────────────────
// RENDER FUNCTIONS
// ───────────────────────────────────────────────
function gre_api_key_render() {
    $val = get_option(GRE_OPT_API_KEY, '');
    printf(
        '<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />
         <span class="toggle-visibility dashicons dashicons-visibility" data-field="%1$s" style="cursor:pointer;"></span>
         <span id="gre-api-status" class="gre-status-icon dashicons"></span>',
        esc_attr(GRE_OPT_API_KEY),
        esc_attr($val)
    );
}

function gre_place_id_render() {
    $val = get_option(GRE_OPT_PLACE_ID, '');
    printf(
        '<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />
         <span class="toggle-visibility dashicons dashicons-visibility" data-field="%1$s" style="cursor:pointer;"></span>
         <span id="gre-place-status" class="gre-status-icon dashicons"></span>',
        esc_attr(GRE_OPT_PLACE_ID),
        esc_attr($val)
    );

    $last = get_option(GRE_OPT_LAST_DATA);
    if (is_array($last) && !empty($last['name'])) {
        printf('<p class="description">Verbonden met: <strong>%s</strong></p>', esc_html($last['name']));
    }
}

function gre_enable_shortcode_render() {
    $val = get_option('gre_enable_shortcode', 1);
    printf(
        '<input type="checkbox" id="gre_enable_shortcode" name="gre_enable_shortcode" value="1" %s /> 
         <label for="gre_enable_shortcode">Enable [google_rating] shortcode</label>',
        checked(1, $val, false)
    );
}

function gre_test_connection_render() {
    echo '<button type="button" class="button" id="gre-test-connection-button">Controleer verbinding</button>
          <span id="gre-test-connection-result" style="margin-left:12px;"></span>';
}

function gre_force_refresh_render() {
    echo '<button type="button" class="button" id="gre-refresh-data-button">Ververs data</button>
          <span id="gre-refresh-data-result" style="margin-left:12px;"></span>';
}

function gre_check_updates_render() {
    if (!function_exists('get_plugin_data')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    $plugin_data = get_plugin_data(__FILE__, false, false);
    $current_version = $plugin_data['Version'];

    echo '<div class="gre-setting">';
    echo '<label>Huidige versie</label>';
    echo '<div><strong>' . esc_html($current_version) . '</strong></div>';
    echo '</div>';

    echo '<div class="gre-setting">';
    echo '<label>GitHub Repository</label>';
    echo '<div><a href="https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/releases" target="_blank" rel="noopener">Bekijk releases op GitHub</a></div>';
    echo '</div>';

    echo '<div class="gre-setting">';
    echo '<label>&nbsp;</label>';
    echo '<div>';
    echo '<button type="button" class="button button-secondary" id="gre-check-updates-button">Controleer op updates</button>';
    echo ' <span id="gre-check-updates-result" style="margin-left:12px;"></span>';
    echo '</div>';
    echo '</div>';

    echo '<p class="description" style="margin-top:16px;">';
    echo 'Updates worden automatisch elke 12 uur gecontroleerd. Klik op de knop om handmatig te checken en de cache te legen.';
    echo '</p>';
}

// ───────────────────────────────────────────────
// INCLUDES
// ───────────────────────────────────────────────
require_once __DIR__ . '/includes/admin/page-settings.php';
require_once __DIR__ . '/includes/admin/page-explainer.php';
require_once __DIR__ . '/includes/admin/page-changelog.php';

// ───────────────────────────────────────────────
// ADMIN MENU + TABS
// ───────────────────────────────────────────────
function gre_options_page() {
    $active_tab = $_GET['tab'] ?? 'settings';
    $active_tab = sanitize_text_field($active_tab);

    echo '<div class="wrap"><h1>Google Rating Settings</h1>';
    echo '<h2 class="nav-tab-wrapper">';
    echo '<a href="?page=gre_options&tab=settings" class="nav-tab ' . ($active_tab=='settings'?'nav-tab-active':'') . '">Instellingen</a>';
    echo '<a href="?page=gre_options&tab=explainer" class="nav-tab ' . ($active_tab=='explainer'?'nav-tab-active':'') . '">Uitleg</a>';
    echo '<a href="?page=gre_options&tab=changelog" class="nav-tab ' . ($active_tab=='changelog'?'nav-tab-active':'') . '">Changelog</a>';
    echo '</h2>';

    switch ($active_tab) {
        case 'explainer': gre_render_explainer_page(); break;
        case 'changelog': gre_render_changelog_page(); break;
        default:          gre_render_settings_page();  break;
    }

    echo '</div>';
}

function gre_add_admin_menu() {
    add_menu_page(
        'Google Rating',
        'Google Rating',
        'manage_options',
        'gre_options',
        'gre_options_page',
        'dashicons-star-filled',
        80
    );
}
add_action('admin_menu','gre_add_admin_menu');

// ───────────────────────────────────────────────
// SETTINGS
// ───────────────────────────────────────────────
function gre_settings_init() {
    register_setting('gre_api_settings', GRE_OPT_API_KEY,  ['sanitize_callback'=>'sanitize_text_field']);
    register_setting('gre_api_settings', GRE_OPT_PLACE_ID, ['sanitize_callback'=>'sanitize_text_field']);
    register_setting('gre_plugin_settings','gre_enable_shortcode',['sanitize_callback'=>'absint','default'=>1]);
}
add_action('admin_init','gre_settings_init');

// ───────────────────────────────────────────────
// NOTICES
// ───────────────────────────────────────────────
function gre_admin_notices() {
    if (!current_user_can('manage_options')) return;
    if (!get_option(GRE_OPT_API_KEY) || !get_option(GRE_OPT_PLACE_ID)) {
        echo '<div class="notice notice-error"><p>Google Rating: Vul API Key en Place ID in.</p></div>';
    }
}
add_action('admin_notices','gre_admin_notices');

// ───────────────────────────────────────────────
// FETCH GOOGLE PLACE DATA (with cache)
// ───────────────────────────────────────────────
function gre_fetch_google_place_data() {
    $cached = get_option(GRE_OPT_LAST_DATA);

    // Return cached data if valid and less than 7 days old
    if (is_array($cached) && !empty($cached['rating'])) {
        $cache_time = get_option('gre_last_fetch_time', 0);
        $week_ago = time() - (7 * 24 * 60 * 60);

        if ($cache_time > $week_ago) {
            return $cached;
        }
    }

    // Fetch fresh data
    $api_key = get_option(GRE_OPT_API_KEY);
    $place_id = get_option(GRE_OPT_PLACE_ID);

    if (!$api_key || !$place_id) {
        return false;
    }

    $url = sprintf(
        'https://maps.googleapis.com/maps/api/place/details/json?place_id=%s&fields=name,rating,user_ratings_total,opening_hours&key=%s',
        urlencode($place_id),
        $api_key
    );

    $response = wp_remote_get($url, array('timeout' => 15));

    if (is_wp_error($response)) {
        return $cached ? $cached : false;
    }

    $json = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($json['result'])) {
        return $cached ? $cached : false;
    }

    // Update cache
    update_option(GRE_OPT_LAST_DATA, $json['result']);
    update_option('gre_last_fetch_time', time());

    return $json['result'];
}

// ───────────────────────────────────────────────
// AJAX – Test verbinding
// ───────────────────────────────────────────────
add_action('wp_ajax_gre_test_connection','gre_test_connection_callback');

function gre_test_connection_callback() {
    if (!current_user_can('manage_options')) wp_send_json_error('Geen toestemming.');

    $api_key  = sanitize_text_field($_POST['api_key'] ?? '');
    $place_id = sanitize_text_field($_POST['place_id'] ?? '');

    if (!$api_key || !$place_id) wp_send_json_error('Vul API Key en Place ID in.');

    $url = sprintf(
        'https://maps.googleapis.com/maps/api/place/details/json?place_id=%s&fields=name,rating,user_ratings_total&key=%s',
        urlencode($place_id),
        $api_key
    );

    $response = wp_remote_get($url);
    if (is_wp_error($response)) wp_send_json_error('HTTP error');

    $json = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($json['result'])) wp_send_json_error('Ongeldig Place ID');

    update_option(GRE_OPT_LAST_DATA, $json['result']);

    wp_send_json_success("Verbonden met: " . $json['result']['name']);
}

// ───────────────────────────────────────────────
// AJAX – Force refresh data
// ───────────────────────────────────────────────
add_action('wp_ajax_gre_force_refresh', 'gre_force_refresh_callback');

function gre_force_refresh_callback() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Geen toestemming.');
    }

    check_ajax_referer('gre_force_refresh', 'nonce');

    // Force nieuwe data op te halen door cache te legen
    delete_option('gre_last_fetch_time');

    // Fetch fresh data
    $data = gre_fetch_google_place_data();

    if ($data && !empty($data['rating'])) {
        wp_send_json_success(array(
            'message' => 'Data succesvol ververst!',
            'rating' => $data['rating'],
            'count' => $data['user_ratings_total'] ?? 0,
            'name' => $data['name'] ?? ''
        ));
    } else {
        wp_send_json_error('Kon geen nieuwe data ophalen. Controleer je API instellingen.');
    }
}

// ───────────────────────────────────────────────
// AJAX – Check for plugin updates
// ───────────────────────────────────────────────
add_action('wp_ajax_gre_check_updates', 'gre_check_updates_callback');

function gre_check_updates_callback() {
    if (!current_user_can('update_plugins')) {
        wp_send_json_error('Geen toestemming.');
    }

    check_ajax_referer('gre_check_updates', 'nonce');

    // Clear the update cache
    delete_transient('gre_github_version');
    delete_site_transient('update_plugins');

    // Force WordPress to check for updates
    wp_update_plugins();

    // Get current and remote version
    if (!function_exists('get_plugin_data')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    $plugin_data = get_plugin_data(__FILE__, false, false);
    $current_version = $plugin_data['Version'];

    // Try to get remote version
    $repo_owner = 'Websitetoday';
    $repo_name = 'google-rating-dynamic-tags-elementor-plugin';
    $api_url = "https://api.github.com/repos/{$repo_owner}/{$repo_name}/releases/latest";

    $response = wp_remote_get($api_url, array(
        'timeout' => 15,
        'headers' => array('Accept' => 'application/vnd.github.v3+json'),
    ));

    if (is_wp_error($response)) {
        wp_send_json_error('Kon GitHub niet bereiken: ' . $response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['tag_name'])) {
        $remote_version = ltrim($data['tag_name'], 'v');

        if (version_compare($current_version, $remote_version, '<')) {
            wp_send_json_success(array(
                'message' => "Nieuwe versie beschikbaar: v{$remote_version}",
                'current' => $current_version,
                'remote' => $remote_version,
                'has_update' => true,
                'url' => $data['html_url'] ?? '',
            ));
        } else {
            wp_send_json_success(array(
                'message' => 'Je hebt de laatste versie!',
                'current' => $current_version,
                'remote' => $remote_version,
                'has_update' => false,
            ));
        }
    } else {
        wp_send_json_error('Kon versie info niet ophalen van GitHub.');
    }
}

// ───────────────────────────────────────────────
// SHORTCODE
// ───────────────────────────────────────────────
function gre_shortcode_google_rating($atts) {
    $atts = shortcode_atts(['field'=>'rating_star'],$atts);

    $data = gre_fetch_google_place_data();
    if (!$data) return 'Geen data beschikbaar.';

    $rating = $data['rating'] ?? 0;
    $count  = $data['user_ratings_total'] ?? 0;

    switch ($atts['field']) {
        case 'rating_number': return $rating;
        case 'count_number':  return $count;
        default:              return sprintf('%.1f ★ (%s reviews)', $rating, $count);
    }
}

if (get_option('gre_enable_shortcode',1)) {
    add_shortcode('google_rating','gre_shortcode_google_rating');
}

// ───────────────────────────────────────────────
// ELEMENTOR DYNAMIC TAGS
// ───────────────────────────────────────────────
add_action('elementor/dynamic_tags/register_tags', function ($tags) {
    if (!class_exists('ElementorPro\\Plugin')) return;
    require_once __DIR__.'/dynamic-tags/class-google-rating-tag.php';
    $tags->register(new \GRE\DynamicTags\Google_Rating_Tag());
},50);

add_action('elementor/dynamic_tags/register_tags', function ($tags) {
    if (!class_exists('ElementorPro\\Plugin')) return;
    require_once __DIR__.'/dynamic-tags/class-google-opening-hours-tag.php';
    $tags->register(new \GRE\DynamicTags\Google_Opening_Hours_Tag());
},51);

// ───────────────────────────────────────────────
// ADMIN STYLES
// ───────────────────────────────────────────────
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'toplevel_page_gre_options') return;

    wp_enqueue_style('gre-admin-style', plugin_dir_url(__FILE__).'assets/css/admin-style.css');
    wp_enqueue_script('gre-admin-js', plugin_dir_url(__FILE__).'assets/js/gre-admin.js',['jquery'],'1.2.0',true);

    wp_localize_script('gre-admin-js','greSettings',[
        'ajaxUrl'=>admin_url('admin-ajax.php'),
        'apiKeyField'=>'gre_api_key',
        'placeIdField'=>'gre_place_id',
        'refreshNonce'=>wp_create_nonce('gre_force_refresh'),
        'updatesNonce'=>wp_create_nonce('gre_check_updates'),
    ]);
});

// ───────────────────────────────────────────────
// PLUGIN OVERZICHT → INSTELLINGEN LINK
// ───────────────────────────────────────────────
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $settings_link = '<a href="'.admin_url('admin.php?page=gre_options').'">Instellingen</a>';
    array_unshift($links,$settings_link);
    return $links;
});
