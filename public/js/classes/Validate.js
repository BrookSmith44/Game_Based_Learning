class Validate {

    // Constuctor method
    constructor(inputs) {
        this._inputs = inputs;
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
                this._inputs[i].classList.add('error');
                // Push false boolean to array to show input not filled in
                input_filled.push(false);
            } else {
                // Push true to array to show input filled in
                input_filled.push(true);
            }
        }

        // Return true if all inputs are filled in
        let validate = input_filled.every(function (e) {
            return e === true;
        });

        return validate;
    }

    // Function to check passwords match
    checkValuesMatch(value_one, value_two) {
        // Set password match bool to false initially
        let pass_match = false;
        
        // Check if passwords match
        if (value_one == value_two) {
            // Set password match variable to true
            pass_match = true;
        }

        // Return password match variable
        return pass_match;
    }


}