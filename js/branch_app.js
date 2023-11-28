$(document).ready(function () {
  console.log("Document ready");

  var pageSize = 20; // Number of items per page
  var currentPage = 1; // Current page number
  var remarksOptions = ["Processing", "Incomplete", "Complete","Registered"]

  function populateCBApplications(branchID, bcprogramID, searchQuery) {
    $.ajax({
      url: "get_branch_applications.php",
      type: "GET",
      data: {
        BranchID: branchID, // Correct the typo here, it should be "BranchID" not "BrachID"
        BCProgramID: bcprogramID,
      },
      dataType: "json",
      success: function (CBApplications) {
        console.log(CBApplications);

        // Filter main applications based on search query
        if (searchQuery) {
          CBApplications = CBApplications.filter(function (CBApp) {
            return CBApp.CBRefNum.includes(searchQuery);
          });
        }

        var CBAppTable = $("#CBAppTable tbody");
        CBAppTable.empty();

        // Inside the loop where you create table rows

        $.each(CBApplications, function (index, CBApp) {
          var row = $("<tr>");
          var pdfCell = $("<td>");
          var pdfButtonsContainer = $("<div>").addClass(
            "pdf-buttons-container"
          );
          row.append("<td>" + CBApp.CBRefNum + "</td>");
          row.append("<td>" + CBApp.ThesisTitle + "</td>");
          row.append("<td>" + CBApp.DateOfSubmission + "</td>")
          .css("line-height", "1.5");

          var pdfCell = $("<td>");
          var pdfButtonsContainer = $("<div>").addClass(
            "pdf-buttons-container"
          );

          var documentTypes = [
            "Record of Copyright Application signed by the Applicant",
            "Full Manuscript & Journal Publication Format",
            "Certificate of Copyright Application",
            "Copyright Application with duly Notarized and Signature of the Members",
            "Original Receipt",
            "Approval Sheet with Signature",
          ];

          for (var i = 0; i < documentTypes.length; i++) {
            var documentType = documentTypes[i];

            var documentTypeElement = $("<p>")
              .text(documentType)
              .addClass("document-type")
              .addClass("text-align","center");

            var pdfButton = $("<button>")
              .text("View PDF " + (i + 1))
              .attr("data-pdf-folder", "student_filecb/" + CBApp.ThesisTitle) // Assuming MUserID is the student's user ID
              .attr("data-pdf-file", CBApp["CBFile_0" + (i + 1)])
              .addClass("btn btn-primary")
              .css("display", "block") // Make the button a block element
              .css("margin", "0 auto")  // Center the button horizontally
              .on("click", function () {
                var pdfFolder = $(this).data("pdf-folder");
                var pdfFile = $(this).data("pdf-file");
                console.log("PDF button clicked:", pdfFolder, pdfFile); // Add this line
                openPdfModal(pdfFolder, pdfFile);
              });

            var mergedContainer = $("<div>").addClass("document-type-and-pdf");
            mergedContainer.append(documentTypeElement, pdfButton);
            pdfButtonsContainer.append(mergedContainer);
          }

          var seeAllPdfButton = $("<button>")
          .text("See All PDF")
          .addClass("btn btn-secondary")
          .css("display", "block") // Make the button a block element
          .css("margin", "0 auto")  // Center the button horizontally
          .on("click", function () {
              row.toggleClass("show-all-pdf");
      
              if (row.hasClass("show-all-pdf")) {
                  // Add space between paragraphs when "See All PDF" is clicked
                  row.find(".pdf-buttons-container p").css("line-height", "1.5");
              } else {
                  // Remove space between paragraphs when "See All PDF" is not active
                  row.find(".pdf-buttons-container p").css("margin-bottom", "0");
              }
          });
      
      var pdfButtons = pdfButtonsContainer.find(".pdf-button"); // Find all PDF buttons
      
      pdfCell.append(pdfButtonsContainer, seeAllPdfButton);
      

          var pdfButtons = pdfButtonsContainer.find(".pdf-button"); // Find all PDF buttons

          pdfCell.append(pdfButtonsContainer, seeAllPdfButton);

          var remarksCell = $("<td>");
          var remarksDropdown = $("<select>")
            .addClass("remarks-dropdown")
            .attr("data-cbappid", CBApp.CBAppID)
            .on("change", function () {
              var selectedRemark = $(this).val();
              if (selectedRemark === "Complete") {
                $(this)
                  .closest(".remarks-comments-container")
                  .find(".upload-file-button")
                  .show();
              } else {
                $(this)
                  .closest(".remarks-comments-container")
                  .find(".upload-file-button")
                  .hide();
              }
            });

          // Populate remarks options from your AJAX call
          $.each(remarksOptions, function (index, option) {
            var optionElement = $("<option>").text(option);
            if (option === CBApp.Remarks) {
              // Assuming Remarks is the field from the database
              optionElement.attr("selected", "selected");
            }
            remarksDropdown.append(optionElement);
          });

          var seeAllRemarksButton = $("<button>")
          .text("Edit Remarks")
          .addClass("btn btn-secondary")
          .css("display", "block")
          .css("margin", "0 auto")
          .on("click", function () {
              row.toggleClass("show-all-remarks");
          });

          var commentsInput = $("<input>")
            .addClass("comments-input")
            .attr("data-cbappid", CBApp.CBAppID)
            .attr("type", "text")
            .attr("placeholder", "Enter comment")
            .val(CBApp.Comments); // Assuming Comments is the field from the database
          var saveButton = $("<button>")
          .addClass("btn btn-secondary")
            .attr("data-cbappid", CBApp.CBAppID)
            .text("Save")
            .css("margin-top", "8px") 
            .css("margin-bottom", "-10px")
            .css("margin-left", " 5px"); 

            var uploadFileButton = $("<input>")
            .addClass("upload-file-button")
            .attr("data-cbappid", CBApp.CBAppID)
            .attr("type", "file")
            .attr("accept", ".pdf")
            .css("display", "none");

          var mergedContainer = $("<div>").addClass(
            "remarks-comments-container"
          );
          mergedContainer.append(
            remarksDropdown,
            commentsInput,
            saveButton,
            uploadFileButton
          );
          remarksCell.append(mergedContainer, seeAllRemarksButton);

          var downloadCell = $("<td>");
          var downloadLink = $("<a>")
          .attr("href", "download_files.php?app_id=" + CBApp.CBAppID + "&mode=branch") // Set the mode to "branch"
          .attr("download", CBApp.ThesisTitle + ".zip")
          .text("Download All Files");


          downloadCell.append(downloadLink);

          row.append(pdfCell, remarksCell, downloadCell);
          CBAppTable.append(row);
        });

        updatePaginationControls(CBApplications.length);
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText); // Log any errors for debugging
      },
    });
  }

  // Populate the Branch dropdown
  $.ajax({
    url: "get_branches.php",
    type: "GET",
    dataType: "json",
    success: function (branches) {
      var branchesDropdown = $("#branchesDropdown");
      $.each(branches, function (index, branch) {
        branchesDropdown.append(
          '<option value="' +
            branch.BranchID +
            '">' +
            branch.BC_Name +
            "</option>"
        );
      });
    },
  });

  $("#branchesDropdown").on("change", function () {
    var branchID = $(this).val();
    var bcProgramsDropdown = $("#bcProgramsDropdown"); // Use the correct ID

    if (branchID !== "") {
      bcProgramsDropdown.prop("disabled", true);
      bcProgramsDropdown.html('<option value="">Loading...</option>');
      // Populate the BC Program dropdown
      $.ajax({
        url: "get_bcprogram.php", // Correct URL
        type: "GET",
        data: { BranchID: branchID },
        dataType: "json",
        success: function (bcPrograms) {
          console.log("Programs data received:", bcPrograms);
          bcProgramsDropdown.html(
            '<option value="">Select BC Program</option>'
          );
          $.each(bcPrograms, function (index, bcProgram) {
            bcProgramsDropdown.append(
              '<option value="' +
                bcProgram.BCProgramID +
                '">' +
                bcProgram.BCProgram_Name +
                "</option>"
            );
          });
          bcProgramsDropdown.prop("disabled", false);
        },
      });
    } else {
      bcProgramsDropdown.prop("disabled", true);
      bcProgramsDropdown.html('<option value="">Select BC Program</option>');
    }
  });
  // College and Program dropdown change event
  $("#branchesDropdown, #bcProgramsDropdown").on("change", function () {
    var BranchID = $("#branchesDropdown").val();
    var BCProgramID = $("#bcProgramsDropdown").val();
    var searchQuery = $("#searchInput").val().trim(); // Get the search query
    populateCBApplications(BranchID, BCProgramID, searchQuery);
  });
  var pdfFiles = []; // Array to store PDF file paths

  function openPdfModal(pdfFolder, pdfFile) {
    pdfFiles.push({ folder: pdfFolder, file: pdfFile });
    showPdf(pdfFiles.length - 1);
    var pdfModal = document.getElementById("pdfModal");
    pdfModal.classList.add("active"); // Add the "active" class
    pdfModal.style.display = "block";
  }

  function showPdf(index) {
    var pdfFrame = document.getElementById("pdfFrame");
    var pdfFile = pdfFiles[index];
    pdfFrame.src = pdfFile.folder + "/" + pdfFile.file;
    currentIndex = index;
  }

  function closePdfModal() {
    var pdfModal = $("#pdfModal");
    pdfModal.removeClass("active"); // Remove the "active" class
    pdfModal.hide();
    var pdfFrame = document.getElementById("pdfFrame");
    pdfFrame.src = "";
  }
  // Bind close button click event
  $("#pdfModal").on("click", "#closePdfButton", function () {
    closePdfModal();
  });

  var currentIndex = 0; // Current index in the pdfFiles array

  // Delegate click event for PDF buttons
  $("#CBAppContainer").on("click", ".pdf-button", function () {
    var pdfFolder = $(this).data("pdf-folder");
    var pdfFile = $(this).data("pdf-file");
    openPdfModal(pdfFolder, pdfFile);
  });

  // Search button click event
  $("#searchButton").on("click", function () {
    var BranchID = $("#branchesDropdown").val();
    var BCProgramID = $("#bcProgramsDropdown").val();
    var searchQuery = $("#searchInput").val().trim(); // Get the search query
    populateCBApplications(BranchID, BCProgramID, searchQuery);
  });
  // Clear Search button click event
  $("#clearSearchButton").on("click", function () {
    $("#searchInput").val(""); // Clear the search input
    var BranchID = $("#branchesDropdown").val();
    var BCProgramID = $("#bcProgramsDropdown").val();
    populateCBApplications(BranchID, BCProgramID, ""); // Pass an empty search query to show all results
  });

  function updatePaginationControls(totalItems) {
    var totalPages = Math.ceil(totalItems / pageSize);
    $("#currentPage").text("Page " + currentPage + " of " + totalPages);
    $("#prevPageButton").prop("disabled", currentPage === 1);
    $("#nextPageButton").prop("disabled", currentPage === totalPages);
  }

  $("#prevPageButton").on("click", function () {
    if (currentPage > 1) {
      currentPage--;
      var BranchID = $("#branchesDropdown").val();
      var BCProgramID = $("#bcProgramsDropdown").val();
      var searchQuery = $("#searchInput").val().trim();
      populateCBApplications(BranchID, BCProgramID, searchQuery);
    }
  });

  $("#nextPageButton").on("click", function () {
    var totalPages = Math.ceil(CBApplications.length / pageSize);
    if (currentPage < totalPages) {
      currentPage++;
      var BranchID = $("#branchesDropdown").val();
      var BCProgramID = $("#bcProgramsDropdown").val();
      var searchQuery = $("#searchInput").val().trim();
      populateCBApplications(BranchID, BCProgramID, searchQuery);
    }
  });
// Delegate change event for remarks dropdown and comments input
$("#CBAppContainer").on(
    "change",
    ".remarks-dropdown, .comments-input",
    function () {
      var CBAppID = $(this).data("cbappid");
      var remarks = $('.remarks-dropdown[data-cbappid="' + CBAppID + '"]').val();
      var comments = $('.comments-input[data-cbappid="' + CBAppID + '"]').val();
      var appType = 'branch'; // Set appType to 'branch' for branch applications
  
      // Use an AJAX call to update the database with the new remarks and comments
      $.ajax({
        url: "update_remarks_comments.php",
        method: "POST",
        data: {
          CBAppID: CBAppID,
          Remarks: remarks,
          Comments: comments,
          AppType: appType
        },
        success: function (response) {
          console.log("Remarks and Comments updated successfully");
        },
        error: function (error) {
          console.error("Error updating Remarks and Comments:", error);
        },
      });
    }
  );
  
  // Delegate click event for save button
  $("#CBAppContainer").on("click", ".save-button", function () {
    var saveButton = $(this); // Store the button element
    saveButton.prop("disabled", true); // Disable the button while saving
  
    var CBAppID = saveButton.data("cbappid");
    var remarks = $('.remarks-dropdown[data-cbappid="' + CBAppID + '"]').val();
    var comments = $('.comments-input[data-cbappid="' + CBAppID + '"]').val();
    var appType = 'branch'; // Set appType to 'branch' for branch applications
  
    // Use an AJAX call to update the database with the new remarks and comments
    $.ajax({
      url: "update_remarks_comments.php", // Replace with the actual PHP endpoint
      method: "POST",
      data: {
        CBAppID: CBAppID,
        Remarks: remarks,
        Comments: comments,
        AppType: appType
      },
      success: function (response) {
        console.log("Remarks and Comments updated successfully");
      },
      error: function (error) {
        console.error("Error updating Remarks and Comments:", error);
      },
      complete: function () {
        saveButton.prop("disabled", false); // Re-enable the button after saving
      },
    });
  });
});