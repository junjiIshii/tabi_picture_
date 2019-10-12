$menubtn = $('.fa-align-justify');

$menubtn.on('click',function(){

    if($('.slidemenu-conteiner').hasClass('slideoff')){
        $('.slidemenu-conteiner').removeClass('slideoff');
        $('.slidemenu-conteiner').animate({'width':'60%'},200);
        $menubtn.css({'color':'white'});
    }else{
        $('.slidemenu-conteiner').animate({'width':'0%'},200);
        $('.slidemenu-conteiner').addClass('slideoff');
        $menubtn.css({'color':'darkcyan'});
        
    }
})