define(['jquery', 'core/ajax', 'core/str', 'core/localstorage', 'core/url'],
    function($, ajax, str, storage, coreurl) {

    var DEFAULT_FONTSIZE = 100;
    var DEFAULT_COLOURSCHEME = 0;
    var self;
    var pxSizes = [10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26];
    var sizes = ['77','85','93','100','108','116','123.1','131','138.5','146.5','153.9','161.6','167','174','182','189','197'];

    return {
        init: function() {
            self = this;

            var url = coreurl.relativeUrl("/local/accessibility/userstyles.php");
            this.sheetnode = $('link[href="'+url+'"]');

            var fontsize = 0;
            var colourscheme = 0;

            if (window.localStorage !== undefined) {
                fontsize = window.localStorage.getItem('fontsize');
                colourscheme = window.localStorage.getItem('colourscheme');
                if(!fontsize){
                    window.localStorage.setItem('fontsize', DEFAULT_FONTSIZE);
                }
                if(!colourscheme){
                    window.localStorage.setItem('colourscheme', DEFAULT_COLOURSCHEME);
                }
            }

            $('#local_accessibility_inc').on('click keypress', this.changeSize);
            $('#local_accessibility_dec').on('click keypress', this.changeSize);
            $('#local_accessibility_reset').on('click keypress', this.changeSize);
            $('#local_accessibility_colour1').on('click keypress', this.changeColor);
            $('#local_accessibility_colour2').on('click keypress', this.changeColor);
            $('#local_accessibility_colour3').on('click keypress', this.changeColor);
            $('#local_accessibility_colour4').on('click keypress', this.changeColor);

            var cache_prevention_salt = new Date().getTime();
            var cssURL = coreurl.relativeUrl("/local/accessibility/userstyles.php?fontsize="+
                fontsize+"&colourscheme="+colourscheme+"&v=" + cache_prevention_salt);
            this.sheetnode.attr("href", cssURL);

        },
        changeSize: function(ev) {
            var idButton = ev.target.id;

            // Now try local storage.
            if (window.localStorage !== undefined) {
                var indexSize = sizes.indexOf(window.localStorage.getItem('fontsize'));
                var fontsize = pxSizes[indexSize];

                switch (idButton) {
                    case 'local_accessibility_inc':
                        if(fontsize < 26) {
                            fontsize = parseInt(fontsize) + 1;
                        }
                        break;
                    case 'local_accessibility_dec':
                        if(fontsize > 10) {
                            fontsize = parseInt(fontsize) - 1;
                        }
                        break;
                    case 'local_accessibility_reset':
                        fontsize = 13;
                        break;
                    default:
                }
                indexSize = pxSizes.indexOf(fontsize);
                window.localStorage.setItem('fontsize', sizes[indexSize]);
            }
            self.reloadStylesheet();
        },
        changeColor: function(ev) {
            var idButton = ev.target.id;

            // Now try local storage.
            if (window.localStorage !== undefined) {
                switch (idButton) {
                    case 'local_accessibility_colour1':
                        window.localStorage.setItem('colourscheme', 1);
                        break;
                    case 'local_accessibility_colour2':
                        window.localStorage.setItem('colourscheme', 2);
                        break;
                    case 'local_accessibility_colour3':
                        window.localStorage.setItem('colourscheme', 3);
                        break;
                    case 'local_accessibility_colour4':
                        window.localStorage.setItem('colourscheme', 4);
                        break;
                    default:
                }
            }
            self.reloadStylesheet();
        },
        reloadStylesheet: function() {
            if (window.localStorage !== undefined) {
                var fontsize = window.localStorage.getItem('fontsize');
                var colourscheme = window.localStorage.getItem('colourscheme');
                var cache_prevention_salt = new Date().getTime();
                var cssURL = coreurl.relativeUrl("/local/accessibility/userstyles.php?fontsize="+
                    fontsize+"&colourscheme="+colourscheme+"&v=" + cache_prevention_salt);
                this.sheetnode.attr("href", cssURL);
            }
        }
    };
});
