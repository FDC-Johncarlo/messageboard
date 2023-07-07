$(document).ready(function () {
    $(document).on("submit", "form#register", function (event) {
        event.preventDefault();
        const data = new FormData(this);
        const response = Ajax("api/register", data);
        console.log(data);
        if (response.status) {
            
        } else {
            alert(response.message);
        }
    });
});