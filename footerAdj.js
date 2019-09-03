//footerが常に下に来るようにする
$(document).ready(function() {
    var $ftr = $('footer');
    if (window.innerHeight > $ftr.offset().top + $ftr.outerHeight()) {
        $ftr.attr({ style: 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;' + 'width:100%;' });
    }
})

$(window).on('resize',function() {
    var $ftr = $('footer');
    if (window.innerHeight > $ftr.offset().top + $ftr.outerHeight()) {
        $ftr.attr({ style: 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;' + 'width:100%;' });
    }
})

