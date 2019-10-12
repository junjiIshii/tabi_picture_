var $incBtn = $('.moreNum');
var $decBtn = $('.lessNum');
var displayNum = 4;


var i = 4;
while(i<10){
    var $s = $('.im'+i).children('.preview-product_img').attr('src');
    if($s ==''){
        var displayNum = i;
        break;
    }
    i++;
    displayNum += 1;
}

//console.log(displayNum);
$incBtn.on('click',function(){

    if(displayNum < 10){
        $('.im'+ displayNum).css('display','inline-block');
        displayNum += 1;
        $decBtn.css('cursor','pointer');
        $decBtn.css('background-color','rgba(85,120,210,0.5)');
        $incBtn.css('background-color','rgba(200,80,65,0.5)');
        $('.imgCount').text('');

        if(displayNum == 10){
            $incBtn.css('background-color','gray');
            $incBtn.css('cursor','not-allowed');
            $('.im'+ displayNum).css('display','none');

            $('.imgCount').text('これ以上画像は追加できません。');
        }
    }
    
});

$decBtn.on('click',function(){
    if(displayNum > 2){
        displayNum -= 1;
        $('.im'+ displayNum).css('display','none');
        $incBtn.css('cursor','pointer');
        $decBtn.css('background-color','rgba(85,120,210,0.5)');
        $incBtn.css('background-color','rgba(200,80,65,0.5)');
        $('.imgCount').text('');

        if(displayNum == 2){
            $decBtn.css('background-color','gray');
            $decBtn.css('cursor','not-allowed');
            $('.im'+ displayNum).css('display','none');

            $('.imgCount').text('これ以上画像は減らせません。');
        }

    }

    
});