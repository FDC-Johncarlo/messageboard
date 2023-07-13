$(document).ready(function () {
    // config
    let offset = 0;
    let limit = 5;

    const mainList = $(".main-list");

    // This will handle the show more
    $(document).on("click", "#show-more", function () {
        let result = "";
        const $this = $(this);
        $this.attr("disabled", true);

        offset += limit;
        limit += 5;

        setTimeout(function () {
            let path = null;
            let chatby = "";
            let totalFilter = null;

            const response = Ajax(`api/more/${offset}/${limit}`);

            if (response.result.length !== 0) {

                totalFilter = response.result[0][0].full_count;

                $.each(response.result, function (index, value) {
                    const messageInformation = value.MessageModel;

                    const getLatestChat = JSON.parse(messageInformation.message);
                    const latestInfo = getLatestChat[getLatestChat.length - 1];

                    if (messageInformation.receiver.length !== 0) {
                        const receiverInformation = messageInformation.receiver.UsersDataModel;
                        if (receiverInformation.profile == null) {
                            path = base_url + "app/webroot/img/default.png";
                        } else {
                            path = base_url + "app/webroot/uploads/profile/" + receiverInformation.profile;
                        }
                    } else {
                        path = base_url + "app/webroot/img/default.png";
                    }

                    // Check if latest from is equal to response from
                    if (latestInfo.from == response.from) {
                        chatby = "<b>You:</b>";
                    }

                    result += `<div class="card-list">
                        <img src="${path}" alt="Avatar">
                        <div class="right-content-msg">
                            <h4 class="nameOfRecipient">${messageInformation.name}</h4>
                            <p class="message-content">${chatby} ${latestInfo.message}</p>
                            <span class="message-timestamp">${latestInfo.date_push}</span>
                        </div>
                        <div class="action-area">
                            <a href="javascript:void(0);" id="delete-entire" data-ref="${messageInformation.id}">Delete Message</a>
                        </div>
                    </div>`;

                });

                // Check if the total filter and the current limit is less than or equal
                if (totalFilter < limit) {
                    // Then hide the show more button
                    $this.remove();
                }

                $this.attr("disabled", false);
                mainList.append(result);
            }
        }, 500);
    });

    $(document).on("click", ".card-list", function () {
        const $this = $(this);
        const room = $this.attr("data-room");
        const usersIds = room.replace(".", "/");
        window.location = base_url + "details/" + usersIds;
    });

    $(document).on("click", "#delete-entire", function (event) {
        event.stopPropagation();

        let path = null;
        let chatby = "";

        const $this = $(this);
        const data = new FormData();

        const ref = $this.attr("data-ref");

        data.append("ref", ref);
        data.append("offset", limit);
        data.append("limit", 2);

        if (confirm("Are you sure? You won't be able to revert this data")) {
            const response = Ajax(`api/delete-message`, data);
            if (response.status) {
                const parentCardList = $this.parents(".card-list");
                parentCardList.fadeOut(200, function (event) {

                    parentCardList.remove();

                    const totalGet = response.newAdded.result.length;

                    if (totalGet !== 0) {
                        const newDataToAdd = response.newAdded.result[0].MessageModel;

                        const getLatestChat = JSON.parse(newDataToAdd.message);
                        const latestInfo = getLatestChat[getLatestChat.length - 1];

                        if (newDataToAdd.receiver.length !== 0) {
                            const receiverInformation = newDataToAdd.receiver.UsersDataModel;
                            if (receiverInformation.profile == null) {
                                path = base_url + "app/webroot/img/default.png";
                            } else {
                                path = base_url + "app/webroot/uploads/profile/" + receiverInformation.profile;
                            }
                        } else {
                            path = base_url + "app/webroot/img/default.png";
                        }

                        // Check if latest from is equal to response from
                        if (latestInfo.from == response.newAdded.from) {
                            chatby = "<b>You:</b>";
                        }

                        const newDataContent = `<div class="card-list">
                            <img src="${path}" alt="Avatar">
                            <div class="right-content-msg">
                                <h4 class="nameOfRecipient">${newDataToAdd.name}</h4>
                                <p class="message-content">${chatby} ${latestInfo.message}</p>
                                <span class="message-timestamp">${latestInfo.date_push}</span>
                            </div>
                            <div class="action-area">
                                <a href="javascript:void(0);" id="delete-entire" data-ref="${newDataToAdd.id}">Delete Message</a>
                            </div>
                        </div>`;

                        if (totalGet == 1) {
                            $("#show-more").remove();
                        }

                        mainList.append(newDataContent);
                    } else {
                        if ($(".card-list").length == 0) {
                            $(".main-list").html(`<p style="text-align: center;">No Message List Yet</p>`);
                        }
                    }
                });
            }
        }
    });
});