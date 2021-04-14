// Class to process list from database
class ListData {
    // Constructor method
    constructor() {}

    // function to get teacher list
    listTeachers() {
        const this_var = this;
        // Send http request to get route to get list of teachers
        $.ajax({
            url: '/football_trivia_game/public/getTeachers',
            type: 'POST',
            data: {},
            // If ajax request is successful
            success: function (data) {
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
                       this_var.createButtons(row, 5);
                   }
                });
            }
        });
    }

    // function to get student list
    listStudents() {
        const this_var = this;
        // Send http request to get route to get list of students
        $.ajax({
            url: '/football_trivia_game/public/getStudents',
            type: 'POST',
            data: {},
            // If ajax request is successful
            success: function (data) {
                console.log('it worked!');
                // Get table to insert teacher data into
                const teacher_table = document.getElementById('students-table');
                // map out json encoded data
                $.map(JSON.parse(data), function(students) {
                   for (let i = 0; i < students.length; i++) {
                       // Insert row table
                       const row = teacher_table.insertRow(i+1);
                       // Insert cells into row
                       let cell_id = row.insertCell(0);
                       let cell_username = row.insertCell(1);
                       let cell_name = row.insertCell(2);
                       let cell_email = row.insertCell(3);
                       let cell_teacher_name = row.insertCell(4);
                       let cell_date_added = row.insertCell(5);
                       // Insert data into cell
                       cell_id.innerHTML = students[i].id;
                       cell_username.innerHTML = students[i].username;
                       cell_name.innerHTML = students[i].fname + ' ' + students[i].surname;
                       cell_email.innerHTML = students[i].email;
                       cell_teacher_name.innerHTML = students[i].teacher_name;
                       cell_date_added.innerHTML = students[i].date_added;
                       // Create buttons
                       this_var.createButtons(row, 6);
                   }
                });
            }
        });
    }

    // function to get question list
    listQuestions() {
        const this_var = this;
        // Send http request to get route to get list of questions
        $.ajax({
            url: '/football_trivia_game/public/getQuestions',
            type: 'POST',
            data: {},
            // If ajax request is successful
            success: function (data) {
                console.log(data);
                // Get table to insert teacher data into
                const teacher_table = document.getElementById('questions-table');
                // map out json encoded data
                $.map(JSON.parse(data), function(questions) {
                   for (let i = 0; i < questions.length; i++) {
                       // Insert row table
                       const row = teacher_table.insertRow(i+1);
                       // Insert cells into row
                       let cell_id = row.insertCell(0);
                       let cell_question = row.insertCell(1);
                       let cell_choice1= row.insertCell(2);
                       let cell_choice2 = row.insertCell(3);
                       let cell_choice3 = row.insertCell(4);
                       let cell_choice4 = row.insertCell(5);
                       let cell_answer = row.insertCell(6);
                       let cell_difficulty = row.insertCell(7);
                       let cell_subject = row.insertCell(8);
                       let cell_teacher = row.insertCell(9);
                       let cell_date_added = row.insertCell(10);
                       // Insert data into cell
                       cell_id.innerHTML = questions[i].question_id;
                       cell_question.innerHTML = questions[i].question;
                       cell_choice1.innerHTML = questions[i].choice1;
                       cell_choice2.innerHTML = questions[i].choice2;
                       cell_choice3.innerHTML = questions[i].choice3;
                       cell_choice4.innerHTML = questions[i].choice4;
                       cell_answer.innerHTML = questions[i].answer;
                       cell_difficulty.innerHTML = questions[i].difficulty;
                       cell_subject.innerHTML = questions[i].subject;
                       cell_teacher.innerHTML = questions[i].teacher_name;
                       cell_date_added.innerHTML = questions[i].date_added;
                       // Create buttons
                       this_var.createButtons(row, 11);
                   }
                });
            }
        });
    }

    getLeaderboard(this_var) {
        //const this_var = this_var;
        // Send http request to get route to get list of game stats
        $.ajax({
            url: '/football_trivia_game/public/getLeaderboard',
            type: 'POST',
            data: {},
            // If ajax request is successful
            success: function (data) {
                // map out json encoded data
                $.map(JSON.parse(data), function(leaderboard) {
                    if (leaderboard.error !== undefined) {
                        alert(leaderboard.error);
                    } else {
                        console.log(leaderboard);
                        // Function to display game stats data returned from db
                        this_var.displayLeaderboard(leaderboard);
                    }
            });
            }
        });
    }

    displayLeaderboard(data) {
        // Get header text
        const stats_header = document.getElementById('modal-header');

        // Get stats content div
        const stats_content = document.getElementsByClassName('stats-content')[0];
        
        stats_header.innerHTML = 'Leaderboard';

        // Clear stats content 
        stats_content.innerHTML = '';

        // Add table
        const table = document.createElement('table');

        // create body
        const tbody = document.createElement('tbody');

        table.appendChild(tbody);

        for (let i = 0; i < data.length; i++) {
            // position variable 
            const position = i + 1;
            tbody.innerHTML += '<tr>' + 
                                '<td>' + position + '</td>' +
                                '<td>' + data[i].name + '</td>' +
                                '<td>' + data[i].team_name + '</td>' +
                                '<td>' + data[i].skill_rating + '</td>' +
                                '</tr>';
        }

        stats_content.appendChild(table);
    }

    createButtons(row, column) {
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
        let cell_buttons = row.insertCell(column);
        cell_buttons.appendChild(edit_button);
        cell_buttons.appendChild(delete_button); 
    }

    listSubjects(difficulty, display_type) {
        const this_var = this;
         // Send http request to get route to get list of subjects
         $.ajax({
            url: '/football_trivia_game/public/getSubjects',
            type: 'POST',
            data: {
                display_type: display_type,
                difficulty: difficulty
            },
            // If ajax request is successful
            success: function (data) {
                // map out json encoded data
                $.map(JSON.parse(data), function(subjects) {
                    if (display_type == 'form') {
                        // Create subject select box
                        this_var.createSubjectSelect(subjects);
                        console.log(subjects);
                    } else {
                        // Create list of subjects in modal
                        this_var.displaySubjects(subjects);
                    }
                });
            }
        });
    }

    displaySubjects(array) {
        // get modal header
        const modal_header = document.getElementById('modal-header');

        // set header text
        modal_header.innerHTML = 'Select Subject';

        // get modal body 
        const modal_body = document.getElementsByClassName('modal-body');

        // Create html content
        const content = '<div id="|" class="subject">|</div>';

        // Split the content
        const split = content.split("|");
        console.log(array);

        for (let i = 0; i < array.length; i++) {
            // add modal content
            modal_body[0].innerHTML += split[0] + array[i].subject + split[1] + 
                                        array[i].subject + split[2];

        }

        // Loop through elements and set click events to store difficulty
        for (let i = 0; i < modal_body[0].childNodes.length; i++) {
            modal_body[0].childNodes[i].addEventListener('click', function() {
                // Store difficulty by getting text from header tag
                localStorage.setItem('subject', modal_body[0].childNodes[i].innerHTML);
                // redirect to match page
                window.location.href = '/football_trivia_game/public/match';
            }); 
        }
    }

    // Create subject select box
    createSubjectSelect(subjects) {
        // Get subject div to add select box to
        const subject_div = document.getElementById('subject-choice');

        // Create select box
        const select = document.getElementById('subject-select');

        // Clear any previous options
        select.innerHTML = '';

        // Loop through subjects
        for (let i = 0; i < subjects.length; i++) {
            // Do not display general as general represents the hard coded questions
            if (subjects[i].subject !== 'General') {
                // Create option element
                const option = document.createElement('option');

                // Set value
                option.value = subjects[i].subject;

                // Display option text
                option.innerHTML = subjects[i].subject;

                // add option to select
                select.appendChild(option);
            }
        }

        // Add select to form
        subject_div.appendChild(select);
    }

    listGameStats() {
        // Local variable for this
        const this_var = this;

        // Send http request to get route to get list of game stats
        $.ajax({
            url: '/football_trivia_game/public/getGameStats',
            type: 'POST',
            data: {},
            // If ajax request is successful
            success: function (data) {
                //console.log(data);
                // map out json encoded data
                $.map(JSON.parse(data), function(game_stats) {
                    // Function to display game stats data returned from db
                    this_var.displayGameStats(this_var, game_stats);
                });
            }
        });
    }

    displayGameStats(this_var, game_stats) {
        // Seperate specific stats
        const all_stats = game_stats['game'];
        const easy_stats = game_stats['easy'];
        const medium_stats = game_stats['medium'];
        const hard_stats = game_stats['hard'];

        // Get modal
        const modal = document.getElementsByClassName('modal-body')[0];

        // add stats class to modal
        modal.classList.add('stats-modal');

        // Create div for select box and leaderboard
        const div = document.createElement('div');

        // add class to div
        div.classList.add('stats-options');

        // Create element
        const leaderboard_button = document.createElement('button');

        leaderboard_button.innerHTML = 'Leaderboard';

        // add class to button
        leaderboard_button.classList.add('leaderboard-button');

        // Create Select to allow user to cycle through game stats
       const select = document.createElement('select');

       // Create array of options
       const options = ['All', 'Easy', 'Medium', 'Hard'];

       // Loop through array and create options
       for (let i = 0; i < options.length; i++) {
        // create element
        const option = document.createElement('option');

        // Set value and text
        option.value = options[i];
        option.text = options[i];

        // Add options to select
        select.appendChild(option);
       }

       // Add select to the div
       div.appendChild(select);

       // Add button to the div
       div.appendChild(leaderboard_button);

       // Add div to the modal
        modal.appendChild(div);

        // Create stats content div
        const stat_content = document.createElement('div');

        // Add class
        stat_content.classList.add('stats-content');

        // Add to modal
        modal.appendChild(stat_content);
        
        // Delete id key
        delete all_stats['game_statistics_id'];

        // Display stats 
        this_var.displayStats(this_var, 'All Game Stats', all_stats);

        select.addEventListener('change', function() {
            if (select.value == 'All') {
                // Display stats 
                this_var.displayStats(this_var, 'All Game Stats', all_stats);
            } else if (select.value == 'Easy') {
                // Delete id key
                delete easy_stats['easy_statistics_id'];
                // Display stats 
                this_var.displayStats(this_var, 'Easy Game Stats', easy_stats);
            } else if (select.value == 'Medium') {
                // Delete id key
                delete medium_stats['medium_statistics_id'];
                // Display stats 
                this_var.displayStats(this_var, 'Medium Game Stats', medium_stats);
            } else if (select.value == 'Hard') {
                // Delete id key
                delete hard_stats['hard_statistics_id'];
                // Display stats 
                this_var.displayStats(this_var, 'Hard Game Stats', hard_stats);
            }
        });

        leaderboard_button.addEventListener('click', function() {
            this_var.getLeaderboard(this_var);
        });
    }

    displayStats(this_var, header, stats) {
        // Get stats content div
        const stats_content = document.getElementsByClassName('stats-content')[0];
        
        // get modal header
        const modal_header = document.getElementById('modal-header');

        // set header text
        modal_header.innerHTML = header;

        // Clear previous content
        stats_content.innerHTML = '';

        // Delete unncessary id keys 
        delete stats['team_id'];

        // Get keys for the object data
        const keys = Object.keys(stats);

        // Loop through the keys
        for (let i = 0; i < keys.length; i++) {
            // make sure stat is not set to null
            if (stats[keys[i]] !== null) {
                if (i <= 7) {
                    // Call method to create stats content
                    this_var.createStats(keys, stats, i)
                } else if(i > 7 && stats[keys[i]] !== '0') {
                    // Call method to create stats content
                    this_var.createStats(keys, stats, i)
                }
            }
        }
    }

    createStats(keys, stats, i) {
        // Get stats content div
        const stats_content = document.getElementsByClassName('stats-content')[0];

        // Seperate string by underscore
        const split = keys[i].split("_");

        // Capitalize first letter of each split
        const capitilized_split_1 = split[0].charAt(0).toUpperCase() + split[0].slice(1);
        const capitilized_split_2 = split[1].charAt(0).toUpperCase() + split[1].slice(1);

        // Create text node
        const text_node = document.createTextNode(capitilized_split_1 + ' ' + capitilized_split_2 + ' ' + stats[keys[i]]);
        // Create h3 text tag
        const stat_h3 = document.createElement('h3');

        const stat_div = document.createElement('div');
        stat_div.classList.add('stat');
        
        // Create text
        stat_h3.appendChild(text_node);

        // Add tags to stat div
        stat_div.appendChild(stat_h3);

        // add stat div to modal body
        stats_content.appendChild(stat_div);
    }
}