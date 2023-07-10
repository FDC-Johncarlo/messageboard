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
class PagesController extends AppController {

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
	public function display() {

		$path = func_get_args();

		if($path[0] == "profile"){
			$this->profile();
		}

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

	public function profile(){
		# Get the all current session
		$sessionData = CakeSession::read();
		# Check if the session users index are isset
		if(isset($sessionData["Users"])){
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
			if(empty($records)){
				# If the records is not yet in the database
				$result["status"] = false;
				$result["userInfo"] = $sessionData["Users"]["data"];
			}else{
				# Joined 2 table columns
				$result["userInfo"] = array_merge($records[0]["LoginModel"], $records[0]["UsersData"]);
				$result["status"] = true;
			}

			# Set a profile variable so that we can access this variable to pages
			$this->set("profile", $result);
		}
	}
} # End of the class
