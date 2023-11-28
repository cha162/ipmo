$(document).ready(function () {
    var adviserCount = 1; // Initial adviser count

    // Function to add adviser input fields
    function addAdviserInput(adviserData) {
        var newAdviserInput = '<div class="form-group row">' +
            '<label for="adviser" class="col-sm-2 form-label">Adviser ' + (adviserCount + 1) + ':</label>' +
            '<div class="col-sm-8">' +
            '<input type="text" class="form-control" required name="adviser[]" style="width:80%;" placeholder="" value="' + adviserData + '">' +
            '</div>' +
            '<div class="col-sm-2">' +
            '<button type="button" class="btn btn-dark close-adviser"><i class="fa fa-trash-o"></i></button>' +
            '</div>' +
            '</div>';

        $('#advisersContainer').append(newAdviserInput);
        adviserCount++;

        $("#advisersContainer").on('click', '.close-adviser', function() {
            $(this).closest('.form-group.row').remove();
        });
    }

    // Function to fetch adviser data from the server
    function fetchAdviserData() {
        $.ajax({
            url: 'fetch_adviser_data.php', // Replace with the actual server-side script
            type: 'POST',
            data: { /* Include any necessary parameters */ },
            dataType: 'json',
            success: function (adviserDataArray) {
                // Check if there is data available for the next adviser
                if (adviserDataArray.length >= adviserCount + 1) {
                    addAdviserInput(adviserDataArray[adviserCount]);
                } else {
                    addAdviserInput('');
                }
            },
            error: function (error) {
                console.error('Error fetching adviser data:', error);
            }
        });
    }

    // Add Adviser button click event
    $('.add-advisers').on('click', function () {
        fetchAdviserData();
    });
});
