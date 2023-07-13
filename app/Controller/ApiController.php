<?php

# Import the Security class
App::uses("Security", "Utility");
App::uses('CakeSession', 'Model/Datasource');

// App::uses('ConnectionManager', 'Model');

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

                $this->Session->write('Users.data', $result["RegisterModel"]);

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

        # Before removing check if the user profile is is not equal to null
        if ($userData["UsersDataModel"]["profile"] !== null) {
            # if the Remove previous profile from the server is not okay
            if (!unlink(WWW_ROOT . "uploads\profile" . DS . $userData["UsersDataModel"]["profile"])) {
                return array(
                    "status" => false,
                    "field" => false,
                    "message" => "Unable to remove previous profile"
                );
            }
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
                    "status" => false
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
                                "status" => false
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
                                "status" => false
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
    public function logout()
    {
        CakeSession::delete('Users');
        $this->redirect(Configure::read("BASE_URL"));
    }
    public function list()
    {
        # Get the session data
        $sessionData = CakeSession::read();
        $user_id = $sessionData["Users"]["data"]["id"];

        # check the request if post
        if ($this->request->is("get")) {
            # Initialize Connection
            $this->loadModel("RegisterModel");

            # Get the post data from form
            $data = $this->request->query;

            # Check the term searchTerm if not empty
            if (!empty($data["searchTerm"])) {
                # Query options
                $options = array(
                    "conditions" => array(
                        "id !=" => $user_id,
                        "name LIKE" => "%$data[searchTerm]%"
                    )
                );

                $results = $this->RegisterModel->find("all", $options);
            } else { # Else display all the records

                # Query options
                $options = array(
                    "conditions" => array(
                        "id !=" => $user_id
                    )
                );

                $results = $this->RegisterModel->find("all", $options);
            }

            # Initialize Connection for usedata
            $this->loadModel("UsersDataModel");


            # check if the result if empty array
            if (!empty($results)) {
                # serialize the reponse accordingly to select2 json response format
                foreach ($results as $key => $value) {
                    # get the userdata information
                    $userData = $this->UsersDataModel->findByFkId($value["RegisterModel"]["id"]);

                    # check if has userdata information
                    if (empty($userData)) {
                        $serializeResult["result"][$key]["image"] = null;
                    } else { #if has then add image
                        $serializeResult["result"][$key]["image"] = $userData["UsersDataModel"]["profile"];
                    }

                    $serializeResult["result"][$key]["id"] = $value["RegisterModel"]["id"];
                    $serializeResult["result"][$key]["text"] = $value["RegisterModel"]["name"];
                }
            } else {
                $serializeResult = ["result" => []];
            }

            return json_encode($serializeResult);
        }
    }
    public function send()
    {
        # Get the session data
        $sessionData = CakeSession::read();
        $user_id = $sessionData["Users"]["data"]["id"];

        # based on Asian Time
        $date = new DateTime("now", new DateTimeZone("Asia/Manila"));
        $formattedDateTime = $date->format("Y-m-d H:i:s");

        # check the request if post
        if ($this->request->is("post")) {
            # Get the post data from form
            $data = $this->request->data;

            # check if the recipient is empty
            if (empty($data["to"])) {
                $response = [
                    "fieldList" => [
                        "" => ["Please Select Recipient"],
                    ],
                    "status" => false
                ];

                return json_encode($response);
            }

            # Initialize Connection
            $this->loadModel("MessageModel");

            # Chat reference code
            $chatRef = "CHAT-" . $this->generateChatRef();

            # Query Options
            $options = array(
                "conditions" => array(
                    "OR" => array(
                        array("pair_one" => $user_id, "pair_two" => $data["to"]),
                        array("pair_one" => $data["to"], "pair_two" => $user_id)
                    )
                )
            );

            # Get the message information
            $userData = $this->MessageModel->find("all", $options);

            $this->MessageModel->create(); # Prepare the model for a new record

            # Message content
            $messageContent = array(
                array(
                    "ref" => $chatRef,
                    "from" => $user_id,
                    "message" => $data["message"],
                    "date_push" => $formattedDateTime
                )
            );

            if (empty($userData)) {
                # column
                $dataToBeInserted = [
                    "pair_one" => $user_id,
                    "pair_two" => $data["to"],
                    "message" => json_encode($messageContent),
                    "last_update" => $formattedDateTime
                ];

                # Perform the insertion
                $this->MessageModel->save($dataToBeInserted);
            } else {
                # Message content
                $prevChat = json_decode($userData[0]["MessageModel"]["message"]);
                array_push($prevChat, $messageContent[0]);

                # Start updating the index
                $userData[0]["MessageModel"]["message"] = json_encode($prevChat);
                $userData[0]["MessageModel"]["last_update"] = $formattedDateTime;

                # Perform the update
                $this->MessageModel->save($userData[0]);
            }

            $response["status"] = true;

            return json_encode($response);
        }
    }
    protected function generateChatRef($strength = 20)
    {
        $permitted_chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $input_length = strlen($permitted_chars);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return strtolower($random_string);
    }

    public function reuseableListMessage($offset, $limit)
    {
        # Get the all current session
        $sessionData = CakeSession::read();

        # Check also if session is isset
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
                $this->loadModel("UsersDataModel");

                $this->loadModel("LoginModel");
                # Intialize Model UserData
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

            $reponse["from"] = $user_id;
            $reponse["result"] = $records;

            return $reponse;
        }
    }

    public function more($offset = 0, $limit = 5)
    {
        # check the request if post
        if ($this->request->is("post")) {
            $result = $this->reuseableListMessage($offset, $limit);
            return json_encode($result);
        }

        # If there is no Request
        $this->errorMessage("INVALID REQUEST");
    }

    public function delete()
    {
        # check the request if post
        if ($this->request->is("post")) {
            # get the request data
            $data = $this->request->data;

            # Intialize Model MessageModel
            $this->loadModel("MessageModel");

            $newList = $this->reuseableListMessage($data["offset"], $data["limit"]);

            $result = $this->MessageModel->deleteAll(
                array(
                    array("id" =>  $data["ref"])
                )
            );

            $reponse["status"] = $result;
            $reponse["newAdded"] = $newList;

            return json_encode($reponse);
        }

        # If there is no Request
        $this->errorMessage("INVALID REQUEST");
    }

    public function reply()
    {
        
        # Get the all current session
        $sessionData = CakeSession::read();
        $user_id = $sessionData["Users"]["data"]["id"];

        # based on Asian Time
        $date = new DateTime("now", new DateTimeZone("Asia/Manila"));
        $formattedDateTime = $date->format("Y-m-d H:i:s");

        # check the request if post
        if ($this->request->is("post")) {
            # get the request data
            $data = $this->request->data;

            # Intialize Model MessageModel
            $this->loadModel("MessageModel");

            # Chat reference code
            $chatRef = "CHAT-" . $this->generateChatRef();

            # Query Options
            $options = array(
                "conditions" => array(
                    "OR" => array(
                        array("pair_one" => $user_id, "pair_two" => $data["to"]),
                        array("pair_one" => $data["to"], "pair_two" => $user_id)
                    )
                )
            );

            # Get the message information
            $userData = $this->MessageModel->find("all", $options);

            $this->MessageModel->create(); # Prepare the model for a new record

            # Message content
            $messageContent = array(
                array(
                    "ref" => $chatRef,
                    "from" => $user_id,
                    "message" => $data["reply"],
                    "date_push" => $formattedDateTime
                )
            );

       
            $prevChat = json_decode($userData[0]["MessageModel"]["message"]);
            array_push($prevChat, $messageContent[0]);

            # Start updating the index
            $userData[0]["MessageModel"]["message"] = json_encode($prevChat);
            $userData[0]["MessageModel"]["last_update"] = $formattedDateTime;

            # Perform the update
            $this->MessageModel->save($userData[0]);

            $response["status"] = true;

            return json_encode($response);
        }

        # If there is no Request
        $this->errorMessage("INVALID REQUEST");
    }
} # end of the class
