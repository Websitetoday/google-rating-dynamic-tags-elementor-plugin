jQuery(document).ready(function($){
    // Toggle visibility van wachtwoord-velden
    $(document).on('click', '.toggle-visibility', function(){
        var fieldID = $(this).data('field');
        var $input  = $('#' + fieldID);
        var type    = $input.attr('type') === 'password' ? 'text' : 'password';
        $input.attr('type', type);
    });

    // Status-icoon updaten
    function updateStatusIcon($icon, ok) {
        $icon
            .removeClass('dashicons-yes green dashicons-no-alt red')
            .addClass(ok ? 'dashicons-yes green' : 'dashicons-no-alt red');
    }

    // AJAX-check uitvoeren (voor real-time velden)
    function checkConnectionIcons() {
        var apiKey  = $('#' + greSettings.apiKeyField).val().trim();
        var placeId = $('#' + greSettings.placeIdField).val().trim();

        if (! apiKey || ! placeId) {
            updateStatusIcon($('#gre-api-status'), false);
            updateStatusIcon($('#gre-place-status'), false);
            return;
        }

        $.post(
            greSettings.ajaxUrl,
            {
                action:   'gre_test_connection',
                api_key:  apiKey,
                place_id: placeId
            },
            function(response) {
                var ok = response.success === true;
                updateStatusIcon($('#gre-api-status'), ok);
                updateStatusIcon($('#gre-place-status'), ok);
                if (ok) {
                    localStorage.setItem('gre_connection_ok', '1');
                } else {
                    localStorage.removeItem('gre_connection_ok');
                }
            }
        );
    }

    // Click-handler voor “Controleer verbinding” knop
    $('#gre-test-connection-button').on('click', function(){
        var apiKey  = $('#' + greSettings.apiKeyField).val().trim();
        var placeId = $('#' + greSettings.placeIdField).val().trim();
        var $result = $('#gre-test-connection-result');

        // Direct feedback bij lege velden
        if (! apiKey || ! placeId) {
            $result
                .text('Vul API Key en Place ID in.')
                .css('color', '#dc3545');
            updateStatusIcon($('#gre-api-status'), false);
            updateStatusIcon($('#gre-place-status'), false);
            localStorage.removeItem('gre_connection_ok');
            return;
        }

        // Knop disablen en “laden” indicatie
        $(this).prop('disabled', true);
        $result.text('Even geduld…').css('color', '');

        // AJAX-call
        $.post(
            greSettings.ajaxUrl,
            {
                action:   'gre_test_connection',
                api_key:  apiKey,
                place_id: placeId
            }
        ).done(function(response) {
            var ok = response.success === true;
            $result
                .text(response.data)
                .css('color', ok ? '#28a745' : '#dc3545');
            updateStatusIcon($('#gre-api-status'), ok);
            updateStatusIcon($('#gre-place-status'), ok);
            if (ok) {
                localStorage.setItem('gre_connection_ok', '1');
            } else {
                localStorage.removeItem('gre_connection_ok');
            }
        }).always(function(){
            $('#gre-test-connection-button').prop('disabled', false);
        });
    });

    // Handler voor “Ververs data” knop
    $('#gre-refresh-data-button').on('click', function(){
        var $btn    = $(this);
        var $result = $('#gre-refresh-data-result');

        // Knop disablen en “laden” indicatie
        $btn.prop('disabled', true);
        $result.text('Even geduld…').css('color', '');

        $.post(
            greSettings.ajaxUrl,
            {
                action:   'gre_force_refresh',
                security: greSettings.refreshNonce
            }
        ).done(function(response){
            var ok  = response.success === true;
            var msg = response.data;
            $result
                .text(msg)
                .css('color', ok ? '#28a745' : '#dc3545');
        }).fail(function(){
            $result.text('AJAX-fout').css('color', '#dc3545');
        }).always(function(){
            $btn.prop('disabled', false);
        });
    });

    // Real-time validatie op blur / input (debounce)
    var $fields = $('#' + greSettings.apiKeyField + ', #' + greSettings.placeIdField);
    $fields.on('blur', checkConnectionIcons);
    $fields.on('input paste', function() {
        // Zodra er getypt wordt: clear vorige status
        localStorage.removeItem('gre_connection_ok');
        updateStatusIcon($('#gre-api-status'), false);
        updateStatusIcon($('#gre-place-status'), false);

        clearTimeout($.data(this, 'timer'));
        var wait = setTimeout(checkConnectionIcons, 800);
        $(this).data('timer', wait);
    });

    // Bij laden: toon vorige succesvolle status als die er is
    if ( localStorage.getItem('gre_connection_ok') === '1' ) {
        updateStatusIcon($('#gre-api-status'), true);
        updateStatusIcon($('#gre-place-status'), true);
        $('#gre-test-connection-result')
            .text('Verbonden! ✔️')
            .css('color', '#28a745');
    }
});
