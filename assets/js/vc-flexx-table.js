// Enhanced VC Flexx Table JS with Toolbar and Column Removal
(function ($) {
  $(document).ready(function () {
    $('.flexx-table-param').each(function () {
      const $wrapper = $(this);
      const $table = $wrapper.find('table.flexx-table-editor tbody');
      const $input = $wrapper.find('input.flexx-table-hidden');
      let columns = JSON.parse($wrapper.find('.flexx-table-columns').text());
      let initDone = false;

      function updateHiddenField() {
        if (!initDone) return; // voorkomt dubbele initialisatie

        const data = [];
        $table.find('tr').each(function () {
          const $row = $(this);
          const rowData = {};
          $row.find('input.flexx-table-cell').each(function () {
            const key = $(this).data('key');
            const val = $(this).val();
            rowData[key] = val;
          });
          data.push(rowData);
        });
        $input.val(JSON.stringify(data));
      }

      function renderHeader() {
        let headerHtml = '<tr>';
        columns.forEach(col => {
          headerHtml += '<th>' +
            '<input type="text" class="flexx-table-heading" data-key="' + col.key + '" value="' + col.label + '">' +
            '<button type="button" class="remove-column" data-key="' + col.key + '">×</button>' +
            '</th>';
        });
        headerHtml += '<th></th></tr>';
        $wrapper.find('table thead').html(headerHtml);
      }

      function renderRow(data = {}) {
        let newRow = '<tr>';
        columns.forEach(function (col) {
          const val = data[col.key] || '';
          newRow += '<td><input type="text" class="flexx-table-cell" data-key="' + col.key + '" value="' + val + '"></td>';
        });
        newRow += '<td><button type="button" class="remove-row">×</button></td></tr>';
        $table.append(newRow);
      }

      // === INIT: parse bestaande data uit hidden input ===
      const existingDataRaw = $input.val();
      console.log('[Init hidden input value]', existingDataRaw); // <- voeg toe
      let existingData = [];

      try {
        existingData = JSON.parse(existingDataRaw);
      } catch (e) {
        existingData = [];
      }

      renderHeader();

      if (Array.isArray(existingData)) {
        existingData.forEach(row => renderRow(row));
      }

      // Pas na het renderen markeren we init als klaar
      initDone = true;
      updateHiddenField();

      $wrapper.on('click', '.add-row', function (e) {
        e.preventDefault();
        renderRow();
        updateHiddenField();
      });

      $wrapper.on('click', '.remove-row', function (e) {
        e.preventDefault();
        $(this).closest('tr').remove();
        updateHiddenField();
      });

      $wrapper.on('click', '.remove-column', function (e) {
        e.preventDefault();
        const keyToRemove = $(this).data('key');
        columns = columns.filter(col => col.key !== keyToRemove);
        $wrapper.find('.flexx-table-columns').text(JSON.stringify(columns));

        renderHeader();
        $table.find('tr').each(function () {
          $(this).find('td').filter(function () {
            return $(this).find('input').data('key') === keyToRemove;
          }).remove();
        });

        updateHiddenField();
      });

      $wrapper.on('click', '.add-column', function (e) {
        e.preventDefault();
        const newKey = 'col' + (columns.length + 1);
        const newLabel = 'Kolom ' + (columns.length + 1);
        columns.push({ key: newKey, label: newLabel });

        renderHeader();
        $table.find('tr').each(function () {
          $(this).find('td:last').before('<td><input type="text" class="flexx-table-cell" data-key="' + newKey + '" value=""></td>');
        });

        $wrapper.find('.flexx-table-columns').text(JSON.stringify(columns));
        updateHiddenField();
      });

      $wrapper.on('change input', '.flexx-table-cell', updateHiddenField);

      $wrapper.on('change input', '.flexx-table-heading', function () {
        const $el = $(this);
        const key = $el.data('key');
        const label = $el.val();
        columns.forEach(c => {
          if (c.key === key) c.label = label;
        });
        $wrapper.find('.flexx-table-columns').text(JSON.stringify(columns));
      });

      // Vertel WPBakery hoe de paramwaarde uitgelezen en opgeslagen moet worden
      if (typeof window.vc !== 'undefined' && typeof vc.shortcode_param !== 'undefined') {
        vc.shortcode_param['table'] = {
          getValue: function($input) {
            return $input.val(); // gewoon ruwe JSON string
          },
          setValue: function($input, value) {
            $input.val(value);
          }
        };
      }
    });
  });
})(jQuery);