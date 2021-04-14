<?php

/**
 * 
 * Class to store all questions used for the general users
 * 
 */

 namespace Game;

 class GeneralQuestions {
     // Properties
      private $num_array;
      private $difficulty;
     // Empty magic methods
     public function __construct() {
        $this->num_array = [];
        $this->difficuly = null;
     }

     public function __destruct() {}
     
     // Setter methods
     public function setDifficulty($difficulty) {
        $this->difficulty = $difficulty;
     }


     // Methods
     // Function to look through commentary or question array and store 20 to send to js
   public function getQuestions() {
      // empty array for questions
      $questions = [];
      // Empty array for questions to return
      $randomized_questions = [];

      // check difficulty and create corresponding questions
      if ($this->difficulty == 'Easy') {
         // Create questions
         $questions = $this->createEasyQuestions();
      } else if ($this->difficulty == 'Medium') {
         // Create questions
         $questions = $this->createMediumQuestions();
      } else {
         // Create questions
         $questions = $this->createHardQuestions();
      }

      // Create loop that cycles 25 times
      for ($i = 0; $i < 25; $i++) {
         $random = $this->randomize($questions);
         //var_dump('same num', $random);

         while (in_array($random, $this->num_array)) {
            $random = $this->randomize($questions);
         }

         array_push($this->num_array, $random);
         array_push($randomized_questions, $questions[$random]);
      }

      return $randomized_questions;
   }

      // Get random number between 0 and array length
      public function randomize($questions) {
      // Get max 
      $max = count($questions);

      $i = mt_rand(0, $max - 1);

      return $i;
      }

      // Create and store questions
     public function createEasyQuestions() {
         // Empty array
         $questions = [];

         // Geography
         $questions[0] = [
             'question' => 'What is the biggest ocean in the world?',
             'choices' => [
                'choice1' => 'Pacific',
                'choice2' => 'Indian',
                'choice3' => 'Arctic',
                'choice4' => 'Atlantic'
             ],
             'answer' => 'Pacific'
            ];

            $questions[1] = [
                'question' => 'What animal is in the Welsh flag?',
                'choices' => [
                   'choice1' => 'Bear',
                   'choice2' => 'Lion',
                   'choice3' => 'Dragon',
                   'choice4' => 'Sheep'
                ],
                'answer' => 'Dragon'
               ];

               $questions[2] = [
                'question' => 'How many continents are there in the world?',
                'choices' => [
                   'choice1' => '10',
                   'choice2' => '6',
                   'choice3' => '7',
                   'choice4' => '4'
                ],
                'answer' => '7'
               ];

               $questions[3] = [
                'question' => 'What’s the tallest mountain in the world?',
                'choices' => [
                   'choice1' => 'K2',
                   'choice2' => 'Everest',
                   'choice3' => 'Kangchenjunga',
                   'choice4' => 'Mount Kilimanjaro'
                ],
                'answer' => 'Everest'
               ];
               
               $questions[4] = [
                'question' => 'The Grand Canyon is found in which country?',
                'choices' => [
                   'choice1' => 'USA',
                   'choice2' => 'Australia',
                   'choice3' => 'India',
                   'choice4' => 'Russia'
                ],
                'answer' => 'USA'
               ];

               // Science

               $questions[5] = [
                'question' => 'What part of the plant conducts photosynthesis?',
                'choices' => [
                   'choice1' => 'Branch',
                   'choice2' => 'Leaf',
                   'choice3' => 'Root',
                   'choice4' => 'Trunk'
                ],
                'answer' => 'Leaf'
               ];

               $questions[6] = [
                'question' => 'What is the boiling point of water?',
                'choices' => [
                   'choice1' => '25°C',
                   'choice2' => '50°C',
                   'choice3' => '75°C',
                   'choice4' => '100°C'
                ],
                'answer' => '100°C'
               ];

               $questions[7] = [
                'question' => 'Which is the largest land animal?',
                'choices' => [
                   'choice1' => 'Lion',
                   'choice2' => 'Tiger',
                   'choice3' => 'Elephant',
                   'choice4' => 'Rhinoceros'
                ],
                'answer' => 'Elephant'
               ];

               $questions[8] = [
                'question' => '____ helps pump blood through the entire body',
                'choices' => [
                   'choice1' => 'Lungs',
                   'choice2' => 'Kidneys',
                   'choice3' => 'Heart',
                   'choice4' => 'Brain'
                ],
                'answer' => 'Heart'
               ];
               
               $questions[9] = [
                'question' => 'What are the three states of matter?',
                'choices' => [
                   'choice1' => 'Solid, Liquid, and Gas',
                   'choice2' => 'Solid, Liquid, and Mist',
                   'choice3' => 'Rock, Liquid and Mist',
                   'choice4' => 'Solid, Goo, and Steam '
                ],
                'answer' => 'Solid, Liquid, and Gas'
               ];

               $questions[10] = [
                'question' => 'If one boils water it will convert into ____',
                'choices' => [
                   'choice1' => 'Mist',
                   'choice2' => 'Steam',
                   'choice3' => 'Clouds',
                   'choice4' => 'Snow'
                ],
                'answer' => 'Steam'
               ];

               $questions[11] = [
                'question' => 'When you push something, you apply ____',
                'choices' => [
                   'choice1' => 'Force',
                   'choice2' => 'Acceleration',
                   'choice3' => 'Mass',
                   'choice4' => 'Compression'
                ],
                'answer' => 'Force'
               ];

               $questions[12] = [
                'question' => 'Which group of animals have scales?',
                'choices' => [
                   'choice1' => 'Mammals',
                   'choice2' => 'Amphibians',
                   'choice3' => 'Reptiles',
                   'choice4' => 'Birds'
                ],
                'answer' => 'Reptiles'
               ];

               $questions[13] = [
                'question' => 'Where does our food collect after we chew and swallow it?',
                'choices' => [
                   'choice1' => 'Small intestine',
                   'choice2' => 'Large intestine',
                   'choice3' => 'Stomach',
                   'choice4' => 'Liver'
                ],
                'answer' => 'Stomach'
               ];

               $questions[14] = [
                'question' => 'Which material from the following has the highest transparency?',
                'choices' => [
                   'choice1' => 'Paper',
                   'choice2' => 'Wood',
                   'choice3' => 'Metal',
                   'choice4' => 'Glass'
                ],
                'answer' => 'Glass'
               ];

               $questions[15] = [
                'question' => 'Which animal from the below list is best adapted to the desert?',
                'choices' => [
                   'choice1' => 'Tiger',
                   'choice2' => 'Cheetah',
                   'choice3' => 'Camel',
                   'choice4' => 'Deer'
                ],
                'answer' => 'Camel'
               ];

               $questions[16] = [
                'question' => 'What part of the skeletal system protects the brain?',
                'choices' => [
                   'choice1' => 'Spine',
                   'choice2' => 'Thigh',
                   'choice3' => 'Pelvis',
                   'choice4' => 'Skull'
                ],
                'answer' => 'Skull'
               ];

               // Math 
               $questions[17] = [
                'question' => 'What is the next prime number after 3?',
                'choices' => [
                   'choice1' => '5',
                   'choice2' => '6',
                   'choice3' => '7',
                   'choice4' => '8'
                ],
                'answer' => '5'
               ];

               $questions[18] = [
                'question' => 'What is the perimeter of a circle known as?',
                'choices' => [
                   'choice1' => 'Circumference',
                   'choice2' => 'Radius',
                   'choice3' => 'Diameter',
                   'choice4' => 'Area'
                ],
                'answer' => 'Circumference'
               ];

               $questions[19] = [
                'question' => 'What is the square root of 81?',
                'choices' => [
                   'choice1' => '7',
                   'choice2' => '8',
                   'choice3' => '9',
                   'choice4' => '11'
                ],
                'answer' => '9'
               ];

               $questions[20] = [
                'question' => 'What does the Roman Numeral C Represent?',
                'choices' => [
                   'choice1' => '1000',
                   'choice2' => '10',
                   'choice3' => '100',
                   'choice4' => '10000'
                ],
                'answer' => '100'
               ];

               $questions[20] = [
                'question' => 'What does a century represent?',
                'choices' => [
                   'choice1' => '1000',
                   'choice2' => '10',
                   'choice3' => '100',
                   'choice4' => '10000'
                ],
                'answer' => '100'
               ];

               $questions[21] = [
                'question' => 'Which is the largest number?',
                'choices' => [
                   'choice1' => '-4',
                   'choice2' => '-150',
                   'choice3' => '3',
                   'choice4' => '2.9845409'
                ],
                'answer' => '3'
               ];

               $questions[22] = [
                'question' => 'What is 25 + 23?',
                'choices' => [
                   'choice1' => '45',
                   'choice2' => '48',
                   'choice3' => '52',
                   'choice4' => '50'
                ],
                'answer' => '48'
               ];

               $questions[23] = [
                'question' => 'What is (25 +24) – 10?',
                'choices' => [
                   'choice1' => '49',
                   'choice2' => '43',
                   'choice3' => '39',
                   'choice4' => '38'
                ],
                'answer' => '39'
               ];

               $questions[24] = [
                'question' => 'What is 22 x 4?',
                'choices' => [
                   'choice1' => '88',
                   'choice2' => '44',
                   'choice3' => '66',
                   'choice4' => '98'
                ],
                'answer' => '88'
               ];

               $questions[25] = [
                'question' => 'What is 21 x 0?',
                'choices' => [
                   'choice1' => '21',
                   'choice2' => '0',
                   'choice3' => '10.5',
                   'choice4' => '42'
                ],
                'answer' => '0'
               ];

               $questions[26] = [
                'question' => 'What is 5² equal to?',
                'choices' => [
                   'choice1' => '10',
                   'choice2' => '25',
                   'choice3' => '15',
                   'choice4' => '50'
                ],
                'answer' => '25'
               ];

               $questions[27] = [
                'question' => 'What is the square root of 36?',
                'choices' => [
                   'choice1' => '20',
                   'choice2' => '6',
                   'choice3' => '3',
                   'choice4' => '13'
                ],
                'answer' => '6'
               ];

               $questions[28] = [
                'question' => 'What comes next in the Fibonacci sequence: 0, 1, 1, 2, 3, 5, 8, 13, __?',
                'choices' => [
                   'choice1' => '26',
                   'choice2' => '21',
                   'choice3' => '19',
                   'choice4' => '23'
                ],
                'answer' => '21'
               ];

               // History
               $questions[29] = [
                'question' => 'What type of fabric was made from flax?',
                'choices' => [
                   'choice1' => 'Cotton',
                   'choice2' => 'Wool',
                   'choice3' => 'Nylon',
                   'choice4' => 'Linen'
                ],
                'answer' => 'Linen'
               ];

               $questions[30] = [
                'question' => 'What did Egyptians mostly drink?',
                'choices' => [
                   'choice1' => 'Water',
                   'choice2' => 'Wine',
                   'choice3' => 'Beer',
                   'choice4' => 'Orange juice'
                ],
                'answer' => 'Beer'
               ];

               $questions[31] = [
                'question' => 'Why did the Egyptians want the Nile to flood?',
                'choices' => [
                   'choice1' => 'It killed their enemies',
                   'choice2' => 'It washed away diseases',
                   'choice3' => 'It made the land fertile',
                   'choice4' => 'It drowned the evil spirits'
                ],
                'answer' => 'It made the land fertile'
               ];

               $questions[32] = [
                'question' => 'What did the Egyptians use to make paper?',
                'choices' => [
                   'choice1' => 'Flax',
                   'choice2' => 'Wood',
                   'choice3' => 'Papyrus',
                   'choice4' => 'Cotton'
                ],
                'answer' => 'Papyrus'
               ];

               $questions[33] = [
                'question' => 'The city states joined together to fight which invaders?',
                'choices' => [
                   'choice1' => 'Celts',
                   'choice2' => 'Aztecs',
                   'choice3' => 'Egyptians',
                   'choice4' => 'Persians'
                ],
                'answer' => 'Persians'
               ];

               $questions[34] = [
                'question' => 'Who was the Greek leader that ruled all of Greece and conquered other lands?',
                'choices' => [
                   'choice1' => 'Alfred the Great',
                   'choice2' => 'Augustus',
                   'choice3' => 'Aristotle',
                   'choice4' => 'Alexander the Great'
                ],
                'answer' => 'Alexander the Great'
               ];

               $questions[35] = [
                'question' => 'What was the name of the people who lived in Crete around 3,000 BCE?',
                'choices' => [
                   'choice1' => 'Persians',
                   'choice2' => 'Maya',
                   'choice3' => 'Minoans',
                   'choice4' => 'Mycenaeans'
                ],
                'answer' => 'Minoans'
               ];

               $questions[36] = [
                'question' => 'At which battle were the Persians defeated?',
                'choices' => [
                   'choice1' => 'Olympia',
                   'choice2' => 'Marathon',
                   'choice3' => 'Sparta',
                   'choice4' => 'Troy'
                ],
                'answer' => 'Marathon'
               ];

               $questions[37] = [
                'question' => 'Who conquered the Greeks in 168 BCE?',
                'choices' => [
                   'choice1' => 'Romans',
                   'choice2' => 'Persians',
                   'choice3' => 'British',
                   'choice4' => 'Egyptians'
                ],
                'answer' => 'Romans'
               ];

               $questions[38] = [
                'question' => 'Which of these was NOT a Greek city state?',
                'choices' => [
                   'choice1' => 'Athens',
                   'choice2' => 'Sparta',
                   'choice3' => 'Rome',
                   'choice4' => 'Corinth'
                ],
                'answer' => 'Rome'
               ];

               $questions[39] = [
                'question' => 'What was the landscape like in Greece?',
                'choices' => [
                   'choice1' => 'Desert',
                   'choice2' => 'Tropical rain forest',
                   'choice3' => 'Tundra',
                   'choice4' => 'Mountainous'
                ],
                'answer' => 'Mountainous'
               ];

               $questions[40] = [
                'question' => 'In which year did the Greek Classical Period begin?',
                'choices' => [
                   'choice1' => '3,000 BCE',
                   'choice2' => '480 BCE',
                   'choice3' => '480 CE',
                   'choice4' => '2,000 CE'
                ],
                'answer' => '480 BCE'
               ];

               $questions[41] = [
                'question' => 'What does BCE mean?',
                'choices' => [
                   'choice1' => 'Before the Common Era',
                   'choice2' => 'Before Christ',
                   'choice3' => 'Before Curried Eggs',
                   'choice4' => 'Before Computer Era'
                ],
                'answer' => 'Before the Common Era'
               ];

         return $questions;
     }


     // Create and store questions
     public function createMediumQuestions() {
      // Empty array
      $questions = [];

      // Geography
      $questions[0] = [
          'question' => 'What is the biggest ocean in the world?',
          'choices' => [
             'choice1' => 'Pacific',
             'choice2' => 'Indian',
             'choice3' => 'Arctic',
             'choice4' => 'Atlantic'
          ],
          'answer' => 'Pacific'
         ];

         $questions[1] = [
             'question' => 'What animal is in the Welsh flag?',
             'choices' => [
                'choice1' => 'Bear',
                'choice2' => 'Lion',
                'choice3' => 'Dragon',
                'choice4' => 'Sheep'
             ],
             'answer' => 'Dragon'
            ];

            $questions[2] = [
             'question' => 'How many continents are there in the world?',
             'choices' => [
                'choice1' => '10',
                'choice2' => '6',
                'choice3' => '7',
                'choice4' => '4'
             ],
             'answer' => '7'
            ];

            $questions[3] = [
             'question' => 'What’s the tallest mountain in the world?',
             'choices' => [
                'choice1' => 'K2',
                'choice2' => 'Everest',
                'choice3' => 'Kangchenjunga',
                'choice4' => 'Mount Kilimanjaro'
             ],
             'answer' => 'Everest'
            ];
            
            $questions[4] = [
             'question' => 'The Grand Canyon is found in which country?',
             'choices' => [
                'choice1' => 'USA',
                'choice2' => 'Australia',
                'choice3' => 'India',
                'choice4' => 'Russia'
             ],
             'answer' => 'USA'
            ];

            // Science

            $questions[5] = [
             'question' => 'What part of the plant conducts photosynthesis?',
             'choices' => [
                'choice1' => 'Branch',
                'choice2' => 'Leaf',
                'choice3' => 'Root',
                'choice4' => 'Trunk'
             ],
             'answer' => 'Leaf'
            ];

            $questions[6] = [
             'question' => 'What is the boiling point of water?',
             'choices' => [
                'choice1' => '25°C',
                'choice2' => '50°C',
                'choice3' => '75°C',
                'choice4' => '100°C'
             ],
             'answer' => '100°C'
            ];

            $questions[7] = [
             'question' => 'Which is the largest land animal?',
             'choices' => [
                'choice1' => 'Lion',
                'choice2' => 'Tiger',
                'choice3' => 'Elephant',
                'choice4' => 'Rhinoceros'
             ],
             'answer' => 'Elephant'
            ];

            $questions[8] = [
             'question' => '____ helps pump blood through the entire body',
             'choices' => [
                'choice1' => 'Lungs',
                'choice2' => 'Kidneys',
                'choice3' => 'Heart',
                'choice4' => 'Brain'
             ],
             'answer' => 'Heart'
            ];
            
            $questions[9] = [
             'question' => 'What are the three states of matter?',
             'choices' => [
                'choice1' => 'Solid, Liquid, and Gas',
                'choice2' => 'Solid, Liquid, and Mist',
                'choice3' => 'Rock, Liquid and Mist',
                'choice4' => 'Solid, Goo, and Steam '
             ],
             'answer' => 'Solid, Liquid, and Gas'
            ];

            $questions[10] = [
             'question' => 'If one boils water it will convert into ____',
             'choices' => [
                'choice1' => 'Mist',
                'choice2' => 'Steam',
                'choice3' => 'Clouds',
                'choice4' => 'Snow'
             ],
             'answer' => 'Steam'
            ];

            $questions[11] = [
             'question' => 'When you push something, you apply ____',
             'choices' => [
                'choice1' => 'Force',
                'choice2' => 'Acceleration',
                'choice3' => 'Mass',
                'choice4' => 'Compression'
             ],
             'answer' => 'Force'
            ];

            $questions[12] = [
             'question' => 'Which group of animals have scales?',
             'choices' => [
                'choice1' => 'Mammals',
                'choice2' => 'Amphibians',
                'choice3' => 'Reptiles',
                'choice4' => 'Birds'
             ],
             'answer' => 'Reptiles'
            ];

            $questions[13] = [
             'question' => 'Where does our food collect after we chew and swallow it?',
             'choices' => [
                'choice1' => 'Small intestine',
                'choice2' => 'Large intestine',
                'choice3' => 'Stomach',
                'choice4' => 'Liver'
             ],
             'answer' => 'Stomach'
            ];

            $questions[14] = [
             'question' => 'Which material from the following has the highest transparency?',
             'choices' => [
                'choice1' => 'Paper',
                'choice2' => 'Wood',
                'choice3' => 'Metal',
                'choice4' => 'Glass'
             ],
             'answer' => 'Glass'
            ];

            $questions[15] = [
             'question' => 'Which animal from the below list is best adapted to the desert?',
             'choices' => [
                'choice1' => 'Tiger',
                'choice2' => 'Cheetah',
                'choice3' => 'Camel',
                'choice4' => 'Deer'
             ],
             'answer' => 'Camel'
            ];

            $questions[16] = [
             'question' => 'What part of the skeletal system protects the brain?',
             'choices' => [
                'choice1' => 'Spine',
                'choice2' => 'Thigh',
                'choice3' => 'Pelvis',
                'choice4' => 'Skull'
             ],
             'answer' => 'Skull'
            ];

            // Math 
            $questions[17] = [
             'question' => 'What is the next prime number after 3?',
             'choices' => [
                'choice1' => '5',
                'choice2' => '6',
                'choice3' => '7',
                'choice4' => '8'
             ],
             'answer' => '5'
            ];

            $questions[18] = [
             'question' => 'What is the perimeter of a circle known as?',
             'choices' => [
                'choice1' => 'Circumference',
                'choice2' => 'Radius',
                'choice3' => 'Diameter',
                'choice4' => 'Area'
             ],
             'answer' => 'Circumference'
            ];

            $questions[19] = [
             'question' => 'What is the square root of 81?',
             'choices' => [
                'choice1' => '7',
                'choice2' => '8',
                'choice3' => '9',
                'choice4' => '11'
             ],
             'answer' => '9'
            ];

            $questions[20] = [
             'question' => 'What does the Roman Numeral C Represent?',
             'choices' => [
                'choice1' => '1000',
                'choice2' => '10',
                'choice3' => '100',
                'choice4' => '10000'
             ],
             'answer' => '100'
            ];

            $questions[20] = [
             'question' => 'What does a century represent?',
             'choices' => [
                'choice1' => '1000',
                'choice2' => '10',
                'choice3' => '100',
                'choice4' => '10000'
             ],
             'answer' => '100'
            ];

            $questions[21] = [
             'question' => 'Which is the largest number?',
             'choices' => [
                'choice1' => '-4',
                'choice2' => '-150',
                'choice3' => '3',
                'choice4' => '2.9845409'
             ],
             'answer' => '3'
            ];

            $questions[22] = [
             'question' => 'What is 25 + 23?',
             'choices' => [
                'choice1' => '45',
                'choice2' => '48',
                'choice3' => '52',
                'choice4' => '50'
             ],
             'answer' => '48'
            ];

            $questions[23] = [
             'question' => 'What is (25 +24) – 10?',
             'choices' => [
                'choice1' => '49',
                'choice2' => '43',
                'choice3' => '39',
                'choice4' => '38'
             ],
             'answer' => '39'
            ];

            $questions[24] = [
             'question' => 'What is 22 x 4?',
             'choices' => [
                'choice1' => '88',
                'choice2' => '44',
                'choice3' => '66',
                'choice4' => '98'
             ],
             'answer' => '88'
            ];

            $questions[25] = [
             'question' => 'What is 21 x 0?',
             'choices' => [
                'choice1' => '21',
                'choice2' => '0',
                'choice3' => '10.5',
                'choice4' => '42'
             ],
             'answer' => '0'
            ];

            $questions[26] = [
             'question' => 'What is 5² equal to?',
             'choices' => [
                'choice1' => '10',
                'choice2' => '25',
                'choice3' => '15',
                'choice4' => '50'
             ],
             'answer' => '25'
            ];

            $questions[27] = [
             'question' => 'What is the square root of 36?',
             'choices' => [
                'choice1' => '20',
                'choice2' => '6',
                'choice3' => '3',
                'choice4' => '13'
             ],
             'answer' => '6'
            ];

            $questions[28] = [
             'question' => 'What comes next in the Fibonacci sequence: 0, 1, 1, 2, 3, 5, 8, 13, __?',
             'choices' => [
                'choice1' => '26',
                'choice2' => '21',
                'choice3' => '19',
                'choice4' => '23'
             ],
             'answer' => '21'
            ];

            // History
            $questions[29] = [
             'question' => 'What type of fabric was made from flax?',
             'choices' => [
                'choice1' => 'Cotton',
                'choice2' => 'Wool',
                'choice3' => 'Nylon',
                'choice4' => 'Linen'
             ],
             'answer' => 'Linen'
            ];

            $questions[30] = [
             'question' => 'What did Egyptians mostly drink?',
             'choices' => [
                'choice1' => 'Water',
                'choice2' => 'Wine',
                'choice3' => 'Beer',
                'choice4' => 'Orange juice'
             ],
             'answer' => 'Beer'
            ];

            $questions[31] = [
             'question' => 'Why did the Egyptians want the Nile to flood?',
             'choices' => [
                'choice1' => 'It killed their enemies',
                'choice2' => 'It washed away diseases',
                'choice3' => 'It made the land fertile',
                'choice4' => 'It drowned the evil spirits'
             ],
             'answer' => 'It made the land fertile'
            ];

            $questions[32] = [
             'question' => 'What did the Egyptians use to make paper?',
             'choices' => [
                'choice1' => 'Flax',
                'choice2' => 'Wood',
                'choice3' => 'Papyrus',
                'choice4' => 'Cotton'
             ],
             'answer' => 'Papyrus'
            ];

            $questions[33] = [
             'question' => 'The city states joined together to fight which invaders?',
             'choices' => [
                'choice1' => 'Celts',
                'choice2' => 'Aztecs',
                'choice3' => 'Egyptians',
                'choice4' => 'Persians'
             ],
             'answer' => 'Persians'
            ];

            $questions[34] = [
             'question' => 'Who was the Greek leader that ruled all of Greece and conquered other lands?',
             'choices' => [
                'choice1' => 'Alfred the Great',
                'choice2' => 'Augustus',
                'choice3' => 'Aristotle',
                'choice4' => 'Alexander the Great'
             ],
             'answer' => 'Alexander the Great'
            ];

            $questions[35] = [
             'question' => 'What was the name of the people who lived in Crete around 3,000 BCE?',
             'choices' => [
                'choice1' => 'Persians',
                'choice2' => 'Maya',
                'choice3' => 'Minoans',
                'choice4' => 'Mycenaeans'
             ],
             'answer' => 'Minoans'
            ];

            $questions[36] = [
             'question' => 'At which battle were the Persians defeated?',
             'choices' => [
                'choice1' => 'Olympia',
                'choice2' => 'Marathon',
                'choice3' => 'Sparta',
                'choice4' => 'Troy'
             ],
             'answer' => 'Marathon'
            ];

            $questions[37] = [
             'question' => 'Who conquered the Greeks in 168 BCE?',
             'choices' => [
                'choice1' => 'Romans',
                'choice2' => 'Persians',
                'choice3' => 'British',
                'choice4' => 'Egyptians'
             ],
             'answer' => 'Romans'
            ];

            $questions[38] = [
             'question' => 'Which of these was NOT a Greek city state?',
             'choices' => [
                'choice1' => 'Athens',
                'choice2' => 'Sparta',
                'choice3' => 'Rome',
                'choice4' => 'Corinth'
             ],
             'answer' => 'Rome'
            ];

            $questions[39] = [
             'question' => 'What was the landscape like in Greece?',
             'choices' => [
                'choice1' => 'Desert',
                'choice2' => 'Tropical rain forest',
                'choice3' => 'Tundra',
                'choice4' => 'Mountainous'
             ],
             'answer' => 'Mountainous'
            ];

            $questions[40] = [
             'question' => 'In which year did the Greek Classical Period begin?',
             'choices' => [
                'choice1' => '3,000 BCE',
                'choice2' => '480 BCE',
                'choice3' => '480 CE',
                'choice4' => '2,000 CE'
             ],
             'answer' => '480 BCE'
            ];

            $questions[41] = [
             'question' => 'What does BCE mean?',
             'choices' => [
                'choice1' => 'Before the Common Era',
                'choice2' => 'Before Christ',
                'choice3' => 'Before Curried Eggs',
                'choice4' => 'Before Computer Era'
             ],
             'answer' => 'Before the Common Era'
            ];

      return $questions;
  }

     // Create and store questions
     public function createHardQuestions() {
      // Empty array
      $questions = [];

      // Geography
      $questions[0] = [
          'question' => 'What is the biggest ocean in the world?',
          'choices' => [
             'choice1' => 'Pacific',
             'choice2' => 'Indian',
             'choice3' => 'Arctic',
             'choice4' => 'Atlantic'
          ],
          'answer' => 'Pacific'
         ];

         $questions[1] = [
             'question' => 'What animal is in the Welsh flag?',
             'choices' => [
                'choice1' => 'Bear',
                'choice2' => 'Lion',
                'choice3' => 'Dragon',
                'choice4' => 'Sheep'
             ],
             'answer' => 'Dragon'
            ];

            $questions[2] = [
             'question' => 'How many continents are there in the world?',
             'choices' => [
                'choice1' => '10',
                'choice2' => '6',
                'choice3' => '7',
                'choice4' => '4'
             ],
             'answer' => '7'
            ];

            $questions[3] = [
             'question' => 'What’s the tallest mountain in the world?',
             'choices' => [
                'choice1' => 'K2',
                'choice2' => 'Everest',
                'choice3' => 'Kangchenjunga',
                'choice4' => 'Mount Kilimanjaro'
             ],
             'answer' => 'Everest'
            ];
            
            $questions[4] = [
             'question' => 'The Grand Canyon is found in which country?',
             'choices' => [
                'choice1' => 'USA',
                'choice2' => 'Australia',
                'choice3' => 'India',
                'choice4' => 'Russia'
             ],
             'answer' => 'USA'
            ];

            // Science

            $questions[5] = [
             'question' => 'What part of the plant conducts photosynthesis?',
             'choices' => [
                'choice1' => 'Branch',
                'choice2' => 'Leaf',
                'choice3' => 'Root',
                'choice4' => 'Trunk'
             ],
             'answer' => 'Leaf'
            ];

            $questions[6] = [
             'question' => 'What is the boiling point of water?',
             'choices' => [
                'choice1' => '25°C',
                'choice2' => '50°C',
                'choice3' => '75°C',
                'choice4' => '100°C'
             ],
             'answer' => '100°C'
            ];

            $questions[7] = [
             'question' => 'Which is the largest land animal?',
             'choices' => [
                'choice1' => 'Lion',
                'choice2' => 'Tiger',
                'choice3' => 'Elephant',
                'choice4' => 'Rhinoceros'
             ],
             'answer' => 'Elephant'
            ];

            $questions[8] = [
             'question' => '____ helps pump blood through the entire body',
             'choices' => [
                'choice1' => 'Lungs',
                'choice2' => 'Kidneys',
                'choice3' => 'Heart',
                'choice4' => 'Brain'
             ],
             'answer' => 'Heart'
            ];
            
            $questions[9] = [
             'question' => 'What are the three states of matter?',
             'choices' => [
                'choice1' => 'Solid, Liquid, and Gas',
                'choice2' => 'Solid, Liquid, and Mist',
                'choice3' => 'Rock, Liquid and Mist',
                'choice4' => 'Solid, Goo, and Steam '
             ],
             'answer' => 'Solid, Liquid, and Gas'
            ];

            $questions[10] = [
             'question' => 'If one boils water it will convert into ____',
             'choices' => [
                'choice1' => 'Mist',
                'choice2' => 'Steam',
                'choice3' => 'Clouds',
                'choice4' => 'Snow'
             ],
             'answer' => 'Steam'
            ];

            $questions[11] = [
             'question' => 'When you push something, you apply ____',
             'choices' => [
                'choice1' => 'Force',
                'choice2' => 'Acceleration',
                'choice3' => 'Mass',
                'choice4' => 'Compression'
             ],
             'answer' => 'Force'
            ];

            $questions[12] = [
             'question' => 'Which group of animals have scales?',
             'choices' => [
                'choice1' => 'Mammals',
                'choice2' => 'Amphibians',
                'choice3' => 'Reptiles',
                'choice4' => 'Birds'
             ],
             'answer' => 'Reptiles'
            ];

            $questions[13] = [
             'question' => 'Where does our food collect after we chew and swallow it?',
             'choices' => [
                'choice1' => 'Small intestine',
                'choice2' => 'Large intestine',
                'choice3' => 'Stomach',
                'choice4' => 'Liver'
             ],
             'answer' => 'Stomach'
            ];

            $questions[14] = [
             'question' => 'Which material from the following has the highest transparency?',
             'choices' => [
                'choice1' => 'Paper',
                'choice2' => 'Wood',
                'choice3' => 'Metal',
                'choice4' => 'Glass'
             ],
             'answer' => 'Glass'
            ];

            $questions[15] = [
             'question' => 'Which animal from the below list is best adapted to the desert?',
             'choices' => [
                'choice1' => 'Tiger',
                'choice2' => 'Cheetah',
                'choice3' => 'Camel',
                'choice4' => 'Deer'
             ],
             'answer' => 'Camel'
            ];

            $questions[16] = [
             'question' => 'What part of the skeletal system protects the brain?',
             'choices' => [
                'choice1' => 'Spine',
                'choice2' => 'Thigh',
                'choice3' => 'Pelvis',
                'choice4' => 'Skull'
             ],
             'answer' => 'Skull'
            ];

            // Math 
            $questions[17] = [
             'question' => 'What is the next prime number after 3?',
             'choices' => [
                'choice1' => '5',
                'choice2' => '6',
                'choice3' => '7',
                'choice4' => '8'
             ],
             'answer' => '5'
            ];

            $questions[18] = [
             'question' => 'What is the perimeter of a circle known as?',
             'choices' => [
                'choice1' => 'Circumference',
                'choice2' => 'Radius',
                'choice3' => 'Diameter',
                'choice4' => 'Area'
             ],
             'answer' => 'Circumference'
            ];

            $questions[19] = [
             'question' => 'What is the square root of 81?',
             'choices' => [
                'choice1' => '7',
                'choice2' => '8',
                'choice3' => '9',
                'choice4' => '11'
             ],
             'answer' => '9'
            ];

            $questions[20] = [
             'question' => 'What does the Roman Numeral C Represent?',
             'choices' => [
                'choice1' => '1000',
                'choice2' => '10',
                'choice3' => '100',
                'choice4' => '10000'
             ],
             'answer' => '100'
            ];

            $questions[20] = [
             'question' => 'What does a century represent?',
             'choices' => [
                'choice1' => '1000',
                'choice2' => '10',
                'choice3' => '100',
                'choice4' => '10000'
             ],
             'answer' => '100'
            ];

            $questions[21] = [
             'question' => 'Which is the largest number?',
             'choices' => [
                'choice1' => '-4',
                'choice2' => '-150',
                'choice3' => '3',
                'choice4' => '2.9845409'
             ],
             'answer' => '3'
            ];

            $questions[22] = [
             'question' => 'What is 25 + 23?',
             'choices' => [
                'choice1' => '45',
                'choice2' => '48',
                'choice3' => '52',
                'choice4' => '50'
             ],
             'answer' => '48'
            ];

            $questions[23] = [
             'question' => 'What is (25 +24) – 10?',
             'choices' => [
                'choice1' => '49',
                'choice2' => '43',
                'choice3' => '39',
                'choice4' => '38'
             ],
             'answer' => '39'
            ];

            $questions[24] = [
             'question' => 'What is 22 x 4?',
             'choices' => [
                'choice1' => '88',
                'choice2' => '44',
                'choice3' => '66',
                'choice4' => '98'
             ],
             'answer' => '88'
            ];

            $questions[25] = [
             'question' => 'What is 21 x 0?',
             'choices' => [
                'choice1' => '21',
                'choice2' => '0',
                'choice3' => '10.5',
                'choice4' => '42'
             ],
             'answer' => '0'
            ];

            $questions[26] = [
             'question' => 'What is 5² equal to?',
             'choices' => [
                'choice1' => '10',
                'choice2' => '25',
                'choice3' => '15',
                'choice4' => '50'
             ],
             'answer' => '25'
            ];

            $questions[27] = [
             'question' => 'What is the square root of 36?',
             'choices' => [
                'choice1' => '20',
                'choice2' => '6',
                'choice3' => '3',
                'choice4' => '13'
             ],
             'answer' => '6'
            ];

            $questions[28] = [
             'question' => 'What comes next in the Fibonacci sequence: 0, 1, 1, 2, 3, 5, 8, 13, __?',
             'choices' => [
                'choice1' => '26',
                'choice2' => '21',
                'choice3' => '19',
                'choice4' => '23'
             ],
             'answer' => '21'
            ];

            // History
            $questions[29] = [
             'question' => 'What type of fabric was made from flax?',
             'choices' => [
                'choice1' => 'Cotton',
                'choice2' => 'Wool',
                'choice3' => 'Nylon',
                'choice4' => 'Linen'
             ],
             'answer' => 'Linen'
            ];

            $questions[30] = [
             'question' => 'What did Egyptians mostly drink?',
             'choices' => [
                'choice1' => 'Water',
                'choice2' => 'Wine',
                'choice3' => 'Beer',
                'choice4' => 'Orange juice'
             ],
             'answer' => 'Beer'
            ];

            $questions[31] = [
             'question' => 'Why did the Egyptians want the Nile to flood?',
             'choices' => [
                'choice1' => 'It killed their enemies',
                'choice2' => 'It washed away diseases',
                'choice3' => 'It made the land fertile',
                'choice4' => 'It drowned the evil spirits'
             ],
             'answer' => 'It made the land fertile'
            ];

            $questions[32] = [
             'question' => 'What did the Egyptians use to make paper?',
             'choices' => [
                'choice1' => 'Flax',
                'choice2' => 'Wood',
                'choice3' => 'Papyrus',
                'choice4' => 'Cotton'
             ],
             'answer' => 'Papyrus'
            ];

            $questions[33] = [
             'question' => 'The city states joined together to fight which invaders?',
             'choices' => [
                'choice1' => 'Celts',
                'choice2' => 'Aztecs',
                'choice3' => 'Egyptians',
                'choice4' => 'Persians'
             ],
             'answer' => 'Persians'
            ];

            $questions[34] = [
             'question' => 'Who was the Greek leader that ruled all of Greece and conquered other lands?',
             'choices' => [
                'choice1' => 'Alfred the Great',
                'choice2' => 'Augustus',
                'choice3' => 'Aristotle',
                'choice4' => 'Alexander the Great'
             ],
             'answer' => 'Alexander the Great'
            ];

            $questions[35] = [
             'question' => 'What was the name of the people who lived in Crete around 3,000 BCE?',
             'choices' => [
                'choice1' => 'Persians',
                'choice2' => 'Maya',
                'choice3' => 'Minoans',
                'choice4' => 'Mycenaeans'
             ],
             'answer' => 'Minoans'
            ];

            $questions[36] = [
             'question' => 'At which battle were the Persians defeated?',
             'choices' => [
                'choice1' => 'Olympia',
                'choice2' => 'Marathon',
                'choice3' => 'Sparta',
                'choice4' => 'Troy'
             ],
             'answer' => 'Marathon'
            ];

            $questions[37] = [
             'question' => 'Who conquered the Greeks in 168 BCE?',
             'choices' => [
                'choice1' => 'Romans',
                'choice2' => 'Persians',
                'choice3' => 'British',
                'choice4' => 'Egyptians'
             ],
             'answer' => 'Romans'
            ];

            $questions[38] = [
             'question' => 'Which of these was NOT a Greek city state?',
             'choices' => [
                'choice1' => 'Athens',
                'choice2' => 'Sparta',
                'choice3' => 'Rome',
                'choice4' => 'Corinth'
             ],
             'answer' => 'Rome'
            ];

            $questions[39] = [
             'question' => 'What was the landscape like in Greece?',
             'choices' => [
                'choice1' => 'Desert',
                'choice2' => 'Tropical rain forest',
                'choice3' => 'Tundra',
                'choice4' => 'Mountainous'
             ],
             'answer' => 'Mountainous'
            ];

            $questions[40] = [
             'question' => 'In which year did the Greek Classical Period begin?',
             'choices' => [
                'choice1' => '3,000 BCE',
                'choice2' => '480 BCE',
                'choice3' => '480 CE',
                'choice4' => '2,000 CE'
             ],
             'answer' => '480 BCE'
            ];

            $questions[41] = [
             'question' => 'What does BCE mean?',
             'choices' => [
                'choice1' => 'Before the Common Era',
                'choice2' => 'Before Christ',
                'choice3' => 'Before Curried Eggs',
                'choice4' => 'Before Computer Era'
             ],
             'answer' => 'Before the Common Era'
            ];

      return $questions;
  }
 }