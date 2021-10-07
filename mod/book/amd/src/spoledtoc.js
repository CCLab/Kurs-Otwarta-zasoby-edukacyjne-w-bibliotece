// Put this file in path/to/plugin/amd/src
// You can call it anything you like

define(['jquery'], function ($) {
    function toggleIcon(e) {
        $(e.target)
            .prev('.coursetoc-header')
            .find(".more-less")
            .toggleClass('fa-angle-down fa-angle-up');
    }
    $('.spoledtoc').on('hidden.bs.collapse', toggleIcon);
    $('.spoledtoc').on('shown.bs.collapse', toggleIcon);

});

