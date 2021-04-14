<?php

// Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;
 
 $app->get('/update/{table}/{id}/{operation}', function(Request $request, Response $response) use ($app) {
    
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
 })->setName('ManagementProcess');