<?php
/**
 * Add Teacher Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/addTeacher', function(Request $request, Response $response) use ($app) {
   // Check to see if session logged in is set
  if (!isset($_SESSION['is_logged_in'])) {
    // Navigate to login page with error
    return $response->withRedirect($this->router->pathFor('Login', ['err' => 'accessErr']));
  }
   return $this->view->render($response,
    'teacherForm.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'action' => '/football_trivia_game/public/addTeacherProcess',
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'page_heading' => 'Add Teacher',
        'heading' => 'Add Teacher',
        'add_teacher_content' => 'Fill in teachers details here to add them to the system',
        'teacher_fname' => 'First Name',
        'teacher_surname' => 'Surname',
        'teacher_email' => 'Email',
        'teacher_cemail' => 'Confirm Email',
    ]);
 });