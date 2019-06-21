$(function(){
    $("form#signin").submit(function () {
        var signin_error_messages = "";
        if($("#signin #InputEmailSignin").val() == ""){
            signin_error_messages += "<strong>Email</strong> field is required!<br>"
        }
        if($("#signin #InputPasswordSignin").val() == ""){
            signin_error_messages += "<strong>Password</strong> field is required!<br>"
        }

        if(signin_error_messages != ""){
            $("#error_signin").html('<div class="alert alert-danger" role="alert">'+
                signin_error_messages +
                '</div>');
            return false;
        }else{
            return true;
        }
    });
    $("form#register").submit(function () {
        var register_error_messages = "";
        if($("#register #InputEmailRegister").val() == ""){
            register_error_messages += "<strong>Email</strong> field is required!<br>"
        }
        if($("#register #InputPasswordRegister").val() == ""){
            register_error_messages += "<strong>Password</strong> field is required!<br>"
        }
        if($("#register #InputConfirmPasswordRegister").val() == ""){
            register_error_messages += "<strong>Password confirmation</strong> field is required!<br>"
        }
        if($("#register #InputConfirmPasswordRegister").val() != $("#register #InputPasswordRegister").val() && $("#register #InputPasswordRegister").val() != "" && $("#register #InputConfirmPasswordRegister").val() != ""){
            register_error_messages += "<strong>Passwords</strong> are not match!<br>"
        }
        if($("#register #InputPasswordRegister").val().length < 6 && $("#register #InputPasswordRegister").val().length != 0 ){
            register_error_messages += "<strong>Password</strong> is too short!<br>"
        }



        if(register_error_messages != ""){
            $("#error_register").html('<div class="alert alert-danger" role="alert">'+
                register_error_messages +
                '</div>');
            return false;
        }else{
            return true;
        }
    });


    /* On text change LoggedInPage */
    $('#diary').bind('input propertychange', function() {

        $.ajax({
            method: "POST",
            url: "updatedatabase.php",
            data: { content: $('#diary').val() }
        })

    });

})