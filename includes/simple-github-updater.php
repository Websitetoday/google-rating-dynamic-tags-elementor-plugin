<?php
/**
 * Simple GitHub Updater
 * A lightweight GitHub update checker that doesn't cause fatal errors
 */

if (!defined('ABSPATH')) exit;

class SimpleGitHubUpdater {
    private $plugin_file;
    private $plugin_slug;
    private $github_repo;
    private $plugin_data;

    public function __construct($plugin_file, $github_repo) {
        $this->plugin_file = $plugin_file;
        $this->plugin_slug = plugin_basename($plugin_file);
        $this->github_repo = $github_repo;

        // Get plugin data
        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        $this->plugin_data = get_plugin_data($plugin_file);

        // Hook into WordPress update system
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        add_filter('plugins_api', array($this, 'plugin_info'), 20, 3);
    }

    /**
     * Check for updates from GitHub
     */
    public function check_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        // Get latest release from GitHub
        $remote_version = $this->get_remote_version();

        if ($remote_version && version_compare($this->plugin_data['Version'], $remote_version, '<')) {
            $download_url = $this->get_download_url();

            $plugin = array(
                'slug' => dirname($this->plugin_slug),
                'new_version' => $remote_version,
                'url' => $this->github_repo,
                'package' => $download_url,
                'tested' => '6.4',
                'requires_php' => '7.4',
            );

            $transient->response[$this->plugin_slug] = (object) $plugin;
        }

        return $transient;
    }

    /**
     * Get plugin information for the popup
     */
    public function plugin_info($result, $action, $args) {
        if ($action !== 'plugin_information') {
            return $result;
        }

        if (!isset($args->slug) || $args->slug !== dirname($this->plugin_slug)) {
            return $result;
        }

        $remote_version = $this->get_remote_version();
        $changelog = $this->get_changelog();

        $plugin = array(
            'name' => $this->plugin_data['Name'],
            'slug' => dirname($this->plugin_slug),
            'version' => $remote_version,
            'author' => $this->plugin_data['AuthorName'],
            'author_profile' => $this->plugin_data['AuthorURI'],
            'homepage' => $this->plugin_data['PluginURI'],
            'requires' => '5.0',
            'tested' => '6.4',
            'requires_php' => '7.4',
            'download_link' => $this->get_download_url(),
            'sections' => array(
                'description' => $this->plugin_data['Description'],
                'changelog' => $changelog,
            ),
        );

        return (object) $plugin;
    }

    /**
     * Get remote version from GitHub
     */
    private function get_remote_version() {
        $cache_key = 'gre_github_version';
        $cached = get_transient($cache_key);

        if ($cached !== false) {
            return $cached;
        }

        // Parse repo URL
        $repo = $this->parse_repo_url();
        if (!$repo) {
            return false;
        }

        // Get latest release info from GitHub API
        $api_url = "https://api.github.com/repos/{$repo['owner']}/{$repo['name']}/releases/latest";

        $response = wp_remote_get($api_url, array(
            'timeout' => 15,
            'headers' => array(
                'Accept' => 'application/vnd.github.v3+json',
            ),
        ));

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['tag_name'])) {
            $version = ltrim($data['tag_name'], 'v');
            set_transient($cache_key, $version, HOUR_IN_SECONDS * 12);
            return $version;
        }

        return false;
    }

    /**
     * Get download URL for latest release
     */
    private function get_download_url() {
        $repo = $this->parse_repo_url();
        if (!$repo) {
            return '';
        }

        // Use zipball URL from main branch
        return "https://github.com/{$repo['owner']}/{$repo['name']}/archive/refs/heads/main.zip";
    }

    /**
     * Get changelog content
     */
    private function get_changelog() {
        $changelog_file = dirname($this->plugin_file) . '/CHANGELOG.md';

        if (file_exists($changelog_file)) {
            $changelog = file_get_contents($changelog_file);
            // Convert markdown to basic HTML
            $changelog = wp_kses_post($changelog);
            $changelog = nl2br($changelog);
            return $changelog;
        }

        return 'See GitHub repository for changelog.';
    }

    /**
     * Parse GitHub repository URL
     */
    private function parse_repo_url() {
        if (preg_match('#github\.com/([^/]+)/([^/]+)#', $this->github_repo, $matches)) {
            return array(
                'owner' => $matches[1],
                'name' => rtrim($matches[2], '/'),
            );
        }
        return false;
    }
}
