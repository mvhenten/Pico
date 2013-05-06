(function($) {
    var ahah = function(form, callback, options ) {
        $.ajax({
            url:  form.action,
            type: form.method,
            data: $(form).serializeArray(),
            success: function(data) {
                if (!data) return;
                
                var html = $(data).find(options.selector);
                $( html ).addClass('ahah-generated');
                $( options.target ).prepend(html);
                
                if (typeof callback === 'function') {
                    callback( html );
                }
            }
        })
    }

    $.fn.nanoAhahForm = function(callback, options) {
        var _self = this;
        
        options = $.extend({
            selector: 'form',
            parent: null,
        },
        options);

        $(this).find('input[type="submit"], button').click(function(evt) {
            $(_self).append(
                $('<input id="ahah-cache"/>').attr({
                    type: 'hidden',
                    name: this.name,
                    value: this.value
                }));
            });

        $(this).submit(function(evt) {
            evt.preventDefault();
            options.target = options.target || $(this).first().parent();            
            $('.ahah-generated').fadeOut().remove();

            ahah(this, callback, options );
            $('#ahah-cache').remove();
        });

        return this;
    }
})(jQuery);