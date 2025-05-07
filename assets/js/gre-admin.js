jQuery(document).ready(function($) {
    console.log('✅ gre-admin.js geladen');

    // Inject CSS voor status-icoontjes en spinner-animatie
    const css = '\
.gre-status-icon.green { color: #46b450; }\
.gre-status-icon.red { color: #dc3232; }\
.gre-status-icon.dashicons-update { color: #0073aa; animation: spin 1s infinite linear; }\
@keyframes spin { from {transform:rotate(0deg);} to {transform:rotate(359deg);} }';
    $('head').append('<style>' + css + '</style>');

    // Toggle visibility voor wachtwoordvelden
    $(document).on('click', '.toggle-visibility', function() {
        const fieldID = $(this).data('field');
        const $input = $('#' + fieldID);
        const type = $input.attr('type') === 'password' ? 'text' : 'password';
        $input.attr('type', type);
    });

    // Helper: statusicoon bijwerken
    function updateIcon($icon, status) {
        // Reset naar basis-dashicons
        $icon.removeClass().addClass('gre-status-icon dashicons');
        if (status === true) {
            $icon.addClass('dashicons-yes green');
        } else if (status === false) {
            $icon.addClass('dashicons-no-alt red');
        } else {
            $icon.addClass('dashicons-update');
        }
    }

    // Helper: status ophalen uit localStorage
    function getStatusFromStorage(placeId) {
        return localStorage.getItem('gre_connection_' + placeId);
    }

    // Helper: status opslaan in localStorage
    function setStatusInStorage(placeId, ok) {
        if (!placeId) return;
        localStorage.setItem('gre_connection_' + placeId, ok ? '1' : '0');
    }

    // Init: bestaande rijen controleren op opgeslagen status
    $('#gre-places-table tbody tr').each(function() {
        const $row = $(this);
        const placeId = $row.find('input[name*="[place_id]"]').val().trim();
        const $icon = $row.find('.gre-status-icon');
        const saved = getStatusFromStorage(placeId);

        if (saved === '1') {
            updateIcon($icon, true);
        } else if (saved === '0') {
            updateIcon($icon, false);
        } else {
            updateIcon($icon, null); // toont spinner voor ongecheckte rijen
        }
    });

    // Bedrijf toevoegen
    $('#gre-add-row').on('click', function(e) {
        e.preventDefault();
        const $tbody = $('#gre-places-table tbody');
        const index = $tbody.find('tr').length;
        const row = `
            <tr>
                <td><input name="gre_places[${index}][label]" /></td>
                <td><input name="gre_places[${index}][place_id]" /></td>
                <td>
                    <button type="button" class="button gre-remove-row">×</button>
                    <button type="button" class="button gre-check-row" style="margin-left:5px;">Check</button>
                    <span class="gre-status-icon dashicons dashicons-update" style="margin-left:6px;"></span>
                </td>
            </tr>`;
        $tbody.append(row);
    });

    // Bedrijf verwijderen
    $(document).on('click', '.gre-remove-row', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();
    });

    // Per-bedrijf “Check” knop
    $(document).on('click', '.gre-check-row', function(e) {
        e.preventDefault();
        const $row = $(this).closest('tr');
        const placeId = $row.find('input[name*="[place_id]"]').val().trim();
        const apiKey = $('#' + greSettings.apiKeyField).val().trim();
        const $icon = $row.find('.gre-status-icon');

        if (!placeId || !apiKey) {
            updateIcon($icon, false);
            return;
        }

        updateIcon($icon, null); // spinner tonen tijdens het testen
        console.log('👉 Check klik:', { placeId, apiKey });

        $.post(greSettings.ajaxUrl, {
            action: 'gre_test_connection',
            api_key: apiKey,
            place_id: placeId
        }, function(response) {
            console.log('✅ AJAX response:', response);
            // Behandel ZERO_RESULTS / Geen resultaten als succesvolle connectie
            let ok = response.success;
            if (!ok && response.data && response.data.indexOf('Geen resultaten') !== -1) {
                ok = true;
            }
            updateIcon($icon, ok);
            setStatusInStorage(placeId, ok);
        }).fail(function() {
            updateIcon($icon, false);
            setStatusInStorage(placeId, false);
        });
    });
});
