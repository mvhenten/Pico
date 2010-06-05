$(document).observe('dom:loaded', function(evt){
    $$('.uploadForm').each( function( el ){
        var form = new FormUpload(el);
    });
});

var FormUpload = Class.create({
    initialize: function( element ){
        this._form = $(element);
        var frame = Element('div').update('<iframe name="go" id="go" src=""></iframe>').hide();

        this._form.up().insert({top: frame});
        this._form.target = 'go';
        this._iframe = frame.down('iframe');

        this._form.observe( 'submit', this.onSubmit.bindAsEventListener(this) );
    },

    onSubmit: function( evt ){
        this.onLoad = this.onFrameLoad.bindAsEventListener( this );
        this._iframe.observe( 'load', this.onLoad );
    },

    onFrameLoad: function(evt){
        this._iframe.stopObserving( 'load', this.onLoad );

        var doc = this._iframe.contentWindow || this._iframe.contentDocument;

        console.log( 'hier', doc );

        if( doc.document ){
            doc = doc.document;
        }

        var url = doc.baseURI;
        var src = doc.baseURI.replace( 'edit', 'view/thumbnail' );
        var name = doc.forms[0].elements['name'].value;

        var html = "\
            <dl class='thumbnail'>\
                <dt><a title='edit %name' href='%url'><img src='%src' /></a></dt>\
                <dd><a title='edit %name' href='%url'>edit %name</strong></dd>\
            </dl>\
        ";

        html = html.replace( /%name/ig, name );
        html = html.replace( /%url/ig, url );
        html = html.replace( /%src/ig, src );

        //$('main-left').insert('<a href="' + uri + '"><img src="' +  thumb + '" /></a>');
        //
        //console.log(img);

        $('upload-results').insert(html);

//        this._form.up().insert(html);
    }
});




//            function loadResults(){
//                var handler = function(){
//                    $('iframeSubmit').stopObserving('load', handler );
//
//                    var frame = $('iframeSubmit');
//
//                    var doc = frame.contentWindow || frame.contentDocument;
//                    if( doc.document ){
//                        doc = doc.document;
//                    }
//
////                    console.log( doc.findElementsByTagName('img') );
//
//
//  //                  console.log( frame.select('img') );
//
//
//                }
//
//                $('iframeSubmit').observe('load', handler );
////                console.log( $('image-add') );
//            }
