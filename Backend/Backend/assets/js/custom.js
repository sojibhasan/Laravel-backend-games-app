$(document).ready(function() {
    
    $('#change_password').prop('disabled', true);
    $('#password, #confirm_password').on('keyup', function () {
        if ($('#password').val() == $('#confirm_password').val()) {
            $('#confirm_pass_message').html('');
            $('#change_password').prop('disabled', false);
        } else {
            $('#change_password').prop('disabled', true);
            $('#confirm_pass_message').html('Password and confirm password not same').css('color', 'red');
        }
    });

    $(document).on('change', '.custom-file-input', function() {       
        field_name = $(this).attr('name');
        readURL(this, field_name+'_preview');
    })

    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function isImage(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'ico':
                return true;
        }
        return false;
    }

    function readURL(input,className) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var res = isImage(input.files[0].name);
            if(res == false){
                var msg = 'Image should be png/PNG, jpg/JPG & jpeg/JPG.';
                
                $(input).val("");
                return false;
            }
            reader.onload = function(e){
                $(document).find('img.'+className).attr('src', e.target.result);
                $(document).find("label."+className).text((input.files[0].name));
                if(className == 'splash_background_url_preview'){
                    $('.splash_preview').css("background-image", "url("+e.target.result+")");
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

});