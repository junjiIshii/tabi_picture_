$(document).ready(function(){
    const MSG_EMPTY = '※入力必須です。';
    const MSG_NOT_EMAIL = '※emailの形式ではありません。';
    const MSG_NOT_MATCH = '※パスワードが一致しません。';

    //最大・最小文字数バリデーション関数
    function maxMinWords(max, min, className ){

        const MSG_TEXT_MIN = "※最低"+ min +"文字入力してください。";
        const MSG_TEXT_MAX = "※最大"+ max +"文字です。";

        $(document).on('keyup',className,function(){
            var form_g = $(this).closest('.form-group');

            if( $(this).val().length ==0){
                form_g.removeClass('has-success').addClass('had-error');
                form_g.find('.help-block').text(MSG_EMPTY);
            }else if($(this).val().length < min){
                form_g.removeClass('has-success').addClass('had-error');
                form_g.find('.help-block').text(MSG_TEXT_MIN);
            }else　if($(this).val().length > max){
                form_g.removeClass('has-success').addClass('had-error');
                form_g.find('.help-block').text(MSG_TEXT_MAX);
            }else{
                form_g.removeClass('has-error').addClass('had-success');
                form_g.find('.help-block').text('');
            }
        });
    };

    //ユーザーネームの最大、最小文字数
    maxMinWords(15, 0, ".valid-user-name");

    //パスワードの最大、最小文字数
    maxMinWords(20, 8, ".valid-pass");


    $(document).on('keyup',".valid-email",function(){
        var form_g = $(this).closest('.form-group');

        if( $(this).val().length == 0){
            form_g.removeClass('has-success').addClass('had-error');
            form_g.find('.help-block').text(MSG_EMPTY);
        }else if(!$(this).val().match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9\._-])+([a-zA-Z0-9\._-]+)+$/)){
            form_g.removeClass('has-success').addClass('had-error');
            form_g.find('.help-block').text(MSG_NOT_EMAIL);
        }else{
            form_g.removeClass('has-error').addClass('had-success');
            form_g.find('.help-block').text('');
        }
    });


    $(document).on('keyup',".valid-repass",function(){
        var form_g = $(this).closest('.form-group');

        if( $(this).val() != $('.valid-pass').val()){
            form_g.removeClass('has-success').addClass('had-error');
            form_g.find('.help-block').text(MSG_NOT_MATCH);
        }else if($(this).val().length == 0){
            form_g.removeClass('has-success').addClass('had-error');
            form_g.find('.help-block').text(MSG_EMPTY);
        }else{
            form_g.removeClass('has-error').addClass('had-success');
            form_g.find('.help-block').text('');
        }
    });
})





