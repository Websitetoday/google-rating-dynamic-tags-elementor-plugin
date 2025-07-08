<?php
/**
 * Renders the “Changelog” tab content
 */
function gre_render_changelog_page() {
    $markdown_file = plugin_dir_path( __FILE__ ) . '../../CHANGELOG.md';

    echo '<div class="wrap gre-admin-wrap">';
    echo '<h2>' . esc_html__( 'Changelog', 'gre' ) . '</h2>';

    if ( file_exists( $markdown_file ) ) {
        $markdown = file_get_contents( $markdown_file );
        echo '<div class="gre-card">';
        echo '<pre style="white-space: pre-wrap; font-family: monospace; font-size: 13px;">' . esc_html( $markdown ) . '</pre>';
        echo '</div>';
    } else {
        echo '<p>' . esc_html__( 'CHANGELOG.md niet gevonden.', 'gre' ) . '</p>';
    }

    echo '</div>';
}
