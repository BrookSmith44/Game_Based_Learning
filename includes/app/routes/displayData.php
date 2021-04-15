<?php
/**
 * display detials route
 * 
 * Route to fetch the data from the 
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/display/{table}/{id}', function(Request $request, Response $response, $args) use ($app) {
    // Check to see if session logged in is set
  if (!isset($_SESSION['is_logged_in'])) {
    // Navigate to login page with error
    return $response->withRedirect($this->router->pathFor('Login', ['err' => 'accessErr']));
  } else {
    // Check access
    // Check it is management account
    $management_access = checkManagementAccess($app);

    if ($management_access == false) {
      // Navigate to player homepage
      return $response->withRedirect($this->router->pathFor('PlayerHomepage'));
    } 
  }
    // Check to see if logged in
    $logged_in = displayHeaderButton();
  
  // Set empty variable for account type  
    $table = '';
    
    // Switch case for different tables
    switch ($args['table']) {
      case 'teacher':
        $table = 'Teacher';
        break;
      case 'student':
        $table = 'Student';
        break;
      case 'question':
        $table = 'Question';
        break;
    }

    // Call function to display
    $data = displayData($app, $args);
  
    return $this->view->render($response,
    'display.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'list_data' => LISTDATA,
        'page_heading' => $table,
        'is_logged_in' => $logged_in,
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'homepage_link' => '/football_trivia_game/public/managementHomepage',
        'signin' => 'Sign In',
        'signout' => 'Sign Out',
        'heading' => $table,
        'action_back' => '/football_trivia_game/public/list' . $table . 's',
        'action_update' => '/football_trivia_game/public/update/' . $args['table'] . '/' . $args['id'],
        'table' => $table,
        'name' => ' ' . $data['name'],
        'username' => ' ' . $data['username'],
        'email' => ' ' . $data['email'],
        'teacher_name' => ' ' . $data['teacher'],
        'da' => ' ' . $data['date_added'],
        'admin' => $data['admin'], 
        'question' => ' ' . $data['question'],
        'choice1' => ' ' . $data['choice1'],
        'choice2' => ' ' . $data['choice2'],
        'choice3' => ' ' . $data['choice3'],
        'choice4' => ' ' . $data['choice4'],
        'answer' => ' ' . $data['answer'],
        'difficulty' => ' ' . $data['difficulty'],
        'subject' => ' ' . $data['subject']
    ]);
 })->setName('DisplayData');

 // Function to display student, teachers or questions
 function displayData($app, $args) {
  $student_model = $app->getContainer()->get('studentModel');
  $team_model = $app->getContainer()->get('teamModel');
  $teacher_model = $app->getContainer()->get('teacherModel');
  $question_model = $app->getContainer()->get('questionModel');
   // Empty varble for returned data
   $data = [];

    switch ($args['table']) {
      case 'student': 
        // Call function to retrieve student data
        $data = getData($app, $student_model, $args);
        break;
      case 'teacher':
        // Call function to retrieve student data
        $data = getData($app, $teacher_model, $args);
        break;
      case 'question':
        // Call function to retrieve student data
        $data = getData($app, $question_model, $args);
        break;
    }

 return $data;
 }

 function getData($app, $model, $args) {
    // Get containers
    $db = $app->getContainer()->get('dbh');
    $db_config = $app->getContainer()->get('settings');
    $db_connection_settings = $db_config['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');

    // empty variable for data
    $data = [];

    // Set properties
    $model->setDb($db);
    $model->setDbConnectionSettings($db_connection_settings);
    $model->setSessionWrapper($session_wrapper);
    $model->setSQLQueries($sql_queries);
    $model->setLogger($logger);

    switch ($args['table']) {
      case 'student';
      case 'teacher':
        // Set username property
        $model->setUsername($args['id']);

        // Get data
        $results = $model->getData();

        // Call function to set display variables
        $data = setDisplayVariables($app, $results, $args);
        break;
      case 'question':
        // Set question id property
        $model->setQuestionId($args['id']);

        // Get data
        $results = $model->getData();

        // Call function to set display variables
        $data = setDisplayVariables($app, $results, $args);  
        break;

    }

    return $data;
 }

 function decryptString($app, $string) {
   // Get encryption containers
   $libsodium = $app->getContainer()->get('libSodiumWrapper');
   $base64 = $app->getContainer()->get('base64Wrapper');

   $decrypted_string = $libsodium->decryption(
     $base64, 
     $string
   );

   return $decrypted_string;
 }

 function setDisplayVariables($app, $results, $args) {
  // Set empty variables
  $data = [];
  $data['fname'] = '';
  $data['surname'] = '';
  $data['name'] = '';
  $data['username'] = '';
  $data['email'] = '';
  $data['date_added'] = '';
  $data['teacher'] = '';
  $data['admin'] = '';
  $data['question'] = '';
  $data['choice1'] = '';
  $data['choice2'] = '';
  $data['choice3'] = '';
  $data['choice4'] = '';
  $data['answer'] = '';
  $data['difficulty'] = '';
  $data['subject'] = '';

  // Switch statement to set variables corresponding with data to be displayed
  switch ($args['table']) {
    case 'student';
    case 'teacher':
        // Decrypt data
        $data['fname'] = decryptString($app, $results['account_fname']);
        $data['surname'] = decryptString($app, $results['account_surname']);
        $data['name'] = $data['fname'] . ' ' . $data['surname'];
        $data['username'] = $results['account_username'];
        $data['email'] = decryptString($app, $results['account_email']);
        $data['date_added'] = $results['date_added'];

        //  Decrypt teacher name if student
        if ($args['table'] == 'student') {
          $data['teacher'] = decryptString($app, $results['teacher_name']);
        } else if ($args['table'] == 'teacher') {
          // Set admin
          $data['admin'] = $results['admin'];
        }
      break;
    case 'question':
      $data['question'] = $results['question'];
      $data['choice1'] = $results['choice1'];
      $data['choice2'] = $results['choice2'];
      $data['choice3'] = $results['choice3'];
      $data['choice4'] = $results['choice4'];
      $data['answer'] = $results['answer'];
      $data['difficulty'] = $results['difficulty'];
      $data['subject'] = $results['subject'];
      $data['teacher'] = $results['teacher_name'];
      $data['date_added'] = $results['date_added'];
      break;
  }

  return $data;
 }