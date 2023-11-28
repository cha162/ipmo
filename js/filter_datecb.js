$(document).ready(function () {
    var originalTableContent = ""; // To store original table content
  
    // Function to fetch original table content
    function fetchOriginalTableContent() {
      originalTableContent = $("#mainAppTable tbody").html();
    }
  
    // Function to revert table to original content
    function revertToOriginalTable() {
      $("#mainAppTable tbody").html(originalTableContent);
    }
  
    // Click event for Filter button
    $("#filterButton").click(function () {
      var startDate = $("#startDate").val();
      var endDate = $("#endDate").val();
  
      $.ajax({
        url: "get_date.php", // Replace with your PHP file that handles filtering data
        method: "GET",
        data: {
          startDate: startDate,
          endDate: endDate,
        },
        success: function (response) {
          $("#mainAppTable tbody").html(response); // Update table with filtered data
        },
        error: function () {
          alert("Failed to fetch data.");
        },
      });
    });
  
    // Click event for Clear Filter button
    $("#clearFilterButton").click(function () {
      $("#startDate").val("");
      $("#endDate").val("");
      revertToOriginalTable(); // Revert table to original content
      location.reload();
    });
  
    // Fetch original table content on page load
    fetchOriginalTableContent();
  
    $('#mainAppTable').on('click', '.edit-link', function () {
      var recordID = $(this).data('id');
      $('#recordID').val(recordID);
          $('#editModal').modal('show');
      });
  
      // Handle form submission for editing registration number
      $('#editForm').submit(function (e) {
          e.preventDefault();
          var recordID = $('#recordID').val();
          var newRegistrationNumber = $('#registrationNumber').val();
          
          // Send an AJAX request to update the registration number in the database
          $.ajax({
              url: 'edit_regnum.php', // Replace with the actual URL of your PHP script
              method: 'POST',
              data: {
                  recordID: recordID,
                  registrationNumber: newRegistrationNumber
              },
              dataType: 'json',
              success: function (response) {
                  if (response.status === 'success') {
                      // Update the HTML table with the new registration number
                      $('#registration_' + recordID).text(newRegistrationNumber);
                      $('#editModal').modal('hide');
                  } else {
                      alert('Failed to update registration number: ' + response.message);
                  }
              },
              error: function () {
                  alert('An error occurred while updating the registration number.');
              }
          });
      });
  });
  