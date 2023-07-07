<?php

class ApiController extends AppController
{
    public $autoRender = false; // Disable the default view rendering

    public function register()
    {
        // check the request if post
        if ($this->request->is("post")) {
            // load the usersmodel
            $this->loadModel("UsersModel");

            $data = $this->request->data;
            $this->UsersModel->set($data);

            if ($this->UsersModel->validates()) {
             
            } else {
                $errors = $this->UsersModel->validationErrors;
                // Handle the validation errors
            }
          

            // if ($validates) {
            //     // Fields are empty, handle the error
            //     $errors = $this->UsersModel->validationErrors;
            //     echo $errors;
            //     // Handle the errors as needed
            // } else {
            //     // Fields are not empty, proceed with the desired logic
            // }


            // // $this->UsersModel->create(); // Prepare the model for a new record
            // // $result = $this->UsersModel->save($data); // Perform the insert operation

            // // echo json_decode("");

            // $this->UsersModel->set($this->request->data); // Set the model data for validation
            // if ($this->UsersModel->validates()) { // Perform model validation
            //     $this->UsersModel->save($this->request->data); //insert data
            // } else {
            //     // Debug validation errors
            //     debug($this->UsersModel->validationErrors);
            // }

            // return;
        }

        // Display Error Message
        // $this->errorMessage("Invalid Request");
    }
} // end of the class
