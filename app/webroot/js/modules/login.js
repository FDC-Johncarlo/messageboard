$(document).ready(function () {
    // initialize error global variable
    const errorDisplay = $(".error-message");
    // Add eventlister to form#login selector
    $(document).on("submit", "form#login", function (event) {
        // hide the error by default when submitting the form
        errorDisplay.hide();

        // preventing from reloading the page
        event.preventDefault();

        // Serialize the form 
        const data = new FormData(this);

        // request an endpoint to Controller API with method Login
        const response = Ajax("api/login", data);

        // check the status of the reponse : Boolean
        if (response.status) {
            // redirect to home page if success login
            location.replace(base_url + "home");
        } else {
            // add the error response from the end point and show the content
            errorDisplay.html(response.error).show();
        }
    });
});