<?php
/**
 * Management Homepage Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/managementHomepage', function(Request $request, Response $response) use ($app) {
  
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

  // Check wheter user is admin
  $admin = getAdmin($app);

  // Check to see if logged in
  $logged_in = displayHeaderButton(); 
  
  return $this->view->render($response,
    'managementHomepage.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'list_data' => LISTDATA,
        'user_team' => USERTEAM,
        'page_heading' => 'Management Homepage',
        'is_logged_in' => $logged_in,
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'homepage_link' => '/football_trivia_game/public/ManagentHomepage',
        'signin' => 'Sign In',
        'signout' => 'Sign Out',
        'admin' => $admin
    ]);
 })->setName('ManagementHomepage');

 function getAdmin($app) {
   // Get session wrapper
  $session_wrapper = $app->getContainer()->get('sessionWrapper');
  // Get admin session variable
  $admin = $session_wrapper->getSessionVar('admin');

  return $admin;
 }