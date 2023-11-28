$(document).ready(function () {
    var authorCount = 1; // Initial author count

    // Function to add author input fields
    function addAuthorInput(authorData) {
        var newAuthorInput = '<div class="form-group row">' +
            '<label for="author" class="col-sm-2 form-label">Author ' + (authorCount + 1) + ':</label>' +
            '<div class="col-sm-8">' +
            '<input type="text" class="form-control" required name="author[]" style="width:80%;" placeholder="" value="' + authorData + '">' +
            '</div>' +
            '<div class="col-sm-2">' +
            '<button type="button" class="btn btn-dark close-author"><i class="fa fa-trash-o"></i></button>' +
            '</div>' +
            '</div>';

        $('#authorsContainer').append(newAuthorInput);
        authorCount++;

        $("#authorsContainer").on('click', '.close-author', function() {
            $(this).closest('.form-group.row').remove();
        });
        
    }

    // Function to fetch author data from the server
    function fetchAuthorData() {
        $.ajax({
            url: 'fetch_author_data.php', // Replace with the actual server-side script
            type: 'POST',
            data: { /* Include any necessary parameters */ },
            dataType: 'json',
            success: function (authorDataArray) {
                // Check if there is data available for the next author
                if (authorDataArray.length >= authorCount + 1) {
                    addAuthorInput(authorDataArray[authorCount]);
                } else {
                    addAuthorInput('');
                }
            },
            error: function (error) {
                console.error('Error fetching author data:', error);
            }
        });
    }

    // Add Person button click event
    $('.add-authors').on('click', function () {
        fetchAuthorData();
    });


});



