<?php
/**
 * Page to display teachers and provide options to add, edit and delete 
 * them from the system
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/listTeachers', function(Request $request, Response $response) use ($app) {
  // Check to see if session logged in is set
  if (!isset($_SESSION['is_logged_in'])) {
    // Navigate to login page with error
    return $response->withRedirect($this->router->pathFor('Login', ['err' => 'accessErr']));
  } else {
    // Check access
    // Check it is management account
    $management_access = checkManagementAccess($app);

    // Check it is management account
    $admin_access = checkAdminAccess($app);

    if ($management_access == false) {
      // Navigate to player homepage
      return $response->withRedirect($this->router->pathFor('PlayerHomepage'));
    } else if ($admin_access == false) {
      // Navigate to player homepage
      return $response->withRedirect($this->router->pathFor('ManagementHomepage'));
    }
  }
  
  // Check it is management account
  checkManagementAccess($app, $response);

  // Check it is management account
  checkAdminAccess($app, $response);
  
  // Check to see if logged in
  $logged_in = displayHeaderButton();  
  
  return $this->view->render($response,
    'listTeachers.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'list_data' => LISTDATA,
        'page_heading' => 'Teachers',
        'is_logged_in' => $logged_in,
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'homepage_link' => '/football_trivia_game/public/managementHomepage',
        'signin' => 'Sign In',
        'signout' => 'Sign Out',
    ]);
 })->setName('ListTeachers');