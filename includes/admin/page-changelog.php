<?php
if ( ! function_exists('gre_render_changelog_page') ) {
    function gre_render_changelog_page() {
        $md_file = plugin_dir_path( __DIR__ ) . '../CHANGELOG.md';
        if ( ! file_exists( $md_file ) ) {
            $md_file = plugin_dir_path( __DIR__ ) . '../changelog.md';
        }
        echo '<div class="wrap gre-admin-wrap">';
        echo '<h2>' . esc_html__( 'Changelog', 'gre' ) . '</h2>';

        if ( file_exists( $md_file ) ) {
            echo '<pre>';
            echo esc_html( file_get_contents( $md_file ) );
            echo '</pre>';
        } else {
            echo '<p style="color:#dc3545;">Geen CHANGELOG.md of changelog.md bestand gevonden in de pluginmap.</p>';
        }

        echo '</div>';
    }
}
