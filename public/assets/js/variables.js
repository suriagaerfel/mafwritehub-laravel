//THIS IS THE FUNCTION TO SET VARIABLES FOR THE CURRENT URL.
var url = window.location;
var urlParams = new URLSearchParams(url.search);
var website_name = $("#website-name-hidden").val();
var session_userid = $("#session-userid").val();
var page_name = $("#page-name").val();
var public_folder = $("#public-folder").val();

var article_category = "";
var article_tag = "";
var article_date = "";
var article_writer = "";
var article_slug = "";
var article_search = "";

var article_processing_file = "";
var login_processing_file = "";
var logout_processing_file = "";
var get_password_reset_link_processing_file = "";
var users_processing_file = "";
var send_logout_link_processing_file = "";
var send_verification_link_processing_file = "";

if (page_name == "Home") {
    // article_processing_file =
    //     "../private/includes/processing/article-processing.php";
    // login_processing_file =
    //     "../private/includes/processing/login-processing.php";
    // logout_processing_file =
    //     "../private/includes/processing/logout-processing.php";
    // get_password_reset_link_processing_file =
    //     "../private/includes/processing/get-password-link-processing.php";
    // users_processing_file =
    //     "../private/includes/processing/users-processing.php";
    // send_logout_link_processing_file =
    //     "../private/includes/processing/send-logout-link-processing.php";
    // send_verification_link_processing_file =
    //     "../private/includes/processing/send-verification-link-processing.php";
} else {
    if (url.href.includes("/articles/")) {
        article_category = $("#hidden-article-category").val();
        article_tag = $("#hidden-article-tag").val();
        article_date = $("#hidden-article-date").val();
        article_writer = $("#hidden-article-writer").val();
        article_slug = $("#hidden-article-slug").val();
        article_search = $("#article-search").val();

        // article_processing_file =
        //     "../../private/includes/processing/article-processing.php";
        // login_processing_file =
        //     "../../private/includes/processing/login-processing.php";
        // logout_processing_file =
        //     "../../private/includes/processing/logout-processing.php";
        // get_password_reset_link_processing_file =
        //     "../../private/includes/processing/get-password-link-processing.php";
        // users_processing_file =
        //     "../../private/includes/processing/users-processing.php";
        // send_logout_link_processing_file =
        //     "../../private/includes/processing/send-logout-link-processing.php";
        // send_verification_link_processing_file =
        //     "../../private/includes/processing/send-verification-link-processing.php";

        if (
            article_category ||
            article_tag ||
            article_date ||
            article_writer ||
            article_slug
        ) {
            // article_processing_file =
            //     "../../../private/includes/processing/article-processing.php";
            // login_processing_file =
            //     "../../../private/includes/processing/login-processing.php";
            // logout_processing_file =
            //     "../../../private/includes/processing/logout-processing.php";
            // get_password_reset_link_processing_file =
            //     "../../../private/includes/processing/get-password-link-processing.php";
            // users_processing_file =
            //     "../../../private/includes/processing/users-processing.php";
            // send_logout_link_processing_file =
            //     "../../../private/includes/processing/send-logout-link-processing.php";
            // send_verification_link_processing_file =
            //     "../../../private/includes/processing/send-verification-link-processing.php";
        }
    }

    if (!url.href.includes("/articles/")) {
        // article_processing_file =
        //     "../../private/includes/processing/article-processing.php";
        // login_processing_file =
        //     "../../private/includes/processing/login-processing.php";
        // logout_processing_file =
        //     "../../private/includes/processing/logout-processing.php";
        // get_password_reset_link_processing_file =
        //     "../../private/includes/processing/get-password-link-processing.php";
        // users_processing_file =
        //     "../../private/includes/processing/users-processing.php";
        // send_logout_link_processing_file =
        //     "../../private/includes/processing/send-logout-link-processing.php";
        // send_verification_link_processing_file =
        //     "../../private/includes/processing/send-verification-link-processing.php";
    }
}
