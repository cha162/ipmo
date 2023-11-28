$(document).ready(function () {
    // Populate the College dropdown
    $.ajax({
        url: 'get_colleges.php',
        type: 'GET',
        dataType: 'json',
        success: function (colleges) {
            var collegesDropdown = $('#collegesDropdown');
            $.each(colleges, function (index, college) {
                collegesDropdown.append('<option value="' + college.CollegeID + '">' + college.College_Name + '</option>');
            });
        }
    });

    // College dropdown change event
    $('#collegesDropdown').on('change', function () {
        var collegeID = $(this).val();
        var programsDropdown = $('#programsDropdown');

        if (collegeID !== '') {
            programsDropdown.prop('disabled', true);
            programsDropdown.html('<option value="">Loading...</option>');
            // Populate the Program dropdown
            $.ajax({
                url: 'get_programs.php',
                type: 'GET',
                data: { CollegeID: collegeID },
                dataType: 'json',
                success: function (programs) {
                    programsDropdown.html('');
                    $.each(programs, function (index, program) {
                        programsDropdown.append('<option value="' + program.ProgramID + '">' + program.Program_Name + '</option>');
                    });
                    programsDropdown.prop('disabled', false);
                }
            });
        } else {
            programsDropdown.prop('disabled', true);
            programsDropdown.html('');
        }
    });
});

$(document).ready(function () {
    // Populate the Branch dropdown
    $.ajax({
        url: 'get_branches.php',
        type: 'GET',
        dataType: 'json',
        success: function (branches) {
            var branchesDropdown = $('#branchesDropdown');
            $.each(branches, function (index, branch) {
                branchesDropdown.append('<option value="' + branch.BranchID + '">' + branch.BC_Name + '</option>');
            });
        }
    });

    $('#branchesDropdown').on('change', function () {
        var branchID = $(this).val();
        var bcProgramsDropdown = $('#bcProgramsDropdown');  // Use the correct ID

        if (branchID !== '') {
            bcProgramsDropdown.prop('disabled', true);
            bcProgramsDropdown.html('<option value="">Loading...</option>');
            // Populate the BC Program dropdown
            $.ajax({
                url: 'get_bcprogram.php',  // Correct URL
                type: 'GET',
                data: { BranchID: branchID },
                dataType: 'json',
                success: function (bcPrograms) {
                    bcProgramsDropdown.html('');
                    $.each(bcPrograms, function (index, bcProgram) {
                        bcProgramsDropdown.append('<option value="' + bcProgram.BCProgramID + '">' + bcProgram.BCProgram_Name + '</option>');
                    });
                    bcProgramsDropdown.prop('disabled', false);
                }
            });
        } else {
            bcProgramsDropdown.prop('disabled', true);
            bcProgramsDropdown.html('');
        }
    });
});
