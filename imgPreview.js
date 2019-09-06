//画像のプレビュー
function livePreview($inputClass, $imgClass){
    var $dropArea = $('.area-drop');
    var $fileInput = $($inputClass);

    $dropArea.on('dragover',function(e){
        e.stopPropagation();
        e.preventDefault();

        $(this).css('border','3px #ccc dashed');
    });

    $dropArea.on('dragleave',function(e){
        e.stopPropagation();
        e.preventDefault();

        $(this).css('border','none');
    });

    $fileInput.on('change',function(e){
        $dropArea.css('border','none');
        var file = this.files[0],
            $img = $(this).siblings($imgClass),
            fileReader = new FileReader();

        fileReader.onload = function(event){
            $img.attr('src',event.target.result).show();
        };

        fileReader.readAsDataURL(file);
    })
}
    livePreview('.input_img','.preview-header');
    livePreview(".input_img",".preview-icon");
    livePreview(".input_img",".preview-product_img");

