<?php
/**
 * Add Teacher Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/addTeacher', function(Request $request, Response $response) use ($app) {
   return $this->view->render($response,
    'teacherForm.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'action' => '/football_trivia_game/public/addTeacherProcess',
        'page_heading' => 'Add Teacher',
        'heading' => 'Add Teacher',
        'add_teacher_content' => 'Fill in teachers details here to add them to the system',
        'teacher_fname' => 'First Name',
        'teacher_surname' => 'Surname',
        'teacher_email' => 'Email',
        'teacher_cemail' => 'Confirm Email',
    ]);
 });