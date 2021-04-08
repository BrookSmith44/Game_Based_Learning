// javascript class to set teams, play match and send match data to back end
class Match {
    constructor(user_team, opposition_team) {
        this.user_team = user_team;
        this.opposition_team = opposition_team;
        this.game_timer = null;
        this.random = null;
        this.current_type = null;
        this.questions = [];
        this.used_questions = [];
        this.question_num = 0;
        this.commentary = [];
    }

    getData() {
        // Create variables to that team objects and this function is accessable in ajax callback
        const this_var = this;
        const user_team = this.user_team;
        const opposition_team = this.opposition_team;

        // Send http request to get route to return team data
        (async () => {
            async function match() {
                // Send http request to get route to return team data
                return $.ajax({
                    url: '/football_trivia_game/public/getGameData',
                    type: 'POST',
                    data: {

                    },
                    // If ajax request is successful
                    success: function (data) {

                    },
                    error: function () {

                    }
                });
            }

            const data = await match();

            // map out json encoded data
            $.map(JSON.parse(data), function(game_data) {
                // Ajax request returns three arrays
                // So must ensure the array required is defined before attempting to handle it
                if (game_data['team_name'] && game_data['team_colour'] !== undefined) {
                    // Set team data
                    this_var.setTeamData(user_team, opposition_team, game_data)

                } else if (game_data['commentary']) {
                      this_var.commentary = game_data['commentary'];

                } else if (game_data['questions'] !== undefined) {
                    // Set match properties to store questions
                    this_var.questions = game_data['questions'];
                }
            });
            this_var.startGame(this_var);
        })();
    }

    // Method to set up user view prior to starting match
    setTeamData(user_team, opposition_team, team_data) {
        // Get user team data and set it in match header
        user_team.setTeamData(team_data);
        // Create opponent team
        opposition_team.createTeam();

        // while loop to ensure that the colours are not the same
        while (user_team.colour == opposition_team.colour) {
            // Run create method again to ensure the colours do not match
            opposition_team.createTeam();
            console.log('Same Colour');
        }

        // Set oppostion posession to opposite of user posession
        opposition_team.home = !user_team.home;
        opposition_team.home = !user_team.home;

        // Set headers
        // Set user header 
        user_team.setHeader();
        // Set opposition team name in header
        opposition_team.setHeader();
    }

    startGame(this_var) {
        // Set end game to 90 as football games are 90 mins long
        const timer_tag = document.getElementById('timer');
        let count = 0;
        let commentary_type = 'Possession';
        this_var.game_timer = null;

        this.setGameTimer(this_var, count, commentary_type);
        
    }

    // Method to create interval
    setGameTimer(this_var, i, commentary_type) {
        // Create count from parameter
        let count = i;
        const timer_tag = document.getElementById('timer');
        const commentary_container = document.getElementById('commentary-container');
        // get score counters
        const user_team_counter = document.getElementById(this_var.user_team.team_name);
        const opposition_team_counter = document.getElementById(this_var.opposition_team.team_name);
        // Create timer that updates every seconds
        const timer = setInterval(function() {

            // Set timer to update every iteration
            timer_tag.innerHTML = count;

            // Set score to update after every iteration 
            user_team_counter.innerHTML = this_var.user_team.score_counter;
            opposition_team_counter.innerHTML = this_var.opposition_team.score_counter;

            // 50% chance or running commentary - more of a natural feel if commentary is stuttered rather than produced every second 
            let run_commentary = (Math.random() < 0.5);

            if (run_commentary == true) {
                // Function to switch between different commentary types
                commentary_type = this_var.switchCommentary(this_var, commentary_type, timer);
            }
            
            // Iterate timer by 1 every time interval runs
            count++
            this_var.game_timer = count;

            // When timer hits 90 clear interval and finish game
            // greater than to ensure that number 90 is displayed 
            // Instead of stopping at 89
            if (count > 90) {
                clearInterval(timer);
                setTimeout(function() {
                    // Check who the winner is by comparing score counters
                    const winner = this_var.getWinner(this_var);
                    // Empty variable for end message
                    let end_message;
                    let end_colour;
                    // Generate different end game message if its a draw
                    if (winner == 'draw') {
                        end_message = 'It all ends in a draw here!!!';
                        end_colour = 'black';
                    } else {
                        end_message = 'The final Whistle Blows!!! ' + winner.team_name + ' win!!!';
                        end_colour = winner.colour;
                    }
                    
                    
                    
                    
                    this_var.generateCommentary(end_message);
                    // Get last commentary article in array of class names
                    const commentary = document.getElementsByClassName('commentary');
                    commentary[commentary.length-1].setAttribute('id', 'end-game');
                    // Make winner flash
                    this_var.commentaryFlash(commentary[commentary.length-1], end_colour);
                    // Ensure new commentary is always in sight by scrolling to bottom of section
                    // After every interval
                    commentary_container.scrollTop = commentary_container.scrollHeight;
                },2000);
            }
        }, 1000);
    }

    // Method to generate commentary
    generateCommentary(text, colour) {
        // Get match container to append elements from
        const commentary_container = document.getElementById('commentary-container');
        // Create new element to be added to DOM
        const commentary = document.createElement("ARTICLE");
        // Add class to element for necessary styling
        commentary.classList.add('commentary');
        // Create text for button
        const commentary_text = document.createTextNode(text);
        // Append text to corresponding button
        commentary.appendChild(commentary_text);
        // Set background colour 
        commentary.style.backgroundColor = colour;
        // Append commentary from match container
        commentary_container.appendChild(commentary);
    }

    commentaryFlash(element, colour) {
        // interval to run for 5 seconds
        let count = 0;        
        const flash_timer = setInterval(function() {

            if (element.style.backgroundColor == 'white') {
                element.style.backgroundColor = colour;
                element.style.color = 'white';
            } else {
                element.style.backgroundColor = 'white';
                element.style.color = colour;
            }
            
            count++;
            if (count > 11) {
                clearInterval(flash_timer);
            }
        }, 600);
    }

    switchCommentary(this_var, commentary_type, timer) {
        // Get match container to append elements from
        const commentary_container = document.getElementById('commentary-container');
        // Create empty variable for commentary type
        let type;
        switch (commentary_type) {
            case 'Possession' :
                // Set current type for question to be able to get key decision commentary
                this_var.current_type = 'Possession';
                // Function to get commentary
                this.getCommentary(this_var, 'Possession');
                // Create chance of question being asked
                // Low chance as leads other team chance on goal from own possession
                type = this.questionChance(this_var, 'Attacking', 0.1);
                break;
            case 'Attacking' :
                // Set current type for question to be able to get key decision commentary
                this_var.current_type = 'Attacking';
                // Function to get commentary
                this.getCommentary(this_var, 'Attacking');
                // Create chance of question being asked
                // higher chance as team is attacking so would be likely of an opportunity to score
                type = this.questionChance(this_var, 'Defending', 0.5);
                break;
            case 'Defending' :
                // Set current type for question to be able to get key decision commentary
                this_var.current_type = 'Defending';
                // Function to get commentary
                this.getCommentary(this_var, 'Defending');
                // Create chance of question being asked
                // relatively even chance of other team being able to dispossess in defending situation
                type = this.questionChance(this_var, 'Possession', 0.3);
                break;
            case 'Question' :
                this.getCommentary(this_var, 'Question');
                type = 'Possession';
                // Clear current interval to pause game while user answers question
                clearInterval(timer);
                // Create timer to answer the question
                type = this.setQuestionTimer(this_var, type);
                break;

        }

        commentary_container.scrollTop = commentary_container.scrollHeight;
        // Return commentary type
        return type;
    }

    getCommentary(this_var, type) {
        // Set team in possession 
        const teamInPossession = this.getTeamInPossession(this_var);
        const teamNotPossession = this.getTeamNotInPossession(this_var);
        // set empty random and commentary text
        let commentary_text;
        let random;
        let next_type;
        const colour = teamInPossession.colour;

        switch (type) {
            case 'Possession' :
                // Fetch new commentary combination every time it cycles back to possession
                this.random = this_var.randomize(this_var.commentary.length);
                // Create commentary with teeam name
                commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].possession.commentary, teamInPossession.team_name);
                // Generate commentary
                this_var.generateCommentary(commentary_text, colour);
                // Set next type to attacking
                next_type = 'Attacking';
                break;
            case 'Attacking' :
                // Create commentary with teeam name
                commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].attacking.commentary, teamInPossession.team_name);
                // Generate commentary
                this_var.generateCommentary(commentary_text, colour);
                 // Set next type to possession
                next_type = 'Defending'; 
                break;
            case 'Defending' :
                // Create commentary with teeam name
                commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].defending.commentary, teamInPossession.team_name);
                // Generate commentary
                this_var.generateCommentary(commentary_text, colour);
                // Set next type to possession
                next_type = 'Possession'; 
                break;
            case 'Question' :
                // Display key Decision commentary before question 
                switch (this_var.current_type) {
                    case 'Possession' :
                        // Create commentary with teeam name
                        commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].possession.key_decision, teamNotPossession.team_name);
                        // Generate commentary
                        this_var.generateCommentary(commentary_text, teamNotPossession.colour);
                        break;
                    case 'Attacking' :
                        // Create commentary with teeam name
                        commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].attacking.key_decision, teamInPossession.team_name);
                        // Generate commentary
                        this_var.generateCommentary(commentary_text, colour);
                        break;
                    case 'Defending' :
                        // Create commentary with teeam name
                        commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].defending.key_decision, teamInPossession.team_name);
                        // Generate commentary
                        this_var.generateCommentary(commentary_text, colour);
                        break;
                }

                // Randomized integer
                random = this_var.randomize(this_var.questions.length);
                // Make sure question has not already been used
                while (this_var.used_questions.includes(random) == true) {
                    // Randomized integer
                    random = this_var.randomize(this_var.questions.length);
                    console.log('same Question');
                }
                // push random integer into used questions array
                this_var.used_questions.push(random);

                // Create question timer commentary element
                // Generate commentary
                this_var.generateCommentary('', 'black');
                // Get last commentary article in array of class names
                const commentary = document.getElementsByClassName('commentary');
                commentary[commentary.length-1].setAttribute('id', 'question-timer');

                this_var.createQuestionForm(this_var, this_var.questions[random]);

                break;
        }

        return next_type;
    }

    getTeamInPossession(this_var) {
        // Check to see if it is the user in possession
        if (this_var.user_team.possession == true) {
            // Set team name to the user team name
            return this_var.user_team;
        } else {
            // Set opposition team name
            return this_var.opposition_team;
        }
    }

    getTeamNotInPossession(this_var) {
        // Check to see if it is the user in possession
        if (this_var.user_team.possession == false) {
            // Set team name to the user team name
            return this_var.user_team;
        } else {
            // Set opposition team name
            return this_var.opposition_team;
        }
    }

    switchPossession(this_var) {
        // Set both teams possession to opposite of each other
        this_var.user_team.possession = !this_var.user_team.possession;
        this_var.opposition_team.possession = !this_var.opposition_team.possession;
    }

    // Set question timer
    setQuestionTimer(this_var, type) {
        // Empty variable for next type to be returned
        let next_type;
        // Get current question form
        const form = document.forms;
        // Create counter for question timer
        let question_count = 10;
        // Get question timer element
        const question_timer_elem = document.getElementById('question-timer');


        const question_timer = setInterval(function() {
            question_timer_elem.innerHTML = question_count;
            question_count--;

            if (question_count < -1) {
                clearInterval(question_timer);
                // Call function to handle the selected answer
                next_type = this_var.handleAnswer(this_var, this_var.current_type);
                // Reinitiate game clock
                this_var.setGameTimer(this_var, this_var.game_timer, next_type);
            }
        }, 1000);

        // Add event listener for button to submit answer
        form[0].addEventListener('submit', function(event) {
            clearInterval(question_timer);
            event.preventDefault();
            
            // Call function to handle the selected answer
            next_type = this_var.handleAnswer(this_var, this_var.current_type);
            // Reinitiate game clock
            this_var.setGameTimer(this_var, this_var.game_timer, next_type);
        });
    }

    createQuestionForm(this_var, question_array) {
        // Set local variables
        const question = question_array['question'];
        const choice = question_array['choices'];

        // Get match container to append elements from
        const commentary_container = document.getElementById('commentary-container');
        // Create new element to be added to DOM
        const commentary = document.createElement("article");

        // Create elements for question form
        const question_form = document.createElement("form");
        const label1 = document.createElement("label"); 
        const element1 = document.createElement("input");
        const label2 = document.createElement("label"); 
        const element2 = document.createElement("input");
        const label3 = document.createElement("label"); 
        const element3 = document.createElement("input");
        const label4 = document.createElement("label"); 
        const element4 = document.createElement("input");
        const question_header = document.createElement("H1");
        // Create div to format label and input
        const choice1_div = document.createElement('div');
        const choice2_div = document.createElement('div');
        const choice3_div = document.createElement('div');
        const choice4_div = document.createElement('div');
        // Create button to submit answer
        const button = document.createElement('button');
        // Create child node to store question text
        const text_node = document.createTextNode(question);

        // Set header tag to question text
        question_header.appendChild(text_node);

        element1.value = choice['choice1'];
        element2.value = choice['choice2'];
        element3.value = choice['choice3'];
        element4.value = choice['choice4'];
        element1.type = 'radio';
        element2.type = 'radio';
        element3.type = 'radio';
        element4.type = 'radio';
        element1.name = 'choice';
        element2.name = 'choice';
        element3.name = 'choice';
        element4.name = 'choice';
        label1.for = choice['choice1'];
        label2.for = choice['choice2'];
        label3.for = choice['choice3'];
        label4.for = choice['choice4'];
        label1.innerHTML = choice['choice1'];
        label2.innerHTML = choice['choice2'];
        label3.innerHTML = choice['choice3'];
        label4.innerHTML = choice['choice4'];
        button.innerHTML = 'Submit';

        choice1_div.appendChild(label1); 
        choice2_div.appendChild(label2); 
        choice3_div.appendChild(label3); 
        choice4_div.appendChild(label4); 

        choice1_div.appendChild(element1); 
        choice2_div.appendChild(element2); 
        choice3_div.appendChild(element3); 
        choice4_div.appendChild(element4); 
        
        question_form.appendChild(choice1_div); 
        question_form.appendChild(choice2_div); 
        question_form.appendChild(choice3_div); 
        question_form.appendChild(choice4_div);  
        question_form.appendChild(button);

        commentary.appendChild(question_header);
        commentary.appendChild(question_form);
        commentary_container.appendChild(commentary);

        commentary.setAttribute('id', 'question');
        question_form.setAttribute('id', 'questions' + this_var.question_num);

        // Add class to element for necessary styling
        commentary.classList.add('commentary');
    }

    getCheckedInput(this_var) {
        // empty varibale for selected answer
        let selected;

        // Get current question form
        const question_form = document.forms[0];

        // get inputs
        const choices = question_form.getElementsByTagName('input');

        // Loop through radio buttons to get selected choice
        for (let i = 0; i < choices.length; i++) {
            // Check to see if radio has been checked
            if (choices[i].checked) {
                selected = choices[i].value;
            }
        }

        return selected;
    }

    randomize(max) {
        // Random number between 0 and maximum parameter given
        const i = Math.floor(Math.random() * max) + 0;
        
        return i;
    }

    splitCommentary(commentary, team_name) {
        // Set empty variable for commentary
        let commentary_text = '';
        // Split team by slash - slash used in php to show where team name should be
        const split = commentary.split("/");

        // Check to see if slash for team name exists
        if (split.length > 1) {
            // Insert team name between the splits to create commentary with team name
            commentary_text = split[0] + team_name + split[1];
        } else {
            // Return commentary without team name as no place has been given for one
            commentary_text = split[0];
        }

        // Return new commentary text
        return commentary_text;
    }

    handleAnswer(this_var, type) {
        // Empty variable for next type to be returned
        let next_type;
        // Set teams
        const teamInPossession = this_var.getTeamInPossession(this_var);
        const teamNotPossession = this_var.getTeamNotInPossession(this_var);
        // Get question timer element to remove
        const question_timer_elem = document.getElementById('question-timer');
        // Get user answer
        const user_answer = this_var.getCheckedInput(this_var);
        // Get index of last question
        const used_index = this_var.used_questions[this_var.used_questions.length -1];
        // Get question
        const question = this_var.questions[used_index];
        // Check answer
        const correct = this_var.checkAnswer(this_var, user_answer, question);
        // Iterate question num property for next question
        this_var.question_num++;
        // Create commentary article to inform user if they are correct or incorrect
        if (correct == true) {
            // Generate commentary
            this_var.generateCommentary('CORRECT!!!', 'black');
        } else {
            // Generate commentary
            this_var.generateCommentary('INCORRECT!!!', 'black');
        }


        switch (type) {
            case 'Possession' :
                // Function to decide what happens if user answer is right or wrong depending on whether they are in possession or not
                this_var.possessionAnswer(this_var, teamInPossession, teamNotPossession, correct);
                break;
            case 'Attacking' :
                this_var.attackingAnswer(this_var, teamInPossession, correct);
                break;
            case 'Defending' :
                this_var.defendingAnswer(this_var, teamInPossession, teamNotPossession, correct);
                break;
        }

        const question_article = document.getElementById('question');

        // Remove question and timer
        question_article.remove();
        question_timer_elem.remove();

        // next type is possession again
        next_type = 'Possession';

        return next_type;
    }
    
    checkAnswer(this_var, user_answer, question) {
        // Empty variable to store correct bool
        let correct;
        // Iterate questions answered variable
        this_var.user_team.questions_answered++;

        // Check to see if user answer matches answer in question object
        if (user_answer == question.answer) {
            // Set correct variable to true
            correct = true;
            // Itertate variable for questions answered correctly to be stored in db later
            this_var.user_team.answered_right++;
        } else {
            // If answers do not match set correct variable to false
            correct = false;
        }

        return correct;
    }

    questionChance(this_var, next_type, percent) {
        // Empty variable to store type
        let type;

        // Create chance based on percentage given - percentage represented by decimal
        const chance = (Math.random() < percent);

        // Check to see if outcome is true or false
        if ((chance == true) && (this_var.game_timer < 88)) {
            console.log(this_var.game_timer);
            // Set type to question 
            type = 'Question';
        } else {
            type = next_type;
        }
        return type;
    }

    // Display the commentary and increment score of user or save to stop opponent scoring depending on who is possession and whether the answer is correct
    possessionAnswer(this_var, teamInPossession, teamNotPossession, correct) {
        // get commentary elements
        const commentary = document.getElementsByClassName('commentary');
        //  Empty varibale for commentary text
        let commentary_text;
        // Check if user is in possession
        if ((teamInPossession == this_var.user_team && correct == true) || (teamInPossession !== this_var.user_team && correct == false)) {
            // Create commentary with teeam name
            commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].possession.save, teamInPossession.team_name);
            // Generate commentary
            this_var.generateCommentary(commentary_text, teamInPossession.colour);

        } else {
            // Increment score 
            teamNotPossession.score_counter++;
            // Create commentary with teeam name
            commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].possession.score, teamNotPossession.team_name);
            // Generate commentary
            this_var.generateCommentary(commentary_text, teamNotPossession.colour);
            // Make commentary flash
            this_var.commentaryFlash(commentary[commentary.length-1], teamNotPossession.colour);

        }
    }

    // Display the commentary and increment score of user or save to stop opponent scoring depending on who is possession and whether the answer is correct
    attackingAnswer(this_var, teamInPossession, correct) {
        console.log(teamInPossession, correct);
        // get commentary elements
        const commentary = document.getElementsByClassName('commentary');
        //  Empty varibale for commentary text
        let commentary_text;
        // Check if user is in possession
        if ((teamInPossession == this_var.user_team && correct == true) || (teamInPossession !== this_var.user_team && correct == false)) {
            // Increment score 
            teamInPossession.score_counter++;
            // Create commentary with teeam name
            commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].attacking.score, teamInPossession.team_name);
            // Generate commentary
            this_var.generateCommentary(commentary_text, teamInPossession.colour);
            // Make commentary flash
            this_var.commentaryFlash(commentary[commentary.length-1], teamInPossession.colour);
            // switch possession
            this_var.switchPossession(this_var);

        } else {
            // Create commentary with teeam name
            commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].attacking.save, teamInPossession.team_name);
            // Generate commentary
            this_var.generateCommentary(commentary_text, teamInPossession.colour);
            // switch possession
            this_var.switchPossession(this_var);

        }
    }

    // Display the commentary and switch possession depending on who is in possession and if answer is correct
    defendingAnswer(this_var, teamInPossession, teamNotPossession, correct) {
        //  Empty varibale for commentary text
        let commentary_text;
        // Check if user is in possession
        if ((teamInPossession == this_var.user_team && correct == true) || (teamInPossession !== this_var.user_team && correct == false)) {
            // Create commentary with teeam name
            commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].defending.retain, teamInPossession.team_name);
            // Generate commentary
            this_var.generateCommentary(commentary_text, teamInPossession.colour);

        } else {
            // Create commentary with teeam name
            commentary_text = this_var.splitCommentary(this_var.commentary[this_var.random].defending.dispossess, teamNotPossession.team_name);
            // Generate commentary
            this_var.generateCommentary(commentary_text, teamNotPossession.colour);
            // switch possession
            this_var.switchPossession(this_var);

        }
    }



    // Get the winner of the match
    getWinner(this_var) {
        // Empty variable to return winning team
        let winner;

        if (this_var.user_team.score_counter > this_var.opposition_team.score_counter) {
            // Set winner to user team
            winner = this_var.user_team;
            // Set user team win property to 1 - 1 instead of true to use for iteration when inserting into database
            this_var.user_team.win++;
        } else if (this_var.user_team.score_counter < this_var.opposition_team.score_counter) {
            // Set winner to opponent team
            winner = this_var.opposition_team;
            // Set user team win property to 1
            this_var.user_team.loss++;
        } else {
            // Set as draw
            winner = 'draw';
            // Set user team win property to 1
            this_var.user_team.draw++;
        }

        console.log('win: ' + this_var.user_team.win + ' draw: ' + this_var.user_team.draw + ' loss: ' + this_var.user_team.loss);

        return winner;
    }
}