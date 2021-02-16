// Function to switch to sign up
function signupSwitch() {
    /*
    // Get Elements
    // Signup div
    const signup_div = document.getElementById('signup-content-div');
    // Login div
    const login_div = document.getElementById('login-content-div');
    // Form wrapper
    const form_wrapper = document.getElementById('signin-form-wrapper');

    // Change width
    signup_div.style.width = '60%';
    login_div.style.width = '40%';
    // Hide wrapper content
    form_wrapper.style.opacity = '0';
    */
}

$('#btn-signup').click(function() {
    // Change width 
    $('#signup-content-div').width('60%'),
    $('#login-content-div').width('40%'),
    $('#signin-submit-input').hide('fast'),
    $('#signin-form-wrapper').animate({
        //opacity: 0,
        height: "toggle"
    }, 1000),
    $('#btn-signup').animate({
        //opacity: 0,
        height: "toggle"
    }),
    $('#signup-form-wrapper').animate({
        height: "50%",
    }),
    $('#signup-form-wrapper').show("slow"),
    $('#btn-signin').show('slow');
});

$('#btn-signin').click(function() {
    // Change width 
    $('#signup-content-div').width('40%'),
    $('#login-content-div').width('60%'),
    $('#signup-form-wrapper').animate({
        //opacity: 0,
        height: "toggle"
    }, 1000),
    $('#btn-signin').animate({
        //opacity: 0,
        height: "toggle"
    }),
    $('#signin-form-wrapper').animate({
        height: "50%",
    }),
    $('#signin-submit-input').show('slow'),
    $('#signin-form-wrapper').show("slow"),
    $('#btn-signup').show('slow');
});