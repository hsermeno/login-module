<?php

//$app->get('test', 'TestController@show');
$app->get('/', ['as' => 'frontend', 'uses' => 'FrontendController@show']);

$app->post('login', 'LoginController@login');
$app->post('registration', 'RegistrationController@register');
$app->post('password/email', 'PasswordController@postEmail');
$app->post('password/reset', 'PasswordController@postReset');
$app->get('lti', 'LTIController@login');

$app->group(['prefix' => 'oauth_server', 'namespace' => 'OAuthServer'], function() use ($app) {
    $app->post('access_token', ['as' => 'access_token', 'uses' => 'AccessTokenController@issue']);
    $app->group(['middleware' => 'oauth'], function() use ($app) {
        $app->get('authorization', ['uses' => 'AuthorizationController@show', 'middleware' => 'check-authorization-params']);
        $app->post('authorization/authorize', ['uses' => 'AuthorizationController@authorize', 'middleware' => 'check-authorization-params']);
        $app->post('authorization/deny', ['uses' => 'AuthorizationController@deny', 'middleware' => 'check-authorization-params']);
        $app->get('user_profile', 'UserProfileController@show');
    });
});

$app->group(['prefix' => 'oauth_client', 'namespace' => 'OAuthClient'], function() use ($app) {
    $app->get('redirect/facebook', 'FacebookController@redirect');
    $app->get('redirect/google', 'GoogleController@redirect');
    $app->get('redirect/pms', 'PMSController@redirect');
    $app->get('callback/facebook', ['as' => 'oauth_client_callback_facebook', 'uses' => 'FacebookController@callback']);
    $app->get('callback/google', ['as' => 'oauth_client_callback_google', 'uses' => 'GoogleController@callback']);
    $app->get('callback/pms', ['as' => 'oauth_client_callback_pms', 'uses' => 'PMSController@callback']);
});