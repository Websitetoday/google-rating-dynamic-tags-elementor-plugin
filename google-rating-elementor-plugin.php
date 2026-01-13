<?php
/**
 * Plugin Name:     Google Rating Dynamic Tags Elementor
 * Plugin URI:      https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin
 * GitHub Plugin URI: https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin
 * Description:     Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score, aantal reviews en link naar reviews) als Elementor Dynamic Tag en via shortcode.
 * Version:         3.8.0
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
define('GRE_OPT_BUSINESSES', 'gre_businesses'); // Array van bedrijven

// ───────────────────────────────────────────────
// MULTI-BUSINESS HELPER FUNCTIONS
// ───────────────────────────────────────────────

/**
 * Haal alle bedrijven op (met migratie van oude single-business data)
 */
function gre_get_businesses() {
    $businesses = get_option(GRE_OPT_BUSINESSES, []);

    // Migratie: converteer oude single-business data naar array
    if (empty($businesses)) {
        $old_place_id = get_option(GRE_OPT_PLACE_ID);
        $old_data = get_option(GRE_OPT_LAST_DATA);

        if ($old_place_id) {
            $businesses = [
                'default' => [
                    'id' => 'default',
                    'name' => $old_data['name'] ?? 'Hoofdbedrijf',
                    'place_id' => $old_place_id,
                    'data' => $old_data,
                    'last_fetch' => get_option('gre_last_fetch_time', 0),
                ]
            ];
            update_option(GRE_OPT_BUSINESSES, $businesses);
        }
    }

    return $businesses;
}

/**
 * Haal een specifiek bedrijf op
 */
function gre_get_business($business_id = 'default') {
    $businesses = gre_get_businesses();
    return $businesses[$business_id] ?? null;
}

/**
 * Sla een bedrijf op
 */
function gre_save_business($business_id, $data) {
    $businesses = gre_get_businesses();
    $businesses[$business_id] = array_merge(
        $businesses[$business_id] ?? [],
        $data,
        ['id' => $business_id]
    );
    return update_option(GRE_OPT_BUSINESSES, $businesses);
}

/**
 * Verwijder een bedrijf
 */
function gre_delete_business($business_id) {
    if ($business_id === 'default') {
        return false; // Voorkom verwijderen van default
    }
    $businesses = gre_get_businesses();
    unset($businesses[$business_id]);
    return update_option(GRE_OPT_BUSINESSES, $businesses);
}

/**
 * Genereer unieke business ID
 */
function gre_generate_business_id() {
    return 'biz_' . substr(md5(uniqid(mt_rand(), true)), 0, 8);
}

/**
 * Converteer AM/PM tijd naar 24-uurs formaat
 * Bijvoorbeeld: "9:00 AM – 5:00 PM" wordt "09:00 – 17:00"
 * Ondersteunt verschillende formaten: "9:00 AM", "9:00AM", "9:00 am", etc.
 */
function gre_convert_to_24h($time_string) {
    // Eerst alle speciale spaties normaliseren naar gewone spaties
    $time_string = preg_replace('/[\x{00A0}\x{2009}\x{202F}]/u', ' ', $time_string);

    // Flexibel patroon voor AM/PM tijden
    $pattern = '/(\d{1,2}):(\d{2})\s*(AM|PM)/i';

    $result = preg_replace_callback($pattern, function($matches) {
        $hour = intval($matches[1]);
        $minute = $matches[2];
        $period = strtoupper($matches[3]);

        if ($period === 'PM' && $hour !== 12) {
            $hour += 12;
        } elseif ($period === 'AM' && $hour === 12) {
            $hour = 0;
        }

        return sprintf('%02d:%s', $hour, $minute);
    }, $time_string);

    return $result;
}

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
function gre_fetch_google_place_data($business_id = 'default') {
    $business = gre_get_business($business_id);

    // Fallback naar legacy systeem als geen business gevonden
    if (!$business) {
        $cached = get_option(GRE_OPT_LAST_DATA);
        if (is_array($cached) && !empty($cached['rating'])) {
            $cache_time = get_option('gre_last_fetch_time', 0);
            $week_ago = time() - (7 * 24 * 60 * 60);
            if ($cache_time > $week_ago) {
                return $cached;
            }
        }
        $place_id = get_option(GRE_OPT_PLACE_ID);
    } else {
        // Gebruik business data
        $cached = $business['data'] ?? null;
        $cache_time = $business['last_fetch'] ?? 0;
        $week_ago = time() - (7 * 24 * 60 * 60);

        if (is_array($cached) && !empty($cached['rating']) && $cache_time > $week_ago) {
            return $cached;
        }
        $place_id = $business['place_id'] ?? null;
    }

    // Fetch fresh data
    $api_key = get_option(GRE_OPT_API_KEY);

    if (!$api_key || !$place_id) {
        return $cached ?: false;
    }

    $url = sprintf(
        'https://maps.googleapis.com/maps/api/place/details/json?place_id=%s&fields=name,rating,user_ratings_total,opening_hours&key=%s',
        urlencode($place_id),
        $api_key
    );

    $response = wp_remote_get($url, array('timeout' => 15));

    if (is_wp_error($response)) {
        return $cached ?: false;
    }

    $json = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($json['result'])) {
        return $cached ?: false;
    }

    // Update cache voor dit bedrijf
    if ($business) {
        gre_save_business($business_id, [
            'data' => $json['result'],
            'last_fetch' => time(),
            'name' => $json['result']['name'] ?? $business['name'],
        ]);
    } else {
        // Legacy fallback
        update_option(GRE_OPT_LAST_DATA, $json['result']);
        update_option('gre_last_fetch_time', time());
    }

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
// AJAX – Add business
// ───────────────────────────────────────────────
add_action('wp_ajax_gre_add_business', 'gre_add_business_callback');

function gre_add_business_callback() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Geen toestemming.');
    }

    check_ajax_referer('gre_add_business', 'nonce');

    $place_id = sanitize_text_field($_POST['place_id'] ?? '');
    if (empty($place_id)) {
        wp_send_json_error('Vul een Place ID in.');
    }

    $api_key = get_option(GRE_OPT_API_KEY);
    if (empty($api_key)) {
        wp_send_json_error('Sla eerst een API Key op.');
    }

    // Haal data op van Google
    $url = sprintf(
        'https://maps.googleapis.com/maps/api/place/details/json?place_id=%s&fields=name,rating,user_ratings_total,opening_hours&key=%s',
        urlencode($place_id),
        $api_key
    );

    $response = wp_remote_get($url, ['timeout' => 15]);
    if (is_wp_error($response)) {
        wp_send_json_error('Kon Google niet bereiken.');
    }

    $json = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($json['result'])) {
        wp_send_json_error('Ongeldig Place ID of geen resultaat.');
    }

    // Genereer ID of gebruik 'default' als eerste bedrijf
    $businesses = gre_get_businesses();
    $business_id = empty($businesses) ? 'default' : gre_generate_business_id();

    // Sla bedrijf op
    gre_save_business($business_id, [
        'name' => $json['result']['name'] ?? 'Onbekend',
        'place_id' => $place_id,
        'data' => $json['result'],
        'last_fetch' => time(),
    ]);

    // Update ook legacy opties voor backwards compatibility
    if ($business_id === 'default') {
        update_option(GRE_OPT_PLACE_ID, $place_id);
        update_option(GRE_OPT_LAST_DATA, $json['result']);
        update_option('gre_last_fetch_time', time());
    }

    wp_send_json_success([
        'message' => 'Bedrijf "' . $json['result']['name'] . '" toegevoegd!',
        'business_id' => $business_id,
        'name' => $json['result']['name'],
        'rating' => $json['result']['rating'] ?? 0,
        'reviews' => $json['result']['user_ratings_total'] ?? 0,
    ]);
}

// ───────────────────────────────────────────────
// AJAX – Delete business
// ───────────────────────────────────────────────
add_action('wp_ajax_gre_delete_business', 'gre_delete_business_callback');

function gre_delete_business_callback() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Geen toestemming.');
    }

    check_ajax_referer('gre_delete_business', 'nonce');

    $business_id = sanitize_text_field($_POST['business_id'] ?? '');
    if (empty($business_id)) {
        wp_send_json_error('Geen bedrijf ID opgegeven.');
    }

    if ($business_id === 'default') {
        wp_send_json_error('Het standaard bedrijf kan niet worden verwijderd.');
    }

    if (gre_delete_business($business_id)) {
        wp_send_json_success(['message' => 'Bedrijf verwijderd.']);
    } else {
        wp_send_json_error('Kon bedrijf niet verwijderen.');
    }
}

// ───────────────────────────────────────────────
// AJAX – Refresh business data
// ───────────────────────────────────────────────
add_action('wp_ajax_gre_refresh_business', 'gre_refresh_business_callback');

function gre_refresh_business_callback() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Geen toestemming.');
    }

    check_ajax_referer('gre_refresh_business', 'nonce');

    $business_id = sanitize_text_field($_POST['business_id'] ?? 'default');
    $business = gre_get_business($business_id);

    if (!$business) {
        wp_send_json_error('Bedrijf niet gevonden.');
    }

    // Reset cache time om verse data te forceren
    gre_save_business($business_id, ['last_fetch' => 0]);

    // Haal nieuwe data op
    $data = gre_fetch_google_place_data($business_id);

    if ($data && !empty($data['rating'])) {
        wp_send_json_success([
            'message' => 'Data voor "' . ($data['name'] ?? 'Bedrijf') . '" ververst!',
            'rating' => $data['rating'],
            'reviews' => $data['user_ratings_total'] ?? 0,
            'name' => $data['name'] ?? '',
        ]);
    } else {
        wp_send_json_error('Kon geen nieuwe data ophalen.');
    }
}

// ───────────────────────────────────────────────
// SHORTCODE
// ───────────────────────────────────────────────
function gre_shortcode_google_rating($atts) {
    $atts = shortcode_atts([
        'field' => 'rating_star',
        'business' => 'default',
    ], $atts);

    $data = gre_fetch_google_place_data($atts['business']);
    if (!$data) return 'Geen data beschikbaar.';

    $rating = $data['rating'] ?? 0;
    $count  = $data['user_ratings_total'] ?? 0;

    switch ($atts['field']) {
        case 'rating_number': return $rating;
        case 'count_number':  return $count;
        case 'badge':         return gre_render_badge($rating, $count);
        default:              return sprintf('%.1f ★ (%s reviews)', $rating, $count);
    }
}

/**
 * Render de Google Rating badge met logo, sterren, score en reviews
 */
function gre_render_badge($rating, $count) {
    // Google logo SVG
    $google_logo = '<svg class="gre-badge-google-logo" viewBox="0 0 74 24" xmlns="http://www.w3.org/2000/svg"><path d="M9.24 8.19v2.46h5.88c-.18 1.38-.64 2.39-1.34 3.1-.86.86-2.2 1.8-4.54 1.8-3.62 0-6.45-2.92-6.45-6.54s2.83-6.54 6.45-6.54c1.95 0 3.38.77 4.43 1.76L15.4 2.5C13.94 1.08 11.98 0 9.24 0 4.28 0 .11 4.04.11 9s4.17 9 9.13 9c2.68 0 4.7-.88 6.28-2.52 1.62-1.62 2.13-3.91 2.13-5.75 0-.57-.04-1.1-.13-1.54H9.24z" fill="#4285F4"/><path d="M25 6.19c-3.21 0-5.83 2.44-5.83 5.81 0 3.34 2.62 5.81 5.83 5.81s5.83-2.46 5.83-5.81c0-3.37-2.62-5.81-5.83-5.81zm0 9.33c-1.76 0-3.28-1.45-3.28-3.52 0-2.09 1.52-3.52 3.28-3.52s3.28 1.43 3.28 3.52c0 2.07-1.52 3.52-3.28 3.52z" fill="#EA4335"/><path d="M53.58 7.49h-.09c-.57-.68-1.67-1.3-3.06-1.3C47.53 6.19 45 8.72 45 12c0 3.26 2.53 5.81 5.43 5.81 1.39 0 2.49-.62 3.06-1.32h.09v.81c0 2.22-1.19 3.41-3.1 3.41-1.56 0-2.53-1.12-2.93-2.07l-2.22.92c.64 1.54 2.33 3.43 5.15 3.43 2.99 0 5.52-1.76 5.52-6.05V6.49h-2.42v1zm-2.93 8.03c-1.76 0-3.1-1.5-3.1-3.52 0-2.05 1.34-3.52 3.1-3.52 1.74 0 3.1 1.5 3.1 3.54 0 2.02-1.36 3.5-3.1 3.5z" fill="#4285F4"/><path d="M38 6.19c-3.21 0-5.83 2.44-5.83 5.81 0 3.34 2.62 5.81 5.83 5.81s5.83-2.46 5.83-5.81c0-3.37-2.62-5.81-5.83-5.81zm0 9.33c-1.76 0-3.28-1.45-3.28-3.52 0-2.09 1.52-3.52 3.28-3.52s3.28 1.43 3.28 3.52c0 2.07-1.52 3.52-3.28 3.52z" fill="#FBBC05"/><path d="M58 .24h2.51v17.57H58z" fill="#34A853"/><path d="M68.26 15.52c-1.3 0-2.22-.59-2.82-1.76l7.77-3.21-.26-.66c-.48-1.3-1.96-3.7-4.97-3.7-2.99 0-5.48 2.35-5.48 5.81 0 3.26 2.46 5.81 5.76 5.81 2.66 0 4.2-1.63 4.84-2.57l-1.98-1.32c-.66.96-1.56 1.6-2.86 1.6zm-.18-7.15c1.03 0 1.91.53 2.2 1.28l-5.25 2.17c0-2.44 1.73-3.45 3.05-3.45z" fill="#EA4335"/></svg>';

    // Sterren genereren op basis van rating
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);

    $stars_html = '<span class="gre-badge-stars">';

    // Volle sterren
    for ($i = 0; $i < $full_stars; $i++) {
        $stars_html .= '<svg class="gre-star gre-star-full" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" fill="#FBBC04"/></svg>';
    }

    // Halve ster
    if ($half_star) {
        $stars_html .= '<svg class="gre-star gre-star-half" viewBox="0 0 24 24"><defs><linearGradient id="halfGrad"><stop offset="50%" stop-color="#FBBC04"/><stop offset="50%" stop-color="#E8E8E8"/></linearGradient></defs><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" fill="url(#halfGrad)"/></svg>';
    }

    // Lege sterren
    for ($i = 0; $i < $empty_stars; $i++) {
        $stars_html .= '<svg class="gre-star gre-star-empty" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" fill="#E8E8E8"/></svg>';
    }

    $stars_html .= '</span>';

    // Badge HTML samenstellen
    $html = '<div class="gre-rating-badge">';
    $html .= $google_logo;
    $html .= $stars_html;
    $html .= '<span class="gre-badge-score">' . number_format($rating, 1, ',', '.') . '</span>';
    $html .= '<span class="gre-badge-separator">|</span>';
    $html .= '<span class="gre-badge-reviews">' . number_format($count, 0, ',', '.') . ' reviews</span>';
    $html .= '</div>';

    return $html;
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
// ELEMENTOR WIDGETS
// ───────────────────────────────────────────────
add_action('elementor/widgets/register', function ($widgets_manager) {
    // Google Rating Badge Widget
    require_once __DIR__ . '/widgets/class-google-rating-badge-widget.php';
    $widgets_manager->register(new \GRE\Widgets\Google_Rating_Badge_Widget());

    // Google Opening Hours Widget
    require_once __DIR__ . '/widgets/class-google-opening-hours-widget.php';
    $widgets_manager->register(new \GRE\Widgets\Google_Opening_Hours_Widget());
});

// ───────────────────────────────────────────────
// FRONTEND STYLES (Badge)
// ───────────────────────────────────────────────
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('gre-frontend-badge', plugin_dir_url(__FILE__).'assets/css/frontend-badge.css', [], '1.0.0');
});

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
        'addBusinessNonce'=>wp_create_nonce('gre_add_business'),
        'deleteBusinessNonce'=>wp_create_nonce('gre_delete_business'),
        'refreshBusinessNonce'=>wp_create_nonce('gre_refresh_business'),
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
