$(document).ready(function(){
    $(document).on('click','.unfollow',function(){
        $(this).removeClass("unfollow");
        $(this).addClass("followed");
    });

    $(document).on('click','.followed',function(){
        $(this).removeClass("followed");
        $(this).addClass("unfollow");
    });
})