function createAccountSubmit() {
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
    url: account_processing_file,
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
    success: function (response) {
      console.log(response);
      $("#create-account-message").show();

      if (response["status"] == "Successful") {
        $("#create-account-message").addClass("alert-success");
        $("#create-account-message").removeClass("alert-danger");
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

        $("#create-account-message").html(
          "Your account was created successfully. Verify it now to continue login. An email has been sent to you.",
        );

        var user_id = response["user-id"];
        var email_address = response["email-address"];
        var account_age = "new";

        sendVerificationLink(
          user_id,
          email_address,
          account_processing_file,
          account_age,
        );
      }

      if (response["status"] == "Unsuccessful") {
        $("#create-account-message").addClass("alert-danger");
        $("#create-account-message").removeClass("alert-success");

        $("#create-account-message").html(response["error"]);
      }
    },
  });

  hideAlerts();
}
