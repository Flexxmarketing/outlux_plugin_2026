(function($) {
  $(document).ready(function() {
    $('.dual-listbox-wrapper').each(function() {
      const wrapper = $(this);
      const available = wrapper.find('.available-items');
      const selected = wrapper.find('.selected-items');
      const input = wrapper.find('input.dual-listbox-hidden');

      function updateHiddenField() {
        const values = selected.find('option').map(function() {
          return $(this).val();
        }).get();
        input.val(values.join(','));
      }

      wrapper.find('.add').on('click', function() {
        available.find(':selected').appendTo(selected);
        updateHiddenField();
      });

      wrapper.find('.remove').on('click', function() {
        selected.find(':selected').appendTo(available);
        updateHiddenField();
      });

      wrapper.find('.move-up').on('click', function() {
        selected.find(':selected').each(function() {
          const prev = $(this).prev('option');
          if (prev.length) {
            $(this).insertBefore(prev);
          }
        });
        updateHiddenField();
      });

      wrapper.find('.move-down').on('click', function() {
        $(selected.find('option:selected').get().reverse()).each(function() {
          const next = $(this).next('option');
          if (next.length) {
            $(this).insertAfter(next);
          }
        });
        updateHiddenField();
      });

      available.on('dblclick', 'option', function() {
        $(this).appendTo(selected);
        updateHiddenField();
      });

      selected.on('dblclick', 'option', function() {
        $(this).appendTo(available);
        updateHiddenField();
      });
    });
  });
})(jQuery);