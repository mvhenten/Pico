/*
    Sentry - form autosave plugin for jquery
    
    Sentry is a simple, flexible plugin that watches your form elements for
    changes, and posts them after a short delay.
    
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
"use strict";
(function($) {
    var formCache = [],
    timerCache = [],
    Timer,
    Sentry,
    init;

    Timer = function() {
        var t, self = this,
        queue = [],
        clear = function() {
            while ((t = queue.pop())) {
                clearTimeout(t);
            }
        };

        this.delay = function(time, fn) {
            queue.push(setTimeout(function() {
                clear();
                fn();
            },
            time));
        }
    };

    Sentry = function(el, config, timer) {
        config = $.extend({
            events: 'change',
            method: null,
            onSuccess: null,
            timeout: 1000,
            url: null
        },
        config);

        var form = $(el).closest('form'),
        method = (config.method || $(form).attr('method') || 'POST'),
        url = (config.url || $(form).attr('action') || document.location.href);

        $(el).bind(config.events, function() {
            timer.delay(config.timeout, function() {
                $.ajax({
                    type: method,
                    url: url,
                    data: $(form).serialize(),
                    success: config.onSuccess
                });
            });
        });
    };

    $.fn.extend({
        sentry: function(config) {
            this.each(function(i, el) {
                var form = $(el).closest('form').get(0),
                pos = formCache.indexOf(form);

                if (pos === -1) {
                    formCache.push(form);
                    timerCache.push(new Timer());
                    pos = formCache.length - 1;
                }

                new Sentry(el, config, timerCache[pos]);
            });
            return this;
        }
    });
})(jQuery);