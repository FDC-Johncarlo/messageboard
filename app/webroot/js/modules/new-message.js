$(document).ready(function () {
    const errorDisplay = $(".error-message");

    // Initialize Select2 Library with Options and Ajax
    $("select[name='to']").select2({
        width: "320px",
        allowClear: true,
        placeholder: "Select Recipient",
        templateResult: formatOption,
        ajax: {
            url: base_url + "api/list",
            dataType: "json",
            delay: 250,
            cache: true,
            data: function (data) {
                return {
                    searchTerm: data.term
                };
            },
            processResults: function (response) {
                return {
                    results: response.result
                };
            }
        }
    });

    // Add eventlister to form#register selector
    $(document).on("submit", "form#new-message", function (event) {
        // hide the error by default when submitting the form
        errorDisplay.hide();

        // preventing form form reloading
        event.preventDefault();

        // Get the value and set the null to empty string
        const recipient = $("select[name='to']").val();
        const recipientVal = recipient == null ? "" : recipient;

        // Serialize the form 
        const data = new FormData(this);
        data.append("to", recipientVal);

        // request an endpoint to Controller API with method Register
        const response = Ajax("api/send", data);

         // check the status of the reponse : Boolean
         if (response.status) {
            window.location = base_url + "home";
        } else {    
            let result = "<ul>";

            $.each(response.fieldList, function(index, value){
                // Convert the first letter to uppercase;
                const firstLetter = index.charAt(0).toUpperCase();
                // Remove the first letter on the index
                const remainingFieldName = index.slice(1);
                // add the first and remaing and filter the _ to space " "
                const fieldName = (firstLetter + remainingFieldName).replace("_"," ");
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

function formatOption(option) {
    // Check if it's a placeholder or an actual option
    if (!option.id) {
        // Return the placeholder text as it is
        return option.text;
    }

    // Get the image URL and option text
    var imageUrl = option.image;
    var optionText = option.text;

    const path = imageUrl == null ? base_url + "/app/webroot/img/default.png" : base_url + "/app/webroot/uploads/profile/" + imageUrl;

    // Create a <span> element with the image and option text
    var $option = $('<span class="custom-row-result"><img src="' + path + '" class="select2-option-image" /> ' + optionText + '</span>');

    // Return the modified option HTML
    return $option;
}