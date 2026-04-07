$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    if (page_name != "Dashboard") {
        getFeaturedCategories();

        if (article_category || article_tag || article_date || article_writer) {
            getFeaturedArticles();
        }
    }

    if (page_name == "Dashboard") {
        getProfile();
        getAuthors();
        getArticles();
        getUsers();
    }

    $("#profile-submit-button").click(function () {
        saveProfile();
    });

    $(".login-button").click(function () {
        $(".modal").hide();
        $("#modal-login").show();
    });

    $("#login-submit-button").click(function () {
        login();
    });

    $("#login-email-username, #login-password").keydown(function (event) {
        if (event.keyCode === 13) {
            login();
        }
    });

    $("#reset-password-button").click(function () {
        showGetPasswordResetLinkModal();
    });

    $("#get-password-reset-link-submit-button").click(function () {
        getPasswordResetLink();
    });

    $("#get-password-reset-link-email-username").keydown(function (event) {
        if (event.keyCode === 13) {
            getPasswordResetLink();
        }
    });

    $("#home-logout-button").click(function () {
        logOut();
    });

    $("#dashboard-logout-button").click(function () {
        logOut();
    });

    $("#dashboard-profile-action-edit-button").click(function () {
        showEditProfileModal();
    });

    $("#article-search").on("input", function () {
        getSearchedArticles();
    });

    $("#dashboard-article-search").on("input", function () {
        $("#article-current-page").val(1);
        getArticles();
    });

    $(document).on("input", "#dashboard-article-author", function () {
        $("#article-current-page").val(1);
        getArticles();
    });

    $("#article-previous-page").click(function () {
        var current_page = $("#article-current-page").val();
        current_page = Number(current_page) - 1;
        $("#article-current-page").val(current_page);
        getArticles();
    });

    $("#article-next-page").click(function () {
        var current_page = $("#article-current-page").val();
        current_page = Number(current_page) + 1;
        $("#article-current-page").val(current_page);
        getArticles();
    });

    //show add or edit article modal and trigger related functions when an add button is clicked
    $("#article-add-button").click(function () {
        $("#article-id").val("");
        $("#article-mode").val("new");
        initializeArticlePanel();
    });

    //show add or edit article modal and trigger related functions when an a title is clicked
    $(document).on("click", "#dashboard-articles-list strong", function () {
        var list_id = $(this).attr("id");
        const number = list_id.match(/\d+/)[0];

        $("#article-id").val(number);
        $("#article-mode").val("edit");

        initializeArticlePanel();
    });

    $(document).on("input", "#article-version", function () {
        getVersionContent();
    });

    $("#article-image-button").click(function () {
        checkFeaturedImage();
    });

    $("#profile-picture-camera-button").click(function () {
        showUploadProfilePictureModal();
    });

    $(".change-featured-image").click(function () {
        $("#modal-show-image").hide();
        showUploadFeaturedImageModal();
    });

    initializeArticleCategory();

    $(document).on("input", "#article-category", function () {
        initializeArticleCategory();
    });

    $(document).on("input", "#article-topic", function () {
        initializeArticleTopics();
    });

    $(document).on("click", "#article-category-add-submit-button", function () {
        categoryAdd();
    });

    $(document).on("click", "#article-topic-add-submit-button", function () {
        topicAdd();
    });

    $(document).on(
        "click",
        "#article-category-delete-submit-button",
        function () {
            categoryDelete();
        },
    );

    $(document).on("click", "#article-topic-delete-submit-button", function () {
        topicDelete();
    });

    $(document).on("click", "#article-category-add-close", function () {
        closeAddCategory();
    });

    $(document).on("click", "#article-topic-add-close", function () {
        closeAddTopic();
    });

    $("#article-delete-button").click(function () {
        showConfirmDeleteArticleModal();
    });

    $("#article-publish-button").click(function () {
        var action = "publish";
        updateArticleStatus(action);
    });

    $("#article-unpublish-button").click(function () {
        var action = "unpublish";
        updateArticleStatus(action);
    });

    $(".note-editable,#article-title,#article-category,#article-topic").on(
        "input",
        function () {
            var storage_type = "session";
            saveArticle(storage_type);
        },
    );

    $("#article-save-button").click(function () {
        var storage_type = "database";
        saveArticle(storage_type);
    });

    $("#dashboard-user-search").on("input", function () {
        $("#user-current-page").val(1);
        getUsers();
    });

    $("#user-previous-page").click(function () {
        var current_page = $("#user-current-page").val();
        current_page = Number(current_page) - 1;
        $("#user-current-page").val(current_page);
        getUsers();
    });

    $("#user-next-page").click(function () {
        var current_page = $("#user-current-page").val();
        current_page = Number(current_page) + 1;
        $("#user-current-page").val(current_page);
        getUsers();
    });

    $(document).on("click", "#dashboard-users-list strong", function () {
        resetAlerts();
        var list_id = $(this).attr("id");
        const number = list_id.match(/\d+/)[0];
        showEditUserModal(number);
    });

    $("#user-add-button").click(function () {
        resetAlerts();
        showAddUserModal();
    });

    $("#user-add-update-submit-button").click(function () {
        var add_update_action = $(this).text();
        submitUser(add_update_action);
    });

    $("#user-delete-button").click(function () {
        showConfirmDeleteUserModal();
    });

    //for profile picture
    $("#profile-picture-camera-button").click(function () {
        resetAlerts();
        $("#modal-upload-image").show();
        $("#upload-type").val("Profile Picture");
        $("#upload-button").text("Update Profile Picture");
        $("#upload-action-file").val(
            "../../private/includes/processing/users-processing.php",
        );
    });

    $("#upload-button").click(function () {
        uploadSubmit();
    });

    $("#close-modal-show-image").click(function () {
        closeShowImageModal();
    });

    $("#close-modal-upload-image").click(function () {
        closeUploadImageModal();
    });

    $("#close-modal-confirm-delete").click(function () {
        closeConfirmDeleteModal();
    });

    $("#delete-confirmed-button").click(function () {
        proceedDelete();
    });

    $("#edit-profile-details-button").click(function () {
        showProfileEditModal();
    });

    $("#update-profile-details-submit-button").click(function () {
        submitProfile();
    });

    //WHEN THE DOCUMENT IS READY, HIDE THE EDITABLE PROFILE DETAILS WHEN A BUTTON IS CLICKED.
    $("#update-profile-details-cancel-button").click(function () {
        cancelProfile();
    });

    $(".close").click(function () {
        $(".modal").hide();
    });

    initializeSummernote();
});
