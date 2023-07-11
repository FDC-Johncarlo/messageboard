$(document).ready(function () {
    // initialize error global variable
    const errorDisplay = $(".error-message");
    // Add eventlister to form#account selector
    $(document).on("submit", "form#account", function (event) {
        // hide the error by default when submitting the form
        errorDisplay.hide();

        // preventing from reloading the page
        event.preventDefault();

        // Serialize the form 
        const data = new FormData(this);

        // request an endpoint to Controller API with method account
        const response = Ajax("api/account", data);

        // check the status of the reponse : Boolean
        if (response.status) {
            // redirect to success page
            alert("Account successfully updated");
        } else {
            let result = "<ul>";

            $.each(response.fieldList, function (index, value) {
                // Convert the first letter to uppercase;
                const firstLetter = index.charAt(0).toUpperCase();
                // Remove the first letter on the index
                const remainingFieldName = index.slice(1);
                // add the first and remaing and filter the _ to space " "
                const fieldName = (firstLetter + remainingFieldName).replace("_", " ");
                // field error message
                const message = value[0];

                result += `<li><b>${fieldName}</b> ${message}</li>`;
            });

            result += "</ul>";

            // add the result string email format and show the content
            errorDisplay.html(result).show();
        }
    });
});