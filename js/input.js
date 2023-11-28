   //author/s
   $(document).ready(function() {
    var maxAuthors = 9;

    $(".author-inputs").on('click', '.add-author', function() {
        // Check if the maximum number of authors has been reached
        if ($(".author-inputs .form-group.row").length < maxAuthors) {
            var authorInput = '<div class="form-group row">' +
                '<div class="col-10">' +
                '<input type="text" class="form-control" required name="author[]" placeholder="Surname, First name M.I Suffix" value="" style="margin-top: 5px;">' +
                '</div>' +
                '<div class="col-2">' +
                '<button type="button" class="btn btn-dark close-author"><i class="fa fa-trash-o"></i></button>' +
                '</div>' +
                '</div>';
            $(".author-inputs").append(authorInput);
        } else {
            // Display a message or take any other action when the maximum is reached
            alert("You can only add a maximum of 10 authors.");
        }
    });

    $(".author-inputs").on('click', '.close-author', function() {
        $(this).closest('.form-group.row').remove();
    });
});


//adviser
$(document).ready(function() {
    var maxAdvisers = 4;

$(".adviser-inputs").on('click', '.add-adviser', function() {
    var adviserInput = '<div class="form-group row">' +
        '<div class="col-10">' +
        '<input type="text" class="form-control" required name="adviser[]" placeholder="Surname, First Name M.I Suffix" value="" style="margin-top: 5px;">' +
        '</div>' +
        '<div class="col-2">' +
        '<button type="button" class="btn btn-dark close-adviser"><i class="fa fa-trash-o"></i></button>' +
        '</div>' +
        '</div>';
    $(".adviser-inputs").append(adviserInput);
});

$(".adviser-inputs").on('click', '.close-adviser', function() {
    $(this).closest('.form-group.row').remove();
});
});
