<?php
/**
 * Page to display questions and provide options to add, edit and delete 
 * them from the system
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/listQuestions', function(Request $request, Response $response) use ($app) {
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
  
  return $this->view->render($response,
    'listQuestions.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'list_data' => LISTDATA,
        'page_heading' => 'Students',
        'is_logged_in' => $logged_in,
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'homepage_link' => '/football_trivia_game/public/managementHomepage',
        'signin' => 'Sign In',
        'signout' => 'Sign Out',
    ]);
 })->setName('ListQuestions');