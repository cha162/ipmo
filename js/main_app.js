$(document).ready(function () {
  console.log("Document ready");

  var pageSize = 20; // Number of items per page
  var currentPage = 1; // Current page number
  var remarksOptions = ["Processing", "Incomplete", "Complete","Registered"]

  // Function to populate main applications based on selected college and program
  function populateMainApplications(collegeID, programID, searchQuery) {
    $.ajax({
      url: "get_main_applications.php",
      type: "GET",
      data: { CollegeID: collegeID, ProgramID: programID },
      dataType: "json",
      success: function (mainApplications) {
        console.log(mainApplications);

        // Filter main applications based on search query
        if (searchQuery) {
          mainApplications = mainApplications.filter(function (mainApp) {
            return mainApp.RefNum.includes(searchQuery);
          });
        }

        var mainAppTable = $("#mainAppTable tbody");
        mainAppTable.empty()

        // Inside the loop where you create table rows

        $.each(mainApplications, function (index, mainApp) {
          var row = $("<tr>");
          var pdfCell = $("<td>");
          var pdfButtonsContainer = $("<div>").addClass(
            "pdf-buttons-container"
          );
          
          row.append("<td>" + mainApp.RefNum + "</td>"); // Display RefNum field
          row.append("<td>" + mainApp.ThesisTitle + "</td>");
          row.append("<td>" + mainApp.DateOfSubmission + "</td>")
          .css("line-height", "1.5"); // Adjust line-height with CSS


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
              .addClass("document-type");

              var pdfButton = $("<button>")
              .text("View PDF " + (i + 1))
              .attr("data-pdf-folder", "student_file/" + mainApp.ThesisTitle)
              .attr("data-pdf-file", mainApp["MFile_0" + (i + 1)])
              .addClass("btn btn-primary") // Add Bootstrap classes here
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
        .attr("data-mappid", mainApp.MAppID)
        .on("change", function () {
        var selectedRemark = $(this).val();
        if (selectedRemark === "Complete") {
         $(this).closest(".remarks-comments-container").find(".upload-file-button").show();
        } else {
          $(this).closest(".remarks-comments-container").find(".upload-file-button").hide();
        }
        });
          // Populate remarks options from your AJAX call
          $.each(remarksOptions, function (index, option) {
            var optionElement = $("<option>").text(option);
            if (option === mainApp.Remarks) {
              // Assuming Remarks is the field from the database
              optionElement.attr("selected", "selected");
            }
            remarksDropdown.append(optionElement);
          });

          var seeAllRemarksButton = $("<button>")
            .text("Edit Remarks")
            .addClass("btn btn-secondary")
            .on("click", function () {
              row.toggleClass("show-all-remarks");
            });

          var commentsInput = $("<input>")
            .addClass("comments-input")
            .attr("data-mappid", mainApp.MAppID)
            .attr("type", "text")
            .attr("placeholder", "Enter comment")
            .val(mainApp.Comments); // Assuming Comments is the field from the database
          var saveButton = $("<button>")
            .addClass("save-button btn btn-secondary")
            .attr("data-mappid", mainApp.MAppID)
            .text("Save")
            .css("margin-top", "8px") 
            .css("margin-bottom", "-10px")
            .css("margin-left", " 5px"); 

          var uploadFileButton = $("<input>")
          .addClass("upload-file-button")
          .attr("data-mappid", mainApp.MAppID)
          .attr("type", "file")
          .attr("accept", ".pdf") // Accept only PDF files
          .css("display", "none"); // Initially hide the upload file button 
          var mergedContainer = $("<div>").addClass(
            "remarks-comments-container"
          );
          mergedContainer.append(remarksDropdown, commentsInput, saveButton, uploadFileButton);
          remarksCell.append(mergedContainer, seeAllRemarksButton);

          var downloadCell = $("<td>");
          var downloadLink = $("<a>")
            .attr("href", "download_files.php?app_id=" + mainApp.MAppID + "&mode=main") // Link to your PHP script
            .attr("download", mainApp.ThesisTitle + ".zip")
            .text("Download All Files")
            

          downloadCell.append(downloadLink);
          row.append(pdfCell, remarksCell, downloadCell);
          mainAppTable.append(row);
        });

        updatePaginationControls(mainApplications.length);
      },
    });
  }

  // Populate the College dropdown
  $.ajax({
    url: "get_colleges.php",
    type: "GET",
    dataType: "json",
    success: function (colleges) {
      console.log("Colleges data received:", colleges);
      var collegesDropdown = $("#collegesDropdown");
      $.each(colleges, function (index, college) {
        collegesDropdown.append(
          '<option value="' +
            college.CollegeID +
            '">' +
            college.College_Name +
            "</option>"
        );
      });
    },
  });

  // College dropdown change event
  $("#collegesDropdown").on("change", function () {
    var collegeID = $(this).val();
    var programsDropdown = $("#programsDropdown");
    console.log("College dropdown change event:", collegeID);

    if (collegeID !== "") {
      programsDropdown.prop("disabled", true);
      programsDropdown.html('<option value="">Loading...</option>');

      // Populate the Program dropdown
      $.ajax({
        url: "get_programs.php",
        type: "GET",
        data: { CollegeID: collegeID },
        dataType: "json",
        success: function (programs) {
          console.log("Programs data received:", programs);
          programsDropdown.html('<option value="">Select Program</option>');
          $.each(programs, function (index, program) {
            programsDropdown.append(
              '<option value="' +
                program.ProgramID +
                '">' +
                program.Program_Name +
                "</option>"
            );
          });
          programsDropdown.prop("disabled", false);
        },
      });
    } else {
      programsDropdown.prop("disabled", true);
      programsDropdown.html('<option value="">Select Program</option>');
    }
  });

  // College and Program dropdown change event
  $("#collegesDropdown, #programsDropdown").on("change", function () {
    var collegeID = $("#collegesDropdown").val();
    var programID = $("#programsDropdown").val();
    var searchQuery = $("#searchInput").val().trim(); // Get the search query
    populateMainApplications(collegeID, programID, searchQuery);
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
  $("#mainAppContainer").on("click", ".pdf-button", function () {
    var pdfFolder = $(this).data("pdf-folder");
    var pdfFile = $(this).data("pdf-file");
    openPdfModal(pdfFolder, pdfFile);
  });

  // Search button click event
  $("#searchButton").on("click", function () {
    var collegeID = $("#collegesDropdown").val();
    var programID = $("#programsDropdown").val();
    var searchQuery = $("#searchInput").val().trim(); // Get the search query
    populateMainApplications(collegeID, programID, searchQuery);
  });
  // Clear Search button click event
  $("#clearSearchButton").on("click", function () {
    $("#searchInput").val(""); // Clear the search input
    var collegeID = $("#collegesDropdown").val();
    var programID = $("#programsDropdown").val();
    populateMainApplications(collegeID, programID, ""); // Pass an empty search query to show all results
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
      var collegeID = $("#collegesDropdown").val();
      var programID = $("#programsDropdown").val();
      var searchQuery = $("#searchInput").val().trim();
      populateMainApplications(collegeID, programID, searchQuery);
    }
  });

  $("#nextPageButton").on("click", function () {
    var totalPages = Math.ceil(mainApplications.length / pageSize);
    if (currentPage < totalPages) {
      currentPage++;
      var collegeID = $("#collegesDropdown").val();
      var programID = $("#programsDropdown").val();
      var searchQuery = $("#searchInput").val().trim();
      populateMainApplications(collegeID, programID, searchQuery);
    }
  });

  $("#mainAppContainer").on("click", ".save-button", function () {
    var saveButton = $(this); // Store the button element
    saveButton.prop("disabled", true); // Disable the button while saving
  
    var MAppID = saveButton.data("mappid");
    var remarks = $('.remarks-dropdown[data-mappid="' + MAppID + '"]').val();
    var comments = $('.comments-input[data-mappid="' + MAppID + '"]').val();
    var appType = 'main'; // Set appType to 'main' for main applications
  
    // Check if the remarks is "Complete" and handle file upload
    if (remarks === "Complete") {
      var fileInput = $('.upload-file-button[data-mappid="' + MAppID + '"]')[0];
      var uploadedFile = fileInput.files[0];
  
      if (uploadedFile) {
        var formData = new FormData();
        formData.append('file', uploadedFile);
        formData.append('MAppID', MAppID);
        formData.append('Remarks', remarks);
        formData.append('Comments', comments);
        formData.append('AppType', appType);
  
        $.ajax({
          url: "update_remarks_comments.php", // Use the existing PHP endpoint
          method: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            console.log("File uploaded and Remarks/Comments updated successfully");
          },
          error: function (error) {
            console.error("Error uploading file:", error);
          },
          complete: function () {
            saveButton.prop("disabled", false); // Re-enable the button after saving
          },
        });
      } else {
        console.error("No file selected for upload");
        saveButton.prop("disabled", false); // Re-enable the button if no file is selected
      }
    } else {
      // Use an AJAX call to update the database with the new remarks and comments without file upload
      $.ajax({
        url: "update_remarks_comments.php", // Replace with the actual PHP endpoint
        method: "POST",
        data: {
          MAppID: MAppID,
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
    }
  });
});
