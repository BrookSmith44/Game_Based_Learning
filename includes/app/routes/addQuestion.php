<?php
/**
 * Add question Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/addQuestion[/{err}]', function(Request $request, Response $response, $args) use ($app) {
   // Check to see if session logged in is set
  if (!isset($_SESSION['is_logged_in'])) {
    // Navigate to login page with error
    return $response->withRedirect($this->router->pathFor('Login', ['err' => 'accessErr']));
  }

  if (!empty($args)) {
      if ($args['err'] == 'success') {
          $success = 'Question stored successfully';
      } else if ($args['err'] == 'storeErr') {
          $err_message = 'Question was not able to be stored at this time, please try again later';
      }
  }

  $content = 'Fill in questions details here to add them to the system';
  $fyi = 'Each subject must have at least 25 questions in them before the student is able to access it';

   // Empty err message
   $err_message = addFormError();

    // Check to see if logged in
    $logged_in = displayHeaderButton();


   return $this->view->render($response,
    'addQuestionForm.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'list_data' => LISTDATA,
        'action' => '/football_trivia_game/public/addQuestionProcess',
        'homepage_link' => '/football_trivia_game/public/managementHomepage',
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'is_logged_in' => $logged_in,
        'err' => $err_message,
        'success' => $success,
        'signout' => 'Sign Out',
        'page_heading' => 'Add Question',
        'heading' => 'Add Question',
        'add_content' => $content,
        'fyi' => $fyi,
        'question' => 'Question',
        'choice1' => 'First Choice',
        'choice2' => 'Second Choice',
        'choice3' => 'Third Choice',
        'choice4' => 'Fourth Choice',
        'answer' => 'Answer', 
        'add' => 'Add Question'
    ]);
 })->setName('AddQuestion');