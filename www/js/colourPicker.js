/**
 * basic color picker
 * <label for="picker-1">
 *  <input class="colorpicker" value="#ccffcc" />
 * </label>
 *
 * $$('.colorpicker').each(function(el){var p = new ColorPicer(el)});
 */
var ColourPicker = Class.create({
    colors: [],
    element: null,

    initialize: function(element, trigger) {
        this.colourArray = new Array();
        this.element = $(element);

        this.buildTable();

        this.element.observe('click', this.togglePicker.bindAsEventListener(this));
        this.element.setStyle({backgroundColor: this.element.value});
    },

    buildTable: function(){
        this.initColors();
        var table = new Element('table');

        for( var i = 0, len = this.colors.length; i < len; i++ ){
            var color = this.colors[i];

            if( i % 8 == 0 ){
                row = new Element('tr');
                table.insert( row );
            }

            row.insert( '<td style="'
                       + 'background-color:#'
                       + color
                       + ';text-indent:-10em;overflow:hidden;width:2em;height:2em;'
                       +'"><a class="colorpicker-color" style="display:block;text-indent:-10em;overflow:hidden;" href="#'
                       + color + '">'
                       + color
                       + '</a></td>' );
        }

        table.setStyle('top:0;width:16em;position:absolute;');

        var holder = new Element( 'div', {style:'position:relative;'})
        this.element.up().insert(holder.insert(table.hide()));

        table.select('a').invoke('observe', 'click', this.onClick.bindAsEventListener(this));
    },

    onClick: function( evt ){
        var color = '#' + evt.element().href.split('#').pop();
        this.element.value = color;
        this.element.setStyle({backgroundColor: color});
        evt.findElement('table').hide();
        evt.stop();
    },

    togglePicker: function( evt ){
        evt.element().up().down('table').show();

    },

    initColors: function() {
        var colourMap = new Array('00', '33', '66', '99', 'AA', 'CC', 'EE', 'FF');
        for(i = 0; i < colourMap.length; i++) {
            this.colors.push(colourMap[i] + colourMap[i] + colourMap[i]);
        }

        // Blue
        for(i = 1; i < colourMap.length; i++) {
            if(i != 0 && i != 4 && i != 6) {
                this.colors.push(colourMap[0] + colourMap[0] + colourMap[i]);
            }
        }
        for(i = 1; i < colourMap.length; i++) {
            if(i != 2 && i != 4 && i != 6 && i != 7) {
                this.colors.push(colourMap[i] + colourMap[i] + colourMap[7]);
            }
        }

        // Green
        for(i = 1; i < colourMap.length; i++) {
            if(i != 0 && i != 4 && i != 6) {
                this.colors.push(colourMap[0] + colourMap[i] + colourMap[0]);
            }
        }
        for(i = 1; i < colourMap.length; i++) {
            if(i != 2 && i != 4 && i != 6 && i != 7) {
                this.colors.push(colourMap[i] + colourMap[7] + colourMap[i]);
            }
        }

        // Red
        for(i = 1; i < colourMap.length; i++) {
            if(i != 0 && i != 4 && i != 6) {
                this.colors.push(colourMap[i] + colourMap[0] + colourMap[0]);
            }
        }
        for(i = 1; i < colourMap.length; i++) {
            if(i != 2 && i != 4 && i != 6 && i != 7) {
                this.colors.push(colourMap[7] + colourMap[i] + colourMap[i]);
            }
        }

        // Yellow
        for(i = 1; i < colourMap.length; i++) {
            if(i != 0 && i != 4 && i != 6) {
                this.colors.push(colourMap[i] + colourMap[i] + colourMap[0]);
            }
        }
        for(i = 1; i < colourMap.length; i++) {
            if(i != 2 && i != 4 && i != 6 && i != 7) {
                this.colors.push(colourMap[7] + colourMap[7] + colourMap[i]);
            }
        }

        // Cyan
        for(i = 1; i < colourMap.length; i++) {
            if(i != 0 && i != 4 && i != 6) {
                this.colors.push(colourMap[0] + colourMap[i] + colourMap[i]);
            }
        }
        for(i = 1; i < colourMap.length; i++) {
            if(i != 2 && i != 4 && i != 6 && i != 7) {
                this.colors.push(colourMap[i] + colourMap[7] + colourMap[7]);
            }
        }

        // Magenta
        for(i = 1; i < colourMap.length; i++) {
            if(i != 0 && i != 4 && i != 6) {
                this.colors.push(colourMap[i] + colourMap[0] + colourMap[i]);
            }
        }
        for(i = 1; i < colourMap.length; i++) {
            if(i != 2 && i != 4 && i != 6 && i != 7) {
                this.colors.push(colourMap[7] + colourMap[i] + colourMap[i]);
            }
        }
    }
});
