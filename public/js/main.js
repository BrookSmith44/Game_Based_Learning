// get form
const signup_form = document.forms['signup-form'];

// Run validate function on form submission
signup_form.addEventListener("submit", (event) => {

    // Validate form - Check all inputs are filled, emails match and passwords match
    validate = validateForm();

    // Do not submit form is funciton returns false bool
    if (validate === false) {
        // Prevent submit
        event.preventDefault();
    }
});

// When user clicks off usernam input
signup_form['signup-username-input'].addEventListener("focusout", (event) => {
    // Function to check the username
    checkUsername(signup_form['signup-username-input'].value);
});


// Function to switch to sign up
function validateForm() {
    // Set validate caiable to false initally 
    let validate = false;

    // get form
    const inputs = document.forms['signup-form'];

    // Empty array for validation
    let validated = [];

    // Instantiate Object
    const validate_form = new Validate(inputs);
    
    // Call method to check all the inputs have been filled in 
    validated['inputs_filled'] = validate_form.checkDataEntered();

    // Call method to check the emails match
    validated['email_match'] = validate_form.checkValuesMatch(inputs['signup-email-input'].value, inputs['signup-cemail-input'].value);

    // Call method to check the passwords match
    validated['pass_match'] = validate_form.checkValuesMatch(inputs['signup-pass-input'].value, inputs['signup-cpass-input'].value);

    // Return true if all validations have come back true
    if (validated['inputs_filled'] === true && validated['pass_match'] === true && validated['email_match'] === true) {
        // Set valdidate bool to true when passing all the checks
        validate = true;
    }

    return validate;
}

// Function to check if the username has been taken
function checkUsername(suggested_username) {
    // Send http request to get route to check username
    $.ajax({
        url: '/football_trivia_game/public/checkUsername',
        type: 'POST',
        data: {
            suggested_username: suggested_username,
        },

        success: function (data) {
            console.log('it worked!');
            console.log(data);
        },
        error: function () {
            console.log('it failed!');
        }
    });
}

// Function to bring up modal
function openModal(modalName, spanName) {
    // Get the modal
    const modal = document.getElementById(modalName);

    // Get close span element
    const span = document.getElementById(spanName);
    
    // change modal display to make it visible
    modal.style.display = "flex";

    // Close the modal when the close span is clicked
    span.onclick = function() {
        // Change modal display to none to close
        modal.style.display = "none";
    }
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