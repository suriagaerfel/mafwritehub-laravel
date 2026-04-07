//THIS IS THE FUNCTION TO SET VARIABLES FOR THE CURRENT URL.
const current_url = window.location;
const urlParams = new URLSearchParams(current_url.search);

var page_name = $("#page-name").val();
var private_folder = $("#private-folder").val();
var public_folder = $("#public-folder").val();
var registrant_id = $("#registrant-id").val();
var registrant_code = $("#registrant-code").val();
var account_name = $("#account-name").val();
var account_type = $("#account-type").val();

let teacher_registration = $("#teacher-registration").val();

let wm_super_manager_registration = $("#wm-super-manager-registration").val();
let wm_registration_manager_registration = $(
    "#wm-registration-manager-registration",
).val();
let wm_subscription_manager_registration = $(
    "#wm-subscription-manager-registration",
).val();
let wm_message_manager_registration = $(
    "#wm-message-manager-registration",
).val();
let wm_promotion_manager_registration = $(
    "#wm-promotion-manager-registration",
).val();

let tool_subscribed = $("#tool-subscribed").val();
let pending_tool_subscription = $("#pending-tool-subscription").val();

let file_subscribed = $("#file-subscribed").val();
let pending_file_subscription = $("#pending-file-subscription").val();

let seller_subscribed = $("#seller-subscribed").val();
let pending_seller_subscription = $("#pending-seller-subscription").val();

let shelf_subscribed = $("#shelf-subscribed").val();
let pending_shelf_subscription = $("#pending-shelf-subscription").val();

var slug = $("#content-slug").val();
var show_comment_modal = $("#comment-modal-shown").val();
const message_seller_code = $("#seller-code").val();

//set the processing files
var home_searched_user = $("#home-searched-user").val();
var show_shared = "";
var content_slug = $("#content-slug").val();
var general_processing_file = public_folder + "/ajax";
var account_processing_file = public_folder + "/ajax";
var contents_processing_file = public_folder + "/ajax";
var content_type = "";
var role = "";

if (!home_searched_user) {
    if (page_name == "Home") {
    }

    if (page_name != "Home") {
        if (!page_name.includes("Workspace")) {
            if (
                page_name == "Teacher Files" ||
                page_name == "Articles" ||
                page_name == "Researches" ||
                page_name == "Tools"
            ) {
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
                }
            }

            if (content_slug) {
            }
        }

        if (page_name.includes("Workspace")) {
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
