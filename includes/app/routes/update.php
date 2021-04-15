<?php

// Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;
 
 $app->get('/update/{table}/{id}[/{err}]', function(Request $request, Response $response, $args) use ($app) {
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
  
  // Empty variable for table
  $table = '';

  switch ($args['table']) {
      case 'student':
        $table = 'Student';
        break;
    case 'teacher':
        $table = 'Teacher';
        // Check it is management account
        $admin_access = checkAdminAccess($app);
        if ($admin_access == false) {
            // Navigate to player homepage
            return $response->withRedirect($this->router->pathFor('ManagementHomepage'));
          }
        break;
    case 'question':
        $table = 'Question';
        break;
  }
    // Check wheter user is admin
    $admin = getAdmin($app);

    // Empty err message
    $err_message = addFormError($args);

    // Check to see if logged in
    $logged_in = displayHeaderButton();

    // Call function to get data
    $data = displayData($app, $args);

    $admin = false;

    if ($data['admin'] == 'Y') {
        $admin = true;
    }

    return $this->view->render($response,
    'updateForm.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'list_data' => LISTDATA,
        'action' => '/football_trivia_game/public/updateProcess/' . $args['table'] . '/' . $args['id'],
        'action_back' => '/football_trivia_game/public/list' . $table . 's',
        'homepage_link' => '/football_trivia_game/public/managementHomepage',
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'is_logged_in' => $logged_in,
        'err' => $err_message,
        'signout' => 'Sign Out',
        'page_heading' => 'Update ' . $table,
        'heading' => 'Update ' . $table,
        'add_content' => 'Update details',
        'table' => $table,
        'username' => $data['username'],
        'fname' => $data['fname'],
        'surname' => $data['surname'],
        'email' => $data['email'],
        'admin' => $admin,
        'question' => $data['question'],
        'choice1' => $data['choice1'],
        'choice2' => $data['choice2'],
        'choice3' => $data['choice3'],
        'choice4' => $data['choice4'],
        'answer' => $data['answer'],
        'difficulty' => $data['difficulty'],
        'subject' => $data['subject']
    ]);
 })->setName('Update');