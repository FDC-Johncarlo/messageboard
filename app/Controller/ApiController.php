<?php

# Import the Security class
App::uses("Security", "Utility");

class ApiController extends AppController
{
    public $autoRender = false; # Disable the default view rendering
    public $components = array("Session");

    public function register()
    {
        # check the request if post
        if ($this->request->is("post")) {
            # load the usersmodel
            $this->loadModel("RegisterModel");

            # get the request data
            $data = $this->request->data;

            # set the request data
            $this->RegisterModel->set($data);

            # check the validation from Register Model
            if ($this->RegisterModel->validates()) {

                $dataToBeInserted = [
                    "name" => $data["name"],
                    "email" => $data["email"],
                    "password" => Security::hash($data["password"], "sha1", true),
                ];

                $this->RegisterModel->create(); # Prepare the model for a new record
                $result = $this->RegisterModel->save($dataToBeInserted);

                $response["status"] = true;
                $response["result"] = $result;
            } else {
                # Response the following required fiels with error message
                $response["fieldList"] = $this->RegisterModel->validationErrors;
                $response["status"] = false;
            }

            # Returning an Json To Preventing from execution the remaining To-Do
            return json_encode($response);
        }

        # If there is no Request
        $this->errorMessage("INVALID REQUEST");
    }
    public function login()
    {
        # check the request if post
        if ($this->request->is("post")) {
            $this->loadModel("LoginModel");

            $email = $this->request->data["email"];
            $password = $this->request->data["password"];

            # Check if email exists in the database
            $user = $this->LoginModel->findByEmail($email);

            if (!empty($user)) {
                # Verify the password
                if ($user["LoginModel"]["password"] === Security::hash($password, "sha1", true)) {
                    $response["status"] = true;

                    # Execute the usersLog functions
                    $this->UsersLog($user["LoginModel"]["id"]);

                    # Set session
                    $this->Session->write('Users.data', $user["LoginModel"]);
                } else {
                    $response["status"] = false;
                    $response["error"] = "Invalid password";
                }
            } else {
                # Email does not exist in the database
                $response["status"] = false;
                $response["error"] = "Email is not found";
            }

            # Returning an Json To Preventing from execution the remaining To-Do
            return json_encode($response);
        }

        # If there is no Request
        $this->errorMessage("INVALID REQUEST");
    }
    public function UsersLog($users_id)
    {
        // based on Asian Time
        $date = new DateTime("now", new DateTimeZone("Asia/Manila"));
        $formattedDateTime = $date->format("Y-m-d H:i:s");

        $this->loadModel("UsersLogModel");

        # Fetch the userslog using base users id
        $user = $this->UsersLogModel->findByFkId($users_id);

        $this->UsersLogModel->create(); # Prepare the model for a new record

        # check if the users have already log on the database
        if (!empty($user)) {
            # Update the : last_login_time
            $user["UsersLogModel"]["last_login_time"] = $formattedDateTime;
            # if the users has already the update only the column : last_login_time
            $this->UsersLogModel->save($user);
            return; # Preventing from excuting the insert
        }

        # for new users log parameters to be inserted
        $newUsersLog = [
            "fk_id" => $users_id, 
            "last_login_time" => $formattedDateTime
        ];

        # Insert the new users log
        $this->UsersLogModel->save($newUsersLog);
    }
} # end of the class
