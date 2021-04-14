<?php
/**
 * Route for account page
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/myAccount[/{err}]', function(Request $request, Response $response, $args) use ($app) {
   // Check to see if session logged in is set
  if (!isset($_SESSION['is_logged_in'])) {
    // Navigate to login page with error
    return $response->withRedirect($this->router->pathFor('Login', ['err' => 'accessErr']));
  }

    // Empty err message
    $err_message = addFormError($args);

    // Check to see if logged in
    $logged_in = displayHeaderButton();

    // Get homepage link 
    $homepage = homepageLink($app);

    // Get data from database
    $data = getAccountData($app);

    // Decrypt data
    $decrypted_data = decryptUserData($app, $data);

   return $this->view->render($response,
    'myAccountForm.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'list_data' => LISTDATA,
        'action' => '/football_trivia_game/public/editAccountDetails',
        'homepage_link' => '/football_trivia_game/public/' . $homepage,
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'is_logged_in' => $logged_in,
        'err' => $err_message,
        'personal_action' => '/football_trivia_game/public/updateAccountProcess/personalDetails',
        'password_action' => '/football_trivia_game/public/updateAccountProcess/password',
        'delete_action' => '/football_trivia_game/public/updateAccountProcess/delete',
        'signout' => 'Sign Out',
        'page_heading' => 'Personal Details',
        'content' => 'Update personal details or delete account',
        'username' => $decrypted_data['username'],
        'fname' => $decrypted_data['fname'],
        'surname' => $decrypted_data['surname'],
        'email' => $decrypted_data['email'],
    ]);
 })->setName('MyAccount');

 function homepageLink($app) {
    // Get containers
    $session_wrapper = $app->getContainer()->get('sessionWrapper');

    $account_type = $session_wrapper->getSessionVar('account_type');

    $link;

    if ($account_type == 'Teacher') {
        $link = 'managementHomepage';
    } else {
        $link = 'playerHomepage';
    }

    return $link;
 }

 function getAccountData($app) {
     // Get containers
     $user_model = $app->getContainer()->get('userModel');
     $session_wrapper = $app->getContainer()->get('sessionWrapper');
     $sql_queries = $app->getContainer()->get('sqlQueries');
     $db = $app->getContainer()->get('dbh');
     $db_config = $app->getContainer()->get('settings');
     $db_connection_settings = $db_config['pdo_settings'];
     $logger = $app->getContainer()->get('logger');

     // empty array for results
     $results = [];

     // Set properties
     $user_model->setDb($db);
     $user_model->setDbConnectionSettings($db_connection_settings);
     $user_model->setSQLQueries($sql_queries);
     $user_model->setSessionWrapper($session_wrapper);
     $user_model->setLogger($logger);

     // call method to get data
     $results = $user_model->getUserDetails();

     return $results;
 }

 function decryptUserData($app, $data) {
     // Get container
    $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');
    $base64_wrapper = $app->getContainer()->get('base64Wrapper');

    // empty array for decrypted data
    $decrypted_data = [];

    // Insert data that does not decrypting
    $decrypted_data['username'] = $data['account_username'];

    // Decrypt students info
    $decrypted_data['fname'] = $libsodium_wrapper->decryption(
        $base64_wrapper,
        $data['account_fname']
    );

    $decrypted_data['surname'] = $libsodium_wrapper->decryption(
       $base64_wrapper,
       $data['account_surname']
   );

   $decrypted_data['email'] = $libsodium_wrapper->decryption(
       $base64_wrapper,
       $data['account_email']
   );

    return $decrypted_data;
 }
