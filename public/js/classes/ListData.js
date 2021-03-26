// Class to process list from database
class ListData {
    // Constructor method
    constructor() {}

    // function to get teacher list
    listTeachers() {
        const this_var = this;
        // Send http request to get route to check username
        $.ajax({
            url: '/football_trivia_game/public/getTeachers',
            type: 'POST',
            data: {
                test: 'test'
            },
            // If ajax request is successful
            success: function (data) {
                console.log('it worked!');
                // Get table to insert teacher data into
                const teacher_table = document.getElementById('teacher-table');
                // map out json encoded data
                $.map(JSON.parse(data), function(teachers) {
                   for (let i = 0; i < teachers.length; i++) {
                       // Insert row table
                       const row = teacher_table.insertRow(i+1);
                       // Insert cells into row
                       let cell_id = row.insertCell(0);
                       let cell_username = row.insertCell(1);
                       let cell_name = row.insertCell(2);
                       let cell_email = row.insertCell(3);
                       let cell_date_added = row.insertCell(4);
                       // Insert data into cell
                       cell_id.innerHTML = teachers[i].id;
                       cell_username.innerHTML = teachers[i].username;
                       cell_name.innerHTML = teachers[i].fname + ' ' + teachers[i].surname;
                       cell_email.innerHTML = teachers[i].email;
                       cell_date_added.innerHTML = teachers[i].date_added;
                       // Create buttons
                       this_var.createButtons(row);
                   }
                });
            },
            error: function () {
                console.log('it failed!');
            }
        });
    }

    createButtons(row) {
        // Create elements
        const edit_button = document.createElement("BUTTON");
        const delete_button = document.createElement("BUTTON");

        // Create text for button
        const edit_text = document.createTextNode('Edit');
        const delete_text = document.createTextNode('Delete');

        // Append text to corresponding button
        edit_button.appendChild(edit_text);
        delete_button.appendChild(delete_text);

        // Create cell for buttons
        let cell_buttons = row.insertCell(5);
        cell_buttons.appendChild(edit_button);
        cell_buttons.appendChild(delete_button); 
    }
}