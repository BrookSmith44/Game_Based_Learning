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

    // Hash password
    $cleaned_values['hashed_pass'] = hashPassword($app, $cleaned_values['pass']);

    // Call function to encrypt form data
    $encrypted = encrypt($app, $cleaned_values);

    // Call function to encode encrypted data
    $encoded = encode($app, $encrypted);

    // Store data in database
    $store_result = storeData($app, $encoded, $cleaned_values);

 });

 // Functions
 // Function to clean form values
 function cleanFormValues($app, $form_values): array {
  // empty array for cleaned values
  $cleaned_values = [];

  // Get validator container
  $validator = $app->getContainer()->get('validator');

  $cleaned_values['username'] = $validator->sanitizeString($form_values['signup_username']);
  $cleaned_values['fname'] = $validator->sanitizeString($form_values['signup_fname']);
  $cleaned_values['surname'] = $validator->sanitizeString($form_values['signup_surname']);
  $cleaned_values['dob'] = $validator->sanitizeDate($form_values['signup_dob']);
  $cleaned_values['email'] = $validator->sanitizeEmail($form_values['signup_email']);
  $cleaned_values['cemail'] = $validator->sanitizeEmail($form_values['signup_cemail']);
  $cleaned_values['pass'] = $validator->sanitizeString($form_values['signup_pass']);
  $cleaned_values['cpass'] = $validator->sanitizeString($form_values['signup_cpass']);
  
  return $cleaned_values;
 }

 // Function to encrypt form values
function encrypt($app, $cleaned_values): array {
    // Get lib sodium container
    $libsodium = $app->getContainer()->get('libSodiumWrapper');
    
    // Empty array for encrypted data
    $encrypted_data = [];

    // Encrypt Data
    $encrypted_data['fname_and_nonce'] = $libsodium->encryption($cleaned_values['fname']);
    $encrypted_data['surname_and_nonce'] = $libsodium->encryption($cleaned_values['surname']);
    $encrypted_data['email_and_nonce'] = $libsodium->encryption($cleaned_values['email']);
    $encrypted_data['pass_and_nonce'] = $libsodium->encryption($cleaned_values['hashed_pass']);

    // Return encrypted data
    return $encrypted_data;
}

// Function to encode encrypted data 
function encode ($app, $encrypted_data): array {
    // Get base64 container
    $base64 = $app->getContainer()->get('base64Wrapper');

    // Empty array for encoded data
    $encoded_data = [];

    // Encode data
    $encoded_data['fname'] = $base64->encode($encrypted_data['fname_and_nonce']['nonce_and_encrypted_string']);
    $encoded_data['surname'] = $base64->encode($encrypted_data['surname_and_nonce']['nonce_and_encrypted_string']);
    $encoded_data['email'] = $base64->encode($encrypted_data['email_and_nonce']['nonce_and_encrypted_string']);
    $encoded_data['pass'] = $base64->encode($encrypted_data['pass_and_nonce']['nonce_and_encrypted_string']);

    return $encoded_data;
}

// Function to hash password
function hashPassword($app, $pass): string {
    // Get bycrypt wrapper
    $bycrypt = $app->getContainer()->get('bycryptWrapper');

    // Has password
    $hashed_password = $bycrypt->createHashedPassword($pass);

    // Return hashed password
    return $hashed_password;
}

function decrypt($app, $encoded): array {
    // Get containers
    $base64 = $app->getContainer()->get('base64Wrapper');
    $libsodium = $app->getContainer()->get('libSodiumWrapper');

    // Empty array for decrypted data
    $decrypted_data = [];

    $decrypted_data['fname'] = $libsodium->decryption(
        $base64,
        $encoded['fname']
    );

    $decrypted_data['surname'] = $libsodium->decryption(
        $base64,
        $encoded['surname']
    );

    $decrypted_data['email'] = $libsodium->decryption(
        $base64,
        $encoded['email']
    );

    $decrypted_data['pass'] = $libsodium->decryption(
        $base64,
        $encoded['pass']
    );

    // Return decrypted data
    return $decrypted_data;
}

// Upload user data to accounts table
function storeData($app, $encoded, $cleaned_values): bool {
    // get containers
    $db = $app->getContainer()->get('dbh');
    $user_model = $app->getContainer()->get('userModel');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');
    $db_config = $app->getContainer()->get('settings');
    $db_connection_settings = $db_config['pdo_settings'];

    // Empty string for store result
    $store_result = '';

    // Set user models properties
    $user_model->setUsername($cleaned_values['username']);
    $user_model->setFname($encoded['fname']);
    $user_model->setSurname($encoded['surname']);
    $user_model->setEmail($encoded['email']);
    $user_model->setDob($cleaned_values['dob']);
    $user_model->setPassword($encoded['pass']);
    $user_model->setDbConnectionSettings($db_connection_settings);
    $user_model->setDb($db);
    $user_model->setSQLQueries($sql_queries);
    $user_model->setSessionWrapper($session_wrapper);
    $user_model->setLogger($logger);

    // Store user data
    $store_results = $user_model->signupStorage();

    var_dump($_SESSION['username']);

    return $store_results;
}
