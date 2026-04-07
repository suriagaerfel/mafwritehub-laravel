//updateURLParameter
function updateUrlParameter(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);

    // window.history.replaceState({}, "", url);
    // Or use: window.history.pushState({}, '', url);
}

//format date
function formatDate(date) {
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0"); // Month is 0-indexed, so +1
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

//save the current page position
function savePosition() {
    window.addEventListener("DOMContentLoaded", () => {
        const savedPosition = sessionStorage.getItem(current_url.origin);
        if (savedPosition !== null) {
            window.scrollTo(0, parseInt(savedPosition, 10));
        }
    });
}

//hide alerts

function hideAlerts() {
    setTimeout(function () {
        $(".alert").hide();
    }, 8000);
}

//reset alerts
function resetAlerts() {
    $(".alert").hide();
}

//hide modals
function hideModals() {
    setTimeout(function () {
        $(".modal").hide();
    }, 5000);
}

//show inputs error message
function showInputsErrorMessage(errors) {
    $("#modal-inputs-message").show();
    $("#inputs-message").show();
    $("#inputs-message").html("");

    $("#inputs-message").addClass("alert");
    $("#inputs-message").removeClass("alert-success");
    $("#inputs-message").addClass("alert-danger");
    const container = $("#inputs-message");

    $.each(errors, function (index, value) {
        container.append("<small>● " + value + "</small>" + "<br>");
    });
}

//show inputs success message

function showInputsSuccessMessage(success_message) {
    $("#modal-inputs-message").show();
    $("#inputs-message").show();
    $("#inputs-message").html("");

    $("#inputs-message").addClass("alert");
    $("#inputs-message").removeClass("alert-danger");
    $("#inputs-message").addClass("alert-success");
    $("#inputs-message").html("<small>" + success_message + "</small>");
}

//toggle header page navigation for mobile and table
function toggleMobileTabletNavigation() {
    var buttonforShowMobileTabletNavigation = document.getElementById(
        "show-mobile-navigation",
    );
    var mobileTabletNavigation = document.getElementById(
        "page-navigation-container-mobile-tablet",
    );
    var buttonforHideMobileTabletNavigation = document.getElementById(
        "hide-mobile-navigation",
    );

    if (mobileTabletNavigation.style.display === "none") {
        mobileTabletNavigation.style.display = "block";
        buttonforHideMobileTabletNavigation.style.display = "block";
        buttonforShowMobileTabletNavigation.style.display = "none";
    } else {
        mobileTabletNavigation.style.display = "none";
        buttonforHideMobileTabletNavigation.style.display = "none";
        buttonforShowMobileTabletNavigation.style.display = "block";
    }
}

//refresh page
function pageRefresh() {
    // getProfile();
    getUpdates();

    if (registrant_id) {
        getUnreadMessages();
        getUnreadUpdates();
    }

    if (
        page_name != "Login" &&
        page_name != "Create Account" &&
        page_name != "Get Password Reset Link" &&
        page_name != "Reset Password"
    ) {
        $("#home-page-account-details").hide();

        if (!slug) {
            if (page_name != "Home") {
                if (!page_name.includes("Workspace")) {
                    if (page_name != "My Account" && page_name != "Messages") {
                        $("#website-sidebar").show();
                        getPromotions();
                    }

                    if (page_name == "Messages") {
                        getMessageUsers();
                    }

                    if (page_name != "My Account") {
                        getGeneralRecords();
                    }
                }

                if (page_name.includes("Workspace")) {
                    getContentComments();

                    if (page_name == "Workspace - Website Manager") {
                        getSiteManagerRecords();
                    }

                    if (page_name != "Workspace - Website Manager") {
                        getGeneralRecords();
                    }
                }
            }

            if (page_name == "Home") {
                $("#website-sidebar").show();
                // getGeneralRecords();
                // getPromotions();
            }
        }

        if (slug) {
            $("#website-sidebar").show();
            getPromotions();
        }
    }
}

// function getProfile() {
//     $.ajax({
//         url: public_folder + "/get-profile",
//         dataType: "json",
//         type: "POST",
//         async: true,
//         data: {
//             home_searched_user: home_searched_user,
//             get_profile_submit: true,
//         },
//         success: function (responses) {
//             console.log(responses);

//             if (responses) {
//                 if (home_searched_user) {
//                     $("#cover-photo").attr(
//                         "src",
//                         responses["cover-photo-link"],
//                     );
//                     $("#profile-picture").attr(
//                         "src",
//                         responses["profile-picture-link"],
//                     );
//                     $("#profile-account-name-view").text(
//                         responses["account-name"],
//                     );
//                     $("#profile-registrations-view").text(
//                         responses["registrations"],
//                     );

//                     $("#account-contents-filter").show();

//                     $("#by-user-indicator").text("Own Contents");

//                     $("#by-user-button").text("Own Contents");

//                     $("#show-shared-indicator").text("Shared With Contents");

//                     $("#show-shared-button").show();
//                     $("#show-shared-button").text("Shared With Contents");

//                     if (responses["description"]) {
//                         $("#profile-description-view-searched").show();
//                         $("#profile-description-view-searched").text(
//                             "Description: " + responses["description"],
//                         );
//                     }

//                     if (!responses["description"]) {
//                         $("#profile-description-view-searched").hide();
//                     }

//                     $("#profile-details").hide();
//                     $("#profile-picture-camera-container").hide();
//                     $("#cover-photo-camera-container").hide();
//                 }

//             }
//         },
//     });
// }

function submitComment() {
    let content_id = $("#content_id").val();
    let content_comment = $("#content-comment").val();

    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            content_id: content_id,
            content_comment: content_comment,
            role: role,
            content_type: content_type,
            registrnt_id: registrant_id,
            comment_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["status"] == "Successful") {
                $("#content-comment").val("");
                showCommentModal();
                initializeActualWorkspace();
            }
        },
    });
}

function getPromotions() {
    let searched_promotion = $("#searched-promotion").val();

    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            content_type: content_type,
            searched_promotion: searched_promotion,
            get_promotions_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            $("#promotions-list").html(responses);
        },
    });
}

function getUpdates() {
    let searched_update = $("#searched-update").val();
    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            searched_update: searched_update,
            registrant_code: registrant_code,
            registrant_id: registrant_id,
            wm_message_manager_registration: wm_message_manager_registration,
            wm_super_manager_registration: wm_super_manager_registration,
            get_updates_submit: true,
        },
        success: function (responses) {
            if (responses) {
                $("#updates-result").html(responses);
            }
        },
    });
}

function submitUpdate() {
    let update_mode = $("#update-mode").val();
    let update_id = $("#update-id").val();
    let update_type = $("#update-type option:selected").val();
    let update_title = $("#update-title").val();
    let update_content = $("#update-content").val();

    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            update_mode: update_mode,
            update_id: update_id,
            update_title: update_title,
            update_type: update_type,
            update_content: update_content,
            registrant_id: registrant_id,
            add_update_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["status"] == "Successful") {
                const success_message = responses["success-message"];
                showInputsSuccessMessage(success_message);
                $("#update-id").val(responses["update-id"]);
                $("#new-update").show();
                getUpdateDetails();
                getUpdates();
            }

            if (responses["status"] == "Unsuccessful") {
                const errors = responses["error"];
                showInputsErrorMessage(errors);
            }
        },
    });
}

function getUpdateDetails() {
    let update_id = $("#update-id").val();

    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            update_id: update_id,
            get_update_details_submit: true,
        },
        success: function (responses) {
            if (responses) {
                $("#update-type option:selected").val(responses["update-type"]);
                $("#update-type option:selected").text(
                    responses["update-type"],
                );
                $("#update-title").val(responses["update-title"]);
                $("#update-content").val(responses["update-content"]);

                $("#edit-update-actions").show();

                if (responses["update-status"] != "Published") {
                    $("#to-publish-update-button").show();
                    $("#to-unpublish-update-button").hide();
                    $("#submit-update-button").show();
                    $("#to-delete-update-button").show();
                }

                if (responses["update-status"] == "Published") {
                    $("#to-publish-update-button").hide();
                    $("#to-unpublish-update-button").show();
                    $("#submit-update-button").hide();
                    $("#to-delete-update-button").hide();
                }
            }
        },
    });
}
function updateUpdateStatus(action) {
    let update_id = $("#update-id").val();
    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            action: action,
            update_id: update_id,
            update_update_status_submit: true,
        },
        success: function (responses) {
            if (responses) {
                getUpdateDetails();
                getUpdates();
            }
        },
    });
}

function getMessages() {
    let message_searched_user = $("#message-searched-user").val();
    let message_type = $("#message-type").val();
    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            message_type: message_type,
            message_searched_user: message_searched_user,
            get_message_threads_submit: true,
        },
        success: function (responses) {
            if (responses) {
                $("#message-threads").html(responses);
            }
        },
    });
}

function getMessageUsers() {
    let message_mode = $("#message-mode").val();
    var message_searched_user = $("#message-searched-user").val();

    let ready_get = true;

    // if (message_searched_user) {
    //   if (message_searched_user.length < 2) {
    //     ready_get = false;
    //   }
    // }

    if (ready_get) {
        $.ajax({
            url: general_processing_file,
            type: "POST",
            async: true,
            data: {
                message_searched_user: message_searched_user,
                message_mode: message_mode,
                registrant_code: registrant_code,
                registrant_id: registrant_id,
                get_message_users_submit: true,
            },
            success: function (responses) {
                if (responses) {
                    $("#message-threads").html(responses);
                }
            },
        });
    }
}

function getThreadMessagesSeller() {
    $("#message-recipient-code").val(message_seller_code);
    $("#thread-message-mode").val("Personal");
    getMessageRecipientAccounts();
    getThreadMessages();

    // Get the current URL
    const url = new URL(window.location.href);

    // Use the URLSearchParams delete() method to remove the parameter
    url.searchParams.delete("sc"); // Replace 'parameter_name' with the key you want to remove

    // Update the URL in the browser's address bar without reloading the page
    window.history.pushState({}, "", url); //
}

function getThreadMessages() {
    let message_mode = $("#thread-message-mode").val();
    let admin_message_thread_code = $("#admin-message-thread-code").val();
    let recipient_code = $("#message-recipient-code").val();
    let sender_code = $("#message-sender-code").val();

    $.ajax({
        url: account_processing_file,
        type: "POST",
        async: true,
        data: {
            registrant_id: registrant_id,
            message_mode: message_mode,
            admin_message_thread_code: admin_message_thread_code,
            recipient_code: recipient_code,
            sender_code: sender_code,
            get_thread_messages_submit: true,
        },
        success: function (responses) {
            if (responses) {
                $("#thread-messages-list").html(responses);
            }
        },
    });
}

function getMessageRecipientAccounts() {
    let recipient_code = $("#message-recipient-code").val();
    $.ajax({
        url: account_processing_file,
        type: "POST",
        async: true,
        data: {
            registrant_id: registrant_id,
            recipient_code: recipient_code,
            get_recipient_records_submit: true,
        },
        success: function (responses) {
            if (responses) {
                $("#message-recipient-accounts").text(
                    responses["type"] + " | " + responses["accounts"],
                );
                $("#message-recipient-account-name").text(
                    responses["account-name"],
                );
                $("#message-recipient-profile-picture").attr(
                    "src",
                    responses["profile-picture"],
                );
            }
        },
    });
}

function sendMessage() {
    let admin_message_thread_code = $("#admin-message-thread-code").val();
    let message_mode = $("#thread-message-mode").val();
    let recipient_code = $("#message-recipient-code").val();
    let sender_code = $("#message-sender-code").val();

    const message_content = $("#message-content").val();

    $.ajax({
        url: account_processing_file,
        type: "POST",
        async: true,
        data: {
            registrant_id: registrant_id,
            message_mode: message_mode,
            admin_message_thread_code: admin_message_thread_code,
            recipient_code: recipient_code,
            sender_code: sender_code,
            message_content: message_content,
            send_message_submit: true,
        },
        success: function (responses) {
            if (responses) {
                $("#message-content").val("");
                getThreadMessages();
            }
        },
    });
}

//create account
function createAccount() {
    var create_type = $("#create-type").val();
    var create_school_name = $("#create-school-name").val();
    var create_school_basic_account = $("#create-school-basic-account").val();
    var create_personal_first_name = $("#create-personal-first-name").val();
    var create_personal_last_name = $("#create-personal-last-name").val();
    var create_personal_birthdate = $("#create-personal-birthdate").val();
    var create_personal_gender = $("#create-personal-gender").val();
    var create_email_address = $("#create-email-address").val();
    var create_username = $("#create-username").val();
    var create_password = $("#create-password").val();
    var create_password_retype = $("#create-password-retype").val();

    $.ajax({
        url: public_folder + "/create-account",
        dataType: "json",
        type: "POST",
        async: true,
        data: {
            create_type: create_type,
            create_school_name: create_school_name,
            create_school_basic_account: create_school_basic_account,
            create_personal_first_name: create_personal_first_name,
            create_personal_last_name: create_personal_last_name,
            create_personal_birthdate: create_personal_birthdate,
            create_personal_gender: create_personal_gender,
            create_email_address: create_email_address,
            create_username: create_username,
            create_password: create_password,
            create_password_retype: create_password_retype,
            create_account_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["status"] == "Successful") {
                $("#create-school-name").val("");
                $("#create-school-basic-account").val("");
                $("#create-personal-first-name").val("");
                $("#create-personal-last-name").val("");
                $("#create-personal-birthdate").val("");
                $("#create-personal-gender").val("");
                $("#create-email-address").val("");
                $("#create-username").val("");
                $("#create-password").val("");
                $("#create-password-retype").val("");

                const success_message = responses["success-message"];
                showInputsSuccessMessage(success_message);

                var user_id = responses["user-id"];
                var email_address = responses["email-address"];
                var account_age = "new";

                sendVerificationLink(
                    user_id,
                    email_address,
                    account_processing_file,
                    account_age,
                );
            }

            if (responses["status"] == "Unsuccessful") {
                const errors = responses["error"];
                showInputsErrorMessage(errors);
            }
        },
    });
}

//login
function login() {
    resetAlerts();
    var login_email_username = $("#login-email-username").val();
    var login_password = $("#login-password").val();

    $.ajax({
        url: public_folder + "/login",
        dataType: "json",
        type: "POST",
        async: true,
        data: {
            login_email_username: login_email_username,
            login_password: login_password,
            login_submit: true,
        },
        success: function (response) {
            console.log(response);
            if (response["login-status"] == "Successful") {
                current_url.reload();
            }

            if (response["login-status"] == "Unsuccessful") {
                const errors = response["error"];
                showInputsErrorMessage(errors);

                if (response["logged-in"]) {
                    var user_id = response["user-id"];
                    var email_address = response["email-address"];
                    sendLogoutLink(user_id, email_address);
                }

                if (response["unverified"]) {
                    var user_id = response["user-id"];
                    var email_address = response["email-address"];
                    var account_age = "old";
                    sendVerificationLink(user_id, email_address, account_age);
                }
            }
        },
    });
}

//send logout link
function sendLogoutLink(user_id, email_address) {
    $.ajax({
        url: public_folder + "/send-logout-link",
        dataType: "json",
        type: "POST",
        async: true,
        data: {
            user_id: user_id,
            email_address: email_address,
            send_logout_link_submit: true,
        },
    });
}

//send verification link
function sendVerificationLink(user_id, email_address, account_age) {
    $.ajax({
        url: public_folder + "/send-verification-link",
        dataType: "json",
        type: "POST",
        async: true,
        data: {
            user_id: user_id,
            email_address: email_address,
            account_age: account_age,
            send_verification_link_submit: true,
        },
    });
}

//get password reset link
function getPasswordResetLink() {
    resetAlerts();
    var credential = $("#get-reset-password-link-credential").val();
    $.ajax({
        url: public_folder + "/get-password-reset-link",
        dataType: "json",
        type: "POST",
        async: true,
        data: {
            credential: credential,
            get_password_reset_link_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["status"] == "Successful") {
                const success_message = responses["success-message"];
                showInputsSuccessMessage(success_message);
            }

            if (responses["status"] == "Unsuccessful") {
                const errors = responses["error"];
                showInputsErrorMessage(errors);
            }
        },
    });
}

//reset password
function resetPassword() {
    resetAlerts();
    var user_code = $("#reset-user-code").val();
    var new_password = $("#reset-new-password").val();
    var new_password_retype = $("#reset-new-password-retype").val();

    console.log(user_code);
    console.log(new_password);
    console.log(new_password_retype);

    $.ajax({
        url: public_folder + "/reset-password",
        dataType: "json",
        type: "POST",
        async: true,
        data: {
            user_code: user_code,
            new_password: new_password,
            new_password_retype: new_password_retype,
            reset_password_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["status"] == "Unsuccessful") {
                const errors = responses["error"];
                showInputsErrorMessage(errors);
            }

            if (responses["status"] == "Successful") {
                const success_message = responses["success-message"];
                showInputsSuccessMessage(success_message);

                setInterval(function () {
                    var login_page = public_folder + "/login";
                    window.location.href = login_page;
                }, 5000);
            }
        },
    });
}

//update profile details
function updateProfileDetails() {
    resetAlerts();
    var profile_description = $("#profile-description-edit").val();
    var profile_first_name = $("#profile-first-name-edit").val();
    var profile_middle_name = $("#profile-middle-name-edit").val();
    var profile_last_name = $("#profile-last-name-edit").val();
    var profile_account_name = $("#profile-account-name-edit").val();
    var profile_school_category = $(
        "#profile-school-category-edit option:selected",
    ).val();
    var profile_username = $("#profile-username-edit").val();
    var profile_email_address = $("#profile-email-address-edit").val();
    var profile_mobile_number = $("#profile-mobile-number-edit").val();
    var profile_birthdate = $("#profile-birthdate-edit").val();
    var profile_gender = $("#profile-gender-edit option:selected").val();
    var profile_civil_status = $(
        "#profile-civil-status-edit option:selected",
    ).val();
    var profile_educational_attainment = $(
        "#profile-educational-attainment-edit option:selected",
    ).val();
    var profile_school = $("#profile-school-edit").val();
    var profile_occupation = $("#profile-occupation-edit").val();
    var profile_country = $("#profile-country").val();
    var profile_region = $("#profile-region option:selected").val();
    var profile_province_state = $(
        "#profile-province-state option:selected",
    ).val();
    var profile_city_municipality = $(
        "#profile-city-municipality option:selected",
    ).val();
    var profile_barangay = $("#profile-barangay option:selected").val();
    var profile_street_subd_village = $("#profile-street-subd-village").val();

    $.ajax({
        url: public_folder + "/update-profile-details",
        dataType: "json",
        type: "POST",
        data: {
            profile_account_type: account_type,
            profile_registrant_id: registrant_id,
            profile_description: profile_description,
            profile_first_name: profile_first_name,
            profile_middle_name: profile_middle_name,
            profile_last_name: profile_last_name,
            profile_account_name: profile_account_name,
            profile_school_category: profile_school_category,
            profile_username: profile_username,
            profile_email_address: profile_email_address,
            profile_mobile_number: profile_mobile_number,
            profile_birthdate: profile_birthdate,
            profile_gender: profile_gender,
            profile_civil_status: profile_civil_status,
            profile_educational_attainment: profile_educational_attainment,
            profile_school: profile_school,
            profile_occupation: profile_occupation,
            profile_country: profile_country,
            profile_region: profile_region,
            profile_province_state: profile_province_state,
            profile_city_municipality: profile_city_municipality,
            profile_barangay: profile_barangay,
            profile_street_subd_village: profile_street_subd_village,
            update_profile_details_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["status"] == "Successful") {
                const success_message = responses["success-message"];
                showInputsSuccessMessage(success_message);
                $("#modal-update-profile").hide();
                setInterval(function () {
                    current_url.reload();
                }, 2000);
            } else {
                const errors = responses["error"];
                showInputsErrorMessage(errors);
            }
        },
    });
}

//logout
function logout() {
    resetAlerts();
    $.ajax({
        url: public_folder + "/logout",
        dataType: "json",
        type: "POST",
        async: true,
        data: {
            registrant_id: registrant_id,
            logout_submit: true,
        },
        success: function (responses) {
            if (responses["status"] == "Successful") {
                current_url.reload();
            }
        },
    });
}

//get general records
function getGeneralRecords() {
    var query = "";
    var query_in = "";
    var is_workspace = "";

    var category = "";
    var tag = "";
    var date = "";
    var owner = "";
    var contents_availability = "";

    if (!home_searched_user) {
        if (page_name == "Search") {
            query = $("#search-page-query").val();
            query_in = $("#query-in").val();

            if (query) {
                if (query.length >= 3 && query_in) {
                    getGeneralRecordsAjax(
                        query,
                        home_searched_user,
                        show_shared,
                        query_in,
                        contents_availability,
                        is_workspace,
                        page_name,
                        category,
                        tag,
                        date,
                        owner,
                    );
                } else {
                    $("#search-results").hide();
                }
            }
        }

        if (page_name != "Search") {
            if (!page_name.includes("Workspace")) {
                query = "";

                if (page_name == "Home") {
                    query_in = "general-contents";
                } else {
                    category = $("#accessed-category").val();
                    tag = $("#accessed-tag").val();
                    date = $("#accessed-date").val();
                    owner = $("#accessed-owner").val();

                    if (page_name == "Teacher Files") {
                        query_in = "teacher-files";
                    }

                    if (page_name == "Articles") {
                        query_in = "articles";
                    }

                    if (page_name == "Researches") {
                        query_in = "researches";
                    }

                    if (page_name == "Tools") {
                        query_in = "tools";
                    }
                }
            }

            if (page_name.includes("Workspace")) {
                query = $(".workspace-search-container input").val();

                is_workspace = "yes";

                if (page_name.includes("Teacher")) {
                    query_in = "teacher-files";
                }

                if (page_name.includes("Writer")) {
                    query_in = "articles";
                }

                if (page_name.includes("Editor")) {
                    contents_availability = $("#articles-availability").val();
                    query_in = "articles";
                }

                if (page_name.includes("Researches")) {
                    query_in = "researches";
                }

                if (page_name.includes("Developer")) {
                    query_in = "tools";
                }
            }

            getGeneralRecordsAjax(
                query,
                home_searched_user,
                show_shared,
                query_in,
                contents_availability,
                is_workspace,
                page_name,
                category,
                tag,
                date,
                owner,
            );
        }
    }

    if (home_searched_user) {
        query_in = "general-contents";
        show_shared = $("#home-searched-user-show-shared").val();

        getGeneralRecordsAjax(
            query,
            home_searched_user,
            show_shared,
            query_in,
            contents_availability,
            is_workspace,
            page_name,
            category,
            tag,
            date,
            owner,
        );
    }

    function getGeneralRecordsAjax(
        query,
        home_searched_user,
        show_shared,
        query_in,
        contents_availability,
        is_workspace,
        page_name,
        category,
        tag,
        date,
        owner,
    ) {
        $.ajax({
            url: general_processing_file,
            type: "POST",
            async: true,
            data: {
                query: query,
                home_searched_user: home_searched_user,
                show_shared: show_shared,
                query_in: query_in,
                contents_availability: contents_availability,
                is_workspace: is_workspace,
                page_name: page_name,
                category: category,
                tag: tag,
                date: date,
                owner: owner,
                registrant_id: registrant_id,
                get_general_records_submit: true,
            },
            success: function (responses) {
                console.log(responses);
                if (page_name == "Search") {
                    $("#search-results").show();
                    $("#search-results").html(responses);
                }

                if (page_name != "Search") {
                    if (!page_name.includes("Workspace")) {
                        if (page_name == "Home") {
                            $("#contents-list").html(responses);
                        }

                        if (page_name == "Teacher Files") {
                            $("#teacher-files-list").html(responses);
                        }

                        if (page_name == "Articles") {
                            $("#articles-list").html(responses);
                        }

                        if (page_name == "Researches") {
                            $("#researches-list").html(responses);
                        }

                        if (page_name == "Tools") {
                            $("#tools-list").html(responses);
                        }

                        if (page_name == "Home") {
                            $("#contents-list").show();
                            $("#contents-list").html(responses);
                            $("#account-contents-list").hide();
                        }

                        if (home_searched_user) {
                            $("#account-contents-list").show();
                            $("#account-contents-list").html(responses);
                            $("#contents-list").hide();
                        }
                    }

                    if (page_name.includes("Workspace")) {
                        $("#sidebar-contents-list").html(responses);

                        if (page_name == "Workspace - Teacher") {
                            $("#workspace-title").text("Teacher Workspace");
                        }

                        if (page_name == "Workspace - Writer") {
                            $("#workspace-title").text("Writer Workspace");
                        }

                        if (page_name == "Workspace - Editor") {
                            $("#workspace-title").text("Editor Workspace");
                        }

                        if (page_name == "School Workspace - Researches") {
                            $("#workspace-title").text(
                                "School Workspace - Researches",
                            );
                        }

                        if (page_name == "Workspace - Developer") {
                            $("#workspace-title").text("Developer Workspace");
                        }
                    }
                }
            },
        });
    }
}

//initialize actual workspace
function initializeActualWorkspace() {
    if (page_name != "Workspace - Website Manager") {
        var content_id = $("#content_id").val();

        if (!content_id) {
            blankContentInfo();
        }

        if (content_id) {
            getContentInfo();
        }

        if (page_name == "School Workspace - Researches") {
            $("#description").attr("placeholder", "Abstract");
        }
    }
}

function blankContentInfo() {
    $(".actual-workspace").show();
    $("#mode").val("new");
    $("#version").val(0);
    $("#status").val("");
    $("#image-link").val("");
    $("#content-action").val("");

    $(".workspace-info-and-buttons").hide();
    $(".workspace-actions-container").hide();

    $("#tool-title").prop("disabled", false);

    $("#original-content").val("");

    $("#title").val("");

    $("#original-category").val("Select Category");
    $("#category option:selected").val("Select Category");

    $("#tags").val("");

    $("#tags-container").hide();
    $("#shared-with-container").hide();
    $("#show-tags-container-button").show();
    $("#show-shared-with-container-button").show();

    $("#original-access-type").val("Free Access");
    $("#access-type option:selected").val("Free Access");

    if (page_name == "Workspace - Teacher") {
        $("#amount").val("");
        $("#amount").prop("disabled", false);
    }

    $("#description").val("");

    $("#shared-with").val("");

    $("#content").show();
    $("#show-content-button").hide();

    if (page_name == "School Workspace - Researches") {
        $("#date").val("");
        $("#proponents").val("");

        $("#date").prop("disabled", false);
        $("#proponents").prop("disabled", false);
    }

    $("#title").prop("disabled", false);
    $("#category").prop("disabled", false);
    $("#tags").prop("disabled", false);
    $("#access-type").prop("disabled", false);
    $("#shared-with").prop("disabled", false);
    $("#description").prop("disabled", false);
    $("#content").prop("disabled", false);

    $("#eskquip-editor").html("");
    // $("#summernote").html("");
    $("#eskquip-editor").attr("contenteditable", true);

    $("#save-content-button").show();

    if (page_name == "Workspace - Teacher") {
        $("#save-content-button").text("Create File");
    }

    if (page_name == "Workspace - Writer") {
        $("#save-content-button").text("Create Article");
    }

    if (page_name == "School Workspace - Researches") {
        $("#save-content-button").text("Create Research");
    }

    if (page_name == "Workspace - Developer") {
        $("#tool-files-list").hide();
        $("#save-content-button").text("Create Tool");
    }

    $("#eskquip-panel").show();

    if (page_name == "Workspace - Editor") {
        $(".actual-workspace").hide();
    }

    getCategories();
    getAccessTypes();
    updateUsedTagsList();
    updateSelectedUsersList();
}

//get content info
function getContentInfo() {
    $(".actual-workspace").show();
    $("#mode").val("edit");

    var content_id = $("#content_id").val();
    var query_in = "";
    if (page_name == "Workspace - Teacher") {
        query_in = "teacher_files";
    }

    if (page_name == "Workspace - Writer") {
        query_in = "writer_articles";
    }

    if (page_name == "Workspace - Editor") {
        query_in = "writer_articles";
    }

    if (page_name == "School Workspace - Researches") {
        query_in = "school_researches";
    }

    if (page_name == "Workspace - Developer") {
        query_in = "developer_tools";
    }

    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            content_id: content_id,
            query_in: query_in,
            get_content_info_submit: true,
        },
        success: function (responses) {
            $(".workspace-info-and-buttons").show();
            $(".workspace-actions-container").show();

            if (page_name == "Workspace - Writer") {
                $("#content-message-button").show();
            }

            $("#status").val(responses["status"]);
            var status = $("#status").val();
            $("#version").val(responses["version"]);
            $("#image-link").val(responses["image"]);
            $("#image-status").val(responses["image-status"]);
            $("#view-content-button").attr("href", responses["slug"]);
            $("#original-content").val(responses["content"]);

            if (page_name == "Workspace - Developer") {
                getToolFiles();
                $("#tool-link").val(responses["link"]);
            }

            $("#title").val(responses["title"]);
            $("#title").prop("disabled", true);

            $("#original-category").val(responses["category"]);
            $("#category option:selected").val(responses["category"]);

            $("#tags").val(responses["tags"]);

            $("#original-access-type").val(responses["access-type"]);
            $("#access-type option:selected").val(responses["access-type"]);

            if (page_name == "Workspace - Teacher") {
                $("#amount").val(responses["amount"]);
            }

            if (page_name == "School Workspace - Researches") {
                $("#date").val(responses["date"]);
                $("#proponents").val(responses["proponents"]);
            }

            if (
                page_name == "Workspace - Teacher" ||
                page_name == "School Workspace - Researches"
            ) {
                $("#content").hide();
            }

            $("#show-content-button").show();

            $("#shared-with").val(responses["shared-with"]);

            getCategories();
            getAccessTypes();
            updateUsedTagsList();
            updateSelectedUsersList();

            if (
                page_name != "Workspace - Writer" &&
                page_name != "Workspace - Editor"
            ) {
                $("#description").val(responses["description"]);
            }

            if (
                page_name == "Workspace - Writer" ||
                page_name == "Workspace - Editor"
            ) {
                if (page_name == "Workspace - Writer") {
                    $("#content-versions").show();
                    $("#content-versions").html("");
                    $("#content-versions").append("<small>Versions: </samll>");
                    const versions_container = $("#content-versions");

                    $.each(responses["versions"], function (index, value) {
                        const version_id = "version-" + value;
                        versions_container.append(
                            "<a id='" +
                                "version-" +
                                value +
                                "' class='link-tag-button'>" +
                                value +
                                "<a>",
                        );
                    });
                }

                $("#eskquip-editor").show();
                $("#eskquip-editor").html(responses["content"]);
            } else {
                $("#content-versions").hide();
                $("#eskquip-editor").hide();
            }

            //Content save button
            if (page_name == "Workspace - Teacher") {
                $("#save-content-button").text("Update File");
            }

            if (page_name == "Workspace - Writer") {
                $("#save-content-button").text("Update Article");
            }

            if (page_name == "School Workspace - Researches") {
                $("#save-content-button").text("Update Research");
            }

            if (page_name == "Workspace - Developer") {
                $("#save-content-button").text("Update Tool");
            }

            //Content notes
            $("#content-notes").show();
            if (status != "Draft") {
                $("#content-published").show();
                $("#content-published").html(
                    "<small>Published: </small><br>" +
                        "<small>" +
                        responses["publish-date"] +
                        "</small>",
                );
            }

            $("#content-last-update").html(
                "<small>Updated: </small><br>" +
                    "<small>" +
                    responses["update-date"] +
                    "</small>",
            );

            if (
                status == "Published" ||
                status == "Waiting for Update" ||
                status == "To Revise"
            ) {
                $("#to-publish-content-button").hide();

                if (status == "Published") {
                    $("#to-unpublish-content-button").show();
                    $(".workspace-actions-container").show();
                }

                if (status == "Waiting for Update") {
                    $("#to-unpublish-content-button").hide();

                    if (page_name !== "Workspace - Editor") {
                        $(".workspace-actions-container").hide();
                    }

                    if (page_name == "Workspace - Editor") {
                        if (registrant_id == responses["editors"]) {
                            $("#take-content-button").hide();
                            $("#leave-content-button").show();
                            $("#comment-button").hide();
                            $("#approve-content-button").show();
                        }

                        if (registrant_id != responses["editors"]) {
                            $("#take-content-button").show();
                            $("#leave-content-button").hide();
                            $("#comment-button").hide();
                            $("#approve-content-button").hide();
                        }
                    }
                }

                $("#eskquip-panel").hide();

                $("#delete-content-button").hide();
                $("#review-content-button").hide();

                if (page_name == "Workspace - Developer") {
                    $("#tool-file-upload-container").hide();
                }

                $("#title").prop("disabled", true);
                $("#category").prop("disabled", true);
                $("#tags").prop("disabled", true);
                $("#access-type").prop("disabled", true);
                $("#amount").prop("disabled", true);
                $("#shared-with").prop("disabled", true);
                $("#description").prop("disabled", true);
                $("#content").prop("disabled", true);
                $("#eskquip-editor").attr("contenteditable", false);

                $("#date").prop("disabled", true);
                $("#proponents").prop("disabled", true);
                $("#tags-container").hide();
                $("#shared-with-container").hide();
                $("#show-tags-container-button").hide();
                $("#show-shared-with-container-button").hide();
                $("#save-content-button").hide();
                $("#show-image-button").hide();
                $("#update-image-button").hide();

                if (status == "To Revise") {
                    if (page_name == "Workspace - Writer") {
                        $("#title").prop("disabled", false);
                        $("#category").prop("disabled", false);
                        $("#tags").prop("disabled", false);
                        $("#access-type").prop("disabled", false);
                        $("#shared-with").prop("disabled", false);
                        $("#eskquip-editor").attr("contenteditable", true);
                        $("#eskquip-panel").show();

                        $("#to-publish-content-button").hide();
                        $("#to-unpublish-content-button").hide();
                        $("#review-content-button").show();
                        $("#delete-content-button").show();
                    }

                    if (page_name == "Workspace - Editor") {
                        $(".workspace-actions-container").hide();
                    }
                }
            }

            if (
                status != "Published" &&
                status != "Waiting for Update" &&
                status != "To Revise"
            ) {
                $("#title").prop("disabled", false);
                $("#category").prop("disabled", false);
                $("#tags").prop("disabled", false);
                $("#access-type").prop("disabled", false);
                $("#amount").prop("disabled", false);
                $("#shared-with").prop("disabled", false);
                $("#description").prop("disabled", false);
                $("#content").prop("disabled", false);
                $("#eskquip-editor").attr("contenteditable", true);
                $("#date").prop("disabled", false);
                $("#proponents").prop("disabled", false);

                $("#show-tags-used").show();
                $("#show-tags-available").show();

                $("#to-publish-content-button").show();
                $("#to-unpublish-content-button").hide();
                $("#delete-content-button").show();
                $("#review-content-button").show();

                $("#eskquip-panel").show();

                $("#show-tags-container-button").show();
                $("#show-shared-with-container-button").show();

                if (page_name == "Workspace - Developer") {
                    $("#tool-file-upload-container").show();
                }

                $("#save-content-button").show();

                var image_status = $("#image-status").val();

                if (image_status == 0) {
                    $("#update-image-button").show();
                    $("#show-image-button").hide();
                }

                if (image_status == 1) {
                    $("#update-image-button").hide();
                    $("#show-image-button").show();
                }

                if (page_name == "Workspace - Editor") {
                    $("#title").prop("disabled", true);
                    $("#category").prop("disabled", true);
                    $("#tags").prop("disabled", true);
                    $("#access-type").prop("disabled", true);
                    $("#amount").prop("disabled", true);
                    $("#shared-with").prop("disabled", true);
                    $("#description").prop("disabled", true);
                    $("#content").prop("disabled", true);
                    $("#eskquip-editor").attr("contenteditable", false);

                    $("#date").prop("disabled", true);
                    $("#proponents").prop("disabled", true);
                    $("#tags-container").hide();
                    $("#shared-with-container").hide();
                    $("#show-tags-container-button").hide();
                    $("#show-shared-with-container-button").hide();
                    $("#save-content-button").hide();
                    $("#show-image-button").hide();
                    $("#update-image-button").hide();
                    $(".workspace-actions-container").hide();
                }
            }
        },
    });
}

//get tool files
function getToolFiles() {
    var tool_id = $("#content_id").val();
    var status = $("#status").val();
    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            tool_id: tool_id,
            status: status,
            get_tool_files_submit: true,
        },
        success: function (responses) {
            $("#tool-files-list").show();
            $("#tool-files-list-body").html(responses);

            var tool_files = $("#tool-files").val();

            if (tool_files > 0) {
                $("#to-publish-content-button").prop("disabled", false);
            }
        },
    });
}

//get content for the specific version
function getVersionContent(version_number) {
    var content_id = $("#content_id").val();

    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            content_id: content_id,
            version_number: version_number,
            content_type: content_type,
            get_version_content_submit: true,
        },
        success: function (responses) {
            $("#eskquip-editor").html(responses["version-content"]);
        },
    });
}

function addCategory() {
    var category_name = $("#new-category").val();
    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            category_name: category_name,
            category_type: content_type,
            add_category_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["status"] == "Successful") {
                const success_message = responses["success-message"];
                showInputsSuccessMessage(success_message);
                $("#new-category").val("");
            }

            if (responses["status"] == "Unsuccessful") {
                const errors = responses["error"];
                showInputsErrorMessage(errors);
            }
        },
    });
}

function getCategories() {
    var original_category = $("#original-category").val();
    var selected_category = $("#category").val();
    var mode = $("#mode").val();

    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            mode: mode,
            original_category: original_category,
            selected_category: selected_category,
            category_type: content_type,
            get_categories_submit: true,
        },
        success: function (responses) {
            $("#category").html(responses);
        },
    });
}

function getAccessTypes() {
    var original_access_type = $("#original-access-type").val();
    var selected_access_type = $("#access-type").val();
    var mode = $("#mode").val();

    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            mode: mode,
            original_access_type: original_access_type,
            selected_access_type: selected_access_type,
            access_type_type: content_type,
            seller_subscribed: seller_subscribed,
            get_access_types_submit: true,
        },
        success: function (responses) {
            $("#access-type").html(responses);
        },
    });
}

function pushTag(newValue) {
    var currentValue = $("#tags").val();

    if (currentValue === "") {
        $("#tags").val(newValue);
    } else {
        if (!currentValue.includes(newValue)) {
            var updatedValues = currentValue + ", " + newValue;
            updatedValues = updatedValues.replace(/,+/g, ",");
            updatedValues = updatedValues.replace(/^,*|,*$|,\s*,/g, "").trim();
            $("#tags").val(updatedValues);
        }
    }

    updateUsedTagsList();
}

function removeTag(newValue) {
    var currentValue = $("#tags").val();

    if (currentValue.includes(newValue)) {
        updatedValues = currentValue.replace(newValue, "");

        if (currentValue.includes(", " + newValue)) {
            updatedValues = currentValue.replace(", " + newValue, "");
        }
        updatedValues = updatedValues.replace(/,+/g, ",");
        updatedValues = updatedValues.replace(/^,*|,*$|,\s*,/g, "").trim();
        $("#tags").val(updatedValues);
    }

    updateUsedTagsList();
}

function updateUsedTagsList() {
    var selected_tags = $("#tags").val();
    $("#show-tags-used").html("<small>Used @tags (click to remove):</small>");
    if (selected_tags) {
        $.ajax({
            url: contents_processing_file,
            type: "POST",
            async: true,
            data: {
                registrant_id: registrant_id,
                selected_tags: selected_tags,
                get_used_tags_submit: true,
            },
            success: function (responses) {
                if (responses) {
                    $("#show-tags-used").append(responses);
                }
            },
        });
    }
    getTags();
}

function getTags() {
    var selected_tags = $("#tags").val();
    var searched_tag = $("#searched-tag").val();

    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            registrant_id: registrant_id,
            selected_tags: selected_tags,
            searched_tag: searched_tag,
            get_tags_submit: true,
        },
        success: function (responses) {
            $("#show-tags-available").html(
                "<small>Available @tags (click to add):</small>",
            );
            $("#show-tags-available").append(responses);
        },
    });
}

function addTag() {
    resetAlerts();
    var new_tag = $("#new-tag").val();

    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            registrant_id: registrant_id,
            new_tag: new_tag,
            new_tag_submit: true,
        },
        success: function (responses) {
            if (responses["status"] == "Successful") {
                const success_message = responses["success-message"];
                showInputsSuccessMessage(success_message);
                getTags();
            }

            if (responses["status"] == "Unsuccessful") {
                const errors = responses["error"];
                showInputsErrorMessage(errors);
            }
        },
    });
}

function pushUser(newValue) {
    var currentValue = $("#shared-with").val();

    if (currentValue === "") {
        $("#shared-with").val(newValue);
    } else {
        if (!currentValue.includes(newValue)) {
            var updatedValues = currentValue + ", " + newValue;
            updatedValues = updatedValues.replace(/,+/g, ",");
            updatedValues = updatedValues.replace(/^,*|,*$|,\s*,/g, "").trim();
            $("#shared-with").val(updatedValues);
        }
    }
    updateSelectedUsersList();
}

function removeUser(newValue) {
    var currentValue = $("#shared-with").val();

    if (currentValue.includes(newValue)) {
        updatedValues = currentValue.replace(newValue, "");

        if (currentValue.includes(", " + newValue)) {
            updatedValues = currentValue.replace(", " + newValue, "");
        }
        updatedValues = updatedValues.replace(/,+/g, ",");
        updatedValues = updatedValues.replace(/^,*|,*$|,\s*,/g, "").trim();
        $("#shared-with").val(updatedValues);
    }

    updateSelectedUsersList();
}

function updateSelectedUsersList() {
    var selected_users = $("#shared-with").val();
    $("#show-users-shared").html(
        "<small>Shared with (click to remove):</small>",
    );

    if (selected_users) {
        $.ajax({
            url: account_processing_file,
            type: "POST",
            async: true,
            data: {
                selected_users: selected_users,
                get_user_info_submit: true,
            },
            success: function (responses) {
                $("#show-users-shared").append(responses);
            },
        });
    }

    getUsers();
}

function getUsers() {
    var selected_users = $("#shared-with").val();
    var searched_user = $("#searched-user").val();

    $.ajax({
        url: account_processing_file,
        type: "POST",
        async: true,
        data: {
            registrant_code: registrant_code,
            selected_users: selected_users,
            searched_user: searched_user,
            get_users_submit: true,
        },
        success: function (responses) {
            $("#show-users-not-shared").html(
                "<small>Share with (click to add):</small>",
            );
            $("#show-users-not-shared").append(responses);
        },
    });
}

function initializeShowContent() {
    var content_link = $("#original-content").val();
    var content_url = private_folder + content_link;

    $("#modal-show-content").show();

    var content_status = $("#status").val();

    if (content_status != "Draft") {
        $("#content-iframe").show();
        $("#content-iframe").attr("src", content_url + "?iframe=true");
        $("#replace-content-button").hide();
        $("#replace-content-container").hide();
    }

    if (content_status == "Draft") {
        $("#content-iframe").show();
        $("#content-iframe").attr("src", content_url + "?iframe=true");
        $("#replace-content-button").show();
    }
}

function replaceContentSubmit() {
    resetAlerts();
    var content_file = $("#original-content").val();
    var content_id = $("#content_id").val();
    var content_status = $("#status").val();
    var content_title = $("#title").val();

    var new_content = document.getElementById("new-content").files[0];

    const formData = new FormData();

    formData.append("content_type", content_type);
    formData.append("content_id", content_id);
    formData.append("content_title", content_title);
    formData.append("content_file", content_file);
    formData.append("content_status", content_status);
    formData.append("new_content", new_content);
    formData.append("registrant_id", registrant_id);
    formData.append("replace_content_submit", true);

    fetch(contents_processing_file, {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((result) => {
            $("#replace-content-message").show();

            $("#replace-content-message").html("");

            if (result["status"] == "Unsuccessful") {
                $("#replace-content-message").removeClass("alert-success");
                $("#replace-content-message").addClass("alert-danger");

                const container = $("#replace-content-message");

                $.each(result["error"], function (index, value) {
                    container.append(value + "<br>");
                });
            }

            if (result["status"] == "Successful") {
                $("#replace-content-message").addClass("alert-success");
                $("#replace-content-message").removeClass("alert-danger");

                $("#replace-content-message").html(
                    "Content was replaced successfully",
                );

                $("#new-content").val("");
                getContentInfo();

                defaultContentShow();
            }

            hideAlerts();
        });
}

function showContentImage() {
    var image_link = $("#image-link").val();

    var change_image_button_text = "";
    if (page_name == "Workspace - Teacher") {
        change_image_button_text = "Update File Thumbnail";
    }

    if (page_name == "Workspace - Writer") {
        change_image_button_text = "Update Featured Image";
    }

    if (page_name == "School Workspace - Researches") {
        change_image_button_text = "Update Research Thumbanil";
    }

    if (page_name == "Workspace - Developer") {
        change_image_button_text = "Update Tool Icon";
    }

    $("#modal-show-image").show();
    $("#image").attr("src", image_link);
    $("#change-image-button").text(change_image_button_text);
}

function showUploadImageModal() {
    $("#modal-upload-image").show();
    if (page_name.includes("Workspace")) {
        $("#upload-action-file").val(contents_processing_file);

        if (page_name == "Workspace - Teacher") {
            $("#upload-type").val("File Thumbnail");
            $("#upload-button").text("Update File Thumbnail");
        }

        if (page_name == "Workspace - Writer") {
            $("#upload-type").val("Featured Image");
            $("#upload-button").text("Update Featured Image");
        }

        if (page_name == "School Workspace - Researches") {
            $("#upload-type").val("Research Thumbnail");
            $("#upload-button").text("Update Research Thumbnail");
        }

        if (page_name == "Workspace - Developer") {
            $("#upload-type").val("Tool Icon");
            $("#upload-button").text("Update Tool Icon");
        }
    }
}

function uploadSubmit() {
    resetAlerts();
    var upload_type = $("#upload-type").val();
    var content_id = $("#content_id").val();
    var upload_action_file = $("#upload-action-file").val();

    if (page_name == "My Account") {
        var account_name = $("#account-name").val();
    }

    alert(account_name);
    const upload_image = document.getElementById("upload-image").files[0];

    const formData = new FormData();
    formData.append("upload_type", upload_type);
    formData.append("registrant_id", registrant_id);
    formData.append("content_id", content_id);
    formData.append("upload_image", upload_image);

    if (page_name == "My Account") {
        formData.append("account_name", account_name);
    }

    formData.append("upload_image_submit", "true");

    fetch(upload_action_file, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    })
        .then((response) => response.json())
        .then((result) => {
            $("#modal-upload-image-message").html("");
            console.log(result);
            if (result["status"] == "Unsuccessful") {
                $("#modal-upload-image-message").show();
                const container = $("#modal-upload-image-message");

                $.each(result["error"], function (index, value) {
                    container.append(value + "<br>");
                });

                hideAlerts();
            }

            if (result["status"] == "Successful") {
                if (page_name.includes("Workspace")) {
                    $("#modal-upload-image").hide();
                    getGeneralRecords();
                    initializeActualWorkspace();
                }

                if (!page_name.includes("Workspace")) {
                    $("#modal-upload-image").hide();
                    current_url.reload();
                }

                $("#upload-image").val("");
            }
        })
        .catch((error) => console.error("Error:", error));
}

function defaultContentShow() {
    var private_folder = $("#private-folder").val();
    var new_file = $("#original-content").val();

    $("#content-iframe").show();
    $("#content-iframe").attr(
        "src",
        private_folder + new_file + "?iframe=true",
    );

    $("#content-iframe").show();
    $("#replace-content-container").hide();
    $("#replace-content-button").show();
    $("#replace-content-submit-button").hide();
    $("#retain-content-button").hide();
}

function deleteRecords() {
    resetAlerts();
    var record_category = $("#delete-record-category").val();
    var record_id = $("#delete-record-id").val();

    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            record_category: record_category,
            record_id: record_id,
            delete_record_submit: true,
        },
        success: function (responses) {
            if (responses) {
                console.log(responses);
                if (responses["status"] == "Successful") {
                    const success_message = responses["success-message"];
                    showInputsSuccessMessage(success_message);
                }

                if (responses["status"] != "Successful") {
                    const errors = responses["error"];
                    showInputsErrorMessage(errors);
                }

                $("#modal-confirm-delete-record").hide();
                getSiteManagerRecords();
            }
        },
    });
}

function saveContent() {
    resetAlerts();
    var save_in = "";
    var content_id = $("#content_id").val();
    var version = $("#version").val();
    var mode = $("#mode").val();
    var status = $("#status").val();

    var title = $("#title").val();
    var category = $("#category option:selected").val();
    var tags = $("#tags").val();
    var access_type = $("#access-type option:selected").val();

    var shared_with = $("#shared-with").val();
    var description = $("#description").val();
    var content = "";

    var original_content = $("#original-content").val();

    if (page_name == "Workspace - Teacher") {
        var amount = $("#amount").val();
        content = document.getElementById("content").files[0];
        save_in = "teacher_files";

        if (mode == "edit") {
            content = original_content;
        }
    }

    if (page_name == "Workspace - Writer") {
        content = $("#eskquip-editor").html();
        // content = $("#summernote").html();
        save_in = "writer_articles";
    }

    if (page_name == "School Workspace - Researches") {
        content = document.getElementById("content").files[0];
        save_in = "school_researches";

        if (mode == "edit") {
            content = original_content;
        }

        var date = $("#date").val();
        var proponents = $("#proponents").val();
    }

    if (page_name == "Workspace - Developer") {
        save_in = "developer_tools";
    }

    const formData = new FormData();

    formData.append("save_in", save_in);
    formData.append("content_id", content_id);
    formData.append("mode", mode);
    formData.append("status", status);
    formData.append("version", version);

    formData.append("title", title);
    formData.append("category", category);
    formData.append("tags", tags);
    formData.append("access_type", access_type);
    formData.append("description", description);
    formData.append("content", content);

    formData.append("shared_with", shared_with);

    if (page_name == "Workspace - Teacher") {
        formData.append("amount", amount);
    }

    if (page_name == "School Workspace - Researches") {
        formData.append("date", date);
        formData.append("proponents", proponents);
    }

    formData.append("registrant_id", registrant_id);
    formData.append("save_content_submit", true);

    fetch(contents_processing_file, {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((result) => {
            if (result["status"] == "Unsuccessful") {
                const errors = result["error"];

                showInputsErrorMessage(errors);
            }

            if (result["status"] == "Successful") {
                const success_message = result["success-message"];
                showInputsSuccessMessage(success_message);

                $("#content_id").val(result["content-id"]);
                $("#mode").val("edit");
                getContentInfo();
                getGeneralRecords();
            }
        });
}
function uploadToolFile() {
    resetAlerts();
    var tool_id = $("#content_id").val();
    var tool_link = $("#tool-link").val();
    const tool_file = document.getElementById("tool-file-upload").files[0];

    const formData = new FormData();
    formData.append("tool_id", tool_id);
    formData.append("tool_link", tool_link);
    formData.append("tool_file", tool_file);
    formData.append("upload_tool_file_submit", "true");

    fetch(contents_processing_file, {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((result) => {
            console.log(result);
            if (result["status"] == "Unsuccessful") {
                const errors = result["error"];
                showInputsErrorMessage(errors);
            }

            if (result["status"] == "Successful") {
                const success_message = result["success-message"];
                showInputsSuccessMessage(success_message);
                initializeActualWorkspace();
            }
        })
        .catch((error) => console.error("Error:", error));
}

function updateContentStatus(action) {
    resetAlerts();
    var content_id = $("#content_id").val();

    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            content_id: content_id,
            action: action,
            content_type: content_type,
            registrant_id: registrant_id,
            update_content_status_submit: true,
        },
        success: function (responses) {
            getGeneralRecords();
            initializeActualWorkspace();
        },
    });
}

function deleteContents() {
    resetAlerts();
    var delete_content_id = $("#delete-content-id").val();
    var delete_sub_content_id = $("#delete-sub-content-id").val();
    //for main content
    if (!delete_sub_content_id) {
        $.ajax({
            url: contents_processing_file,
            type: "POST",
            data: {
                content_type: content_type,
                delete_content_id: delete_content_id,
                delete_content_submit: true,
            },
            success: function (response) {
                console.log(response);
                $("#modal-confirm-delete-content").hide();
                $("#content_id").val("");
                getGeneralRecords();
                initializeActualWorkspace();
            },
        });
    }
    //for sub content
    if (delete_sub_content_id) {
        $.ajax({
            url: contents_processing_file,
            type: "POST",
            data: {
                delete_content_id: delete_content_id,
                delete_sub_content_id: delete_sub_content_id,
                delete_sub_content_submit: true,
            },
            success: function (response) {
                $("#modal-confirm-delete-content").hide();
                getContentInfo();
            },
        });
    }
}

//auto focus on search input
function searchAutoFocus() {
    $('input[type="search"]').focus();
}

function redirectToSearchPage() {
    var search_page = public_folder + "/search/";
    window.location.href = search_page;
}

function redirectToAccountPage() {
    var account_page = public_folder + "/account/";
    window.location.href = account_page;
}

function redirectToLoginPage() {
    var login_page = public_folder + "/login/";
    window.location.href = login_page;
}

function getSiteManagerRecords() {
    var record_category = $("#sm-record-category option:selected").val();

    if (record_category) {
        var record_sub_category = "";
        var record_status = "";

        if (record_category == "General Registration") {
            record_sub_category = $(
                "#sm-record-sub-category-general-registration option:selected",
            ).val();

            record_status = $(
                "#sm-record-status-general-registration option:selected",
            ).val();
        }

        if (record_category == "Other Registration") {
            record_sub_category = $(
                "#sm-record-sub-category-other-registration option:selected",
            ).val();

            record_status = $(
                "#sm-record-status-other-registration option:selected",
            ).val();
        }

        if (record_category == "Subscription") {
            record_sub_category = $(
                "#sm-record-sub-category-subscription option:selected",
            ).val();

            record_status = $(
                "#sm-record-status-subscription option:selected",
            ).val();
        }

        if (record_category == "Promotion") {
            record_sub_category = $(
                "#sm-record-sub-category-promotion option:selected",
            ).val();

            record_status = $(
                "#sm-record-status-promotion option:selected",
            ).val();
        }

        if (record_category == "Messages") {
            record_sub_category = $(
                "#sm-record-sub-category-messages option:selected",
            ).val();

            record_status = $(
                "#sm-record-status-messages option:selected",
            ).val();
        }

        var record_date = $("#sm-record-date").val();
        var record_search = $("#sm-record-search").val();

        $.ajax({
            url: general_processing_file,
            type: "POST",
            async: true,
            data: {
                record_category: record_category,
                record_sub_category: record_sub_category,
                record_status: record_status,
                record_date: record_date,
                record_search: record_search,
                get_website_manager_records_submit: true,
            },
            success: function (responses) {
                $("#sm-records").html(responses);
            },
        });
    }

    if (!record_category) {
        $("#sm-records").html("<p>Please select records</p>");
    }
}

function toggleRecords() {
    var record_category = $("#sm-record-category option:selected").val();

    if (record_category) {
        if (record_category == "General Registration") {
            $("#sm-record-sub-category-general-registration").show();
            $("#sm-record-sub-category-other-registration").hide();
            $("#sm-record-sub-category-subscription").hide();
            $("#sm-record-sub-category-promotion").hide();
            $("#sm-record-sub-category-messages").hide();

            $("#sm-record-status-general-registration").show();
            $("#sm-record-status-other-registration").hide();
            $("#sm-record-status-subscription").hide();
            $("#sm-record-status-promotion").hide();
            $("#sm-record-status-messages").hide();
        }

        if (record_category == "Other Registration") {
            $("#sm-record-sub-category-general-registration").hide();
            $("#sm-record-sub-category-other-registration").show();
            $("#sm-record-sub-category-subscription").hide();
            $("#sm-record-sub-category-promotion").hide();
            $("#sm-record-sub-category-messages").hide();

            $("#sm-record-status-general-registration").hide();
            $("#sm-record-status-other-registration").show();
            $("#sm-record-status-subscription").hide();
            $("#sm-record-status-promotion").hide();
            $("#sm-record-status-messages").hide();
        }

        if (record_category == "Subscription") {
            $("#sm-record-sub-category-general-registration").hide();
            $("#sm-record-sub-category-other-registration").hide();
            $("#sm-record-sub-category-subscription").show();
            $("#sm-record-sub-category-promotion").hide();
            $("#sm-record-sub-category-messages").hide();

            $("#sm-record-status-general-registration").hide();
            $("#sm-record-status-other-registration").hide();
            $("#sm-record-status-subscription").show();
            $("#sm-record-status-promotion").hide();
            $("#sm-record-status-messages").hide();
        }

        if (record_category == "Promotion") {
            $("#sm-record-sub-category-general-registration").hide();
            $("#sm-record-sub-category-other-registration").hide();
            $("#sm-record-sub-category-subscription").hide();
            $("#sm-record-sub-category-promotion").show();
            $("#sm-record-sub-category-messages").hide();

            $("#sm-record-status-general-registration").hide();
            $("#sm-record-status-other-registration").hide();
            $("#sm-record-status-subscription").hide();
            $("#sm-record-status-promotion").show();
            $("#sm-record-status-messages").hide();
        }

        if (record_category == "Messages") {
            $("#sm-record-sub-category-general-registration").hide();
            $("#sm-record-sub-category-other-registration").hide();
            $("#sm-record-sub-category-subscription").hide();
            $("#sm-record-sub-category-promotion").hide();
            $("#sm-record-sub-category-messages").show();

            $("#sm-record-status-general-registration").hide();
            $("#sm-record-status-other-registration").hide();
            $("#sm-record-status-subscription").hide();
            $("#sm-record-status-promotion").hide();
            $("#sm-record-status-messages").show();
        }

        $("#sm-record-status").show();
        $("#sm-record-date").show();
        $("#sm-record-search").show();
    }

    if (!record_category) {
        $(".sub-category").hide();
        $(".status").hide();
        $("#sm-record-date").hide();
        $("#sm-record-search").hide();
    }
}
function initializeSubscription(subscription_type) {
    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            tool_subscribed: tool_subscribed,
            pending_tool_subscription: pending_tool_subscription,
            file_subscribed: file_subscribed,
            pending_file_subscription: pending_file_subscription,
            seller_subscribed: seller_subscribed,
            pending_seller_subscription: pending_seller_subscription,
            shelf_subscribed: shelf_subscribed,
            pending_shelf_subscription: pending_shelf_subscription,
            subscription_type: subscription_type,
            initialize_subscription_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses) {
                if (!responses["subscribed"] && !responses["pending"]) {
                    $("#modal-subscription-inputs").show();
                    $("#modal-subscription-notes").hide();
                } else {
                    $("#modal-subscription-inputs").hide();
                    $("#modal-subscription-notes").show();
                    $("#subscription-message").show();
                    $("#subscription-message").addClass("alert");
                    $("#subscription-message").addClass("alert-success");

                    if (responses["pending"]) {
                        $("#subscription-message").text(
                            "Please wait for the approval.",
                        );
                    }

                    if (responses["subscribed"]) {
                        $("#subscription-message").text(
                            "You are currently subscribed.",
                        );
                    }

                    getSubscriptions(subscription_type);
                }
            }
        },
    });
}

function getUnreadMessages() {
    $.ajax({
        url: account_processing_file,
        type: "POST",
        async: true,
        data: {
            registrant_id: registrant_id,
            registrant_code: registrant_code,
            message_manager_registration: wm_message_manager_registration,
            super_manager_registration: wm_super_manager_registration,
            get_unread_messages_submit: true,
        },
        success: function (responses) {
            console.log(responses);

            if (responses["unread-messages-personal"] != 0) {
                $("#unread-message-counter-personal").show();
                $("#unread-message-counter-personal").text(
                    responses["unread-messages-personal"],
                );
            } else {
                $("#unread-message-counter-personal").hide();
            }

            if (responses["unread-messages-message-manager-admin"] != 0) {
                $("#unread-message-counter-message-manager-admin").show();
                $("#unread-message-counter-message-manager-admin").text(
                    responses["unread-messages-message-manager-admin"],
                );
            } else {
                $("#unread-message-counter-message-manager-admin").hide();
            }

            if (responses["unread-messages-message-manager-non-admin"] != 0) {
                $("#unread-message-counter-message-manager-non-admin").show();
                $("#unread-message-counter-message-manager-non-admin").text(
                    responses["unread-messages-message-manager-non-admin"],
                );
            } else {
                $("#unread-message-counter-message-manager-non-admin").hide();
            }

            let unread_messages = 0;

            if (responses["message-manager"]) {
                unread_messages =
                    responses["unread-messages-personal"] +
                    responses["unread-messages-message-manager-admin"];
            }

            if (!responses["message-manager"]) {
                unread_messages =
                    responses["unread-messages-personal"] +
                    responses["unread-messages-message-manager-non-admin"];
            }

            if (unread_messages != 0) {
                $("#unread-messages-counter").show();
                $("#unread-messages-counter").text(unread_messages);
            } else {
                $("#unread-messages-counter").hide();
            }
        },
    });
}

function getUnreadUpdates() {
    $.ajax({
        url: account_processing_file,
        type: "POST",
        async: true,
        data: {
            get_unread_updates_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["unread-updates"] != 0) {
                $("#unread-updates-counter").show();
                $("#unread-updates-counter").text(responses["unread-updates"]);
            } else {
                $("#unread-updates-counter").hide();
            }
        },
    });
}

function getSubscriptions(subscription_type) {
    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            subscription_type: subscription_type,
            registrant_id: registrant_id,
            get_subscriptions_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses) {
                $("#modal-subscriptions-list").html(responses);
            }
        },
    });
}

function subscribe() {
    resetAlerts();

    var subscription_type = $("#subscription-type").val();
    var subscription_duration = $("#subscription-duration").val();
    var subscription_total = $("#subscription-total").val();
    var subscription_payment_option = $("#subscription-payment-option").val();
    var subscription_reference_number = $(
        "#subscription-reference-number",
    ).val();

    const subscription_proof_of_payment = document.getElementById(
        "subscription-proof-of-payment",
    ).files[0];

    const formData = new FormData();
    formData.append("subscription_type", subscription_type);
    formData.append("subscription_duration", subscription_duration);
    formData.append("subscription_total", subscription_total);
    formData.append("subscription_payment_option", subscription_payment_option);
    formData.append(
        "subscription_reference_number",
        subscription_reference_number,
    );
    formData.append(
        "subscription_proof_of_payment",
        subscription_proof_of_payment,
    );
    formData.append("registrant_id", registrant_id);
    formData.append("teacher_registration", teacher_registration);
    formData.append("tool_subscribed", tool_subscribed);
    formData.append("pending_tool_subscription", pending_tool_subscription);
    formData.append("file_subscribed", file_subscribed);
    formData.append("pending_file_subscription", pending_file_subscription);
    formData.append("seller_subscribed", seller_subscribed);
    formData.append("pending_seller_subscription", pending_seller_subscription);
    formData.append("shelf_subscribed", shelf_subscribed);
    formData.append("pending_shelf_subscription", pending_shelf_subscription);
    formData.append("subscription_submit", "true");

    fetch(general_processing_file, {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((result) => {
            if (result["status"] == "Successful") {
                const success_message = result["success-message"];
                showInputsSuccessMessage(success_message);
                initializeSubscription(subscription_type);
            }

            if (result["status"] == "Unsuccessful") {
                const errors = result["error"];
                showInputsErrorMessage(errors);
            }
        })
        .catch((error) => console.error("Error:", error));
}

function submitPromotion() {
    resetAlerts();
    var promotion_id = $("#promotion-id").val();
    var promotion_name_company = $("#promotion-name-company").val();
    var promotion_title = $("#promotion-title").val();
    var promotion_type = $("#promotion-type").val();
    var promotion_topics = $("#promotion-topics").val();
    var promotion_description = $("#promotion-description").val();
    var promotion_link = $("#promotion-link").val();
    var promotion_duration = $("#promotion-duration").val();
    var promotion_amount = $("#promotion-amount").val();
    var promotion_image_link = $("#promotion-image-link").val();
    var promotion_agreement_link = $("#promotion-agreement-link").val();

    const promotion_image = document.getElementById("promotion-image-edit")
        .files[0];
    const promotion_agreement = document.getElementById("promotion-agreement")
        .files[0];

    const formData = new FormData();

    formData.append("promotion_id", promotion_id);
    formData.append("promotion_name_company", promotion_name_company);
    formData.append("promotion_title", promotion_title);
    formData.append("promotion_type", promotion_type);
    formData.append("promotion_topics", promotion_topics);
    formData.append("promotion_description", promotion_description);
    formData.append("promotion_link", promotion_link);
    formData.append("promotion_duration", promotion_duration);
    formData.append("promotion_amount", promotion_amount);
    formData.append("promotion_image_link", promotion_image_link);
    formData.append("promotion_agreement_link", promotion_agreement_link);
    formData.append("promotion_image", promotion_image);
    formData.append("promotion_agreement", promotion_agreement);
    formData.append("promotion_submit", "true");

    fetch(general_processing_file, {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((result) => {
            if (result["status"] == "Unsuccessful") {
                const errors = result["error"];
                showInputsErrorMessage(errors);
                getSiteManagerRecords();
            }

            if (result["status"] == "Successful") {
                const success_message = result["success-message"];
                showInputsSuccessMessage(success_message);
                getSiteManagerRecords();
            }
        })
        .catch((error) => console.error("Error:", error));
}

function submitNote() {
    resetAlerts();
    var button_id = $(this).attr("id");
    const number = button_id.match(/\d+/)[0];

    var note_userid = $("#note-userid-" + number).val();
    var note_regtype = $("#note-regtype-" + number).val();
    var note_regtype_cap = $("#note-regtype-cap-" + number).val();
    var note_notes = $("#note-notes-" + number).val();

    if (button_id.includes("clear")) {
        note_notes = "";
        $("#note-notes-" + number).val("");
    }

    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            note_userid: note_userid,
            note_regtype: note_regtype,
            note_regtype_cap: note_regtype_cap,
            note_notes: note_notes,
            note_submit: true,
        },
        success: function (response) {
            $("#modal-notes-container-" + number).show();
            $("#modal-note-message-" + number).show();
            $("#modal-note-message-" + number).html(response);
            toggleClearNoteButton(number);
            hideAlerts();
        },
    });
}

function toggleClearNoteButton(number) {
    var note_notes = $("#note-notes-" + number).val();

    if (note_notes) {
        $("#note-clear-button-" + number).show();
    } else {
        $("#note-clear-button-" + number).hide();
    }
}

function newPromotion() {
    resetAlerts();
    $("#modal-promotion").show();
    $("#promotion-id").val("");
    $("#promotion-name-company").val("");
    $("#promotion-title").val("");
    $("#promotion-type").val("");
    $("#promotion-topics").val("");
    $("#promotion-description").val("");
    $("#promotion-link").val("");
    $("#promotion-duration").val("");
    $("#promotion-amount").val("");
    $("#promotion-image-link").val("");
    $("#promotion-agreement-link").val("");

    $("#show-promotion-image-button").hide();
    $("#show-promotion-agreement-button").hide();

    $("#promotion-submit-button").text("Add");
}

function updatePromotionStatus(to_do, number, promotion_duration) {
    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            to_do: to_do,
            promotion_id: number,
            promotion_duration: promotion_duration,
            update_promotion_status_submit: true,
        },
        success: function (responses) {
            if (responses["status"] == "Successful") {
                getSiteManagerRecords();
            }
        },
    });
}

function updateSubscription(to_do, number, subscription_type) {
    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            to_do: to_do,
            subscription_id: number,
            subscription_type: subscription_type,
            update_subscription_submit: true,
        },
        success: function (responses) {
            if (responses["status"] == "Successful") {
                getSiteManagerRecords();
            }
        },
    });
}

function updateRegistration(to_do, number, regtype) {
    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            to_do: to_do,
            registration_id: number,
            regtype: regtype,
            update_registration_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["status"] == "Successful") {
                getSiteManagerRecords();
            }
        },
    });
}
function showConfirmDeleteRecordModal(number, record_category) {
    $("#modal-confirm-delete-record").show();
    $("#delete-record-id").val(number);
    $("#delete-record-category").val(record_category);
}

function checkRegistration(regtype) {
    $.ajax({
        url: public_folder + "/check-other-registration",
        dataType: "json",
        type: "POST",
        data: {
            regtype: regtype,
            registrant_id: registrant_id,
            check_other_registration_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["have-registration"]) {
                $("#modal-other-registration-inputs-container").hide();
                $("#other-registration-message").show();
                getSubmissions(regtype);
            } else {
                $("#other-registration-message").hide();
                $("#other-registration-message").removeClass("alert");
                $("#other-registration-message").removeClass("alert-success");
                $("#modal-other-registration-inputs-container").show();
                $(
                    "#modal-other-registration-submitted-documents-container",
                ).hide();

                if (regtype == "Teacher") {
                    $(
                        "#other-registration-license-certification-container",
                    ).show();
                    $("#other-registration-sample-container").hide();
                }

                if (regtype != "Teacher") {
                    $(
                        "#other-registration-license-certification-container",
                    ).hide();
                    if (regtype != "Researches") {
                        $("#other-registration-sample-container").show();
                        $("#sample").attr(
                            "placeholder",
                            "URL to your sample as " + regtype,
                        );
                    } else {
                        $("#other-registration-sample-container").hide();
                    }
                }

                $("#download-agreement-form-link").attr(
                    "href",
                    public_folder +
                        "/downloadables/agreement-" +
                        regtype.toLowerCase() +
                        ".docx",
                );
            }
        },
    });
}

function getSubmissions(regtype) {
    $.ajax({
        url: account_processing_file,
        type: "POST",
        async: true,
        data: {
            regtype: regtype,
            registrant_id: registrant_id,
            get_submissions_submit: true,
        },
        success: function (responses) {
            if (responses["submitted"]) {
                $(
                    "#modal-other-registration-submitted-documents-container",
                ).show();
                if (regtype == "Teacher") {
                    $("#submitted-license-certification").show();
                    $("#submitted-sample").hide();
                }

                if (regtype != "Teacher") {
                    $("#submitted-license-certification").hide();
                    if (regtype != "Researches") {
                        $("#submitted-sample").show();
                    } else {
                        $("#submitted-sample").hide();
                    }
                }

                $("#submitted-license-certification").attr(
                    "href",
                    responses["license-certification"],
                );
                $("#submitted-sample").attr("href", responses["sample"]);
                $("#submitted-agreement").attr("href", responses["agreement"]);

                $("#other-registration-message").show();
                $("#other-registration-message").addClass("alert");
                $("#other-registration-message").addClass("alert-success");

                $("#other-registration-message").text(
                    "Status: " + responses["status"],
                );
            } else {
                $(
                    "#modal-other-registration-submitted-documents-container",
                ).hide();
            }
            console.log(responses);
        },
    });
}

function register() {
    var regtype = $("#regtype-hidden").val();

    const other_registration_license_certification = document.getElementById(
        "license-certification",
    ).files[0];

    var other_registration_sample = $("#sample").val();

    const other_registration_agreement =
        document.getElementById("agreement").files[0];

    const formData = new FormData();

    formData.append("regtype", regtype);

    formData.append(
        "other_registration_license_certification",
        other_registration_license_certification,
    );
    formData.append("other_registration_sample", other_registration_sample);

    formData.append(
        "other_registration_agreement",
        other_registration_agreement,
    );
    formData.append("registrant_id", registrant_id);
    formData.append("account_name", account_name);
    formData.append("other_registration_submit", "true");

    fetch(public_folder + "/other-registration-submit", {
        method: "POST",
        dataType: "json",
        body: formData,
    })
        .then((response) => response.json())
        .then((result) => {
            console.log(result);
            if (result["status"] == "Successful") {
                const success_message = result["success-message"];
                showInputsSuccessMessage(success_message);
                checkRegistration(regtype);
                $("#sample").val("");
                $("#agreement").val("");
                $("#license-certification").val("");
            }

            if (result["status"] == "Unsuccessful") {
                const errors = result["error"];
                showInputsErrorMessage(errors);
            }
        })
        .catch((error) => console.error("Error:", error));
}

function showPurchaseModal(content_type, content_id) {
    $("#modal-purchase").show();

    if (!registrant_id) {
        $("#purchase-purchase-details").hide();
        $("#purchase-seller-details").hide();
        $("#show-seller-details-button").hide();
        $("#login-purchase-notice").show();
    }

    if (registrant_id) {
        getPurchaseContentInfo(content_type, content_id);
    }
}

function getPurchaseContentInfo(content_type, content_id) {
    $.ajax({
        url: contents_processing_file,
        type: "POST",
        sync: true,
        data: {
            content_type: content_type,
            content_id: content_id,
            registrant_id: registrant_id,
            get_purchase_content_info_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            $("#purchase-message").html("");
            $("#purchase-processing-file").val(contents_processing_file);
            if (responses["content-info"] == "Content found") {
                $("#purchase-content-title").text(responses["title"]);
                $("#purchase-content-type").text(content_type);
                $("#purchase-content-id").text(content_id);
                $("#purchase-content-seller-id").text(responses["seller-id"]);
                $("#purchase-content-seller-name").text(
                    responses["seller-name"],
                );
                $("#purchase-content-seller-code").text(
                    responses["seller-code"],
                );
                $("#purchase-content-amount").text(
                    "₱" + parseFloat(Number(responses["amount"])),
                );

                $("#purchase-content-send-to").text(
                    responses["payment-channel"] +
                        " (" +
                        responses["account-name"] +
                        ", " +
                        responses["account-number"] +
                        ")",
                );

                $("#purchase-content-review").text(
                    responses["purchase-review"],
                );

                if (responses["seller-subscription-info"] == "With record") {
                    $("#show-seller-details-button").show();
                }

                if (responses["seller-subscription-info"] == "No record") {
                    $("#show-seller-details-button").hide();
                }

                if (responses["purchased"] == "Purchased") {
                    $("#purchase-inputs").hide();
                    if (responses["purchase-status"] == "Pending") {
                        $("#purchase-message").html(
                            "<p>Please wait for the seller's approval.</p>" +
                                "<br><br>" +
                                "<small id=message-seller-" +
                                responses["seller-code"] +
                                " class='link-tag-button message-seller'" +
                                ">Message</small>" +
                                "<small id=flag-user-" +
                                responses["seller-id"] +
                                " class=link-tag-button>Flag</small>",
                        );
                    }

                    if (responses["purchase-status"] != "Pending") {
                        if (responses["purchase-status"] != "Revoked") {
                            $("#purchase-message").html(
                                "<p>Your purchase is " +
                                    responses["purchase-status"] +
                                    ".</p>" +
                                    "<br><br>" +
                                    "<a href=" +
                                    responses["href"] +
                                    " class=link-tag-button>View</a>",
                            );
                        }

                        if (responses["purchase-status"] == "Revoked") {
                            $("#purchase-message").html(
                                "<p>Your purchase is " +
                                    responses["purchase-status"] +
                                    ".</p>",
                            );
                        }
                    }
                }

                if (responses["purchased"] == "Not Purchased") {
                    if (responses["seller-subscription-status"] == "Revoked") {
                        $("#purchase-inputs").hide();
                        $("#purchase-message").html(
                            "<p>Seller's seller subscription was revoked.</p>",
                        );
                    }

                    if (
                        responses["seller-subscription-status"] == "Approved" ||
                        responses["seller-subscription-status"] == "Kept"
                    ) {
                        $("#purchase-inputs").show();
                    }
                }

                $("#seller-name-shown").text(responses["seller-name"]);
                $("#subscription-status-shown").text(
                    responses["seller-subscription-status"],
                );
                $("#subscription-start-shown").text(
                    responses["seller-subscription-start"],
                );
                $("#subscription-expiry-shown").text(
                    responses["seller-subscription-expiry"],
                );

                setInterval(function () {
                    const expiration_date = new Date(
                        responses["seller-subscription-expiry"],
                    );
                    const current_time = new Date();

                    const remaining_days =
                        (expiration_date - current_time) /
                        (1000 * 60 * 60 * 24);

                    $("#subscription-remaining-days-shown").text(
                        remaining_days.toFixed(4),
                    );
                }, 1000);

                $("#subscription-active-shown").text(
                    responses["seller-subscription-active"],
                );
            }

            if (responses["content-info"] == "Content not found") {
                $("#purchase-purchase-details").hide();
                $("#purchase-seller-details").hide();
            }
        },
    });
}

function purchase() {
    resetAlerts();

    let purchase_content_type = $("#purchase-content-type").text();
    let purchase_content_id = $("#purchase-content-id").text();
    let seller_id = $("#purchase-content-seller-id").text();
    let amount = $("#purchase-content-amount").text().match(/\d+/)[0];
    let payment_channel = $(
        "#purchase-content-payment-channel option:selected",
    ).val();
    let reference_number = $("#purchase-content-reference-number").val();

    const payment_proof = document.getElementById(
        "purchase-content-payment-proof",
    ).files[0];

    const formData = new FormData();
    formData.append("purchase_content_type", purchase_content_type);
    formData.append("purchase_content_id", purchase_content_id);
    formData.append("seller_id", seller_id);
    formData.append("amount", amount);
    formData.append("payment_channel", payment_channel);
    formData.append("reference_number", reference_number);
    formData.append("payment_proof", payment_proof);
    formData.append("registrant_id", registrant_id);
    formData.append("purchase_content_submit", "true");

    fetch(contents_processing_file, {
        method: "POST",
        body: formData,
    })
        .then((responses) => responses.json())
        .then((result) => {
            console.log(result);
            if (result["status"] == "Unsuccessful") {
                const errors = result["error"];
                showInputsErrorMessage(errors);
            }

            if (result["status"] == "Successful") {
                const success_message = result["success-message"];
                showInputsSuccessMessage(success_message);
                getGeneralRecords();
                getPurchaseContentInfo(content_type, content_id);
            }
        });
}

function contentPurchases() {
    var filtered_status = $("#filtered-status").val();

    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            filtered_status: filtered_status,
            content_type: content_type,
            registrant_id: registrant_id,
            get_content_purchases_submit: true,
        },
        success: function (responses) {
            $("#modal-purchases").show();
            $("#purchases-list").html(responses);
        },
    });
}

function hidePurchasesList(purchase_id) {
    $("#purchases-status-filter").hide();
    $("#purchase-count").hide();
    $(".list-purchase-details").hide();
    $("#payment-proof-for-purchase-" + purchase_id).show();
}

function showPurchasesList() {
    $("#purchases-status-filter").show();
    $("#purchase-count").show();
    $(".list-purchase-details").show();
    $(".payment-proof").hide();
}

function updatePurchaseStatus(purchase_id, action) {
    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            purchase_id: purchase_id,
            action: action,
            update_purchase_status_submit: true,
        },
        success: function (responses) {
            contentPurchases();
        },
    });
}

function showMySellerDetails() {
    $.ajax({
        url: account_processing_file,
        type: "POST",
        async: true,
        data: {
            registrant_id: registrant_id,
            get_my_seller_details_submit: true,
        },
        success: function (responses) {
            $("#modal-seller-details").show();
            $("#my-payment-channel").val(responses["payment-channel"]);
            $("#my-account-name").val(responses["account-name"]);
            $("#my-account-number").val(responses["account-number"]);
            $("#my-review-schedules").val(responses["review-schedules"]);
        },
    });
}

function updateMySellerDetails() {
    resetAlerts();
    let payment_channel = $("#my-payment-channel").val();
    let account_name = $("#my-account-name").val();
    let account_number = $("#my-account-number").val();
    let review_schedules = $("#my-review-schedules").val();

    $.ajax({
        url: account_processing_file,
        type: "POST",
        async: true,
        data: {
            registrant_id: registrant_id,
            payment_channel: payment_channel,
            account_name: account_name,
            account_number: account_number,
            review_schedules: review_schedules,
            update_my_seller_details_submit: true,
        },
        success: function (responses) {
            $("#update-my-seller-details-message").show();
            if (responses["status"] == "Unsuccessful") {
                $("#update-my-seller-details-message").removeClass(
                    "alert-success",
                );
                $("#update-my-seller-details-message").addClass("alert-danger");
                $("#update-my-seller-details-message").html(responses["error"]);
            }

            if (responses["status"] == "Successful") {
                showMySellerDetails();
                $("#update-my-seller-details-message").removeClass(
                    "alert-danger",
                );
                $("#update-my-seller-details-message").addClass(
                    "alert-success",
                );
                $("#update-my-seller-details-message").html(
                    "You updated your seller details successfully!",
                );
            }
            hideAlerts();
        },
    });
}

function deleteCategory(category_id, category_name) {
    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            category_id: category_id,
            category_name: category_name,
            content_type: content_type,
            delete_category_submit: true,
        },
        success: function (responses) {
            if (responses["status"] == "Successful") {
                getGeneralRecords();
                showCategoriesModal();
                $("#delete-category-message").show();
                $("#delete-category-message").addClass("alert");
                $("#delete-category-message").addClass("alert-success");
                $("#delete-category-message").html(
                    "<small>" + responses["success-message"] + "</small>",
                );

                setInterval(function () {
                    $("#delete-category-message").hide();
                }, 2000);
            }
        },
    });
}

function showCategoriesModal() {
    $.ajax({
        url: contents_processing_file,
        type: "POST",
        asycn: true,
        data: {
            content_type: content_type,
            get_all_categories_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            $("#modal-all-categories").show();
            $("#all-categories-result").html(responses);
        },
    });
}
function deleteTag(tag_id) {
    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            tag_id: tag_id,
            delete_tag_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["status"] == "Successful") {
                const success_message = responses["success-message"];
                showInputsSuccessMessage(success_message);
                getTags();
                updateUsedTagsList();
            }
        },
    });
}

function duplicateUpdate() {
    $("#update-id").val("");
    $("#update-mode").val("new");

    let update_title = $("#update-title").val();
    $("#update-title").val(update_title + "_copy");

    submitUpdate();
}

function readUpdate(update_id) {
    $("#update-filter").hide();
    $("#updates-result").hide();
    $("#update-item").hide();
    $("#update-read").show();

    $.ajax({
        url: contents_processing_file,
        type: "POST",
        async: true,
        data: {
            update_id: update_id,
            registrant_code: registrant_code,
            read_update_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["details"]) {
                if (responses["status"] == "Published") {
                    $("#update-read-details").show();
                    $("#update-read-title").text(responses["title"]);
                    $("#update-read-timestamp").text(responses["timestamp"]);
                    $("#update-read-viewers").text(responses["viewers"]);
                    $("#update-read-content").html(responses["content"]);
                } else {
                    $("#update-read-details").hide();
                }
            } else {
                $("#update-read-details").hide();
            }
        },
    });
}

function showConfirmDeleteUpdateModal(action) {
    let update_id = $("#update-id").val();

    $("#modal-confirm-delete-update").show();
    $("#delete-update-id").val(update_id);
}

function deleteUpdate() {
    let update_id = $("#delete-update-id").val();

    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            update_id: update_id,
            delete_update_submit: true,
        },
        success: function (responses) {
            if (responses) {
                $("#modal-confirm-delete-update").hide();
                $("#updates-result").show();
                $("#update-item").hide();
                getUpdates();
            }
        },
    });
}

function showConfirmLogoutModal() {
    $("#modal-confirm-logout").show();
}

function showSubscriptionModal() {
    $("#modal-subscription").show();
}

function getSubscriptionDetails(subscription_id) {
    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            subscription_id: subscription_id,
            get_subscription_details_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses) {
                $("#subscription-details-timestamp").text(
                    "Timestamp: " + responses["timestamp"],
                );

                $("#subscription-details-type").text(
                    "Type: " + responses["type"],
                );
                $("#subscription-details-duration").text(
                    "Duration: " + responses["duration"],
                );
                $("#subscription-details-total").text(
                    "Total: ₱" + responses["total"],
                );
                $("#subscription-details-payment-option").text(
                    "Payment Option: " + responses["payment-option"],
                );

                $("#subscription-details-reference-code").text(
                    "Reference Code: " + responses["reference-code"],
                );

                $("#subscription-details-status").text(
                    "Status: " + responses["status"],
                );

                $("#subscription-details-activated").text(
                    "Active: " + responses["activated"],
                );

                $("#subscription-details-expired").text(
                    "Expired: " + responses["expired"],
                );

                setInterval(function () {
                    const expiration_date = new Date(responses["expired"]);
                    const current_time = new Date();

                    const remaining_days =
                        (expiration_date - current_time) /
                        (1000 * 60 * 60 * 24);

                    $("#subscription-details-remaining-days").text(
                        "Remaining Days: " + remaining_days.toFixed(4),
                    );
                }, 1000);

                $("#modal-subscription-payment-proof-view").attr(
                    "src",
                    responses["payment-proof"],
                );
            }
        },
    });
}

function showConfirmUpdateSuperManagerRegistrationModal(
    record_id,
    website_manager_type,
    action,
) {
    $("#modal-confirm-super-manager-registration").show();
    $("#confirm-super-manager-registration-record-id").val(record_id);
    $("#confirm-super-manager-registration-website-manager-type").val(
        website_manager_type,
    );
    $("#confirm-super-manager-registration-action").val(action);
    $("#active-super-manager-password").val("");
}

function confirmUpdateSuperManagerRegistration() {
    let record_id = $("#confirm-super-manager-registration-record-id").val();
    let website_manager_type = $(
        "#confirm-super-manager-registration-website-manager-type",
    ).val();
    let action = $("#confirm-super-manager-registration-action").val();
    let password = $("#active-super-manager-password").val();

    $.ajax({
        url: account_processing_file,
        type: "POST",
        async: true,
        data: {
            password: password,
            registrant_id: registrant_id,
            check_password_for_super_manager_registration_update_submit: true,
        },
        success: function (responses) {
            if (responses["status"] != "Successful") {
                const errors = responses["error"];
                showInputsErrorMessage(errors);
            }

            if (responses["status"] == "Successful") {
                $("#modal-confirm-super-manager-registration").hide();
                $("#active-super-manager-password").val("");
                updateWebsiteManagerRegistrations(
                    record_id,
                    website_manager_type,
                    action,
                );
            }
        },
    });
}

function updateWebsiteManagerRegistrations(
    record_id,
    website_manager_type,
    action,
) {
    $.ajax({
        url: general_processing_file,
        type: "POST",
        async: true,
        data: {
            record_id: record_id,
            website_manager_type: website_manager_type,
            action: action,
            update_website_manager_registrations_submit: true,
        },
        success: function (responses) {
            console.log(responses);

            if (responses["status"] == "Successful") {
                getSiteManagerRecords();
            }
        },
    });
}

function recordPromotionClick(promotion_id) {
    $.ajax({});
}

function showSubscriptionProofOfPayment(proof_of_payment_link) {
    $("#modal-subscription-proof-of-payment").show();
    $("#proof-of-payment-view").attr("src", proof_of_payment_link);
}

function showOtherRegsitrationAgreement(other_registration_agreement_link) {
    $("#modal-other-registration-agreement").show();
    $("#other-registration-agreement-view").attr(
        "src",
        other_registration_agreement_link,
    );
}

function showOtherRegsitrationLicenseCertification(
    other_registration_license_certification_link,
) {
    $("#modal-other-registration-license-certification").show();
    $("#other-registration-license-certification-view").attr(
        "src",
        other_registration_license_certification_link,
    );
}

// check if the page is open
function isPageVisible() {
    return document.visibilityState === "visible";
}

//tract the time spent in a page
function trackTime() {
    if (isPageVisible() && isScrolling) {
        timeSpentSeconds++;
        navigator.sendBeacon(
            current_url.href,
            new URLSearchParams({
                time_spent: Math.ceil(timeSpentSeconds / 60),
            }),
        );
    }
}
