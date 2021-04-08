/**
 * 
 * Class to handle any interaction with the opposition team
 * 
 */

 class OppositionTeam {
    // Constructor method
    constructor(home) {
        this.team_name = null;
        this.colour = null;
        this.rating = null;
        this.score_counter = 0;
        this.home = home;
        this.possession = this.setPossession();
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

    // method to randomize team name and colour
    createTeam() {
        // Create arrays for team names and colours
        const team_names = ['Bears', 'Snakes', 'Rats', 'Cougars', 'Tigers', 'Lions', 'Rabbits', 'Hawks', 'Seagulls', 'Hammers'];
        const colours = ['pink', 'cyan', 'brown', 'orange', 'khaki', 'lightblue', 'blue', 'purple', 'magenta', 	'teal', 'lightseagreen', 'seagreen', 'aquamarine', 'indigo', 'violet', 'darkgreen', 'palegreen', 'darkorange', 'coral', 'tomato'];

        // Randomize array offset
        const name_num = Math.floor(Math.random() * team_names.length) + 0;
        const colour_num = Math.floor(Math.random() * colours.length) + 0;

        // Set class properties
        this.team_name = team_names[name_num];
        this.colour = colours[colour_num];
    }

    setHeader() {
        // get team name header secion by class name
        const team_section = document.getElementsByClassName('team-name');

        // get score counters by class name
        const score_counter = document.getElementsByClassName('score-counter');

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

        // Get header text tag to set team name in 
        const h1 = team_section[i].querySelectorAll('H1');

        // Set inner html of section on the home side (left )to team name
        h1[0].innerHTML = this.team_name;

        // Set id of score counter to team name
        score_counter[i].setAttribute('id', this.team_name);

        // Set colour
        team_section[i].style.backgroundColor = this.colour;
    }
}