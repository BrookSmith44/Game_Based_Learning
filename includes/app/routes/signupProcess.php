<?php
/**
 * Login Page Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/signupProcess', function(Request $request, Response $response) use ($app) {
    // Get form values
    $form_values = $request->getParsedBody();

    // Call function to clean form values
    $cleaned_values = cleanFormValues($app, $form_values);


 });

 // Functions
 // Function to clean form values
 function cleanFormValues($app, $form_values) {
  // empty array for cleaned values
  $cleaned_values = [];

  // Get validator container
  $validator = $app->getContainer()->get('validator');

  $cleaned_values['fname'] = $validator->sanitizeString($form_values['signup_fname']);
  $cleaned_values['surname'] = $validator->sanitizeString($form_values['signup_surname']);
  $cleaned_values['dob'] = $validator->sanitizeDate($form_values['signup_dob']);
  $cleaned_values['email'] = $validator->sanitizeEmail($form_values['signup_email']);
  $cleaned_values['cemail'] = $validator->sanitizeEmail($form_values['signup_cemail']);
  $cleaned_values['pass'] = $validator->sanitizeString($form_values['signup_pass']);
  $cleaned_values['cpass'] = $validator->sanitizeString($form_values['signup_cpass']);
  
  return $cleaned_values;
 }

