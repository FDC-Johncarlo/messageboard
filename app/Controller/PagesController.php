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
		// parent::beforeFilter();
		// # Get the current Path loaded

		// $currentRouteDisplay = $this->params->url;

		// echo '<pre>';
		// print_r($this->params);
		// exit;

		// # Un-Authenticated Page
		// $unauthPages = array(
		// 	"login",
		// 	"register",
		// 	"success-registration"
		// );

		// # Authenticated Page
		// $authPages = array(
		// 	"home",
		// 	"my-profile",
		// 	"my-account",
		// 	"new-message",
		// 	"details",
		// 	"success-registration"
		// );

		// $sessionData = CakeSession::read();

		// # Check if the session Users index is set
		// if (isset($sessionData["Users"])) {
		// 	# Check if the current route is exist on array if not the redirect  to home
		// 	if (!in_array($currentRouteDisplay, $authPages)) {
		// 		$this->redirect(Configure::read("BASE_URL") . "/home");
		// 	}
		// } else {
		// 	# Check if the current route is exist on array if not the redirect  to login
		// 	if (!in_array($currentRouteDisplay, $unauthPages)) {
		// 		$this->redirect(Configure::read("BASE_URL") . "/login");
		// 	}
		// }
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

	public function myInfo()
	{
		# Get the all current session
		$sessionData = CakeSession::read();
		$user_id = $sessionData["Users"]["data"]["id"];

		$this->loadModel("LoginModel");

		// $userDataPairOne = $this->UsersDataModel->findById($user_id);
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
		# default filter
		$overAll = 0;

		# config
		$offset = 0;
		$limit = 5;

		if (isset($sessionData["Users"])) {
			# Get the id from Session
			$user_id = $sessionData["Users"]["data"]["id"];

			# Initialize the Login Model
			$this->loadModel("MessageModel");

			$options = array(
				"fields" => array("MessageModel.*", "count(*) OVER() AS full_count"),
				"order" => array("MessageModel.last_update DESC"),
				"conditions" => array(
					"OR" => array(
						"MessageModel.pair_one" => $user_id,
						"MessageModel.pair_two" => $user_id
					)
				),
				"offset" => $offset,
				"limit" => $limit,
			);

			# Execute the query
			$records = $this->MessageModel->find("all", $options);

			# check if the Excuted query is not empty array
			if (!empty($records)) {
				# Change the overall records
				$overAll = $records[0][0]["full_count"];
				# Intialize Model UserData
				$this->loadModel("UsersDataModel");

				# Intialize Model UserData
				$this->loadModel("LoginModel");

				# inorder to filter and add receiver information
				foreach ($records as $key => $value) {
					# Check if the current user is in pair one
					if ($user_id == $value["MessageModel"]["pair_one"]) {
						$toBeFind = $value["MessageModel"]["pair_two"];
					} else { # if not then the FK will be on the pair one
						$toBeFind = $value["MessageModel"]["pair_one"];
					}

					# Execute 
					$userDataPairOne = $this->UsersDataModel->findByFkId($toBeFind);

					# Query options
					$options = array(
						"fields" => array("LoginModel.name"),
						"conditions" => array(
							"LoginModel.id" => $toBeFind
						)
					);

					# Execute the query
					$userIformationData = $this->LoginModel->find("all", $options);

					$records[$key]["MessageModel"]["name"] = $userIformationData[0]["LoginModel"]["name"];
					$records[$key]["MessageModel"]["receiver"] = $userDataPairOne;
				}
			}

			# Inorder to access this set variable to pages home
			$this->set("list", $records);
			$this->set("currentUser", $user_id);
			$this->set("totalFilter", $overAll);
		}
	}
	public function displaydetails($pair_one = null, $pair_two = null)
	{
		$path = func_get_args();

		$this->details();

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
	public function details()
	{
		# Get the all current session
		$sessionData = CakeSession::read();

		$pair_one =  $this->request->params["pair_one"];
		$pair_two =  $this->request->params["pair_two"];

		if (isset($sessionData["Users"])) {
			# Get the id from Session
			$user_id = $sessionData["Users"]["data"]["id"];

			if($pair_one == $user_id){
				$receiver_id = $pair_two;
			}else{
				$receiver_id = $pair_one;
			}

			$this->loadModel("MessageModel");

			# Query options
			$options = array(
				"fields" => array("MessageModel.*"),
				"conditions" => array(
					"MessageModel.pair_one" => $pair_one,
					"MessageModel.pair_two" => $pair_two
				)
			);

			# Execute the query
			$userIformationData = $this->MessageModel->find("all", $options);

			if (!empty($userIformationData)) {
				$this->loadModel("UsersDataModel");
				$this->loadModel("LoginModel");

				# Execute 
				$userDataReceiver = $this->UsersDataModel->findByFkId($receiver_id);
				$userAccounReceiver = $this->LoginModel->findById($receiver_id);

				$currentUserData = $this->UsersDataModel->findByFkId($user_id);

				$userIformationData[0]["MessageModel"]["userAccount"] = $userAccounReceiver["LoginModel"];
				$userIformationData[0]["MessageModel"]["userData"] = empty($userDataReceiver) ? [] : $userDataReceiver["UsersDataModel"];
				$userIformationData[0]["MessageModel"]["currentUserData"] = empty($currentUserData) ? [] : $currentUserData["UsersDataModel"];

				$this->set("details", $userIformationData[0]["MessageModel"]);
				$this->set("currentUser", $user_id);
				$this->set("receiver", $receiver_id);
				return;
			}

			# If there is no Request
			$this->errorMessage("INVALID REQUEST");
		}

        # If there is no Request
        $this->errorMessage("INVALID REQUEST");
	}
} # End of the class
