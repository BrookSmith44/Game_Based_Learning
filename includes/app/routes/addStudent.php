<?php
/**
 * Add Student Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/addStudent[/{err}]', function(Request $request, Response $response, $args) use ($app) {
   // Check to see if session logged in is set
  if (!isset($_SESSION['is_logged_in'])) {
    // Navigate to login page with error
    return $response->withRedirect($this->router->pathFor('Login', ['err' => 'accessErr']));
  }

   // Empty err message
   $err_message = addFormError($args);

    // Check to see if logged in
    $logged_in = displayHeaderButton();


   return $this->view->render($response,
    'addForm.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'list_data' => LISTDATA,
        'action' => '/football_trivia_game/public/addStudentProcess',
        'homepage_link' => '/football_trivia_game/public/managementHomepage',
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'is_logged_in' => $logged_in,
        'err' => $err_message,
        'signout' => 'Sign Out',
        'page_heading' => 'Add Student',
        'heading' => 'Add Student',
        'add_content' => 'Fill in students details here to add them to the system',
        'fname' => 'First Name',
        'surname' => 'Surname',
        'email' => 'Email',
        'cemail' => 'Confirm Email',
        'add' => 'Add Student!',
        'admin' => false
    ]);
 })->setName('AddStudent');