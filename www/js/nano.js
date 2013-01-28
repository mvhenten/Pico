(function($){
    var formAction = function( modalConfig ){
        var $form = $( this.form ), formData = $form.serializeArray();
        
        formData.push({
            name: $(this).attr('name'),
            value: $(this).val()
        });

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            context: document.body,
            data: formData,
            success: function( data, status, xhr ){               
                if( !data ) return;
                
                var html = $(data).find('form');
                
                modalConfig = $.extend({
                    title: 'Select options',
//                    position: { my: "center", at: "center", of: '#main' },
                    modal: true,
                    width: 600,
                    height: 500,
                    resizable: false,
                    buttons: [
                        { text: "Cancel", click: function() {
                            $( this ).dialog( "close" );
                        }},
                        { text: "Save", click: function() {
                            $(html).submit();
                            $( this ).dialog( "close" );
                        }}
                    ]
                }, modalConfig );
                
                $(html).dialog( modalConfig );            
            }
        });        
    };
    
    $.fn.extend({
        nanoAhahForm: function( targetSelector, modalConfig ) {
            $( this ).click( function( evt ){
                evt.preventDefault();
                formAction.call( this, targetSelector, modalConfig );
            });

            return this;
        }
    });
})(jQuery);

var nano = {
    ahah: {
        /**
         * shows the link in a dialog
         */
        link: function( link, selector ){
            var url = link.href;

            $.ajax({
                url: url,
                type: "GET",
                context: document.body,
                success: function( data, status, xhr ){
                    $(data).find(selector).dialog({
                        modal:true,
                        height: 'auto',
                        maxHeight: 500
                    });
                }
            });

            return false;
        }
    },

    validate: {
      /**
       * simple validation: checks for file types
       * @todo Check for size, too
       */
      upload: function( el, types, label, buttons ){
        var ext = el.value.toLowerCase().split('.').pop();
        label = $(el.form).find(label);
        buttons = $(el.form).find(buttons);

        var validate = {};
        for( i in types ) validate[types[i]] = true;


        if( ext in validate ){
            $(label).text("Selected " + el.value)
            $(buttons).removeAttr('disabled');
        }
        else{
            el.value = null;
            $(label).text('Invalid image type: ' + ext)
            $(buttons).attr('disabled','disabled');
        }
      }
    }
}

function upload_validate(el){
    var ext = el.value.toLowerCase().split('.').pop();

    if( ext in {'png':1,'jpeg':1,'jpg':1} ){
        $(el.parentNode).find('label').text("Selected " + el.value)
        $('.input-submit').removeAttr('disabled');
    }
    else{
        el.value = null;
        $(el.parentNode).find('label').text('Invalid image type: ' + ext)
        $('.input-submit').attr('disabled','disabled');
    }
}