<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	# Routes that will handle the pages for Unthenticate Users
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'login'));
	Router::connect('/login', array('controller' => 'pages', 'action' => 'display', 'login'));
	Router::connect('/register', array('controller' => 'pages', 'action' => 'display', 'register'));
	Router::connect('/success-registration', array('controller' => 'pages', 'action' => 'display', 'success'));

	# Routes that will handle the pages for Authenticated Users
	Router::connect('/home', array('controller' => 'pages', 'action' => 'display', 'home'));
	Router::connect('/my-profile', array('controller' => 'pages', 'action' => 'display', 'profile'));
	Router::connect('/my-account', array('controller' => 'pages', 'action' => 'display', 'account'));
	Router::connect('/new-message', array('controller' => 'pages', 'action' => 'display', 'new-message'));
	Router::connect('/details/:pair_one/:pair_two', array('controller' => 'pages', 'action' => 'displaydetails', 'chat'));
	Router::connect('/logout', array('controller' => 'api', 'action' => 'logout'));

	# Routes that will handle the API END POINT
	Router::connect('/api/register', array('controller' => 'api', 'action' => 'register'));
	Router::connect('/api/login', array('controller' => 'api', 'action' => 'login'));
	Router::connect('/api/profile', array('controller' => 'api', 'action' => 'profile'));
	Router::connect('/api/account', array('controller' => 'api', 'action' => 'account'));
	Router::connect('/api/list', array('controller' => 'api', 'action' => 'list'));
	Router::connect('/api/send', array('controller' => 'api', 'action' => 'send'));
	Router::connect('/api/more', array('controller' => 'api', 'action' => 'more'));
	Router::connect('/api/delete-message', array('controller' => 'api', 'action' => 'delete'));
	Router::connect('/api/reply', array('controller' => 'api', 'action' => 'reply'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
