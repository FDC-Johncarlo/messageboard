$(document).ready(function(){
    $(document).on("submit","#reply-form", function(event){
        event.preventDefault();
        const to = $("textarea[name='reply']").attr("data-reply-to");

        const data = new FormData(this);
        data.append("to", to);

        const response = Ajax("api/reply", data);

        if(response.status){
            location.reload();
        }
    });
});