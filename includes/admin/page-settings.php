<?php
/**
 * Renders the “Instellingen” tab content
 */
function gre_render_settings_page() {
    // 1) Handle reset van API-call teller
    if ( isset( $_POST['gre_reset_calls_button'] ) && check_admin_referer( 'gre_reset_calls' ) ) {
        update_option( 'gre_api_call_count', 0 );
        echo '<div class="updated"><p>' . esc_html__( 'API-call teller gereset.', 'gre' ) . '</p></div>';
    }

    // 2) Toon huidige API-call teller
    $calls = (int) get_option( 'gre_api_call_count', 0 );
    ?>
    <p><strong><?php esc_html_e( 'API-calls gedaan via cron:', 'gre' ); ?></strong>
       <?php echo esc_html( $calls ); ?>
    </p>

    <!-- 3) Reset-knop -->
    <form method="post">
        <?php wp_nonce_field( 'gre_reset_calls' ); ?>
        <?php submit_button( esc_html__( 'Reset API-call teller', 'gre' ), 'secondary', 'gre_reset_calls_button' ); ?>
    </form>

    <hr/>

    <!-- 4) Bestaande instellingen-form -->
    <form action="options.php" method="post">
        <?php
        settings_fields( 'gre_settings' );
        do_settings_sections( 'gre_settings' );
        submit_button();
        ?>
    </form>
    <?php
}
