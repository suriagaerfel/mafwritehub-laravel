// Share via Facebook
function fbShare() {
    let url_share =
        "https://www.facebook.com" + encodeURIComponent(window.location.href);
    window.open(url_share, "facebook-share-dialog", "width=626,height=436");
}

// Share via Twitter
function twitterShare() {
    let url =
        "https://twitter.com" +
        encodeURIComponent(window.location.href) +
        "&text=" +
        encodeURIComponent(document.title);
    window.open(url, "twitter-popup", "width=600,height=350");
}

// Share via LinkedIn
function linkedIdShare() {
    let url =
        "https://www.linkedin.com" +
        encodeURIComponent(window.location.href) +
        "&title=" +
        encodeURIComponent(document.title);
    window.open(url, "linkedin-popup", "width=600,height=350");
}

function hideAlerts() {
    setTimeout(function () {
        $(".alert").hide();
    }, 8000);
}

function resetAlerts() {
    $(".alert").hide();
}

function getProfile() {
    $.ajax({
        url: public_folder + "/get-profile",
        type: "POST",
        dataType: "json",
        async: true,
        data: {
            get_profile_submit: true,
        },
        success: function (responses) {
            if (responses) {
                $("#profile-description-view").html(
                    "Description: " + responses["profile-description"],
                );
                $("#profile-first-name-view").html(
                    "First Name: " + responses["profile-first-name"],
                );
                $("#profile-middle-name-view").html(
                    "Middle Name: " + responses["profile-middle-name"],
                );
                $("#profile-last-name-view").html(
                    "Last Name: " + responses["profile-last-name"],
                );
                $("#profile-email-address-view").html(
                    "Email Address: " + responses["profile-email-address"],
                );
                $("#profile-username-view").html(
                    "Username: " + responses["profile-username"],
                );
                $("#profile-account-type-view").html(
                    "Type: " + responses["profile-account-type"],
                );
            }
        },
    });
}

function updateProfile() {
    var profile_first_name = $("#profile-first-name-edit").val();
    var profile_middle_name = $("#profile-middle-name-edit").val();
    var profile_last_name = $("#profile-last-name-edit").val();
    var profile_username = $("#profile-username-edit").val();
    var profile_email_address = $("#profile-email-address-edit").val();
    var profile_account_type = $(
        "#profile-account-type-edit option:selected",
    ).val();

    $.ajax({
        url: public_folder + "/update-profile",
        type: "POST",
        dataType: "json",
        async: true,
        data: {
            profile_first_name: profile_first_name,
            profile_middle_name: profile_middle_name,
            profile_last_name: profile_last_name,
            profile_username: profile_username,
            profile_email_address: profile_email_address,
            profile_account_type: profile_account_type,
            update_profile_submit: true,
        },
        success: function (responses) {
            if (responses["status"] == "Successful") {
                $("#modal-edit-profile").hide();
                console.log(responses);
                getProfile();
            }

            if (responses["status"] == "Unsuccessful") {
                console.log(responses);
                getProfile();
            }
        },
    });
}

function login() {
    resetAlerts();
    var login_email_username = $("#login-email-username").val();
    var login_password = $("#login-password").val();

    $.ajax({
        url: public_folder + "/login",
        type: "POST",
        dataType: "json",
        async: true,
        data: {
            login_email_username: login_email_username,
            login_password: login_password,
            login_submit: true,
        },
        success: function (responses) {
            if (responses["error"] == "No error") {
                url.reload();
            }

            if (responses["error"] != "No error") {
                $("#login-message").show();
                $("#login-message").html(responses["error"]);
                $("#login-message").addClass("alert-danger");

                if (
                    responses["error"] ==
                    "Your account is not yet verified. Check your email to verify."
                ) {
                    let verifying_email_address =
                        responses["temporary-session-email-address"];
                    let verifying_userid =
                        responses["temporary-session-userid"];
                    sendVerificationLink(
                        verifying_email_address,
                        verifying_userid,
                    );
                }

                if (
                    responses["error"] ==
                    "You are logged in in the other device. Open the email sent to log out."
                ) {
                    let logout_email_address =
                        responses["temporary-session-email-address"];
                    let logout_userid = responses["temporary-session-userid"];

                    sendLogoutLink(logout_email_address, logout_userid);
                }
            }

            console.log(responses["error"]);

            hideAlerts();
        },
    });
}

function sendLogoutLink(logout_email_address, logout_userid) {
    $.ajax({
        url: public_folder + "/send-logout-link",
        type: "POST",
        dataType: "json",
        async: true,
        data: {
            logout_email_address: logout_email_address,
            logout_userid: logout_userid,
            send_logout_link_submit: true,
        },
    });
}

function sendVerificationLink(verifying_email_address, verifying_userid) {
    $.ajax({
        url: public_folder + "/send-verification-link",
        type: "POST",
        dataType: "json",
        async: true,
        data: {
            verifying_email_address: verifying_email_address,
            verifying_userid: verifying_userid,
            send_verification_link_submit: true,
        },
    });
}

function showGetPasswordResetOTPModal() {
    $("#modal-get-password-reset-otp").show();
    $("#modal-login").hide();
}

function getPasswordResetOTP() {
    resetAlerts();

    var password_reset_email_username = $(
        "#get-password-reset-otp-email-username",
    ).val();

    $.ajax({
        url: public_folder + "/get-password-reset-otp",
        type: "POST",
        dataType: "json",
        async: true,
        data: {
            password_reset_email_username: password_reset_email_username,
            get_password_link_submit: true,
        },
        success: function (responses) {
            $("#get-otp-message").show();

            if (responses["error"] == "No error") {
                // $("#get-link-message").html(
                //     "The password reset link has been sent to your email address.",
                // );

                // $("#get-link-message").addClass("alert-success");

                $("#modal-get-password-reset-otp").hide();
                $("#modal-otp-for-reset-password").show();
                $("#password-reset-email-username-otp").val(
                    password_reset_email_username,
                );
            }

            if (responses["error"] != "No error") {
                $("#get-otp-message").html(responses["error"]);
                $("#get-otp-message").addClass("alert-danger");
            }

            console.log(responses);
        },
    });
}

function checkOTPPasswordReset() {
    resetAlerts();

    let otp = $("#password-reset-otp").val();
    let credential = $("#password-reset-email-username-otp").val();

    $.ajax({
        url: public_folder + "/check-password-reset-otp",
        type: "POST",
        dataType: "json",
        async: true,
        data: {
            otp: otp,
            credential: credential,
            check_password_reset_otp_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["error"] == "No error") {
                $(".modal").hide();
                $("#modal-reset-password").show();
                $("#password-reset-email-username-proceed").val(credential);
            }

            if (responses["error"] !== "No error") {
                $("#otp-message").show();
                $("#otp-message").addClass("alert-danger");
                $("#otp-message").html(responses["error"]);

                hideAlerts();
            }
        },
    });
}

function resetPassword() {
    resetAlerts();
    let new_password = $("#new-password").val();
    let new_password_retyped = $("#new-password-retyped").val();
    let credential = $("#password-reset-email-username-proceed").val();

    $.ajax({
        url: public_folder + "/reset-password",
        type: "POST",
        dataType: "json",
        async: true,
        data: {
            new_password: new_password,
            new_password_retyped: new_password_retyped,
            credential: credential,
            reset_password_submit: true,
        },
        success: function (responses) {
            console.log(responses);

            if (responses["error"] == "No error") {
                $(".modal").hide();
                $("#login-message").show();
                $("#login-message").addClass("alert-success");
                $("#login-message").text(
                    "You reset your password successfully.",
                );

                hideAlerts();
            }

            if (responses["error"] != "No error") {
                $("#password-reset-message").show();
                $("#password-reset-message").addClass("alert-danger");
                $("#password-reset-message").html(responses["error"]);

                hideAlerts();
            }
        },
    });
}

function logOut() {
    $.ajax({
        url: public_folder + "/logout",
        type: "POST",
        dataType: "json",
        async: true,
        data: {
            logout_submit: true,
        },
        success: function (responses) {
            if (responses["status"] == "Successful") {
                url.reload();
            }
        },
    });
}

function showEditProfileModal() {
    $("#modal-edit-profile").show();
}

function getHomeArticles() {
    $.ajax({
        url: "../private/includes/processing/article-processing.php",
        type: "POST",
        async: true,
        data: {
            get_home_articles_submit: true,
        },
        success: function (responses) {
            $("#home-contents-list").html(responses);
            console.log(removeEventListener);
        },
    });
}

function getAuthors() {
    $.ajax({
        url: public_folder + "/get-authors",
        type: "POST",
        async: true,
        data: {
            get_authors_submit: true,
        },
        success: function (responses) {
            $("#dashboard-article-author").html(responses);
        },
    });
}

function getArticles() {
    var userid = session_userid;
    var author = $("#dashboard-article-author option:selected").text();
    var query = $("#dashboard-article-search").val();
    var page = $("#article-current-page").val();

    $.ajax({
        url: public_folder + "/get-articles",
        type: "POST",
        async: true,
        data: {
            page: page,
            userid: userid,
            author: author,
            query: query,
            get_articles_submit: true,
        },
        success: function (responses) {
            $("#dashboard-articles-list").html(responses);
            var article_rows = $("#article-rows").val();
            var article_pages = $("#article-pages").val();
            var current_page = $("#article-current-page").val();

            if (article_rows > 0) {
                if (article_pages > 1) {
                    $(".article-pagination").show();
                }

                if (article_pages == 1) {
                    $(".article-pagination").hide();
                }

                if (current_page == 1) {
                    $("#article-previous-page").hide();
                }

                if (current_page == article_pages) {
                    $("#article-next-page").hide();
                }

                $(".article-pages-show").show();
                $("#article-current-page-show").text(
                    "Page " + current_page + " of",
                );
                $("#article-pages-show").text(article_pages);
            }

            if (article_rows == 0) {
                $(".article-pagination").hide();
                $(".article-pages-show").hide();
            }
        },
    });
}

function initializeArticlePanel() {
    $("#article-actions-container").hide();

    var article_id = $("#article-id").val();
    var article_mode = $("#article-mode").val();

    $("#modal-add-edit-article").show();
    appendToToolBar();

    if (article_mode == "edit") {
        $("#article-delete-button").show();
        $("#article-publish-button").show();
        $("#article-unpublish-button").show();
        $.ajax({
            url: public_folder + "/get-article",
            type: "POST",
            dataType: "json",
            async: true,
            data: {
                article_id: article_id,
                get_article_submit: true,
            },
            success: function (responses) {
                $("#article-title").val(responses["article-title"]);

                $("#article-category option:selected").val(
                    responses["article-category"],
                );
                $("#article-tags-selected").val(responses["article-tags"]);

                $("#article-original-version").val(
                    responses["article-version"],
                );

                $("#article-original-category").val(
                    responses["article-category"],
                );

                $("#article-version").prop("disabled", false);

                $("#editor").html(responses["article-body"]);

                $("#article-actions-container").show();

                if (responses["article-status"] != "Published") {
                    $("#article-save-button").show();
                    $("#article-publish-button").show();
                    $("#article-unpublish-button").hide();
                    $("#article-delete-button").show();
                    $("#article-image-button").show();
                    $("#editor").show();
                }

                if (responses["article-status"] == "Published") {
                    $("#article-save-button").hide();
                    $("#article-publish-button").hide();
                    $("#article-unpublish-button").show();
                    $("#article-delete-button").hide();
                    $("#article-image-button").hide();
                    $("#editor").hide();
                }

                $("#article-view-button").show();

                getArticleCategories();
                getArticleTags();
                getArticleVersions();

                console.log(responses);
            },
        });
    }

    if (article_mode == "new") {
        $("#editor").show();

        $("#article-delete-button").hide();
        $("#article-publish-button").hide();
        $("#article-unpublish-button").hide();

        $("#article-title").val("");

        $("#article-category option:selected").val("Select Category");
        $("#article-tags-selected").val("");

        $("#article-original-version").val("");
        $("#article-original-category").val("");

        $("#article-version").prop("disabled", true);

        $("#article-save-button").show();
        $("#article-image-button").hide();
        $("#article-view-button").hide();

        $("#editor").html("");

        $("#article-actions-container").show();

        getArticleCategories();
        getArticleTags();
        getArticleVersions();
    }
}

function getVersionBody(selected_version) {
    var article_version = selected_version;

    var article_id = $("#article-id").val();

    $.ajax({
        url: public_folder + "/get-version-body",
        type: "POST",
        async: true,
        data: {
            article_id: article_id,
            article_version: article_version,
            get_version_body_submit: true,
        },
        success: function (responses) {
            $("#editor").html(responses);
        },
    });
}

function appendToToolBar() {
    $("#dashboard-add-edit-article-buttons").appendTo(".panel-heading");
}

function showUploadProfilePictureModal() {
    resetAlerts();
    var user_id = $("#session-userid").val();
    $("#modal-upload-image").show();
    $("#upload-type").val("Profile");
    $("#upload-button").text("Update Profile Picture");
    $("#upload-action-file").val(
        "../../private/includes/processing/users-processing.php",
    );
    $("#content-hidden-id").val(user_id);
}

function closeShowImageModal() {
    $("#modal-show-image").hide();
}

function closeUploadImageModal() {
    $("#modal-upload-image").hide();
}

function closeConfirmDeleteModal() {
    $("#modal-confirm-delete").hide();
}

function checkFeaturedImage() {
    var article_id = $("#article-id").val();
    $.ajax({
        url: public_folder + "/get-article-image",
        type: "POST",
        async: true,
        data: {
            article_id: article_id,
            get_article_image_submit: true,
        },
        success: function (responses) {
            if (responses) {
                $("#modal-show-image").show();
                $("#article-image-shown").attr(
                    "src",
                    public_folder + "/uploads/featured-images/" + responses,
                );
            }

            if (!responses) {
                showUploadFeaturedImageModal();
            }

            console.log(responses);
        },
    });
}

function showProfileEditModal() {
    $(".profile-details-edit").show();
    $(".profile-details-view").hide();
    $(this).hide();
}

function submitProfile() {
    var profile_hidden_userid = $("#profile-hidden-userid").val();
    var profile_hidden_account_type = $("#profile-hidden-account-type").val();
    var profile_description = $("#profile-description").val();
    var profile_first_name = $("#profile-first-name").val();
    var profile_middle_name = $("#profile-middle-name").val();
    var profile_last_name = $("#profile-last-name").val();
    var profile_account_name = $("#profile-account-name").val();
    var profile_school_type = $("#profile-school-type").val();
    var profile_username = $("#profile-username").val();
    var profile_email_address = $("#profile-email-address").val();
    var profile_mobile_number = $("#profile-mobile-number").val();
    var profile_birthdate = $("#profile-birthdate").val();
    var profile_gender = $("#profile-gender").val();
    var profile_civil_status = $("#profile-civil-status").val();
    var profile_educational_attainment = $(
        "#profile-educational-attainment",
    ).val();
    var profile_school = $("#profile-school").val();
    var profile_occupation = $("#profile-occupation").val();
    var profile_country = $("#profile-country").val();
    var profile_region = $("#profile-region").val();
    var profile_province_state = $("#profile-province-state").val();
    var profile_city_municipality = $("#profile-city-municipality").val();
    var profile_barangay = $("#profile-barangay").val();
    var profile_street_subd_village = $("#profile-street-subd-village").val();
    var processing_file =
        "../../private/includes/processing/update-details-processing.php";

    $.ajax({
        url: processing_file,
        type: "POST",
        data: {
            profile_hidden_userid: profile_hidden_userid,
            profile_hidden_account_type: profile_hidden_account_type,
            profile_description: profile_description,
            profile_first_name: profile_first_name,
            profile_middle_name: profile_middle_name,
            profile_last_name: profile_last_name,
            profile_account_name: profile_account_name,
            profile_school_type: profile_school_type,
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
        success: function (response) {
            if (response == "Updated successfully!") {
                url.reload();
            } else {
                $(".profile-details-edit").show();
                $(".profile-details-view").hide();
                $("#profile-update-message").show();
                $("#profile-update-message").html(response);
                hideAlerts();
            }
        },
    });
}

function cancelProfile() {
    $(".profile-details-edit").hide();
    $(".profile-details-view").show();
    $("#edit-profile-details-button").show();
    url.reload();
}

//function to show featured image
function showUploadFeaturedImageModal() {
    resetAlerts();
    var article_id = $("#article-id").val();
    $("#modal-upload-image").show();
    $("#upload-type").val("Featured Image");
    $("#upload-button").text("Update Featured Image");
    $("#upload-action-file").val(
        "../../private/includes/processing/article-processing.php",
    );
    $("#content-hidden-id").val(article_id);
}

//function to show featured image
function showFeaturedImageModal() {
    resetAlerts();
    $("#modal-show-image").show();
}

function getArticleCategories() {
    var mode = $("#article-mode").val();
    var original_category = "";
    var selected_category = "";

    if (mode == "edit") {
        original_category = $("#article-original-category").val();
        selected_category = $("#article-category option:selected").val();
    }

    if (mode == "new") {
        original_category = "";
        selected_category = "Select Category";
    }

    $.ajax({
        url: public_folder + "/get-article-categories",
        type: "POST",
        async: true,
        data: {
            selected_category: selected_category,
            original_category: original_category,
            mode: mode,
            get_article_categories_submit: true,
        },
        success: function (responses) {
            $("#article-category").html(responses);
            console.log(responses);
        },
    });
}

function getArticleCategoriesSettings() {
    $.ajax({
        url: public_folder + "/get-article-categories-settings",
        type: "POST",
        async: true,
        data: {
            get_article_categories_settings_submit: true,
        },
        success: function (responses) {
            $("#article-categories-list-settings").html(responses);
            console.log(responses);
        },
    });
}

function getArticleTags() {
    let selected_tags = $("#article-tags-selected").val();

    $.ajax({
        url: public_folder + "/get-article-tags",
        type: "POST",
        async: true,
        data: {
            selected_tags: selected_tags,
            get_article_tags_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            $("#article-tags-list").html(responses);
        },
    });
}

function pushTag(newValue) {
    var currentValue = $("#article-tags-selected").val();

    if (currentValue === "") {
        $("#article-tags-selected").val(newValue);
    } else {
        if (!currentValue.includes(newValue)) {
            var updatedValues = currentValue + ", " + newValue;
            updatedValues = updatedValues.replace(/,+/g, ",");
            updatedValues = updatedValues.replace(/^,*|,*$|,\s*,/g, "").trim();
            $("#article-tags-selected").val(updatedValues);
        }
    }

    // getArticleTags();
}

function removeTag(newValue) {
    var currentValue = $("#article-tags-selected").val();

    if (currentValue.includes(newValue)) {
        updatedValues = currentValue.replace(newValue, "");

        if (currentValue.includes(", " + newValue)) {
            updatedValues = currentValue.replace(", " + newValue, "");
        }
        updatedValues = updatedValues.replace(/,+/g, ",");
        updatedValues = updatedValues.replace(/^,*|,*$|,\s*,/g, "").trim();
        $("#article-tags-selected").val(updatedValues);
    }

    // getArticleTags();
}

function getArticleTagsSettings() {
    $.ajax({
        url: public_folder + "/get-article-tags-settings",
        type: "POST",
        async: true,
        data: {
            get_article_tags_settings_submit: true,
        },
        success: function (responses) {
            $("#article-tags-list-settings").html(responses);
            console.log(responses);
        },
    });
}

//get article topicss
// function getArticleTopics() {
//     var mode = $("#article-mode").val();
//     var original_topic = "";
//     var selected_topic = "";

//     if (mode == "edit") {
//         var original_topic = $("#article-original-topic").val();
//         var selected_topic = $("#article-topic option:selected").val();
//     }

//     if (mode == "new") {
//         var original_topic = "";
//         var selected_topic = "";
//     }

//     $.ajax({
//         url: public_folder + "/get-article-topics",
//         type: "POST",
//         async: true,
//         data: {
//             selected_topic: selected_topic,
//             original_topic: original_topic,
//             mode: mode,
//             get_article_topics_submit: true,
//         },
//         success: function (responses) {
//             $("#article-topic").html(responses);
//             console.log(responses);
//         },
//     });
// }

function categoryAdd() {
    var new_category = $("#article-category-add-input").val();
    $.ajax({
        url: public_folder + "/add-category",
        type: "POST",
        async: true,
        data: {
            new_category: new_category,
            add_category_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            $("#article-category-add-input").val("");
            getArticleCategoriesSettings();
        },
    });
}

function tagAdd() {
    var new_tag = $("#article-tag-add-input").val();
    $.ajax({
        url: public_folder + "/add-tag",
        type: "POST",
        async: true,
        data: {
            new_tag: new_tag,
            add_tag_submit: true,
        },
        success: function (responses) {
            $("#article-tag-add-input").val("");
            getArticleTagsSettings();
        },
    });
}

// function topicAdd() {
//     var new_topic = $("#article-topic-add-input").val();
//     $.ajax({
//         url: public_folder + "/add-topic",
//         type: "POST",
//         async: true,
//         data: {
//             new_topic: new_topic,
//             add_topic_submit: true,
//         },
//         success: function (responses) {
//             console.log(responses);
//             $("#article-topic-add-input").val("");
//             $("#article-originally-selected-topic").val("");
//             getArticleTopics();
//         },
//     });
// }

function categoryDelete(category) {
    $.ajax({
        url: public_folder + "/delete-category",
        type: "POST",
        async: true,
        data: {
            delete_category: category,
            delete_category_submit: true,
        },
        success: function (responses) {
            getArticleCategoriesSettings();
        },
    });
}

function tagDelete(tag) {
    $.ajax({
        url: public_folder + "/delete-tag",
        type: "POST",
        async: true,
        data: {
            delete_tag: tag,
            delete_tag_submit: true,
        },
        success: function (responses) {
            getArticleTagsSettings();
        },
    });
}

// function topicDelete() {
//     var delete_topic = $("#article-topic option:selected").text();
//     var original_topic = $("#article-original-topic").val();

//     $.ajax({
//         url: public_folder + "/delete-topic",
//         type: "POST",
//         async: true,
//         data: {
//             delete_topic: delete_topic,
//             delete_topic_submit: true,
//         },
//         success: function (responses) {
//             $("#article-topic option:selected").val(original_topic);
//             getArticleTopics();
//         },
//     });
// }

// function closeAddCategory() {
//     $(".article-category-add").hide();
//     $(".article-category-update").show();
//     $("#article-category-delete-submit-button").hide();

//     getArticleCategories();
// }

// function closeAddTopic() {
//     $(".article-topic-add").hide();
//     $(".article-topic-update").show();
//     var original_topic = $("#article-original-topic").val();
//     $("#article-topic option:selected").text(original_topic);
//     getArticleTopics();
// }

function getArticleVersions() {
    var article_id = $("#article-id").val();

    $.ajax({
        url: public_folder + "/get-article-versions",
        type: "POST",
        async: true,
        data: {
            article_id: article_id,
            get_article_versions_submit: true,
        },
        success: function (responses) {
            $("#article-versions-list").html(responses);
        },
    });
}

function showConfirmDeleteArticleModal() {
    var title_name = $("#article-title").val();
    var message =
        "Once deleted, other records for " +
        title_name +
        " will no longer be available.";
    var type = "article";
    var id = $("#article-id").val();
    showConfirmDeleteModal(message, type, id);
}

function updateArticleStatus(action) {
    var article_id = $("#article-id").val();
    $.ajax({
        url: public_folder + "/update-article-status",
        type: "POST",
        data: {
            action: action,
            article_id: article_id,
            update_article_status_submit: true,
        },
        success: function (responses) {
            if (responses == "Successful") {
                initializeArticlePanel();
            }

            console.log(responses);
        },
    });
}

function saveArticle(storage_type) {
    var article_mode = $("#article-mode").val();
    var article_id = $("#article-id").val();
    var article_title = $("#article-title").val();
    var article_category = $("#article-category").val();
    var article_tags = $("#article-tags-selected").val();
    var article_version = $("#article-original-version").val();
    var article_body = $("#editor").html();

    $.ajax({
        url: public_folder + "/save-article",
        type: "POST",
        dataType: "json",
        data: {
            storage_type: storage_type,
            article_mode: article_mode,
            article_id: article_id,
            article_title: article_title,
            article_category: article_category,
            article_tags: article_tags,
            article_version: article_version,
            article_body: article_body,
            save_article_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (storage_type == "db") {
                if (responses["status"] == "Successful") {
                    $("#article-id").val(responses["article-id"]);
                    $("#article-mode").val("edit");

                    initializeArticlePanel();
                    getArticles();
                }

                if (responses["status"] == "Unsuccessful") {
                    $("#article-message").show();
                    $("#article-message").text(responses["error"]);
                    hideAlerts();
                }
            }
        },
    });
}

function getUsers() {
    var query = $("#dashboard-user-search").val();
    var page = $("#user-current-page").val();

    $.ajax({
        url: public_folder + "/get-users",
        type: "POST",
        async: true,
        data: {
            page: page,
            query: query,
            get_users_submit: true,
        },
        success: function (responses) {
            $("#dashboard-users-list").html(responses);
            var user_rows = $("#user-rows").val();
            var user_pages = $("#user-pages").val();
            var current_page = $("#user-current-page").val();

            if (user_rows > 0) {
                if (user_pages > 1) {
                    $(".user-pagination").show();
                }

                if (user_pages == 1) {
                    $(".user-pagination").hide();
                }

                if (current_page == 1) {
                    $("#user-previous-page").hide();
                }

                if (current_page == user_pages) {
                    $("#user-next-page").hide();
                }

                $(".user-pages-show").show();
                $("#user-current-page-show").text(
                    "Page " + current_page + " of",
                );
                $("#user-pages-show").text(user_pages);
            }

            if (user_rows == 0) {
                $(".user-pagination").hide();
                $(".user-pages-show").hide();
            }

            console.log(responses);
        },
    });
}

function showEditUserModal(number) {
    $("#modal-add-edit-user").show();

    $.ajax({
        url: public_folder + "/get-user",
        type: "POST",
        dataType: "json",
        async: true,
        data: {
            user_id: number,
            get_user_submit: true,
        },
        success: function (responses) {
            $("#current-user-id").val(number);

            $("#user-first-name").val(responses["user-first-name"]);
            $("#user-first-name").prop("disabled", true);

            $("#user-middle-name").val(responses["user-middle-name"]);
            $("#user-middle-name").prop("disabled", true);

            $("#user-last-name").val(responses["user-last-name"]);
            $("#user-last-name").prop("disabled", true);

            $("#user-name").val(responses["user-name"]);

            $("#user-email-address").val(responses["user-email-address"]);
            $("#user-email-address").prop("disabled", true);

            $("#user-username").val(responses["user-username"]);
            $("#user-username").prop("disabled", true);

            $("#user-status-delete").show();

            $("#user-add-update-submit-button").text("Update");
            $("#user-delete-button").show();
        },
    });
}

function showAddUserModal() {
    $("#modal-add-edit-user").show();

    $("#user-first-name").val("");
    $("#user-first-name").prop("disabled", false);

    $("#user-middle-name").val("");
    $("#user-middle-name").prop("disabled", false);

    $("#user-last-name").val("");
    $("#user-last-name").prop("disabled", false);

    $("#user-email-address").val("");
    $("#user-email-address").prop("disabled", false);

    $("#user-username").val("");
    $("#user-username").prop("disabled", false);

    $("#user-status-delete").hide();

    $("#user-add-update-submit-button").text("Add");
    $("#user-delete-button").hide();
}

function saveUser(save_action) {
    var user_id = $("#current-user-id").val();
    var user_first_name = $("#user-first-name").val();
    var user_last_name = $("#user-last-name").val();

    var user_email_address = $("#user-email-address").val();
    var user_username = $("#user-username").val();

    var user_type = $("#user-type").val();
    var user_status = $("#user-status").val();

    $.ajax({
        url: public_folder + "/save-user",
        type: "POST",
        dataType: "json",
        async: true,
        data: {
            user_id: user_id,
            user_first_name: user_first_name,
            user_last_name: user_last_name,
            user_email_address: user_email_address,
            user_username: user_username,
            user_type: user_type,
            user_status: user_status,
            save_action: save_action,
            save_user_submit: true,
        },
        success: function (responses) {
            console.log(responses);
            if (responses["status"] == "Unsuccessful") {
                $("#user-message").show();
                $("#user-message").html(responses["error"]);
            }

            if (responses["status"] == "Successful") {
                $("#modal-add-edit-user").hide();
                getUsers();

                if (save_action == "Add") {
                    var verifying_userid = responses["user-id"];
                    var verifying_email_address =
                        responses["user-email-address"];

                    sendVerificationLink(
                        verifying_email_address,
                        verifying_userid,
                    );
                }
            }

            console.log(responses);
        },
    });
}

function showConfirmDeleteUserModal() {
    var title_name = $("#user-name").val();
    var message =
        "Once deleted, other records for " +
        title_name +
        " will no longer be available.";
    var type = "user";
    var id = $("#current-user-id").val();
    showConfirmDeleteModal(message, type, id);
}
function getFeaturedCategories() {
    $.ajax({
        url: public_folder + "/get-featured-categories",
        type: "POST",
        async: true,
        data: {
            get_featured_categories_submit: true,
        },
        success: function (responses) {
            $(".categories-list").html(responses);
        },
    });
}

//function to submit the image
function uploadSubmit() {
    var upload_type = $("#upload-type").val();
    const upload_image = document.getElementById("upload-image").files[0];
    var profile_upload_registrant_hidden_id = $(
        "#profile-upload-registrant-hidden-id",
    ).val();
    var profile_upload_registrant_hidden_accountName = $(
        "#profile-upload-registrant-hidden-accountName",
    ).val();

    var content_hidden_type = $("#content-hidden-type").val();
    var content_hidden_id = $("#content-hidden-id").val();

    var upload_action_file = $("#upload-action-file").val();

    const formData = new FormData();
    formData.append("upload_type", upload_type);
    formData.append("upload_image", upload_image);

    formData.append("content_hidden_type", content_hidden_type);
    formData.append("content_hidden_id", content_hidden_id);

    formData.append(
        "profile_upload_registrant_hidden_id",
        profile_upload_registrant_hidden_id,
    );
    formData.append(
        "profile_upload_registrant_hidden_accountName",
        profile_upload_registrant_hidden_accountName,
    );

    formData.append("upload_image_submit", "true");

    fetch(upload_action_file, {
        method: "POST",
        body: formData,
    })
        .then((response) => response.text())
        .then((result) => {
            if (result != "Upload Successful") {
                $("#modal-upload-image-message").show();
                $("#modal-upload-image-message").html(result);
                hideAlerts();
            }

            if (result == "Upload Successful") {
                $("#modal-upload-image").hide();
            }

            console.log(result);
        })
        .catch((error) => console.error("Error:", error));
}

//function to get featured categories
function getFeaturedArticles() {
    $.ajax({
        url: public_folder + "/get-featured-articles",
        type: "POST",
        async: true,
        data: {
            article_search: article_search,
            article_category: article_category,
            article_tag: article_tag,
            article_date: article_date,
            article_writer: article_writer,
            get_featured_articles_submit: true,
        },
        success: function (responses) {
            $("#articles-list").html(responses);
            console.log(responses);
        },
    });
}

//function to get searched articles
function getSearchedArticles() {
    var query = $("#article-search").val();

    if (query.length > 2) {
        $.ajax({
            url: public_folder + "/get-searched-articles",
            type: "POST",
            async: true,
            data: {
                article_category: article_category,
                article_tag: article_tag,
                article_writer: article_writer,
                article_date: article_date,
                query: query,
                get_searched_articles_submit: true,
            },
            success: function (responses) {
                $("#searched-articles-list").show();
                $("#searched-articles-list").html(responses);
            },
        });
    } else {
        $("#searched-articles-list").hide();
    }
}

function tableOfContents() {
    var tocList = $("#table-of-contents");
    var index = 0;
    var prevH2List = null;

    // Iterate through all H2 and H3 elements within the content container
    $("#article-content h2, #article-content h3").each(function () {
        var $this = $(this);
        var anchorName = "section-" + index;

        // Add a unique ID to the heading so we can link to it
        $this.attr("id", anchorName);

        // Create the list item link
        var listItem =
            "<li><a href='#" + anchorName + "'>" + $this.text() + "</a></li>";

        // Handle nesting
        if ($this.is("h2")) {
            // If it's an H2, add it directly to the main list and prepare a sub-list for H3s
            prevH2List = $("<ul></ul>");
            $(listItem).appendTo(tocList).append(prevH2List);
        } else if ($this.is("h3") && prevH2List) {
            // If it's an H3, nest it under the previous H2's sub-list
            prevH2List.append(listItem);
        }

        index++;
    });
}

// let timeSpentSeconds = 0;
// let scrollTimeout;
// let isScrolling = false;

// function isPageVisible() {
//   return document.visibilityState === "visible";
// }

// window.addEventListener("scroll", () => {
//   isScrolling = true;
//   clearTimeout(scrollTimeout);
//   scrollTimeout = setTimeout(() => {
//     isScrolling = false;
//   }, 10000);
// });

// function trackTime() {
//   if (isPageVisible() && isScrolling) {
//     timeSpentSeconds++;
//     navigator.sendBeacon(
//       url.href,
//       new URLSearchParams({
//         time_spent: Math.ceil(timeSpentSeconds / 60),
//       }),
//     );
//   }
// }

// isScrolling = true;
// scrollTimeout = setTimeout(() => {
//   isScrolling = false;
// }, 10000);

// setInterval(trackTime, 1000);

// window.addEventListener("beforeunload", function () {
//   if (timeSpentSeconds > 0) {
//     navigator.sendBeacon(
//       url.href,
//       new URLSearchParams({
//         time_spent: Math.ceil(timeSpentSeconds / 60),
//       }),
//     );
//   }
// });

function showConfirmDeleteModal(message, type, id) {
    $("#modal-confirm-delete").show();
    $("#delete-message").text(message);
    $("#delete-type").val(type);
    $("#delete-id").val(id);
}

function proceedDelete() {
    var type = $("#delete-type").val();
    var id = $("#delete-id").val();

    $.ajax({
        url: public_folder + "/delete",
        type: "POST",
        async: true,
        data: {
            id: id,
            type: type,
            delete_submit: true,
        },
        success: function (responses) {
            if (responses == "Successful") {
                $("#modal-confirm-delete").hide();

                if (type == "article") {
                    $("#modal-add-edit-article").hide();
                    getArticles();
                }

                if (type == "user") {
                    $("#modal-add-edit-user").hide();
                    getUsers();
                }
            }
        },
    });
}

function showSearchArticlesModal() {
    $("#modal-search-articles").show();
}

function hideSearchArticlesModal() {
    $("#modal-search-articles").hide();
}

function toggleMenuContentMobile() {
    $("#modal-menu").toggle();
}

function showSettingsModal() {
    getArticleCategoriesSettings();
    getArticleTagsSettings();
    $("#modal-settings").show();
}

function toggleArticleMeta() {
    $("#article-meta-container").toggle();
}

function toggleArticleVersions() {
    $("#article-versions-container").toggle();
}
function initializeSummernote() {
    $("#summernote").summernote();

    $("#summernote")
        .summernote({})
        .on("summernote.enter", function (we, e) {
            $(this).summernote("pasteHTML", "<br><br>");
            e.preventDefault();
        });
}
