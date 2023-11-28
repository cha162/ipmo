// JavaScript equivalent of the PHP file type validation
    var allowedFileTypes = ['application/pdf'];

    function validateFileType(fileInput) {
        var fileType = fileInput.files[0].type;
        return allowedFileTypes.includes(fileType);
    }

    // Add event listener to the form submission
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.querySelector('form');
        form.addEventListener('submit', function (event) {
            var fileInputs = document.querySelectorAll('input[type="file"]');
            for (var i = 0; i < fileInputs.length; i++) {
                if (!validateFileType(fileInputs[i])) {
                    alert("Error: Only PDF files are allowed.");
                    event.preventDefault(); // Prevent form submission
                    return;
                }
            }
        });
    });