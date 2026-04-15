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

if (url.href.includes("/articles/")) {
    article_category = $("#hidden-article-category").val();
    article_tag = $("#hidden-article-tag").val();
    article_date = $("#hidden-article-date").val();
    article_writer = $("#hidden-article-writer").val();
    article_slug = $("#hidden-article-slug").val();
    article_search = $("#article-search").val();
}
