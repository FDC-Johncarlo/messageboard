<?php

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https:#cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https:#cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https:#cakefoundation.org)
 * @link          https:#cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       https:#opensource.org/licenses/mit-license.php MIT License
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
class RegisterModel extends AppModel
{
    # table name
    public $useTable = "users";

    # for registration validation
    public $validate = array(
        "name" => array(
            "require" => array(
                "rule" => "notBlank",
                "message" => "Please enter your name."
            ),
            "minLength" => array(
                "rule" => array("minLength", 5),
                "message" => "must be between 5 and more characters long."
            ),
        ),
        "email" => array(
            "required" => array(
                "rule" => array("email"),
                "message" => "Kindly provide your email for verification."
            ),
            "unique" => array(
                "rule" => "isUnique",
                "message" => "Provided is already exists."
            )
        ),
        "password" => array(
            "rule" => array("minLength", 8),
            "message" => "Minimum length of 8 characters."
        ),
        "confirmation_password" => array(
            "rule" => "matchPasswords",
            "message" => "Passwords did not match."
        ),
    );

    # This will handle the match password between the password and confirm password
    public function matchPasswords($check)
    {
        $password = $this->data[$this->alias]["password"];
        $confirmPassword = $this->data[$this->alias]["confirmation_password"];

        return ($password === $confirmPassword);
    }
} # end of the class
