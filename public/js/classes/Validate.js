class Validate {

    // Constuctor method
    constructor(inputs) {
        this._inputs = inputs;
    }

    // Function to switch to sign up
    checkDataEntered() {
        // Set input filled variable to false initially
        let input_filled = [];
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

        console.log(validate);
        return validate;
    }
}