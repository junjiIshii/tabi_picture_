var currentImg = 1;
var $left = $('.toLess');
var $right = $('.toMore');
var $maxImgNum = $('.slidesImg').length;

console.log($maxImgNum);

function btnColor(){
    if($maxImgNum-1 ==0 ){
        $right.add($left).css('color','gray');
    }else if(currentImg ==　$maxImgNum ){
        $right.css('color','gray');
    }else if(currentImg ==1){
        $left.css('color','gray');
        
    }else{
        $right.css('color','#01DFA5');
        $left.css('color','#01DFA5');
    }
}

//ボタンのデフォルトカラー指定
btnColor();

$right.on('click',function(){
    if(currentImg < $maxImgNum){
        $('.pic'+currentImg).css('display','none');
        $('.pic'+(currentImg+1)).css('display','inline-block');

        $('.dot'+currentImg).css('color','#01DFA5');
        $('.dot'+(currentImg+1)).css('color','#088A4B');
        
        currentImg += 1;
        btnColor();
    }
})

$left.on('click',function(){
    if(currentImg > 1){
        $('.pic'+currentImg).css('display','none');
        $('.pic'+(currentImg-1)).css('display','inline-block');

        $('.dot'+currentImg).css('color','#01DFA5');
        $('.dot'+(currentImg-1)).css('color','#088A4B');

        currentImg -= 1;
        btnColor();

    }
})

