// Initiate form listeners
formListeners();
// Get teachers 
getTeacherData();
// Get students 
getStudentData();
//
getQuestionsData()
// Function for form listeners
function formListeners() {
    // Event to run when page loads
    window.addEventListener('load', function() {
        // Get match div
        const match_div = document.getElementById('match-container');

        if (match_div !== null) {
            // Get subject and difficulty
            const difficulty = localStorage.getItem('difficulty');
            const subject = localStorage.getItem('subject');

            if (difficulty !== null && subject !== null) {
                // Instantiate user team, opposition team and match classes
                const user_team = new UserTeam();
                const opposition_team = new OppositionTeam();
                const match = new Match(user_team, opposition_team, difficulty, subject);

                match.getData();
            } else {
                // redirect to homepage
                window.location.href = '/football_trivia_game/public/playerHomepage';
            }
        }
    });

    // Check to see if current page has sign up form before settting listeners
    if (document.forms['signin-form'] !== undefined) {
        // get form
        const signin_form = document.forms['signin-form'];

        // get error p tag
        const err = document.getElementById('signin-err');

        // When user clicks off username input
        signin_form['signin-username-input'].addEventListener("focusout", (event) => {
            // Get error p tag
            const element = document.getElementById('signin-err');

            // Function to check the username
            checkUsername(signin_form, element);
        });

        // Run validate function on form submission
        signin_form.addEventListener("submit", (event) => {
            
            // Instantiate validate object
            const validate = new Validate(signin_form, err, 'seagreen');

            // Check all inputs are filled
            const validated = validate.checkDataEntered();

            // Check taken is not set to err p tag
            const taken = validate.classTaken();

            // Do not submit form is funciton returns false bool
            if (validated === false || taken == true) {
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

            if (validated == false) {
                event.preventDefault();
            }
        });     

        // When user clicks off username input
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

            // Check the size of the team name - limit at 25 characters
            validated['name_size'] = validate.checkStringSize();

            // Check radio has been selected
            validated['radio_checked'] = validate.checkRadioSelected();

            // Do not submit form is funciton returns false bool
            if (validated['inputs_filled'] === false || validated['radio_checked'] == false || validated['name_size'] == false) {
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

    // Check if add teacher form exists
    if (document.forms['add-question-form'] !== undefined) {
        // get form
        const question_form = document.getElementById('add-question-form');
        // Get err p tag
        const err_p = document.getElementById('add-question-err');

        // Create empty validted variable
        let validated = [];

        question_form.addEventListener('submit', (event) => {
            // Instantiate validate object
            const validate = new Validate(question_form, err_p, 'seagreen');

            // Check data has been entered
            validated['inputs_filled'] = validate.checkDataEntered();

            // Do not submit form is funciton returns false bool
            if (validated['inputs_filled'] === false) {
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

    if (document.getElementById('player-homepage-content') !== null) {
        // Get elements for click functions
        const play_button = document.getElementById('homepage-match-button');
        const game_stats_button = document.getElementById('gamestats-button');
        const edit_team_button = document.getElementById('edit-team-button');
        
        // Add event listeners for loading up models
        play_button.addEventListener('click', selectDifficulty);

        game_stats_button.addEventListener('click', displayGameStats);

        edit_team_button.addEventListener('click', function() {
            window.location.href = '/football_trivia_game/public/teamDetails';
        });
    }

    if (document.forms['add-question-form'] !== undefined) {
        // Get radio buttons
        const radio_subjects = document.getElementsByClassName('radio-subject');

        const difficulty_select = document.getElementById('select-difficulty');

        // Create of existing initially so input is always shown
        createSubjectInput('existing');
        
        difficulty_select.addEventListener('change', function() {
            createSubjectInput('existing'); 
        });

        // loop through radio buttons
        for (let i = 0; i < radio_subjects.length; i++) {
            // add event listeners to radio buttons
            radio_subjects[i].addEventListener('change', function() {
                console.log(radio_subjects[i].value);
                createSubjectInput(radio_subjects[i].value) 
            });
        }
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
            
    // Check username is not already in the database
    validate_form.checkUsername();
    
    // Call method to check all the inputs have been filled in 
    validated['inputs_filled'] = validate_form.checkDataEntered();

    // Call method to check the emails match
    validated['email_match'] = validate_form.checkValuesMatch(form['signup-email-input'].value, form['signup-cemail-input'].value);

    // Call method to check the passwords match
    validated['pass_match'] = validate_form.checkValuesMatch(form['signup-pass-input'].value, form['signup-cpass-input'].value);

    // Check error p tag does not have a class of taken
    validated['taken'] = validate_form.classTaken();

    // Return true if all validations have come back true
    if (validated['inputs_filled'] === true && validated['pass_match'] === true && validated['email_match'] === true && validated['taken'] == false) {
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
function openModal() {
    // Get the modal
    const modal = document.getElementById('playerModal');

    // Get modal body
    const modal_body = document.getElementsByClassName('modal-body')[0];

    // Get close span element
    const span = document.getElementById('playerModal-close');

    // get backdrop
    const backdrop = document.getElementById('backdrop');

    // display backdrop
    backdrop.style.display = 'block';

    // Change opacity to make backdrop appear
    backdrop.style.opacity = 1;
    
    // change modal display to make it visible
    modal.style.display = "flex";

    // Close the modal when the close span is clicked
    span.onclick = function() {
        // Change modal display to none to close
        modal.style.display = "none";

        // reset display of backdrop
        backdrop.style.display = 'none';

        // reset oapcity of backdrop
        backdrop.style.opacity = 0;

        // Check to see if stats modal class exists
        if (modal_body.classList.contains('stats-modal')) {
            // remove stats class to modal
            modal_body.classList.remove('stats-modal');
        }

        removeModalContent();
    }
}

function selectDifficulty() {
    // Create array for difficulties
    const difficulty_params = [
            {
                'points': 'Win: +20 <br> Loss: -10',
                'difficulty': 'Easy',
                'range': '0-499'
            },
            {
                'points': 'Win: +20 <br> Loss: -15',
                'difficulty': 'Medium',
                'range': '500-1499'
            },
            {
                'points': 'Win: +20 <br> Loss: -20',
                'difficulty': 'Hard',
                'range': '1500+'
            }
        ];
    // Create html content
    const content = 
    '<div id="|" class="difficulty">' +
        '<div class="points">|</div>' +
        '<h2>|</h2>' +
        '<p id="difficulty-range">|</p>' +
        '</div>' +
    '</div>';
    // Create modal content
    loadContent(difficulty_params, content, 'Select Difficulty');


    lockDifficulties();

    // Opent he modal with backdrop
    openModal();
}

function displayGameStats() {
    // Instantiate object for list data class
    const list_data = new ListData();

    // Call list data method to fetch and display game stats
    list_data.listGameStats();

    // Open modal with backdrop
    openModal();
}

// Function to load content for difficulty
function loadContent(array, content, header) {
    // get modal header
    const modal_header = document.getElementById('modal-header');

    // set header text
    modal_header.innerHTML = header;

    // get modal body 
    const modal_body = document.getElementsByClassName('modal-body');

    const split = content.split("|");

    for (let i = 0; i < array.length; i++) {
        // add modal content
        modal_body[0].innerHTML += split[0] + array[i].difficulty + split[1] + 
                                    array[i].points + split[2] + array[i].difficulty 
                                    + split[3] + array[i].range + split[4];

    }
}

function removeModalContent() {
    // get modal header
    const modal_header = document.getElementById('modal-header');

    // get modal body 
    const modal_body = document.getElementsByClassName('modal-body');

    // set header text
    modal_header.innerHTML = '';

    // Reset content
    modal_body[0].innerHTML = "";
}

function lockDifficulties() {
    // Get difficulty divs
    const easy = document.getElementById('Easy');
    const medium = document.getElementById('Medium');
    const hard = document.getElementById('Hard');
    // get skill rating from DOM
    const rating_container = document.getElementById('rating-container');

    // get rating from header tag
    const rating = rating_container.childNodes[3].innerHTML;

    // If rating is lower than 500 then lock medium and hard difficulties
    if (rating <= 499) {
        easy.classList.add('open');
        medium.classList.add('lock');
        hard.classList.add('lock');
    } else if ((rating >= 500) && (rating <= 1499)) {
        // if rating is higher than 500 but less than 1500 then lock eady and hard difficulties
        easy.classList.add('lock');
        medium.classList.add('open');
        hard.classList.add('lock');
    } else {
        easy.classList.add('lock');
        medium.classList.add('lock');
        hard.classList.add('open');
    }

    // Create click event for open class difficulty
    const open_difficulty = document.getElementsByClassName('open');

    open_difficulty[0].addEventListener('click', function() {
        // Set difficulty
        const difficulty = open_difficulty[0].childNodes[1].innerHTML;
        // Store difficulty
        localStorage.setItem('difficulty', difficulty);

        // Clear modal content
        removeModalContent();

        // Instantiate new list data object
        const list_data = new ListData();

        // Call get subjects 
        list_data.listSubjects(difficulty, 'game');
    });
}

// function get teacher data
function getTeacherData() {
    if (document.getElementById('teacher-table') !== null) {
        // Initiate list data class
        const list_data = new ListData();

        // get teachers 
        list_data.listTeachers();
    }
}

// function get students data
function getStudentData() {
    if (document.getElementById('students-table') !== null) {
        // Initiate list data class
        const list_data = new ListData();

        // get students 
        list_data.listStudents();
    }
}

// function get questions data
function getQuestionsData() {
    if (document.getElementById('questions-table') !== null) {
        // Initiate list data class
        const list_data = new ListData();

        // get questions 
        list_data.listQuestions();
    }
}

function createSubjectInput(value) {
    // get subject container
    const subject_div = document.getElementById('subject-choice');

    // Get elements
    const subject_input = document.getElementById('subject-input');
    const subject_select = document.getElementById('subject-select');

    // Check what user has selected
    if (value == 'existing') {
        // Set input display to none
        subject_input.style.display = 'none';

        // Get difficulty select box
        const difficulty_select = document.getElementById('select-difficulty');
        // In order to create existing must fetch subjects from db
        // Instantiate list data object
        const list_data = new ListData();

        list_data.listSubjects(difficulty_select.value, 'form');

        // Set input display to block
        subject_select.style.display = 'block';

    } else {
        // Set input display to block
        subject_select.style.display = 'none';

        // Set input display to none
        subject_input.style.display = 'block';
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