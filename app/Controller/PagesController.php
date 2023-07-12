<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');
App::uses('CakeSession', 'Model/Datasource');
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public $uses = array();

	/**
	 * Displays a view
	 *
	 * @return CakeResponse|null
	 * @throws ForbiddenException When a directory traversal attempt.
	 * @throws NotFoundException When the view file could not be found
	 *   or MissingViewException in debug mode.
	 */

	public function beforeFilter()
	{
		parent::beforeFilter();
		# Get the current Path loaded

		$currentRouteDisplay = $this->params->url;

		# Un-Authenticated Page
		$unauthPages = array(
			"login",
			"register",
			"success-registration"
		);

		# Authenticated Page
		$authPages = array(
			"home",
			"my-profile",
			"my-account",
			"new-message",
			"success-registration"
		);

		$sessionData = CakeSession::read();

		# Check if the session Users index is set
		if (isset($sessionData["Users"])) {
			# Check if the current route is exist on array if not the redirect  to home
			if (!in_array($currentRouteDisplay, $authPages)) {
				$this->redirect(Configure::read("BASE_URL") . "/home");
			}
		} else {
			# Check if the current route is exist on array if not the redirect  to login
			if (!in_array($currentRouteDisplay, $unauthPages)) {
				$this->redirect(Configure::read("BASE_URL") . "/login");
			}
		}
	}

	public function display()
	{

		$path = func_get_args();

		# Load the profile informaton
		if ($path[0] == "profile") {
			$this->profile();
		}

		# load the messages
		if ($path[0] == "home") {
			$this->allList();
		}

		$this->set("path", $path);

		$count = count($path);
		if (!$count) {
			return $this->redirect('/');
		}
		if (in_array('..', $path, true) || in_array('.', $path, true)) {
			throw new ForbiddenException();
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));

		try {
			$this->render(implode('/', $path));
		} catch (MissingViewException $e) {
			if (Configure::read('debug')) {
				throw $e;
			}
			throw new NotFoundException();
		}
	}

	public function profile()
	{
		# Get the all current session
		$sessionData = CakeSession::read();
		# Check if the session users index are isset
		if (isset($sessionData["Users"])) {
			# Get the id from Session
			$user_id = $sessionData["Users"]["data"]["id"];

			# Initialize the Login Model
			$this->loadModel("LoginModel");

			# Select only the following array for joining
			$columns = [
				"LoginModel.name",
				"UsersData.birth_date",
				"UsersData.gender",
				"UsersData.hubby",
				"UsersData.profile",
			];

			# Perform the JOIN operation between 2 table Users:LoginModel & users_data as UsersData
			$records = $this->LoginModel->find("all", array(
				"fields" => $columns,
				"joins" => array(
					array(
						"table" => "users_data",
						"alias" => "UsersData", // Add the table alias
						"conditions" => array(
							"LoginModel.id = UsersData.fk_id"
						)
					)
				),
				"conditions" => array("LoginModel.id" => $user_id)
			));

			# Check if the records joined is not empty
			if (empty($records)) {
				# If the records is not yet in the database
				$result["status"] = false;
				$result["userInfo"] = $sessionData["Users"]["data"];
			} else {
				# Joined 2 table columns
				$result["userInfo"] = array_merge($records[0]["LoginModel"], $records[0]["UsersData"]);
				$result["status"] = true;
			}

			# Set a profile variable so that we can access this variable to pages
			$this->set("profile", $result);
		}
	}

	public function allList()
	{
		# Get the all current session
		$sessionData = CakeSession::read();
		if (isset($sessionData["Users"])) {
			# Get the id from Session
			$user_id = $sessionData["Users"]["data"]["id"];

			# Initialize the Login Model
			$this->loadModel("MessageModel");

			$options = array(
				"fields" => array("MessageModel.*"),
				"order" => array("MessageModel.last_update DESC"),
				"conditions" => array(
					"OR" => array(
						"MessageModel.pair_one" => $user_id,
						"MessageModel.pair_two" => $user_id
					)
				)
			);

			$records = $this->MessageModel->find("all", $options);

			if(!empty($records)){
				$this->loadModel("UsersDataModel");
				foreach($records as $key => $value){
					if($user_id == $value["MessageModel"]["pair_one"]){
						$userDataPairOne = $this->UsersDataModel->findByFkId($value["MessageModel"]["pair_two"]);
					}else{
						$userDataPairOne = $this->UsersDataModel->findByFkId($value["MessageModel"]["pair_one"]);
					}

					$records[$key]["MessageModel"]["receiver"] = $userDataPairOne;
				}
			}

			$this->set("list", $records);
			$this->set("currentUser", $user_id);
		}
	}
} # End of the class
