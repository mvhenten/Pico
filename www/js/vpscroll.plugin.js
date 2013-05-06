/*
    vpscroll - scroll viewport by dragging it's contents

    Creates a viewport which' contents can be scrolled by dragging.
    Usefull for e.g. a cropped image inside a scrollable div.
    ( and I cannot think of other use cases besides this )

    Copyright (c) <2012> <Matthijs van Henten>, <http://matthijs.ischen.nl>

	Permission is hereby granted, free of charge, to any person obtaining
	a copy of this software and associated documentation files (the
	"Software"), to deal in the Software without restriction, including
	without limitation the rights to use, copy, modify, merge, publish,
	distribute, sublicense, and/or sell copies of the Software, and to
	permit persons to whom the Software is furnished to do so, subject to
	the following conditions:

	The above copyright notice and this permission notice shall be
	included in all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
	LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
	OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
	WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
(function($) {
    var _init = function( el ){

        var drag = false, _delta = { deltaX: 0, deltaY: 0, pageX: 0, pageY: 0 };

        function delta ( pageX, pageY ) {
            _delta.deltaX = pageX - _delta.pageX;
            _delta.deltaY = pageY - _delta.pageY;

            _delta.pageX = pageX;
            _delta.pageY = pageY;

            return _delta;
        }

        $(el).bind('mousedown dragstart', function( evt ){
            // prevents native dragstart events from taking over
            evt.preventDefault();
            drag = true;
        });

        $(el).bind('mouseup mouseout focus click dragend', function(){
            drag = false;
        });

        $(el).bind('mousemove', function( evt ){
            var d = delta( evt.pageX, evt.pageY );

            if( drag ){
                var $el = $(el)[0];
                $el.scrollLeft = $el.scrollLeft + d.deltaX;
                $el.scrollTop = $el.scrollTop + d.deltaY;
            }
        });

        $(el).css({ cursor: 'pointer' } );
    }

    $.fn.extend({
        vpscroll: function( el ){
            this.each( function(i, el){ _init( el ) });
        }
    });
})(jQuery);

