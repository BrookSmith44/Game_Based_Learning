<?php
/**
 * update account detials process route
 * 
 * Route take the details from the form and update the account data
 * To change password will have to authenticate old password before creating new
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/updateAccountProcess/{operation}', function(Request $request, Response $response, $args) use ($app) {

  // Empty array for redirect
  $redirect = []; 

  $form_data = $request->getParsedBody();
  
  // Clean data
  $cleaned_values = cleanUpdateData($app, $form_data, $args);

  // Call function to update password
  $redirect = handleAccountData($app, $cleaned_values, $args);
 
  // Navigate to next page
   return $response->withRedirect($this->router->pathFor($redirect['page'], ['err' => $redirect['err']]));
 });

 // Clean form data
 function cleanUpdateData($app, $form_data, $args) {
   // Get validator
   $validator = $app->getContainer()->get('validator');

   // Empty array for cleaned values
   $cleaned_values = [];

   // Switch statement to clean values for based on what route parameter was used
   switch ($args['operation']) {
     case 'personalDetails': 
        // Sanitize stringd
        $cleaned_values['username'] = $validator->sanitizeString($form_data['update_username']);
        $cleaned_values['fname'] = $validator->sanitizeString($form_data['update_fname']);
        $cleaned_values['surname'] = $validator->sanitizeString($form_data['update_surname']);
        $cleaned_values['email'] = $validator->sanitizeString($form_data['update_email']);
        break;
      case 'password':
        // Sanitize stringd
        $cleaned_values['old_pass'] = $validator->sanitizeString($form_data['update_old_passs']);
        $cleaned_values['new_pass'] = $validator->sanitizeString($form_data['update_pass']);
        $cleaned_values['confirm_pass'] = $validator->sanitizeString($form_data['update_cpass']);
        break;
   }

   return $cleaned_values;
 }

 // Function to update/ delete account data
 function handleAccountData($app, $cleaned_values, $args) {
    // Get containers
    $user_model = $app->getContainer()->get('userModel');
    $team_model = $app->getContainer()->get('userModel');
    $db = $app->getContainer()->get('dbh');
    $db_config = $app->getContainer()->get('settings');
    $db_connection_settings = $db_config['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');

    // Set properties
    $user_model->setDb($db);
    $user_model->setDbConnectionSettings($db_connection_settings);
    $user_model->setSessionWrapper($session_wrapper);
    $user_model->setSQLQueries($sql_queries);
    $user_model->setLogger($logger);

    // get account type
    $account_type = $session_wrapper->getSessionVar('account_type');

    // Set accoun type as user property
    $user_model->setAccountType($account_type);
    
    // Set empty array for redirect data 
    $redirect = [];

    switch ($args['operation']) {
      case 'personalDetails': 
        // Call function to check if username already exists
        $check = checkUsername($app, $cleaned_values['username']);
        // If username does not exist update details
        if ($check == 0) {
          $store_result = updatePersonalDetails($app, $user_model, $cleaned_values);

          // Get store result and create redirect
          if ($store_result == true) {
            $store_result = $user_model->setSessionData();
            if ($store_result == true) {
              $redirect['page'] = 'MyAccount';
              $redirect['err'] = 'storeSuccess'; 
            } else {
              $redirect['page'] = 'MyAccount';
              $redirect['err'] = 'storeErr'; 
            }
          } else {
            $redirect['page'] = 'MyAccount';
            $redirect['err'] = 'storeErr'; 
          }

        } else {
          $redirect['page'] = 'MyAccount';
          $redirect['err'] = 'existErr'; 
        }
        break;
      case 'password':
        // Check passwords match
        if ($cleaned_values['new_pass'] == $cleaned_values['confirm_pass']) {
          // Call function to update password
          $store_result = updatePassword($app, $user_model, $cleaned_values);

          if ($store_result['authenticate'] == false) {
            $redirect['page'] = 'MyAccount';
            $redirect['err'] = 'passErr'; 
          } else if ($store_result['update'] == false) {
            $redirect['page'] = 'MyAccount';
            $redirect['err'] = 'storeErr'; 
          } else {
              $redirect['page'] = 'MyAccount';
              $redirect['err'] = 'storeSuccess';
          }
        } else {
          $redirect['page'] = 'MyAccount';
          $redirect['err'] = 'passMatchErr'; 
        }
        break;
      case 'delete':
        $store_result = $user_model->deleteAccount();

        if($store_result == true) {
          $user_model->destroySessionVar();
          $team_model->destroySessionVar();
          $redirect['page'] = 'Homepage';
          $redirect['err'] = ''; 
        } else {
          $redirect['page'] = 'MyAccount';
          $redirect['err'] = 'deleteErr'; 
        }
        break;
    }
 return $redirect;
 }

 // Encrypt string
 function encryptString($app, $string) {
    // Get Encryption containers
    // Get lib sodium container
    $libsodium = $app->getContainer()->get('libSodiumWrapper');
    // Get base64 container
    $base64 = $app->getContainer()->get('base64Wrapper');

    // Set empty array for encrypted data
    $encrypted_data = [];

    // Encrypt string
    $encrypted_string['string_and_nonce'] = $libsodium->encryption($string);
    
    // Encode string
    $encrypted_data = $base64->encode($encrypted_string['string_and_nonce']['nonce_and_encrypted_string']);

    return $encrypted_data;
 }

 // function to update personal details
 function updatePersonalDetails($app, $user_model, $cleaned_values) {
    // Store encrypted data
    $encrypted_data['fname'] = encryptString($app, $cleaned_values['fname']);
    $encrypted_data['surname'] = encryptString($app, $cleaned_values['surname']);
    $encrypted_data['email'] = encryptString($app, $cleaned_values['email']);

    // Set user properties
    $user_model->setUsername($cleaned_values['username']);
    $user_model->setFname($encrypted_data['fname']);
    $user_model->setSurname($encrypted_data['surname']);
    $user_model->setEmail($encrypted_data['email']);

    // Update Personal Details
    $store_result = $user_model->updateUserDetails();

    return $store_result;
 }

 // function to update password
 function updatePassword($app, $user_model, $cleaned_values) {
    // Get containers
    $libsodium = $app->getContainer()->get('libSodiumWrapper');
    $base64 = $app->getContainer()->get('base64Wrapper');
    $bycrypt = $app->getContainer()->get('bycryptWrapper');

    // Set properties
    $user_model->setLibSodiumWrapper($libsodium);
    $user_model->setBase64Wrapper($base64);
    $user_model->setBycrypt($bycrypt);

    // Set user model password property
    $user_model->setPassword($cleaned_values['old_pass']);
  
    // Authenticate old password before updating
    $store_result = $user_model->processPasswordUpdate($cleaned_values['new_pass']);

    return $store_result;
 }