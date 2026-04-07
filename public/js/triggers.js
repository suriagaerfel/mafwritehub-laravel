$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    console.log(page_name);

    savePosition();

    //initialize the actual workpace on load
    if (page_name.includes("Workspace")) {
        $("#content_id").val("");
        initializeActualWorkspace();
    }

    if (page_name == "Messages") {
        getMessageUsers();
        getThreadMessages();

        if (message_seller_code) {
            getThreadMessagesSeller();
        }
    }

    searchAutoFocus();

    //initialize the page on load
    pageRefresh();

    //initialize the page very 5 seconds
    // setInterval(function () {
    //     pageRefresh();
    // }, 1000);

    $("#searched-promotion").on("input", function () {
        getPromotions();
    });

    //redirect to the search page
    $("#header-search-button").click(function () {
        redirectToSearchPage();
    });

    $("#available-articles-button").click(function () {
        $("#articles-availability").val("");
        $("#taken-articles-button").removeClass("active");
        $(this).addClass("active");
        getGeneralRecords();
    });

    $("#taken-articles-button").click(function () {
        $("#articles-availability").val("taken");
        $("#available-articles-button").removeClass("active");
        $(this).addClass("active");

        getGeneralRecords();
    });

    //get the records duirng the search input (SEARCH PAGE)
    $("#search-page-query").on("input", function () {
        getGeneralRecords();
    });

    $(document).on("click", "#category", function () {
        getCategories();
    });

    $(document).on("click", "#access-type", function () {
        getAccessTypes();
    });

    //filter search results

    $("#search-results-filter a").click(function () {
        var filter_id = $(this).attr("id");
        $(".indicator").hide();
        $(".filter-button").show();

        var record_type = "";

        if (filter_id.includes("teacher-files")) {
            record_type = "teacher-files";
        }

        if (filter_id.includes("articles")) {
            record_type = "articles";
        }

        if (filter_id.includes("researches")) {
            record_type = "researches";
        }

        if (filter_id.includes("tools")) {
            record_type = "tools";
        }

        if (filter_id.includes("accounts")) {
            record_type = "accounts";
        }

        $("#query-in").val(record_type);
        $("#filter-" + record_type + "-button").hide();
        $("#filter-" + record_type + "-indicator").show();

        getGeneralRecords();
    });

    //get the records duirng the search input (Workspace Page)
    $(document).on("input", ".workspace-search-container input", function () {
        var workspace_search_input = $(this).val();

        if (workspace_search_input) {
            if (workspace_search_input.length >= 3) {
                getGeneralRecords();
            }
        } else {
            getGeneralRecords();
        }
    });

    //show the records shared with the searched user
    $("#show-shared-button").click(function () {
        $(this).hide();
        $("#by-user-indicator").hide();
        $("#show-shared-indicator").show();
        $("#by-user-button").show();
        $("#home-searched-user-show-shared").val("Show Shared");
        getGeneralRecords();
    });

    //show the records of the searched user
    $("#by-user-button").click(function () {
        $(this).hide();
        $("#by-user-indicator").show();
        $("#show-shared-indicator").hide();
        $("#show-shared-button").show();
        $("#home-searched-user-show-shared").val("");
        getGeneralRecords();
    });

    //set the content id and reset the workspace with its records
    $(document).on("click", "#sidebar-actions a", function () {
        resetAlerts();
        var sidebar_action_id = $(this).attr("id");
        var content_id = sidebar_action_id.match(/\d+/)[0];

        $("#content_id").val(content_id);
        initializeActualWorkspace();
        //if in tablet or mobile mode, hide the sidebar to give full space to the actual workspace
        //the list icon is shown while the edit icon is hidden
        if ($(window).width() < 1024) {
            $(".workspace-sidebar").css("visibility", "hidden");
            $(".actual-workspace").css("visibility", "visible");
            $(".edit-icon").hide();
            $(".list-icon").show();
        }
    });

    //set the content id to null and reset the workspace
    $("#new-button").click(function () {
        $("#content_id").val("");
        resetAlerts();
        initializeActualWorkspace();
    });

    //by pressing ENTER key, add the typed category to the list of categories
    $("#new-category").keydown(function (event) {
        if (event.keyCode === 13) {
            addCategory();
        }
    });

    $("#show-all-categories").click(function () {
        showCategoriesModal();
    });

    $(document).on("click", "#all-categories-list span", function () {
        let delete_button_id = $(this).attr("id");
        const category_id = delete_button_id.match(/\d+/)[0];
        const category_name = $(this).text();
        deleteCategory(category_id, category_name);
    });

    //update the categories by deleting one
    $("#delete-category-button").click(function () {
        var category_action = "delete";
        updateCategory(category_action);
    });

    //toggle the tags container
    $("#show-tags-container-button").click(function () {
        $("#tags-container").toggle();
    });

    //toggle the shared with container
    $("#show-shared-with-container-button").click(function () {
        $("#shared-with-container").toggle();
    });

    //add the clicked tag to the used tags
    $(document).on("click", "#show-tags-available span", function () {
        var newValue = $(this).attr("id");
        pushTag(newValue);
    });

    //remove the clicked tag from the used tags
    $(document).on("click", "#show-tags-used span", function () {
        var newValue = $(this).text();
        removeTag(newValue);
    });

    //see all the tags
    $("#searched-tag").on("input", function () {
        var searched_tag = $("#searched-tag").val();
        if (searched_tag.length > 2) {
            getTags();
        }

        if (searched_tag.length == 0) {
            getTags();
        }
    });

    //by pressing ENTER key, add the typed tag to the list of tags
    $("#new-tag").keydown(function (event) {
        if (event.keyCode === 13) {
            addTag();
        }
    });
    //by clicking the button, add the typed tag to the list of tags
    $("#new-tag-button").click(function () {
        addTag();
    });

    //delete tag
    $(document).on("click", "#show-tags-available img", function () {
        var button_id = $(this).attr("id");
        var tag_id = button_id.match(/\d+/)[0];
        deleteTag(tag_id);
    });

    //show comment modal
    $("#comment-button, #content-message-button").click(function () {
        $("#comment-modal-shown").val("show");
        showCommentModal();
    });

    $("#submit-comment").click(function () {
        submitComment();
    });

    $("#submit-comment").keydown(function (event) {
        if (event.keyCode === 13) {
            submitComment();
        }
    });

    $("#message-searched-user").on("input", function () {
        getMessageUsers();
    });

    $("#message-modes span").click(function () {
        let button_text = $(this).text();
        $("#message-mode").val(button_text);
        $("#thread-message-mode").val(button_text);
        $("#message-modes span").removeClass("active");

        $(this).addClass("active");
        getMessageUsers();
        getThreadMessages();
    });

    $("#message-admin-button").click(function () {
        $("#thread-message-mode").val("Message Manager");
        $("#message-recipient-profile-picture").attr(
            "src",
            public_folder + "/assets/images/eskquip-icon-new.png",
        );
        $("#message-recipient-account-name").text("EskQuip Support");
        $("#message-recipient-accounts").text("Educational App");
        $("#admin-message-thread-code").val("TOADMIN" + registrant_code);

        getThreadMessages();
    });

    $(document).on("click", "#message-user-action span", function () {
        let button_id = $(this).attr("id");
        let recipient_id = button_id.match(/\d+/)[0];
        let recipient_code = $("#code-for-message-user-" + recipient_id).val();
        $("#message-recipient-code").val(recipient_code);

        let message_mode = $("#message-mode").val();
        let thread_message_mode = $("#thread-message-mode").val();

        if (message_mode != thread_message_mode) {
            $("#thread-message-mode").val("Personal");
        }

        if (message_mode == "Message Manager") {
            $("#admin-message-thread-code").val("TOADMIN" + recipient_code);
        }
        getMessageRecipientAccounts();
        getThreadMessages();
    });

    $("#send-message-button").click(function () {
        sendMessage();
    });

    $("#updates-button").click(function () {
        $("#modal-updates").show();
        getUpdates();
    });

    $("#searched-update").on("input", function () {
        let searched_update = $("#searched-update").val();

        if (searched_update.length > 2) {
            getUpdates();
        }

        if (searched_update.length == 0) {
            getUpdates();
        }
    });

    $("#new-update").click(function () {
        $("#updates-result").hide();
        $("#update-item").show();
        $(this).hide();
        $("#update-id").val("");
        $("#update-mode").val("new");
        $("#update-type option:selected").val("");
        $("#update-type option:selected").text("Update Type");
        $("#update-title").val("");
        $("#update-content").val("");
        $("#edit-update-actions").hide();
        $("#submit-update-button").show();
        $("#submit-update-button").text("Add");
    });

    $("#back-to-updates-result").click(function () {
        $("#updates-result").show();
        $("#update-item").hide();
        $("#new-update").show();
    });

    $("#submit-update-button").click(function () {
        submitUpdate();
    });

    $(document).on("click", "#update-actions span", function () {
        let button_id = $(this).attr("id");
        let update_id = button_id.match(/\d+/)[0];

        if (button_id.includes("edit")) {
            $("#updates-result").hide();
            $("#update-item").show();
            $("#update-mode").val("edit");
            $("#submit-update-button").text("Update");
            $("#update-id").val(update_id);

            getUpdateDetails();
        }
    });

    $("#update-item-actions span").click(function () {
        let button_id = $(this).attr("id");
        let action = "";

        if (button_id.includes("to-publish")) {
            action = "publish";
        }

        if (button_id.includes("to-unpublish")) {
            action = "unpublish";
        }

        if (button_id.includes("to-duplicate")) {
            action = "duplicate";
        }

        if (button_id.includes("to-delete")) {
            action = "delete";
        }

        if (action == "publish" || action == "unpublish") {
            updateUpdateStatus(action);
        }

        if (action == "delete") {
            showConfirmDeleteUpdateModal();
        }

        if (action == "duplicate") {
            duplicateUpdate();
        }
    });

    $(document).on("click", ".read-update", function () {
        let button_id = $(this).attr("id");
        let update_id = button_id.match(/\d+/)[0];

        readUpdate(update_id);
    });

    $("#hide-update-read").click(function () {
        $("#update-filter").show();
        $("#updates-result").show();
        $("#update-read").hide();
        getUpdates();
    });

    $("#confirmed-delete-update-button").click(function () {
        deleteUpdate();
    });

    $("#close-confirm-delete-update-modal-button").click(function () {
        $("#modal-confirm-delete-update").hide();
    });

    //add the clicked user to the shared with list
    $(document).on("click", "#show-users-not-shared span", function () {
        var newValue = $(this).attr("id");
        pushUser(newValue);
    });

    //remove the clicked user from the shared with list
    $(document).on("click", "#show-users-shared span", function () {
        var newValue = $(this).attr("id");
        removeUser(newValue);
    });

    //see all the users
    $("#searched-user").on("input", function () {
        var searched_user = $("#searched-user").val();
        if (searched_user.length > 2) {
            getUsers();
        }

        if (searched_user.length == 0) {
            getUsers();
        }
    });

    //show the content
    $("#show-content-button").click(function () {
        initializeShowContent();
    });

    $("#replace-content-button").click(function () {
        resetAlerts();
        $(this).hide();
        $("#content-iframe").hide();
        $("#replace-content-container").show();
        $("#replace-content-submit-button").show();
        $("#retain-content-button").show();
    });

    $("#retain-content-button").click(function () {
        resetAlerts();
        $(this).hide();
        $("#content-iframe").show();
        $("#replace-content-container").hide();
        $("#replace-content-submit-button").hide();
        $("#replace-content-button").show();
    });

    //replace the content
    $("#replace-content-submit-button").click(function () {
        resetAlerts();
        replaceContentSubmit();
    });

    //show the content image
    $("#show-image-button").click(function () {
        showContentImage();
    });

    //show the upload image when a button to change the image is clicked
    $("#change-image-button").click(function () {
        $("#modal-show-image").hide();
        showUploadImageModal();
    });

    //show the upload image modal when a button to update the image is clicked
    $("#update-image-button").click(function () {
        showUploadImageModal();
    });

    //get the content's content for the given version
    $(document).on("click", "#content-versions a", function () {
        var button_id = $(this).attr("id");
        const version_number = button_id.match(/\d+/)[0];
        getVersionContent(version_number);
    });

    //save the content
    $("#save-content-button").click(function () {
        saveContent();
    });

    //perfom actions for content based on the button type
    $(".workspace-action-buttons button").click(function () {
        var action_id = $(this).attr("id");
        var content_id = $("#content_id").val();

        if (action_id.includes("delete")) {
            var proceed_delete = true;

            if (page_name == "Workspace - Developer") {
                var tool_files = $("#tool-files").val();

                if (tool_files > 0) {
                    proceed_delete = false;
                } else {
                    proceed_delete = true;
                }
            }

            if (proceed_delete) {
                $("#modal-confirm-delete-content").show();
                $("#delete-content-id").val(content_id);
                $("#delete-sub-content-id").val("");
                $("#delete-content-processing-file").val(
                    contents_processing_file,
                );
            } else {
                if (page_name == "Workspace - Developer") {
                    alert("Please delete the file (s) to proceed.");
                }
            }
        }

        if (!action_id.includes("delete")) {
            var action = "";

            if (action_id.includes("to-publish")) {
                action = "publish";

                if (page_name == "Workspace - Developer") {
                    var tool_files = $("#tool-files").val();

                    if (tool_files == 0) {
                        alert("Please uplaod a file to publish this tool.");
                    }

                    if (tool_files > 0) {
                        updateContentStatus(action);
                    }
                }

                if (page_name != "Workspace - Developer") {
                    if (
                        page_name == "Workspace - Teacher" ||
                        page_name == "School Workspace - Researches"
                    ) {
                        var content_status = $("#status").val();

                        if (content_status == "Draft") {
                            $("#modal-confirm-publish").show();
                        } else {
                            updateContentStatus(action);
                        }
                    }

                    if (
                        page_name != "Workspace - Teacher" &&
                        page_name != "School Workspace - Researches"
                    ) {
                        updateContentStatus(action);
                    }
                }
            }

            if (action_id.includes("to-unpublish")) {
                action = "unpublish";
                updateContentStatus(action);
            }

            if (action_id.includes("review")) {
                action = "review";
                updateContentStatus(action);
            }

            if (action_id.includes("take")) {
                action = "take";
                updateContentStatus(action);
            }

            if (action_id.includes("leave")) {
                action = "leave";
                updateContentStatus(action);
            }

            if (action_id.includes("approve")) {
                action = "approve";
                updateContentStatus(action);
            }
        }
    });

    $("#tool-file-upload-submit-button").click(function () {
        uploadToolFile();
    });

    //show sub content modal based on the button type
    $(document).on("click", "#tool-file-actions .link-tag-button", function () {
        var tool_file_action_id = $(this).attr("id");
        const number = tool_file_action_id.match(/\d+/)[0];
        var content_id = $("#content_id").val();

        if (tool_file_action_id.includes("delete")) {
            $("#modal-confirm-delete-content").show();
            $("#delete-content-id").val(content_id);
            $("#delete-sub-content-id").val(number);
        }
    });

    //show confirm publish modal for teacher file and research content
    $("#confirmed-publish-button").click(function () {
        $("#modal-confirm-publish").hide();
        var action = "publish";
        updateContentStatus(action);
    });

    //toggle the record categories on the site manager workspace and get initial records
    $("#sm-record-category").on("input", function () {
        toggleRecords();
    });

    //toggle the record categories on the site manager workspace and get filtered records
    $(".record-input").on("input", function () {
        toggleRecords();
        getSiteManagerRecords();
    });

    //show the inputs for creating personal account
    $("#create-account-personal-button").click(function () {
        resetAlerts();

        $(this).addClass("active");
        $("#create-account-school-button").removeClass("active");

        $("#create-account-school-button").show();
        $(".inputs-group-personal").show();
        $(".inputs-group-personal").css("display", "flex");
        $(".inputs-group-school").hide();
        $(".inputs-group-general").show();
        $("#create-type").val("Personal");
    });

    //show the inputs for creating school account
    $("#create-account-school-button").click(function () {
        resetAlerts();
        $(this).addClass("active");
        $("#create-account-personal-button").removeClass("active");

        $("#create-account-personal-button").show();
        $(".inputs-group-school").show();
        $(".inputs-group-personal").hide();
        $(".inputs-group-general").show();
        $("#create-type").val("School");
    });

    //create the account
    $("#create-submit-button").click(function () {
        createAccount();
    });

    //login when the button is clicked
    $("#login-submit-button").click(function () {
        login();
    });

    //login when the ENTER Key is pressed
    $("#login-email-username, #login-password").keydown(function (event) {
        if (event.keyCode === 13) {
            login();
        }
    });

    //get the password reset link when the button is clicked
    $("#get-password-reset-link-submit-button").click(function () {
        getPasswordResetLink();
    });

    //reset the password
    $("#reset-password-submit-button").click(function () {
        resetPassword();
    });

    //logout
    $("#logout-button").click(function () {
        showConfirmLogoutModal();
    });

    $("#confirmed-logout-button").click(function () {
        logout();
    });

    //show the amount input if the access type is 'Purchased'
    $("#access-type").on("input", function () {
        var access_type = $("#access-type option:selected").val();
        if (access_type == "Purchased") {
            $("#amount").show();
        } else {
            $("#amount").hide();
        }
    });

    //show uplaod image modal for cover photo
    $("#cover-photo-camera-icon").click(function () {
        resetAlerts();
        $("#modal-upload-image").show();
        $("#upload-type").val("Cover Photo");
        $("#upload-button").text("Update Cover Photo");
        $("#upload-action-file").val(public_folder + "/upload-cover-photo");
    });

    //show uplaod image modal for profile picture
    $("#profile-picture-camera-icon").click(function () {
        resetAlerts();
        $("#modal-upload-image").show();
        $("#upload-type").val("Profile Picture");
        $("#upload-button").text("Update Profile Picture");
        $("#upload-action-file").val(public_folder + "/upload-profile-picture");
    });

    //upload the image
    $("#upload-button").click(function () {
        uploadSubmit();
    });

    //show editable profile details
    $("#edit-profile-details-button").click(function () {
        $("#modal-update-profile").show();
        $(".profile-details-edit").show();
    });

    //update profile details
    $("#update-profile-details-submit-button").click(function () {
        updateProfileDetails();
    });

    //cancel updating profile details
    $("#update-profile-details-cancel-button").click(function () {
        $("#modal-update-profile").hide();
        // getProfile();
    });

    //show the subscription modal
    $("#show-my-subscription-button").click(function () {
        showSubscriptionModal();
    });

    $("#subscription-type").on("input", function () {
        let subscription_type = $(this).val();
        initializeSubscription(subscription_type);
    });

    $(document).on("click", ".subscription-actions span", function () {
        let button_id = $(this).attr("id");
        let subscription_id = button_id.match(/\d+/)[0];
        $("#modal-subscriptions-list").hide();
        $("#modal-subscription-other-details").show();
        $("#subscription-message-container").hide();
        $("#subscription-type").hide();
        getSubscriptionDetails(subscription_id);
    });

    $("#subscription-details-payment-proof").click(function () {
        $("#modal-subscription-other-details").hide();
        $("#modal-subscription-payment-proof-view-container").show();
    });

    $("#show-subscription-other-details-button").click(function () {
        $("#modal-subscription-other-details").show();
        $("#modal-subscription-payment-proof-view-container").hide();
    });

    $("#hide-subscription-other-details-button").click(function () {
        $("#modal-subscriptions-list").show();
        $("#modal-subscription-other-details").hide();
        $("#subscription-message-container").show();
        $("#subscription-type").show();
    });
    //show the subscription summary
    $("#subscription-duration").change(function () {
        var subscriptionDuration = $("#subscription-duration").val().toString();
        var subscriptionType = $("#subscription-type").val();

        if (subscriptionType == "Tools") {
            subscriptionAmount = 69;
        }

        if (subscriptionType == "Seller") {
            subscriptionAmount = 100;
        }

        var subscriptionTotal = subscriptionDuration * subscriptionAmount;
        $("#subscription-summary").show();
        $("#subscription-summary").text(
            "Total: " +
                "₱" +
                subscriptionTotal +
                " (₱" +
                subscriptionAmount +
                " x " +
                subscriptionDuration +
                " month)",
        );

        $("#subscription-total").val(subscriptionTotal);
    });

    //subscribe
    $("#submit-subscription-button").click(function () {
        subscribe();
    });

    $(document).on("click", ".message-seller", function () {
        const button_id = $(this).attr("id");
        const seller_code = button_id.match(/\d+/)[0];

        var message_page = public_folder + "/messages/" + "?sc=" + seller_code;
        window.open(message_page, "_blank");
    });

    //reset the duration and summary when switching to another subcription type
    $("#subscription-type").change(function () {
        $("#subscription-duration").val("");
        $("#subscription-summary").hide();
    });

    //show the other registration modal
    $("#show-other-registration-button").click(function () {
        $("#modal-other-registration").show();
    });

    //get the modal for teacher regsitration
    $("#regtype-buttons button").click(function () {
        let regtype = $(this).text();
        $("#regtype-buttons button").removeClass("active");
        $(this).addClass("active");
        $("#regtype-hidden").val(regtype);
        checkRegistration(regtype);
    });

    //register
    $("#register-button").click(function () {
        register();
    });

    //show workspace list
    $("#show-workspace-button").click(function () {
        $("#modal-workspace-list").show();
    });

    // close the modal
    $(".close-modal").click(function () {
        $(".website-modal-wrapper").hide();
        $("#inputs-message").html("");
        $("#show-comment-modal").val("");
    });

    $("#close-inputs-message-modal").click(function () {
        $("#modal-inputs-message").hide();
    });

    // show live content details
    $(".show-details").click(function () {
        $(".live-content-details-container").show();
        $(".hide-details").show();
        $(this).hide();
    });

    // hide live content details
    $(".hide-details").click(function () {
        $(".live-content-details-container").hide();
        $(".show-details").show();
        $(this).hide();
    });

    //show purchase modal
    $(document).on("click", ".purchase", function () {
        var button_id = $(this).attr("id");
        var content_id = button_id.match(/\d+/)[0];

        if (button_id.includes("teacher-file")) {
            content_type = "Teacher File";
        }

        if (button_id.includes("article")) {
            content_type = "Article";
        }

        if (button_id.includes("research")) {
            content_type = "Research";
        }

        if (button_id.includes("tool")) {
            content_type = "Tool";
        }

        showPurchaseModal(content_type, content_id);
    });

    //purchase
    $("#purchase-submit-button").click(function () {
        purchase();
    });

    //get content purchases (initial)
    $("#show-content-purchases-button").click(function () {
        $("#filtered-status").val("");
        $("#purchases-status-filter small").removeClass("active");
        $("#purchase-status-all").addClass("active");

        contentPurchases();
    });

    //get content purchases (filtered)
    $("#purchases-status-filter small").click(function () {
        var status = $(this).text();
        if (status == "All") {
            status = "";
        }

        $("#filtered-status").val(status);
        $("#purchases-status-filter small").removeClass("active");
        $(this).addClass("active");

        contentPurchases();
    });

    //perform operation on purchase based on the button type
    $(document).on("click", ".purchase-action-buttons small", function () {
        var button_id = $(this).attr("id");
        var purchase_id = button_id.match(/\d+/)[0];

        if (button_id.includes("view-proof")) {
            hidePurchasesList(purchase_id);
        }

        if (button_id.includes("approve")) {
            var action = "approve";
            updatePurchaseStatus(purchase_id, action);
        }

        if (button_id.includes("reject")) {
            var action = "reject";
            updatePurchaseStatus(purchase_id, action);
        }

        if (button_id.includes("revoke")) {
            var action = "revoke";
            updatePurchaseStatus(purchase_id, action);
        }

        if (button_id.includes("keep")) {
            var action = "keep";
            updatePurchaseStatus(purchase_id, action);
        }
    });
    //show purchases list
    $(document).on("click", "#hide-purchase-proof", function () {
        showPurchasesList();
    });

    //redirect to message page
    $(document).on("click", ".message", function () {
        var message_page = public_folder + "/messages";
        window.location.href = message_page;
    });

    //show purchase seller details
    $("#show-seller-details-button").click(function () {
        $(this).hide();
        $("#purchase-purchase-details").hide();
        $("#purchase-seller-details").show();
        $("#show-purchase-details-button").show();
    });

    //hide purchase seller details
    $("#show-purchase-details-button").click(function () {
        $(this).hide();
        $("#purchase-purchase-details").show();
        $("#purchase-seller-details").hide();
        $("#show-seller-details-button").show();
    });

    //show my seller details
    $("#show-my-seller-details-button").click(function () {
        resetAlerts();
        showMySellerDetails();
    });

    //update my seller details
    $("#update-my-seller-details-submit-button").click(function () {
        updateMySellerDetails();
    });

    //on table or mobile mode, show the actual workspace
    $(".edit-icon").click(function () {
        $(".actual-workspace").css("visibility", "visible");
        $(".list-icon ").show();
        $(".edit-icon").hide();
        $(".workspace-sidebar").css("visibility", "hidden");
        var content_id = "#content-id".val();
        if (content_id) {
            $(".workspace-actions-container").show();
        }
    });

    //on table or mobile mode, show the contents list
    $(".list-icon").click(function () {
        $(".workspace-sidebar").css("visibility", "visible");
        $(".edit-icon ").show();
        $(".list-icon").hide();
        $(".actual-workspace").css("visibility", "hidden");
        $(".workspace-actions-container").hide();
    });

    //delete contents
    $("#confirmed-delete-content-button").click(function () {
        deleteContents();
    });

    //perform operation on site manager record based on the button type
    $(document).on("click", "#sm-record-actions span", function () {
        resetAlerts();
        var action_id = $(this).attr("id");
        var number = action_id.match(/\d+/)[0];
        var to_do = "";

        if (action_id.includes("revoke")) {
            to_do = "revoke";
        }

        if (action_id.includes("keep")) {
            to_do = "keep";
        }

        if (action_id.includes("approve")) {
            to_do = "approve";
        }

        if (action_id.includes("reject")) {
            to_do = "reject";
        }

        if (action_id.includes("to-publish")) {
            to_do = "publish";
        }

        if (action_id.includes("to-unpublish")) {
            to_do = "unpublish";
        }

        if (action_id.includes("delete")) {
            to_do = "delete";
        }

        if (to_do != "delete") {
            if (action_id.includes("registration")) {
                var regtype = "";

                if (action_id.includes("Teacher")) {
                    regtype = "teacher";
                }

                if (action_id.includes("Writer")) {
                    regtype = "writer";
                }

                if (action_id.includes("Editor")) {
                    regtype = "editor";
                }

                if (action_id.includes("Developer")) {
                    regtype = "developer";
                }

                if (action_id.includes("Researches")) {
                    regtype = "researches";
                }

                updateRegistration(to_do, number, regtype);
            }

            if (action_id.includes("note")) {
                if (action_id.includes("show")) {
                    number = action_id.match(/\d+/)[0];
                    $("#modal-notes-container-" + number).show();
                    toggleClearNoteButton(number);
                }
            }

            if (action_id.includes("subscription")) {
                if (action_id.includes("Tools")) {
                    subscription_type = "Tools";
                }

                if (action_id.includes("Seller")) {
                    subscription_type = "Seller";
                }

                if (action_id.includes("Shelf")) {
                    subscription_type = "Shelf";
                }

                if (action_id.includes("Files")) {
                    subscription_type = "Files";
                }

                updateSubsciption(to_do, number, subscription_type);
            }

            if (action_id.includes("promotion")) {
                if (action_id.includes("show")) {
                    $("#modal-promotion").show();
                    var update_promotion_id = number;
                    var update_promotion_name_company = $(
                        "#update-promotion-name-company-" + number,
                    ).val();
                    var update_promotion_title = $(
                        "#update-promotion-title-" + number,
                    ).val();
                    var update_promotion_type = $(
                        "#update-promotion-type-" + number,
                    ).val();
                    var update_promotion_topics = $(
                        "#update-promotion-topics-" + number,
                    ).val();
                    var update_promotion_description = $(
                        "#update-promotion-description-" + number,
                    ).val();
                    var update_promotion_link = $(
                        "#update-promotion-link-" + number,
                    ).val();
                    var update_promotion_duration = $(
                        "#update-promotion-duration-" + number,
                    ).val();
                    var update_promotion_amount = $(
                        "#update-promotion-amount-" + number,
                    ).val();
                    var update_promotion_image_link = $(
                        "#update-promotion-image-link-" + number,
                    ).val();
                    var update_promotion_agreement_link = $(
                        "#update-promotion-agreement-link-" + number,
                    ).val();

                    $("#show-promotion-image-button").show();
                    $("#show-promotion-agreement-button").show();

                    $("#promotion-id").val(update_promotion_id);
                    $("#promotion-name-company").val(
                        update_promotion_name_company,
                    );
                    $("#promotion-title").val(update_promotion_title);
                    $("#promotion-type").val(update_promotion_type);
                    $("#promotion-topics").val(update_promotion_topics);
                    $("#promotion-description").val(
                        update_promotion_description,
                    );
                    $("#promotion-link").val(update_promotion_link);
                    $("#promotion-duration").val(update_promotion_duration);
                    $("#promotion-amount").val(update_promotion_amount);
                    $("#promotion-image-link").val(update_promotion_image_link);
                    $("#promotion-agreement-link").val(
                        update_promotion_agreement_link,
                    );
                    $("#promotion-submit-button").text("Update");
                }

                if (action_id.includes("status")) {
                    var promotion_duration = $("#promotion-duration").val();
                    updatePromotionStatus(to_do, number, promotion_duration);
                }
            }
        }

        if (to_do == "delete") {
            var record_category = "";

            if (action_id.includes("promotion")) {
                record_category = "Promotion";
            }
            showConfirmDeleteRecordModal(number, record_category);
        }
    });

    $(document).on(
        "click",
        "#website-manager-registration-buttons span",
        function () {
            let button_id = $(this).attr("id");
            let record_id = button_id.match(/\d+/)[0];
            let action = "";
            let website_manager_type = "";

            if (button_id.includes("to-register")) {
                action = "register";
            }

            if (button_id.includes("to-unregister")) {
                action = "unregister";
            }

            if (button_id.includes("super")) {
                website_manager_type = "Super Manager";
            }

            if (button_id.includes("registration")) {
                website_manager_type = "Registration Manager";
            }

            if (button_id.includes("subscription")) {
                website_manager_type = "Subscription Manager";
            }

            if (button_id.includes("promotion")) {
                website_manager_type = "Promotion Manager";
            }

            if (button_id.includes("message")) {
                website_manager_type = "Message Manager";
            }

            if (website_manager_type != "Super Manager") {
                updateWebsiteManagerRegistrations(
                    record_id,
                    website_manager_type,
                    action,
                );
            }

            if (website_manager_type == "Super Manager") {
                showConfirmUpdateSuperManagerRegistrationModal(
                    record_id,
                    website_manager_type,
                    action,
                );
            }
        },
    );

    $("#confirmed-update-super-manager-registration-button").click(function () {
        confirmUpdateSuperManagerRegistration();
    });

    $(document).on("click", "#new-promotion", function () {
        newPromotion();
    });

    $("#show-promotion-image-button").click(function () {
        const promotion_image_link = $("#promotion-image-link").val();
        $("#modal-promotion-image").show();
        $("#promotion-image-view").attr(
            "src",
            private_folder + promotion_image_link,
        );
    });

    $("#close-modal-promotion-image").click(function () {
        $("#modal-promotion-image").hide();
    });

    $("#show-promotion-agreement-button").click(function () {
        const promotion_agreement_link = $("#promotion-agreement-link").val();
        $("#modal-promotion-agreement").show();
        $("#promotion-agreement-view").attr(
            "src",
            private_folder + promotion_agreement_link,
        );
    });

    $("#close-modal-promotion-agreement").click(function () {
        $("#modal-promotion-agreement").hide();
    });

    $(document).on("click", "#promotions-list a", function () {
        let button_id = $(this).attr("id");
        let promotion_id = button_id.match(/\d+/)[0];
        recordPromotionClick(promotion_id);
    });

    $("#confirmed-delete-record-button").click(function () {
        deleteRecords();
    });

    $(".note-buttons-container button").click(function () {
        submitNote();
    });

    $("#promotion-submit-button").click(function () {
        submitPromotion();
    });

    $(document).on("click", ".proof-of-payment span", function () {
        const proof_of_payment_link = $(this).attr("id");

        showSubscriptionProofOfPayment(proof_of_payment_link);
    });

    $("#close-modal-subscription-proof-of-payment").click(function () {
        $("#modal-subscription-proof-of-payment").hide();
    });

    $(document).on("click", ".other-registration-agreement span", function () {
        const other_registration_agreement_link = $(this).attr("id");
        showOtherRegsitrationAgreement(other_registration_agreement_link);
    });

    $("#close-modal-other-registration-agreement").click(function () {
        $("#modal-other-registration-agreement").hide();
    });

    $(document).on(
        "click",
        ".other-registration-license-certification span",
        function () {
            const other_registration_license_certification_link =
                $(this).attr("id");

            showOtherRegsitrationLicenseCertification(
                other_registration_license_certification_link,
            );
        },
    );

    $("#close-modal-other-registration-license-certification").click(
        function () {
            $("#modal-other-registration-license-certification").hide();
        },
    );

    //WHEN THE DOCUMENT IS READY, RECORD THE TIME SPENT ON THE CONTENT ON SCROLL AND OPEN TAB.
    let timeSpentSeconds = 0;
    let scrollTimeout;
    let isScrolling = false;

    // check if the page is scrolled
    isScrolling = true;
    scrollTimeout = setTimeout(() => {
        isScrolling = false;
    }, 10000);

    window.addEventListener("scroll", () => {
        isScrolling = true;
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            isScrolling = false;
        }, 10000);
    });

    setInterval(trackTime, 1000);

    window.addEventListener("beforeunload", function () {
        if (timeSpentSeconds > 0) {
            navigator.sendBeacon(
                current_url.href,
                new URLSearchParams({
                    time_spent: Math.ceil(timeSpentSeconds / 60),
                }),
            );
        }
    });
});
