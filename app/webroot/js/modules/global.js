
// base url on js side
const base_url = window.origin + "/message-board/";

// reusable ajax request
function Ajax(path, data) {
    var res = null;
    $.ajax({
        type: "POST",
        url: base_url + path,
        data: data,
        async: false,
        processData: false,
        contentType: false,
        success: function (response) {
            res = JSON.parse(response);
        },
        error: function (xhr) {
            alert("ERROR, an error has occured while performing you request!");
        },
    });
    return res;
};