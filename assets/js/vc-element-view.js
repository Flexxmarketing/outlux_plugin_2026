(function ($) {
  window.VcCustomElementView = vc.shortcode_view.extend({

    changeShortcodeParams: function (model) {
      var $params,
        $tag,
        $settings,
        $element;

      window.VcCustomElementView.__super__.changeShortcodeParams.call(this, model);
      $params = model.get('params');
      $tag = model.get('shortcode');
      $settings = vc.map[$tag];
      $element = this.$el;

      if (_.isObject($params)) {
        _.each($settings.params, function (param_settings) {
          var $name,
            $type,
            $value,
            $wrapper,
            $admin_label;

          $name = param_settings.param_name;
          $type = param_settings.type;
          $value = $params[$name];
          $wrapper = $element.find('> .wpb_element_wrapper, .vc_element-wrapper');
          $admin_label = $wrapper.find('.admin_label_' + $name + ' label').text().replace(':', '');

          // Handle link params
          if ($type === 'vc_link') {
            var $url,
              $link,
              $title;

            if ($value) {
              $link = $value.split('|');
              if ($link.length > 1) {
                $url = decodeURIComponent($link[0].split(':').pop());
                $title = decodeURIComponent($link[1].split(':').pop());
                $element.find('.admin_label_' + $name).addClass('is-flexx-link').html('<label>' + $admin_label + ':</label> ' + $title + ' <span>(url: ' + $url + ')</span>');
              } else {
                $element.find('.admin_label_' + $name);
              }
            } else {
              $element.find('.admin_label_' + $name).hide();
            }
          }

          // Handle image params
          if ($type === 'attach_image') {
            if ($value && $value.match(/^\d+$/)) {
              $element.find('.admin_label_' + $name).addClass('is-flexx-image').html('<label>' + $admin_label + ':</label> <span></span>');
              $.ajax({
                type: 'POST',
                url: window.ajaxurl,
                data: {
                  action: 'wpb_single_image_src',
                  content: $value,
                  size: 'thumbnail',
                  _vcnonce: window.vcAdminNonce
                },
                dataType: 'html',
                context: this
              }).done(function (url) {
                $element.find('.admin_label_' + $name).addClass('is-flexx-image').html('<label>' + $admin_label + ':</label> <img src="' + url + '">');
              });
            }
          }

          // Handle image gallery params
          if($type === 'attach_images') {
            $element.find('.admin_label_' + $name).addClass('is-flexx-image').html('<label style="display: block;">' + $admin_label + '</label>');

            if($value) {
              var $images = $value.split(',');
              $.each($images, function(index, value) {
                $.ajax({
                  type: 'POST',
                  url: window.ajaxurl,
                  data: {
                    action: 'wpb_single_image_src',
                    content: value,
                    size: 'thumbnail',
                    _vcnonce: window.vcAdminNonce
                  },
                  dataType: 'html',
                  context: this
                }).done(function (url) {
                  $element.find('.admin_label_' + $name).append('<img style="display: inline-block; margin-right: 5px;" src="' + url + '">');
                });
              });
            }
          }

          if($type === 'textfield' || $type === 'textarea') {
            if($value) {
              $value = $value.replace(/(?:\*)(?:(?!\s))((?:(?!\*|\n).)+)(?:\*)/g,'$1')
                .replace(/(?:_)(?:(?!\s))((?:(?!\n|_).)+)(?:_)/g, '$1')
                .replace(/(?:~)(?:(?!\s))((?:(?!\n|~).)+)(?:~)/g,'$1')
                .replace(/(?:--)(?:(?!\s))((?:(?!\n|--).)+)(?:--)/g,'$1')
              $element.find('.admin_label_' + $name).html('<label>' + $admin_label + ':</label> ' + $value);
            }
          }

        });

      }

    }

  });
})(window.jQuery);
