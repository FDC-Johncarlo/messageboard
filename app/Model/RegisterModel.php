<?php

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class UsersModel extends AppModel
{
    public $useTable = "users";
    // // for registration validation

    public $validate = array(
        "name" => array(
            "rule" => "notBlank",
            "message" => "Please enter your name."
        ),
        "email" => "email",
        "password" => array(
            "rule" => array("minLength", 8),
            "message" => "Minimum length of 8 characters."
        ),
        "confirm_password" => array(
            "rule" => "matchPasswords",
            "message" => "Passwords did not match."
        ),
    );

    public function matchPasswords($check)
    {
        $password = $this->data[$this->alias]['password'];
        $confirmPassword = $this->data[$this->alias]['confirm_password'];

        return ($password === $confirmPassword);
    }
    // // Custom validation method for matching passwords
    // public function matchPasswords($check) {
    //     $password = $this->data[$this->alias]['password'];
    //     $confirmPassword = $this->data[$this->alias]['cpwd'];

    //     return ($password === $confirmPassword);
    // }

    // public function beforeSave($options = array()) {
    //     if (isset($this->data[$this->alias]['password'])) {
    //         $passwordHash = Security::hash($this->data[$this->alias]['password'], null, true);
    //         $this->data[$this->alias]['password'] = $passwordHash;
    //     }

    //     return true;
    // }
}
