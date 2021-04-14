<?php
/**
 * Add Teacher Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/addTeacher[/{err}]', function(Request $request, Response $response, $args) use ($app) {
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
        'action' => '/football_trivia_game/public/addTeacherProcess',
        'homepage_link' => '/football_trivia_game/public/managementHomepage',
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'is_logged_in' => $logged_in,
        'err' => $err_message,
        'signout' => 'Sign Out',
        'page_heading' => 'Add Teacher',
        'heading' => 'Add Teacher',
        'add_content' => 'Fill in teachers details here to add them to the system',
        'fname' => 'First Name',
        'surname' => 'Surname',
        'email' => 'Email',
        'cemail' => 'Confirm Email',
        'add' => 'Add Teacher!',
        'admin' => true
    ]);
 })->setName('AddTeacher');

 function addFormError($args) {
  // Empty err message
  $err_message = '';
  
  if (!empty($args)) {
    if ($args['err'] == 'storeErr') {
      $err_message = 'Data was not stored, please submit form again';
    } else if ($args['err'] == 'emailErr') {
      $err_message = "Email providing login details to teacher was not able to send";
    } else if ($args['err'] == 'storeSuccess') {
      $err_message = 'Successfully added to the system';
    } else if ($args['err'] == 'accessErr') {
      // Set error message to inform user they have to be logged in to gain access to the website
      $err_message = 'Must be logged in to gain access';
    } else if ($args['err'] == 'existErr') {
      $err_message = 'Username already exists please try another one';
    } else if ($args['err'] == 'passMatchErr') {
      $err_message = 'Passwords do not match!';
    } else if ($args['err'] == 'passErr') {
      $err_message = 'Incorrect password entered!';
    } else if ($args['err'] == 'deleteErr') {
      $err_message = 'Error Deleting your account!';
    }
  }

  return $err_message;
 }