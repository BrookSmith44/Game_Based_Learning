class Validate {

    // Constuctor method
    constructor(inputs, element, colour, radio) {
        this._inputs = inputs;
        this._element = element;
        this._colour = colour;
        this._radio = radio;
    }

    // Function to switch to sign up
    checkDataEntered() {
        // Set input filled variable to false initially
        let input_filled = [];

        // Loop through all form inputs
        for (var i = 0; i < this._inputs.length -1; i++) {
            if (this._inputs[i].value == "") {
                // Change styles to red to indicate error
                this._inputs[i].style.borderBottomColor = "red";
                // Push false boolean to array to show input not filled in
                input_filled.push(false);
            } else {
                // Push true to array to show input filled in
                input_filled.push(true);
                // Change styles to white to show input is filled
                this._inputs[i].style.borderBottomColor = this._colour;
            }
        }

        // Return true if all inputs are filled in
        let validate = input_filled.every(function (e) {
            return e === true;
        });

        // If validate is not true display error message
        if (validate !== true) {
            this.displayMessage('All fields must be filled out!', 'red', this._element);
        }

        // Return validation result
        return validate;
    }

    // Function to check passwords match
    checkValuesMatch(value_one, value_two) {
        // Set value match bool to false initially
        let values_match = false;
        
        // Check if value match
        if (value_one == value_two) {
            // Set value match variable to true
            values_match = true;
        } else {
            this.displayMessage('Please ensure passwords and emails match!', 'red', this._element);
        }

        // Return password match variable
        return values_match;
    }

    // Function to checkUsername
    checkUsername() {
        // Set variables to be used in ajax call
        const this_var = this;
        const suggested_username = this._inputs[0].value;
  
        // Send http request to get route to check username
         $.ajax({
            url: '/football_trivia_game/public/checkUsername',
            type: 'POST',
            data: {
                // Set data to be sent to php to username value
                suggested_username: suggested_username,
            },
            // If ajax request is successful
            success: function (data) {
                console.log('it worked!');
                this_var.userNameCheckResponse(data);
            },
            error: function () {
                console.log('it failed!');
            }
        });
    }

    // Function display username check response
    userNameCheckResponse(check) {
        const taken = this.classTaken();

        // Get id of form to decipher which form is being used
        const form_id = this._inputs.id;

        // If username exists in database
        if (check == 1) {
            // Switch case to determine which form is being used and what message to display
            switch (form_id) {
                case 'signin-form':
                    if (taken == true) {
                        // Display error message to inform user username does exist
                        this.displayMessage('This Username exists!', 'seagreen', this._element);
                        // remove class name taken 
                        this._element.classList.remove('taken');
                    }
                    break;
                case 'signup-form':
                    // Display error message to inform user username has been taken
                    this.displayMessage('This Username has been taken!', 'red', this._element);
                    // Set class name to taken to indicate username has been taken
                    this._element.classList.add('taken');
                    break;
                }

        } else if (check == 0) {
            // Switch case to determine which form is being used and what message to display
            switch (form_id) {
                case 'signin-form':
                    // Display error message to inform user username does not exist
                    this.displayMessage('This Username does not exist!', 'red', this._element);
                    // Set class name to taken to indicate username has been taken
                    this._element.classList.add('taken');
                    break;
                case 'signup-form':
                    if (taken == true) {
                        // Display error message to inform user username has been taken
                        this.displayMessage('This username is available!', 'white', this._element);
                        // remove class name taken 
                        this._element.classList.remove('taken');
                    }
                    break;
                }
        }
    }

    classTaken() {
        // Set variable for err p tag
        const err_p = this._element;
        // Check if 
        const taken = err_p.classList.contains("taken");

        return taken;
    }

    checkRadioSelected() {
        // Set empty variable for selected
        let selected = false;
        // Loop to check each radio
        for (const radio of this._radio) {
            // If one of the radios has been selected
            if (radio.checked) {
                console.log(radio.value);
                selected = true;
                break;
            }
        }

        return selected;
    }

    // Function to display error message
    displayMessage(msg, colour, element) {
        // Add error Message to P tag
        element.innerHTML = msg;

        // Style Error Message
        element.style.color = colour;
        
        // Set font weight to bold
        element.style.fontWeight = 'bold';

        // Set username input border to red
        this._inputs[0].style.borderBottomColor = colour;
    }
}