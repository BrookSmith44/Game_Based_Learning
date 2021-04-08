<?php

/**
 * 
 * Class to store and retrieve commentary  
 * 
 */

 namespace Game;

 class Commentary {

    // Magic methods
    public function __construct() {}

    public function __destruct() {}
    
    // Methods
    public function getCommentary() {
        // Empty array for commentary
        $commentary = [];
        $commentary[0] = [
            'possession' => [
                'commentary' => '/ are comfortable in possession for now, they are passing it around the midfield with ease',
                'key_decision' => 'Pass intercepted!!! / are on the ball now as they charge down the middle and go one on one with the keeper!',
                'save' => 'An unbelievable save pulled off by the / keeper and he keeps it in his control!',
                'score' => 'And he smashes it into the back of the net!!! a goal that has been utterly gifted to them'
            ],

            'attacking' => [
                'commentary' => 'Possession turns into attack as they work their way through the midfield to the edge of the box',
                'key_decision' => 'Space has opened up as / look to shoot from range!',
                'score' => 'PICK THAT ONE OUT!!! An unstoppable strike right into the top corner',
                'save' => 'Oooooh so close! the ball smashes off the crossbar and out of play!'
            ],

            'defending' => [
                'commentary' => 'Shot blocked!!! big scramble for the ball but / look like they\'re going to keep hold of the ball',
                'key_decision' => '/ really being put under pressure here, can they keep the ball?',
                'retain' => 'A good show of strength there as he shoves off the tackle and retains possession',
                'dispossess' => 'He can\'t seem to sort his feet out and gets dispossessed by /'
            ],
        ];

        $commentary[1] = [
            'possession' => [
                'commentary' => '/ are passing it across the back line comfortably',
                'key_decision' => 'OOOOH that pass to the keeper is very short and / have a chance to score!',
                'save' => 'Luckily the keeper is there first and cleans up but that was close!',
                'score' => 'A lovely finish as the striker chips the keeper from the edge of the box!!!'
            ],

            'attacking' => [
                'commentary' => 'A sharp pass by the / right back sends them behind the defence and pulled back into the box',
                'key_decision' => 'The striker latches onto the ball and has room to shoot!',
                'score' => 'GOAL!!! A lovely counter attacking move ends in a tidy finish from the striker',
                'save' => 'OOOH he has got the completely wrong as he sends it straight at the keeper! poor finishing'
            ],
            'defending' => [
                'commentary' => 'The ball fizzes across the box but no one can get onto it',
                'key_decision' => 'There is a chase for the ball here',
                'retain' => '/ just about get there first and manage to keep the ball',
                'dispossess' => 'he is too quick for the attacker as he wins the ball back for /'
            ],
        ];

        // return commentary
        return $commentary;
    }

    public function attackingCommentary() {
        // Empty array for commentary
        $commentary = [];

        $commentary[0] = ' have injected a bit more energy into the game now as they burst down the wing';
        $commentary[1] = ' are playing quick football now as they surge down the pitch with quick one touch passes';

        // return commentary
        return $commentary;
    }

    public function defendingCommentary() {
        // Empty array for commentary
        $commentary = [];

        $commentary[0] = ' are under pressure here, they may be in serious trouble here if they cannot regain possession';
        $commentary[1] = ' have all 11 men behind the ball as they attempt to weather this onslaught';

        // return commentary
        return $commentary;
    }
 }