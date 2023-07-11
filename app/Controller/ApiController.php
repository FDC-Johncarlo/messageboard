<?php

# Import the Security class
App::uses("Security", "Utility");
App::uses('CakeSession', 'Model/Datasource');

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
        # based on Asian Time
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
    public function profile()
    {
        # Get the session data
        $sessionData = CakeSession::read();
        $user_id = $sessionData["Users"]["data"]["id"];

        # check the request if post
        if ($this->request->is('post')) {
            # Initialize the UsersDataModel : Model
            $this->loadModel("UsersDataModel");
            # Get the post data
            $postData = $this->request->data;

            # check if the gender is not isset then the default value will be bull for the index gender
            if (!isset($postData["gender"])) {
                $postData["gender"] = null;
            }

            $this->UsersDataModel->set($postData);

            # check if the validation for name is not okay
            if (!$this->UsersDataModel->validates()) {
                $response["status"] = false;
                $response["message"] = $this->UsersDataModel->validationErrors;
                $response["field"] = true;
                return json_encode($response);
            }

            $dataToBeInserted = [
                "fk_id" => $user_id,
                "birth_date" => $postData["birthdate"],
                "gender" => $postData["gender"],
                "hubby" => $postData["hubby"],
            ];

            # Get the userdata using Foreign Key
            $userData = $this->UsersDataModel->findByFkId($user_id);

            # Get the uploaded file information
            $file = $this->request->params["form"]["profile"];

            # Process the file upload
            $uploadFile = $this->handleTheUploadProfile($file, $userData);

            if (is_array($uploadFile)) {
                if (!$uploadFile["status"]) {
                    return json_encode($uploadFile);
                } else {
                    $dataToBeInserted["profile"] = $uploadFile["filename"];
                }
            }

            $this->UsersDataModel->create(); # Prepare the model for a new record

            if (!empty($userData)) {
                # Handle the userdata column (birth_date, birth_date, gender, hubby, profile) to be updated

                foreach ($dataToBeInserted as $key => $value) {
                    $userData["UsersDataModel"][$key] = $value;
                }

                $this->UsersDataModel->save($userData);

                # Handle the user column (name) to be updated
                $this->loadModel("LoginModel");
                $userData = $this->LoginModel->findById($user_id);

                $userData["LoginModel"]["name"] = $postData["name"];
                $this->LoginModel->save($userData);

                $response["status"] = true;
                $response["message"] = "Successfully updated the Profile Information";
            } else {
                # Handle the user column (name) to be updated
                $this->UsersDataModel->save($dataToBeInserted);

                # Handle the user column (name) to be updated
                $this->loadModel("LoginModel");
                $userData = $this->LoginModel->findById($user_id);
                $userData["LoginModel"]["name"] = $postData["name"];
                $this->LoginModel->save($userData);

                $response["status"] = true;
                $response["message"] = "Successfully updated the Profile Information";
            }

            return json_encode($response);
        }

        # If there is no Request
        $this->errorMessage("INVALID REQUEST");
    }

    public function handleTheUploadProfile($file, $userData)
    {
        # Get the current timestamp
        $timestamp = time();
        # allowed fiels to be uplaoded only
        $allowedFiles = array("jpg", "png", "gif");
        # Filter the file name and get only the file extension
        $fileExtension = strtolower(strrev(explode(".", strrev($file["name"]))[0]));

        # Check if the file tmn_name index is not empty
        if (empty($file["tmp_name"])) {
            return true;
        }

        # check if the files extension is in array of the allow files
        if (!in_array($fileExtension, $allowedFiles)) {
            return array(
                "status" => false,
                "field" => false,
                "message" => "Our system does not support <b>" . $fileExtension . "</b> only <b>jpg</b>, <b>png</b> and <b>gif</> extension only."
            );
        }

        # Filename
        $fileName = "Profile-" . $timestamp . "." . $fileExtension;
        # Directory to be uploaded
        $filePath = WWW_ROOT . "uploads\profile" . DS . $fileName;

        # Initialize the upload file and if move file is returning to false
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            return array(
                "status" => false,
                "field" => false,
                "message" => "Unable to upload the file."
            );
        }

        # Check also if has not already Foreign Data in userData
        if (empty($userData)) {
            return array(
                "status" => true,
                "field" => false,
                "filename" => $fileName
            );
        }

        # if the Remove previous profile from the server is not okay
        if (!unlink(WWW_ROOT . "uploads\profile" . DS . $userData["UsersDataModel"]["profile"])) {
            return array(
                "status" => false,
                "field" => false,
                "message" => "Unable to remove previous profile"
            );
        }

        # If everthing was Ok
        return array(
            "status" => true,
            "field" => false,
            "filename" => $fileName
        );
    }
    public function account()
    {
        # Get the session data
        $sessionData = CakeSession::read();
        $user_id = $sessionData["Users"]["data"]["id"];
        $flag = null;

        # check the request if post
        if ($this->request->is("post")) {
            # load the usersmodel
            $this->loadModel("RegisterModel");

            $userData = $this->RegisterModel->findById($user_id);

            # get the request data
            $data = $this->request->data;

            # If all fields are empty
            if (
                empty(trim($data["old_password"]))
                && empty(trim($data["email"]))
                && empty(trim($data["password"]))
                && empty(trim($data["confirmation_password"]))
            ) {
                $response = [
                    "fieldList" => [
                        "Note:" => ["Please update atleast one of the required below"],
                        "Email" => [""],
                        "Password" => [""]
                    ],
                    "false" => false
                ];
                return json_encode($response);
            }

            # If all fields are not empty then the flag will be set as default
            if (
                !empty(trim($data["old_password"]))
                && !empty(trim($data["email"]))
                && !empty(trim($data["password"]))
                && !empty(trim($data["confirmation_password"]))
            ) {
                $flag = null;
            }

            # To avoid from validation
            if (empty(trim($data["email"]))) {
                unset($data["email"]);
                $flag = "password";
            }

            # To avoid from upading empty password
            if (
                empty(trim($data["old_password"]))
                && empty(trim($data["password"]))
                && empty(trim($data["confirmation_password"]))
            ) {
                unset($data["password"]);
                unset($data["confirmation_password"]);
                unset($data["old_password"]);
                $flag = "email";
            }

            # set the request data
            $this->RegisterModel->set($data);

            # Prepare the model for a new record
            $this->RegisterModel->create();

            # check the validation from Register Model
            if ($this->RegisterModel->validates()) {
                switch ($flag) {
                    case "email":
                        # Handle the update email only
                        $updatedEmail = $data["email"];

                        $userData["RegisterModel"]["email"] = $updatedEmail;
                        $this->RegisterModel->save($userData);

                        $response["status"] = true;

                        break;
                    case "password":
                        # Handle the update password
                        $oldPassword = Security::hash($data["old_password"], "sha1", true);
                        $newPassword = Security::hash($data["password"], "sha1", true);

                        if ($userData["RegisterModel"]["password"] === $oldPassword) {
                            $userData["RegisterModel"]["password"] = $newPassword;
                            $this->RegisterModel->save($userData);

                            $response["status"] = true;
                        } else {
                            $response = [
                                "fieldList" => [
                                    "old_password" => ["Invalid Confirmation Password"],
                                ],
                                "false" => false
                            ];
                        }

                        break;
                    default:
                        # Handle the update email only
                        $updatedEmail = $data["email"];
                        # Handle the update password
                        $oldPassword = Security::hash($data["old_password"], "sha1", true);
                        $newPassword = Security::hash($data["password"], "sha1", true);

                        # Handle both password & email
                        if ($userData["RegisterModel"]["password"] === $oldPassword) {

                            $userData["RegisterModel"]["email"] = $updatedEmail;
                            $userData["RegisterModel"]["password"] = $newPassword;

                            $this->RegisterModel->save($userData);

                            $response["status"] = true;
                        } else {
                            $response = [
                                "fieldList" => [
                                    "old_password" => ["Invalid Confirmation Password"],
                                ],
                                "false" => false
                            ];
                        }
                }
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
    public function logout(){
        CakeSession::delete('Users');
        $this->redirect(Configure::read("BASE_URL"));
    }
} # end of the class
