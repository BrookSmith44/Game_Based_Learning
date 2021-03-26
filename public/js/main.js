// Initiate form listeners
formListeners();

// Get teachers 
getTeacherData();


// Function for form listeners
function formListeners() {
    // Check to see if current page has sign up form before settting listeners
    if (document.forms['signin-form'] !== undefined) {
        // get form
        const signup_form = document.forms['signin-form'];

        // get error p tag
        const err = document.getElementById('signin-err');

        // Run validate function on form submission
        signup_form.addEventListener("submit", (event) => {
            
            // Instantiate validate object
            const validate = new Validate(signup_form, err, 'seagreen');

            // Check all inputs are filled
            const validated = validate.checkDataEntered();

            // Do not submit form is funciton returns false bool
            if (validated === false) {
                // Prevent submit
                event.preventDefault();
            }
        });  
    }

    // Check to see if current page has sign up form before settting listeners
    if (document.forms['signup-form'] !== undefined) {
        // get form
        const signup_form = document.forms['signup-form'];

        // Run validate function on form submission
        signup_form.addEventListener("submit", (event) => {

            // Validate form - Check all inputs are filled, emails match and passwords match
            const validated = validateSignUpForm(signup_form);
            
            // Instantiate validate object
            const validate = new Validate(signup_form, signup_form['signup-username-input'], 'white');

            // Check username is not already in the database
            validate.checkUsername();

            // Check error p tag does not have a class of taken
            const taken = validate.classTaken();

            // Do not submit form is funciton returns false bool
            if (validated === false || taken == true) {
                // Prevent submit
                event.preventDefault();
            }
        });     

        // When user clicks off usernam input
        signup_form['signup-username-input'].addEventListener("focusout", (event) => {
            const element = document.getElementById('signup-err');
            
            // Function to check the username
            checkUsername(signup_form, element);
        });
    }
    // Check current page has edit team form before setting listeners
    if (document.forms['edit-team-form'] != undefined) {
        // Get form by id
        const team_form = document.forms['edit-team-form'];
        const err_p = document.getElementById('team-err');
        const colour_radio = document.querySelectorAll('input[name="colour"]');

        // Check data has been entered when submitting
        team_form.addEventListener('submit', (event) => {
            // Validate form - Check all inputs are filled, emails match and passwords match
            const validate = new Validate(team_form, err_p, 'seagreen', colour_radio);

            // Set empty validated array
            let validated = [];

            // Check inputs are filled out
            validated['inputs_filled'] = validate.checkDataEntered();

            // Check radio has been selected
            validated['radio_checked'] = validate.checkRadioSelected();

            // Do not submit form is funciton returns false bool
            if (validated['inputs_filled'] === false || validated['radio_checked'] == false) {
                // Prevent submit
                event.preventDefault();
            }
        });
    }

    // Check if add teacher form exists
    if (document.forms['add-teacher-form'] !== undefined) {
        // get form
        const addTeacher_form = document.getElementById('add-teacher-form');
        // Get err p tag
        const err_p = document.getElementById('add-teacher-err');

        // Create empty validted variable
        let validated = [];

        addTeacher_form.addEventListener('submit', (event) => {
            // Instantiate validate object
            const validate = new Validate(addTeacher_form, err_p, 'seagreen');

            // Check data has been entered
            validated['inputs_filled'] = validate.checkDataEntered();

            // Check emails match
            validated['emails_match'] = validate.checkValuesMatch(addTeacher_form['add-teacher-email'].value, addTeacher_form['add-teacher-cemail'].value);

            // Do not submit form is funciton returns false bool
            if (validated['inputs_filled'] === false || validated['emails_match'] == false) {
                // Prevent submit
                event.preventDefault();
            }
        });
    }

    // Check to see if current page has sign up form before settting listeners
    if (document.forms['change-pass-form'] !== undefined) {
        // get form
        const change_pass_form = document.forms['change-pass-form'];

        // get error p tag
        const err = document.getElementById('change-pass-err');

        // Run validate function on form submission
        change_pass_form.addEventListener("submit", (event) => {
            
            // Instantiate validate object
            const validate = new Validate(change_pass_form, err, 'seagreen');

            // Set empty 
            let validated = []

            // Check all inputs are filled
            validated['inputs_filled'] = validate.checkDataEntered();

            // Make sure passwords match
            validated['pass_match'] = validate.checkValuesMatch(change_pass_form['password-input'].value, change_pass_form['cpassword-input'].value);

            // Do not submit form is funciton returns false bool
            if (validated['inputs_filled'] === false || validated['pass_match'] == false) {
                // Prevent submit
                event.preventDefault();
            }
        });  
    }

}

// Function to switch to sign up
function validateSignUpForm(form) {
    // Get error message p tag by ID
    const err_p = document.getElementById('signup-err');

    // Set validate caiable to false initally 
    let validate = false;

    // Empty array for validation
    let validated = [];

    // Instantiate Object
    const validate_form = new Validate(form, err_p, 'white');
    
    // Call method to check all the inputs have been filled in 
    validated['inputs_filled'] = validate_form.checkDataEntered();

    // Call method to check the emails match
    validated['email_match'] = validate_form.checkValuesMatch(form['signup-email-input'].value, form['signup-cemail-input'].value);

    // Call method to check the passwords match
    validated['pass_match'] = validate_form.checkValuesMatch(form['signup-pass-input'].value, form['signup-cpass-input'].value);

    // Return true if all validations have come back true
    if (validated['inputs_filled'] === true && validated['pass_match'] === true && validated['email_match'] === true) {
        // Set valdidate bool to true when passing all the checks
        validate = true;
    }

    return validate;
}

// Function to check if the username has been taken
function checkUsername(form, err_p) {
    // Instantiate new validate object 
    validate = new Validate(form, err_p);

    // Call check username function
    validate.checkUsername();
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

// function get teacher data
function getTeacherData() {
    if (document.getElementById('teacher-table') !== undefined) {
        // Initiate list data class
        const list_data = new ListData();

        // get teachers 
        let teachers = list_data.listTeachers();
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