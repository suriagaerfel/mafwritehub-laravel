//THIS IS THE FUNCTION TO SET VARIABLES FOR THE CURRENT URL.
const current_url = window.location;
const urlParams = new URLSearchParams(current_url.search);
var website_name = $("#website-name-hidden").val();
var page_name = $("#page-name").val();
var private_folder = $("#private-folder").val();
var public_folder = $("#public-folder").val();
var registrant_id = $("#registrant-id").val();
var registrant_code = $("#registrant-code").val();
var slug = $("#content-slug").val();
var show_comment_modal = $("#comment-modal-shown").val();
const message_seller_code = $("#seller-code").val();

//set the processing files
var home_searched_user = $("#home-searched-user").val();
var show_shared = "";
var content_slug = $("#content-slug").val();
var general_processing_file = "";
var account_processing_file = "";
var contents_processing_file = "";
var content_type = "";
var role = "";

if (!home_searched_user) {
  if (page_name == "Home") {
    general_processing_file =
      "../private/includes/processing/general-processing.php";
    account_processing_file =
      "../private/includes/processing/account-processing.php";
    contents_processing_file =
      "../private/includes/processing/contents-processing.php";
    sessions_processing_file =
      "../private/includes/processing/contents-processing.php";
  }

  if (page_name != "Home") {
    if (!page_name.includes("Workspace")) {
      general_processing_file =
        "../../private/includes/processing/general-processing.php";
      account_processing_file =
        "../../private/includes/processing/account-processing.php";
      contents_processing_file =
        "../../private/includes/processing/contents-processing.php";
      sessions_processing_file =
        "../../private/includes/processing/contents-processing.php";

      if (
        page_name == "Teacher Files" ||
        page_name == "Articles" ||
        page_name == "Researches" ||
        page_name == "Tools"
      ) {
        account_processing_file =
          "../../private/includes/processing/account-processing.php";
        general_processing_file =
          "../../private/includes/processing/general-processing.php";
        contents_processing_file =
          "../../private/includes/processing/contents-processing.php";
        sessions_processing_file =
          "../../private/includes/processing/contents-processing.php";

        if (page_name == "Teacher Files") {
          content_type = "Teacher File";
        }

        if (page_name == "Articles") {
          content_type = "Article";
        }

        if (page_name == "Researches") {
          content_type = "Research";
        }

        if (page_name == "Tools") {
          content_type = "Tool";
        }

        category = $("#accessed-category").val();
        tag = $("#accessed-tag").val();
        date = $("#accessed-date").val();
        owner = $("#accessed-owner").val();

        if (category || tag || date || owner) {
          general_processing_file =
            "../../../private/includes/processing/general-processing.php";
          account_processing_file =
            "../../../private/includes/processing/account-processing.php";
          contents_processing_file =
            "../../../private/includes/processing/contents-processing.php";
          sessions_processing_file =
            "../../../private/includes/processing/contents-processing.php";
        }
      }

      if (content_slug) {
        account_processing_file =
          "../../../private/includes/processing/account-processing.php";
        contents_processing_file =
          "../../../private/includes/processing/contents-processing.php";
        general_processing_file =
          "../../../private/includes/processing/general-processing.php";
        sessions_processing_file =
          "../../../private/includes/processing/contents-processing.php";

        // content_type = $("#content-type").val();
      }
    }

    if (page_name.includes("Workspace")) {
      processing_file = general_processing_file =
        "../../private/includes/processing/general-processing.php";
      account_processing_file =
        "../../private/includes/processing/account-processing.php";
      contents_processing_file =
        "../../private/includes/processing/contents-processing.php";
      sessions_processing_file =
        "../../private/includes/processing/contents-processing.php";

      if (page_name == "Workspace - Teacher") {
        content_type = "Teacher File";
        role = "Teacher";
      }

      if (page_name == "Workspace - Writer") {
        content_type = "Article";
        role = "Writer";
      }

      if (page_name == "Workspace - Editor") {
        content_type = "Article";
        role = "Editor";
      }

      if (page_name == "School Workspace - Researches") {
        content_type = "Research";
        role = "School";
      }

      if (page_name == "Workspace - Developer") {
        content_type = "Tool";
        role = "Developer";
      }
    }
  }
}

if (home_searched_user) {
  general_processing_file =
    "../private/includes/processing/general-processing.php";
  account_processing_file =
    "../private/includes/processing/account-processing.php";
  contents_processing_file =
    "../private/includes/processing/contents-processing.php";
  sessions_processing_file =
    "../private/includes/processing/contents-processing.php";
}

$.getJSON(sessions_processing_file.php, function (data) {});
