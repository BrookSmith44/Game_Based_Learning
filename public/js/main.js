// Function to switch to sign up
function validateForm() {
    // get input by IDs
    const inputs = document.forms['signup-form'];

    // Empty variable for validation
    let validated;

    // Instantiate Object
    const validate_form = new Validate(inputs);

    console.log(validate_form);
    
    // Call method to check all the inputs have been filled in 
    const inputs_filled = validate_form.checkDataEntered();

    return inputs_filled;
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