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

    // Bedrijfsnaam tonen
    function updateCompanyName(name) {
        $('#gre-place-company').remove(); // Remove old
        if (name) {
            $('#' + greSettings.placeIdField)
                .closest('.gre-setting')
                .append('<span id="gre-place-company" style="display:block;margin-top:8px;color:#28a745;font-weight:500;">Verbonden met: ' + $('<div>').text(name).html() + '</span>');
        }
    }

    // AJAX-check uitvoeren (voor real-time velden)
    function checkConnectionIcons() {
        var apiKey  = $('#' + greSettings.apiKeyField).val().trim();
        var placeId = $('#' + greSettings.placeIdField).val().trim();

        if (! apiKey || ! placeId) {
            updateStatusIcon($('#gre-api-status'), false);
            updateStatusIcon($('#gre-place-status'), false);
            updateCompanyName('');
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
                    updateCompanyName(response.data && response.data.company ? response.data.company : '');
                } else {
                    localStorage.removeItem('gre_connection_ok');
                    updateCompanyName('');
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
            updateCompanyName('');
            return;
        }

        // Knop disablen en “laden” indicatie
        $(this).prop('disabled', true);
        $result.text('Even geduld…').css('color', '');
        updateCompanyName('');

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
                .text(typeof response.data === 'string' ? response.data : (ok ? 'Verbonden! ✔️' : 'Verbinding mislukt'))
                .css('color', ok ? '#28a745' : '#dc3545');
            updateStatusIcon($('#gre-api-status'), ok);
            updateStatusIcon($('#gre-place-status'), ok);
            if (ok && response.data && response.data.company) {
                localStorage.setItem('gre_connection_ok', '1');
                updateCompanyName(response.data.company);
            } else {
                localStorage.removeItem('gre_connection_ok');
                updateCompanyName('');
            }
        }).fail(function(){
            $result.text('AJAX-fout').css('color', '#dc3545');
            updateCompanyName('');
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
            if (ok) {
                msg += ' (Let op: standaard wordt de data maximaal 1x per week automatisch opgehaald.)';
            }
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
        updateCompanyName('');

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
