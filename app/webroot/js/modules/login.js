$(document).ready(function () {
    $(document).on("submit", "form#login", function (event) {
        event.preventDefault();
        const data = new FormData(this);
        const response = Ajax("api/register", data);

        if (response) {
            
        } else {

        }
    });
});