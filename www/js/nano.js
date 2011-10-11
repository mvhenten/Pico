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
                        modal:true
                    });
                }
            });

            return false;
        },

        /**
         * override form submit handler: post the form and display the
         * results in a dialog
         */
        form: function( form, selector, origin ){
            var url     = $(form).attr('action');
            var mtd  = $(form).attr('method');

            var form_data = $(form).serializeArray();
            
            if( origin ){
                form_data.push({
                    name: origin.name,
                    value: origin.value
                });
            }

            $.ajax({
                url: url,
                type: mtd,
                context: document.body,
                data: form_data,
                success: function( data, status, xhr ){
                    $(data).find(selector).dialog({
                        modal:true
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

function ajaxify( link, search ){
    var url = link.href;

    $.ajax({
        url: url,
        context: document.body,
        type: "GET",
        success: function( data, status, xhr ){
            $(data).find(search).dialog({
                title: '',
                modal:true
            });
        }
    });
    return false;

}
