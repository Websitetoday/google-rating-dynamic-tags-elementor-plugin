<?php
/**
 * Renders the “Instellingen” tab content
 */
function gre_render_settings_page() {
    ?>
    <form action="options.php" method="post">
        <?php
        settings_fields( 'gre_settings' );
        do_settings_sections( 'gre_settings' );
        submit_button();
        ?>
    </form>
    <?php
}
