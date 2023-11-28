$(document).ready(function() {
    $(".author-inputs").on('click', '.add-author', function() {
        var authorInput = '<div class="form-group row">' +
            '<div class="col-10">' +
            '<input type="text" class="form-control" required name="author[]" placeholder="Surname, First name M.I Suffix" value="" style="margin-top: 5px;">' +
            '</div>' +
            '<div class="col-2">' +
            '<button type="button" class="btn btn-dark close-author"><i class="fa fa-trash-o"></i></button>' +
            '</div>' +
            '</div>';
        $(".author-inputs").append(authorInput);
    });

    $(".author-inputs").on('click', '.close-author', function() {
        $(this).closest('.form-group.row').remove();
    });
});

//adviser
$(document).ready(function() {
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
