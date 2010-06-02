$(document).observe('dom:loaded', function(evt){
    console.log('ready');
    $$('.uploadForm').each( function( el ){
        var form = new FormUpload(el);
    });
});

var FormUpload = Class.create({
    initialize: function( element ){
        this._form = $(element);
        //var frame = Element('iframe').setStyle('border:1px solid red; height:100px; width:100px;');
        $('go').remove();

        var frame = Element('div').update('<iframe name="go" id="go" src=""></iframe>');

        this._form.up().insert({top: frame});
//        this._form.up().insert({top: '<a href="/admin/image" target="go">go</a>'});
        this._form.target = 'go';

        this._iframe = frame.down('iframe');

        this._form.observe('submit', function(evt){
            this._iframe.observe('load', function(evt){
                console.log('load');
                var frame = this._iframe;// evt.findElement('iframe');
                console.log(frame);

                var doc = frame.contentWindow || frame.contentDocument;


                if( doc.document ){
                    doc = doc.document;
                }
                console.log(doc.forms[0]);


                this._form.up().insert(doc.forms[0]);

                //console.log(doc.findElementsByTagName('img'));

//                console.log($(doc).select('img'));




                //console.log( doc.findElementsByTagName('img') );
                //
                //console.log( doc.baseURI.replace('edit', 'view/thumbnail') );
                //
                //var thumb = doc.baseURI.replace('edit', 'view/thumbnail');
                //var uri   = doc.baseURI;
                //
                //this._form.up().insert('<a href="' + uri + '"><img src="' +  thumb + '" /></a>');

            }.bind(this));
        }.bind(this));




        //frame.name = 'go';
        //frame.id = 'go';

        //frame.setAtribute('id', 'go');
        //frame.setAtribute('NAME', 'go');
        //frame.setAtribute('name', 'go');

//        var id = frame.identify();
        //
  //      frame.name = id;
        //
    //    this._form.target = id;

        //this._form.observe('submit', function( evt ){
        //    //evt.stop();
        //
        //    form = evt.findElement('form');
        //
        //    console.log('submit');
        //
        //
        //
        //   // form.submit();
        //
        //    //return false;
        //});

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
//                    console.log( doc.baseURI.replace('edit', 'view/thumbnail') );
//
//                    var thumb = doc.baseURI.replace('edit', 'view/thumbnail');
//                    var uri   = doc.baseURI;
//
//                    $('main-left').insert('<a href="' + uri + '"><img src="' +  thumb + '" /></a>');
//
//  //                  console.log( frame.select('img') );
//
//
//                }
//
//                $('iframeSubmit').observe('load', handler );
////                console.log( $('image-add') );
//            }
