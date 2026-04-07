$(document).ready(function () {
  initializeEditor();

  $("#eskquip-editor").on("input", function () {
    defaultStyles();
  });

  function initializeEditor() {
    appendPanelHeading();
    defaultStyles();
  }

  function appendPanelHeading() {
    $("#eskquip-editor").before("<div id='eskquip-panel'></div>");
    $("#eskquip-panel").append(
      "<icon id='insert-picture'>Insert Picture</icon>",
    );
  }

  function defaultStyles() {
    $("#eskquip-editor p").css("font-family", "initial");
    $("#eskquip-editor p").css("font-size", "initial");
  }

  $("#insert-picture").click(function () {
    alert("insert");
  });

  $("#eskquip-editor").mouseup(function () {
    var selectedText = window.getSelection().toString();
    if (selectedText.length > 0) {
      // alert("You highlighted: " + selectedText);
    }
  });
});
