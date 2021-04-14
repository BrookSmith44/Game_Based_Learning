/**
 * 
 * Class to handle any interaction with the user team
 * 
 */
class UserTeam {
    // Constructor method
    constructor() {
        this.team_name = null;
        this.colour = null;
        this.rating = null;
        this.questions_answered = 0;
        this.answered_right = 0;
        this.score_counter = 0;
        this.home = (Math.random() < 0.5);
        this.possession = this.setPossession();
        this.win = 0;
        this.draw = 0;
        this.loss = 0;
    }

    // Method to set possession at beginning of the match
    // If its the home team then they start with possession
    setPossession() {
        // Set empty variable for possession
        let possession;
        
        // Check whether team is at home or not 
        if (this.home == true) {
            // If they are at home set possession to true
            possession = true;
        } else {
            // If user team is away then set possession to false
            possession = false;
        }

        // return 
        return possession;
    }

    setTeamData(team_data) {
        // Set team properties
        // Team name
        this.team_name = team_data['team_name'];

        // Colour
        this.colour = team_data['team_colour'];

        // Rating
        this.rating = team_data['skill_rating'];
    }

    setHeader() {
        // get team name header secion by class name
        const team_section = document.getElementsByClassName('team-name');

        // get score counters by class name
        const score_counter = document.getElementsByClassName('score-counter');

        // Check whether user team is at home or note 
        const i = this.determineHome();
        
        // Get header text tag to set team name in 
        const h1 = team_section[i].querySelectorAll('H1');

        // Set inner html of section on the home side (left )to team name
        h1[0].innerHTML = this.team_name;

        // Set id of score counter to team name
        score_counter[i].setAttribute('id', this.team_name);
        
        // Set background colour of section
        team_section[i].style.backgroundColor = this.colour;
    }

    determineHome() {
        // set empty variable or array offset
        let i;

        // Check to see if home team
        if (this.home == true) {
            // Set i to 0 to represent first section in array - left side
            i = 0;
        } else {
            // Set i to 1 to represent second section in array - right side
            i = 1;
        } 

        return i;
    }
}