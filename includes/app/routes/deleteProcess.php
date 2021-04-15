<?php
/**
 * Delete process, use route parameters to decipher which table and row it is deleting
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/deleteProcess/{table}/{id}', function(Request $request, Response $response, $args) use ($app) {

    $redirect = handleDeleteRequest($app, $args);
   
   // Navigate to next page
   return $response->withRedirect($this->router->pathFor($redirect['page'], ['table' => $redirect['table'], 'id' => $redirect['id'], 'err' => $redirect['err']]));

 });

 function handleDeleteRequest($app, $args) {
    // Get containers
    $student_model = $app->getContainer()->get('studentModel');
    $teacher_model = $app->getContainer()->get('teacherModel');
    $question_model = $app->getContainer()->get('questionModel');

    // Empty variable for redirect
    $redirect = [];

    // switch case to decide which table to delete from 
    switch ($args['table']) {
        case 'student':
            // Set to student model
            $model = $student_model;
            break;
        case 'teacher':
            // Set to teacher model
            $model = $teacher_model;
            break;
        case 'question':
            // Set to question model
            $model = $question_model;
            break;
    }

    // Call function to delete data and return store result
    $store_result = deleteData($app, $model, $args);

    if ($store_result == true) {
        $redirect['page'] = 'List' . ucfirst($args['table']) . 's';
    } else {
        $redirect['page'] = 'DisplayData';
        $redirect['table'] = $args['table'];
        $redirect['id'] = $args['id'];
        $redirect['err'] = 'storeErr';
    }

    return $redirect;
 }


function deleteData($app, $model, $args) {
    // Set properties
    setProperties($app, $model);

    // set empty variable for store result
    $store_result = [];

    //  Provie Unique id to delete from - username for student and teacher or question id for question
    switch ($args['table']) {
        case 'student';
        case 'teacher':
            // Set username property
            $model->setUsername($args['id']);
            break;
        case 'question':
            // Set id property
            $model->setQuestionId($args['id']);
            break;
    }

    // Call method to delete row
    $store_result = $model->deleteData();

    return $store_result;
}