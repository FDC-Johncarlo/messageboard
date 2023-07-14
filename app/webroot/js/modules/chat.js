$(document).ready(function () {
    const Limit = 5;
    let currentOffset = 0;
    let currentLimit = 5;


    // For replying message
    $(document).on("submit", "#reply-form", function (event) {
        event.preventDefault();
        const to = $("textarea[name='reply']").attr("data-reply-to");

        const data = new FormData(this);
        data.append("to", to);

        const response = Ajax("api/reply", data);

        if (response.status) {
            location.reload();
        }
    });

    $(document).on("click", "#show-more", function () {
        const $thisElement = $(this);
        const to = $("textarea[name='reply']").attr("data-reply-to");

        const data = new FormData();
        data.append("to", to);

        const response = Ajax("api/more-message", data);

        if (response.status) {

            const result = response.result;

            const nameOfRecipient = result.userAccount.name;

            const duplicateConvoInfo = JSON.parse(result.message);
            const duplicateReverseConvo = duplicateConvoInfo.reverse();

            const convoInfo = JSON.parse(result.message);
            const reverseConvo = convoInfo.reverse();
            reverseConvo.splice(currentOffset, currentLimit);
            currentLimit += 5;

            let path2 = "";

            let currentList = 0;
            $.each($(".card-list"), function (index, value) {
                const thisCardListElement = $(value);
                const img = thisCardListElement.find("img");
                const action = thisCardListElement.find(".action-area");

                img.remove();
                action.remove();

                const from = response.from == duplicateConvoInfo[currentList].from ? "You" : nameOfRecipient;

                thisCardListElement.removeClass("me-chat").removeClass("not-me").addClass(from == "You" ? "me-chat" : "not-me");
                thisCardListElement.attr("data-ref", duplicateReverseConvo[currentList].ref);
                thisCardListElement.find("p.message-content").text(duplicateReverseConvo[currentList].message);
                thisCardListElement.find("h4.nameOfRecipient").text(from);
                thisCardListElement.find("span.message-timestamp").text(duplicateReverseConvo[currentList].date_push);

                if (from == "You") {
                    if (result.currentUserData !== 0) {
                        if (result.currentUserData.profile == null) {
                            path2 = base_url + "app/webroot/img/default.png";
                        } else {
                            path2 = base_url + "app/webroot/uploads/profile/" + result.currentUserData.profile;
                        }
                    } else {
                        path2 = base_url + "app/webroot/img/default.png";
                    }

                    $(`<img src="${path2}" alt="Avatar">`).insertAfter(thisCardListElement.find("div.right-content-msg"));
                    thisCardListElement.append(action);
                } else {
                    if (result.userData !== 0) {
                        if (result.userData.profile == null) {
                            path2 = base_url + "app/webroot/img/default.png";
                        } else {
                            path2 = base_url + "app/webroot/uploads/profile/" + result.userData.profile;
                        }
                    } else {
                        path2 = base_url + "app/webroot/img/default.png";
                    }

                    $(` <img src="${path2}" alt="Avatar"> `).insertBefore(thisCardListElement.find("div.right-content-msg"));
                }

                currentList++;
            });

            let contentResult = "";
            let path = "";

            for (let i = 0; i < reverseConvo.length; i++) {
                if (i < Limit) {
                    if (reverseConvo[i].from == response.from) {
                        if (result.currentUserData !== 0) {
                            if (result.currentUserData.profile == null) {
                                path = base_url + "app/webroot/img/default.png";
                            } else {
                                path = base_url + "app/webroot/uploads/profile/" + result.currentUserData.profile;
                            }
                        } else {
                            path = base_url + "app/webroot/img/default.png";
                        }

                        contentResult += `<div class="card-list me-chat" data-ref="${reverseConvo[i].ref}">
                                <div class="right-content-msg">
                                    <h4 class="nameOfRecipient">You</h4>
                                    <p class="message-content">${reverseConvo[i].message}</p>
                                    <span class="message-timestamp">${reverseConvo[i].date_push}</span>
                                </div>
                                <img src="${path}" alt="Avatar">                         
                                <div class="action-area">
                                    <a href="javascript:void(0);" id="delete-entire">Delete Message</a>
                                </div>
                            </div>`;
                    } else {
                        if (result.userData !== 0) {
                            if (result.userData.profile == null) {
                                path = base_url + "app/webroot/img/default.png";
                            } else {
                                path = base_url + "app/webroot/uploads/profile/" + result.userData.profile;
                            }
                        } else {
                            path = base_url + "app/webroot/img/default.png";
                        }

                        contentResult += `<div class="card-list not-me" data-ref="${reverseConvo[i].ref}">
                        <img src="${path}" alt="Avatar">
                                <div class="right-content-msg">
                                    <h4 class="nameOfRecipient">${nameOfRecipient}</h4>
                                    <p class="message-content">${reverseConvo[i].message}</p>
                                    <span class="message-timestamp">${reverseConvo[i].date_push}</span>
                                </div>                         
                            </div>`;
                    }
                }
            }

            $(contentResult).insertBefore($thisElement.parent("div"));

            if (reverseConvo.length < Limit) {
                $thisElement.remove();
            }
        }
    });

    $(document).on("click","#delete-entire", function(){
        const parentsElementCard = $(this).parents(".card-list.me-chat");
        const ref = parentsElementCard.attr("data-ref");
        const to = $("textarea[name='reply']").attr("data-reply-to");
        
        const data = new FormData();
        data.append("ref", ref);
        data.append("to", to);

        if(confirm("Are you sure you want to remove your reply? You won't be able to revert this message.")){
            const response = Ajax("api/delete-reply", data);
            
            if(response.status){
                parentsElementCard.fadeOut(200, function () {
                    parentsElementCard.remove();
                    location.reload();
                });
            }
        }
    });
});