$(document).ready(function () {
  var originalTableContent = $("#mainAppTable tbody").html();

  function revertToOriginalTable() {
      $("#mainAppTable tbody").html(originalTableContent);
  }

  function populateMainApplications(collegeID, programID) {
      $.ajax({
          url: "get_main_records.php",
          type: "GET",
          data: { CollegeID: collegeID, ProgramID: programID },
          dataType: "json",
          success: function (mainRecords) {
              var mainAppTable = $("#mainAppTable tbody");
              mainAppTable.empty(); // Clear table before appending new records

              $.each(mainRecords, function (index, record) {
                  var row = $("<tr>");
                  row.append("<td>" + record.RegistrationNumber + "</td>");
                  row.append("<td>" + record.Author + "</td>");
                  row.append("<td>" + record.ThesisTitle + "</td>");
                  row.append("<td>" + record.College + "</td>");
                  row.append("<td>" + record.Program + "</td>");
                  row.append("<td>" + record.Adviser + "</td>");
                  row.append("<td>" + record.DateOfSubmission + "</td>");
                  row.append("<td><a href='#' class='edit-link' data-id='" + record.MRecordID + "'>Edit</a></td>");

                  mainAppTable.append(row);
              });
          },
          error: function () {
              alert("Failed to fetch main records.");
          },
      });
  }

  // College and Program dropdown change event
  $("#collegesDropdown, #programsDropdown").on("change", function () {
      var collegeID = $("#collegesDropdown").val();
      var programID = $("#programsDropdown").val();
      populateMainApplications(collegeID, programID);
  });

  // Event handling for editing a record
  $(document).on("click", ".edit-link", function () {
      var recordID = $(this).data("id");
      $("#recordID").val(recordID);
      $("#editModal").modal("show");
  });


  // Event handling for submitting an edited record
  $("#editForm").submit(function (e) {
      e.preventDefault();
      var recordID = $("#recordID").val();
      var newRegistrationNumber = $("#registrationNumber").val();

      $.ajax({
          url: "edit_regnum.php",
          method: "POST",
          data: {
              recordID: recordID,
              registrationNumber: newRegistrationNumber,
          },
          dataType: "json",
          success: function (response) {
              if (response.status === "success") {
                  $("#registration_" + recordID).text(newRegistrationNumber);
                  $("#editModal").modal("hide");
              } else {
                  alert("Failed to update registration number: " + response.message);
              }
          },
          error: function () {
              alert("An error occurred while updating the registration number.");
          },
      });
  });
  

  // Event handling for filtering records based on date
  $("#filterButton").click(function () {
    var startDate = $("#startDate").val();
    var endDate = $("#endDate").val();

    $.ajax({
      url: "get_date.php",
      method: "GET",
      data: {
        startDate: startDate,
        endDate: endDate,
      },
      success: function (response) {
        $("#mainAppTable tbody").html(response);
      },
      error: function () {
        alert("Failed to fetch data.");
      },
    });
  });

  // Event handling for clearing filters and reverting to original table
  $("#clearFilterButton").click(function () {
    $("#startDate, #endDate").val("");
    revertToOriginalTable();
    location.reload();
  });

  $("#downloadExcelButton").click(function () {
    var startDate = $("#startDate").val();
    var endDate = $("#endDate").val();
    var collegesDropdown = $("#collegesDropdown").val();
    var programsDropdown = $("#programsDropdown").val();
  
    // Create a hidden iframe
    var iframe = $("<iframe>")
      .css("display", "none")
      .attr("src", "export_excel.php?" +
        "startDate=" + startDate +
        "&endDate=" + endDate +
        "&collegesDropdown=" + collegesDropdown +
        "&programsDropdown=" + programsDropdown
      )
      .appendTo("body");
  });
  
  
  // College dropdown change event for populating programs dropdown
  $("#collegesDropdown").on("change", function () {
      var collegeID = $(this).val();
      var programsDropdown = $("#programsDropdown");

      if (collegeID !== "") {
          programsDropdown.prop("disabled", true);
          programsDropdown.html('<option value="">Loading...</option>');

          // Populate the Program dropdown based on the selected college
          $.ajax({
              url: "get_programs.php",
              type: "GET",
              data: { CollegeID: collegeID },
              dataType: "json",
              success: function (programs) {
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
              error: function () {
                  alert("Failed to fetch programs.");
                  programsDropdown.prop("disabled", true);
                  programsDropdown.html('<option value="">Select Program</option>');
              }
          });
      } else {
          // If no college is selected, disable and reset the program dropdown
          programsDropdown.prop("disabled", true);
          programsDropdown.html('<option value="">Select Program</option>');
      }
  });

  // College and Program dropdown change event for populating table
  $("#collegesDropdown, #programsDropdown").on("change", function () {
      var collegeID = $("#collegesDropdown").val();
      var programID = $("#programsDropdown").val();
      populateMainApplications(collegeID, programID);
  });

  // Initial population of Colleges dropdown
  $.ajax({
      url: "get_colleges.php",
      type: "GET",
      dataType: "json",
      success: function (colleges) {
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
});