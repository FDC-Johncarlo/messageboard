$(document).ready(function () {
    const datePicker = $("input[name='birthdate']");

    // Initialize the date picker
    datePicker.datepicker({
      defaultDate: "+1w",
      numberOfMonths: 1,
    });

    const errorDisplay = $(".error-message");
    // Add eventlister to form#profile selector
    $(document).on("submit", "form#profile", function (event) {
        // hide the error by default when submitting the form
        errorDisplay.hide();

        // preventing from reloading the page
        event.preventDefault();

        // Serialize the form 
        const data = new FormData(this);

        setTimeout(function () {
            // request an endpoint to Controller API with method Profile
            const response = Ajax("api/profile", data);

            // check the status of the reponse : Boolean
            if (response.status) {
                alert("Profile Successfully Updated");
            } else {
                let result = "<ul>";
                // check if the error is for field
                if (response.field) {
                    $.each(response.message, function (index, value) {
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
                    // preventing from excution for non field trapping code
                    return;
                }

                result += `<li>${response.message}</li>`;
                result += "</ul>";

                errorDisplay.html(result).show();
            }
        }, 500);
    });

    $(document).on("change", "#preiviewer", function () {
        const file = this.files[0];
        // if the file is not empty
        if (file) {
            // preview the image
            $("#preview-here").attr("src", URL.createObjectURL(file));
        }
    });
});