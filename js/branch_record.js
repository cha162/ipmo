$(document).ready(function () {
    var originalTableContent = $("#mainAppTable tbody").html();

  function revertToOriginalTable() {
      $("#mainAppTable tbody").html(originalTableContent);
  }

    function populateBranchRecords(branchID, bcProgramID) {
        $.ajax({
            url: "get_branch_records.php",
            type: "GET",
            data: { BranchID: branchID, BCProgramID: bcProgramID },
            dataType: "json",
            success: function (branchRecords) {
                var branchAppTable = $("#mainAppTable tbody");
                branchAppTable.empty(); // Clear table before appending new records

                $.each(branchRecords, function (index, record) {
                    var row = $("<tr>");
                    row.append("<td>" + record.RegistrationNumber + "</td>");
                    row.append("<td>" + record.Author + "</td>");
                    row.append("<td>" + record.ThesisTitle + "</td>");
                    row.append("<td>" + record.Branch + "</td>");
                    row.append("<td>" + record.Program + "</td>");
                    row.append("<td>" + record.Adviser + "</td>");
                    row.append("<td>" + record.DateOfSubmission + "</td>");
                    row.append("<td><a href='#' class='edit-link' data-id='" + record.CBRecordID + "'>Edit</a></td>");

                    branchAppTable.append(row);
                });
            },
            error: function () {
                alert("Failed to fetch branch records.");
            },
        });
    }

    // Event handling for populating branch and program dropdowns based on branch selection
$("#branchesDropdown").on("change", function () {
    var branchID = $(this).val();
    var bcprogramsDropdown = $("#bcprogramsDropdown");

    if (branchID !== "") {
        bcprogramsDropdown.prop("disabled", true);
        bcprogramsDropdown.html('<option value="">Loading...</option>');

        // Populate the Program dropdown based on the selected branch
        $.ajax({
            url: "get_bcprogram.php",
            type: "GET",
            data: { BranchID: branchID },
            dataType: "json",
            success: function (programs) {
                bcprogramsDropdown.html('<option value="">Select Program</option>');
                $.each(programs, function (index, bcprogram) {
                    bcprogramsDropdown.append(
                        '<option value="' +
                        bcprogram.BCProgramID +
                        '">' +
                        bcprogram.BCProgram_Name +
                        "</option>"
                    );
                });
                bcprogramsDropdown.prop("disabled", false);
            },
            error: function () {
                alert("Failed to fetch programs.");
                bcprogramsDropdown.prop("disabled", true);
                bcprogramsDropdown.html('<option value="">Select Program</option>');
            }
        });
    } else {
        // If no branch is selected, disable and reset the program dropdown
        bcprogramsDropdown.prop("disabled", true);
        bcprogramsDropdown.html('<option value="">Select Program</option>');
    }
});


    // Event handling for populating table based on branch and program selection
    $("#branchesDropdown, #bcprogramsDropdown").on("change", function () {
        var branchID = $("#branchesDropdown").val();
        var bcProgramID = $("#bcprogramsDropdown").val();
        populateBranchRecords(branchID, bcProgramID);
    });

    // Initial population of Branches dropdown
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
        url: "edit_regnumcb.php", // Update to match your PHP file handling campus and branches record editing
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
        url: "get_datecb.php", // Update to match your PHP file handling date-based filtering for campus and branches records
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

// Event handling for clearing filters and reverting to the original table
$("#clearFilterButton").click(function () {
    $("#startDate, #endDate").val("");
    revertToOriginalTable(); // Ensure you define the revertToOriginalTable function
    location.reload();
});

// Event handling for downloading data as Excel
$("#downloadExcelButton").click(function () {
    var startDate = $("#startDate").val();
    var endDate = $("#endDate").val();
    var branchesDropdown = $("#branchesDropdown").val();
    var bcprogramsDropdown = $("#bcprogramsDropdown").val();
  
    // Create a hidden iframe to trigger the download
    var iframe = $("<iframe>")
        .css("display", "none")
        .attr("src", "export_excelcb.php?" +
            "startDate=" + startDate +
            "&endDate=" + endDate +
            "&branchesDropdown=" + branchesDropdown +
            "&bcprogramsDropdown=" + bcprogramsDropdown
        )
        .appendTo("body");
});
});
