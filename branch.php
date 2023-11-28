<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION["logged_out"]) && $_SESSION["logged_out"] === true) {
    header('location:login.php');
    exit();
}
$studentId = $_SESSION["user_id"]; // Assuming the user_id is the student's ID
$query = "SELECT RefNum, Author,ThesisTitle, Adviser, BranchID, BCProgramID, DateofSubmission, Remarks, Comments  FROM campusbranchesapplication WHERE CBUserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $studentId);
$stmt->execute();
$stmt->bind_result($randomgen,$author,$thesistitle,$adviser,$branchesDropdown,$bcProgramsDropdown,$date,$remarks, $comments);
$stmt->fetch();
$stmt->close();

$syquery = "SELECT School_Year FROM acadyear WHERE selected = 'true'";
$systmt = $conn->prepare($syquery);
$systmt->execute();
$systmt->bind_result($schoolYear);
$systmt->fetch();
$systmt->close();

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.bundle.min.js / bootstrap.bundle.js" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="icon" href="imgs/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Applicant - Upload</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/brinputs.js"></script>
    <script src="js/validate.js"></script>

   <style>
        .navbar{
            background-color: maroon;
        }
         #dropbtns {
            color: blue;
            font-size: 14px;
            border: none;
            cursor: pointer;
            }

        .dropdowns {
            position: relative;
            display: inline-block;
            }

         .dropdowns-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
       
            }

            .dropdowns-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            }

            .dropdowns-content a:hover {
                background-color: gray;
             }

            .dropdowns:hover .dropdowns-content {
            display: block;
            }
            .card{
             width: 75%;
             text-align: center;
             margin-left: 10%;
             margin-top: 5%;

            }
             .card-footer{
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                text-align: center;
           
            }
            .card-body{
                text-align: left;
                color: black;
            }
         

         
    </style>
</head>
<body>
    
<?php 
    include 'new-header.php';
?>


<div class="card border-light mb-3" style="margin-left: 10%;">
  <div class="card-header" style="font-size:larger; text-align:left;">Copyright Applications - Branches/Campuses</div>
  <div class="card-body">

                <strong>Please fill in the required information:</strong>
                <form action="application.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="branch_mode" value="branch">

                    <div class="form-group row">
                        <label for="school_year" class="col-sm form-label">School Year</label>
                        <div>
                        <input type="text" class="form-control" required name="school_year" placeholder="<?php echo htmlspecialchars($schoolYear); ?>" value="<?php echo htmlspecialchars($schoolYear); ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="thesis_classification" class="col-sm form-label" >Thesis Classification:</label>
                        <select id="thesis_classification" class="form-control" name="thesis_classification" style="margin-left:1%;" required>
                        <option selected disabled class="col-sm-2 col-form-label">Classification which the work belongs</option>
                        <option value="a">a - Books, Pamphlets, articles and other writings</option>
                        <option value="b">b - Periodicals and newspaper</option>
                        <option value="c">c - Lectures, sermons, addresses, dissertations for oral delivery, whether or not reduced in writing or other material form</option>
                        <option value="d">d - Letters</option>
                        <option value="e">e - Dramatic or dramatico-musical compositions; choreographic works or entertainment in dumb shows</option>
                        <option value="f">f - Musical compositions with or without words</option>
                        <option value="g">g - Works of drawing, painting, architecture, sculpture, engraving, litography or other works of arts</option>
                        <option value="h">h - Original ornamental designs, or models for articles of manufacture, whether or not registrable as an industrial designs and other works of applied art</option>
                        <option value="I">I - Illustrations maps, plans, sketches, charts and three-dimensional works relative to geography, topography, architecture or science</option>
                        <option value="j">j - Drawings or plastic works of a scientific or technical character</option>
                        <option value="k">k - Photographic works including works produced by a process analogous to photography, lantern slide</option>
                        <option value="l">l - Audiovisual works and cinematographic works produced by a process analogous to cinematography or any process for making audio-visual recordings</option>
                        <option value="m">m - Pictorial illustrations and advertisements</option>
                        <option value="n">n - Computer Programs</option>
                        <option value="o">o - Other literary; scholarly, scientific and artistic works</option>
                        <option value="P">P - Sound recordings</option>
                        <option value="q">q - Broadcast recordings</option>
                    </select>
                    </div>

                    <div class="form-group row">
                        <label for="author" class="col-sm form-label">Author/s:</label>
                        <div class="author-inputs">
                            <input type="text" class="form-control" required name="author" placeholder="Surname, First name M.I Suffix"  value="<?php echo $author; ?>">
                            <br>
                            <button type="button" class="btn btn-dark add-author">Add Person</button>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="thesis_title" class="col-sm form-label" >Thesis Title:</label>
                        <input type="text" class="form-control" name="thesis_title" placeholder="Title" style="margin-left:1%; " required value="<?php echo $thesistitle; ?>">
                    </div>

                    <div class="form-group row">
                        <label for="adviser" class="col-sm form-label">Adviser</label>
                        <div class="adviser-inputs">
                        <input type="text" class="form-control" required id="adviser" placeholder="Surname, First Name M.I Suffix"  name="adviser" value="<?php echo $adviser; ?>">
                        <br>
                        <button type="button" class="btn btn-dark add-adviser">Add Person</button>
                        </div>
                    </div>

                <div class="form-group row">
                    <label for="branchesDropdown" class="col-sm-2 col-form-label">Select Branch:</label>
                    <select id="branchesDropdown" class="form-control" name="branchesDropdown"style="margin-left:1%;" required value="<option value='$branchID' $selected>$bc_name</option>">
                    </select>
                </div>

                <div class="form-group row">
                    <label for="bcProgramsDropdown" class="col-sm-2 col-form-label">Select Program:</label>
                    <select id="bcProgramsDropdown" class="form-control" name="bcProgramsDropdown" style="margin-left:1%;"  required value="<option value='$BCprogramID' $selected>$BCprogramName</option>">
                    <option selected disabeled class="col-sm-2 col-form-label">Select Program</option>
                </select>
                </div>

                <div class="form-group row" style="display: flex; align-items:center;">
                        <label for="file_01" class="col-sm form-label">Record of Copyright Application signed by applicant: </label>
                        <input type="file" id="file" class="form-control" name="file_01">
                        <div class="col-auto">
                        <button type="button" id="preview_record" class="btn btn-dark" data-toggle="modal" >View Sample</button>
                    </div>
                </div>

                    <div class="form group row" style="display: flex; align-items:center;">
                        <label for="file_02" class="col-sm form-label">Manuscript in Journal Publication Format:</label>
                        <input type="file" id="file" class="form-control" name="file_02"><br><br>
                        <div class="col-auto">
                        <button type="button" id="preview_journal" class="btn btn-dark">View Sample</button>
                        </div>
                    </div>
                    
                    <div class="form-group row" style="display: flex; align-items:center;">
                        <label for="file_03" class="col-sm form-label">Certificate of Copyright Application:</label>
                        <input type="file" id="file" class="form-control" name="file_03"><br><br>
                        <div class="col-auto">
                        <button type="button" id="preview_certificate" class="btn btn-dark" >View Sample</button>
                        </div>
                    </div>

                    <div class="form-group row" style="display: flex; align-items:center;">
                        <label for="file_04" class="col-sm form-label">Copyright Application with Signature of the Members:</label>
                        <input type="file" id="file" class="form-control" name="file_04"><br><br>
                        <div class="col-auto">
                        <button type="button" id="preview_notarized" class="btn btn-dark">View Sample</button>
                        </div>
                    </div>

                    <div class="form-group row" style="display: flex; align-items:center;">
                        <label for="file_05" class="col-sm form-label">Original Receipt:</label>
                        <input type="file" id="file" class="form-control" name="file_05"><br><br>
                        <div class="col-auto">
                        <button type="button" id="preview_or" class="btn btn-dark" >View Sample</button>
                        </div>
                    </div>
                <div class="form-group row" style="display: flex; align-items: center;">
                    <label for="file_06" class="col-sm form-label">Approval Sheet with signature:</label>
                    <input type="file" id="file" class="form-control" name="file_06"><br><br>
                    <div class="col-auto">
                    <button type="button" id="preview_approval" class="btn btn-dark" data-toggle="modal" data-target="#approvalModal" >View Sample</button>
                    </div>
                </div>

                    <input type="submit" class="btn btn-primary" value="Submit Application" style="margin-left:-1%; margin-bottom: 2%;"><br>
                </form>      
  </div>
</div>

<div class="card-footer" style="background-color: #f9f9f9;">
            <small>All rights reserved 2023</small>
</div>


<!-- Modify the Remarks and Status Modal -->
<div class="modal fade"  id="remarksStatusModal"  data-bs-backdrop="static" tabindex="-1" aria-labelledby="remarksStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" >
        <div class="modal-content" style="width: 100%; height: 50%;" >
            <div class="modal-header">
                <h5 class="modal-title" id="remarksStatusModalLabel">Application Status</h5>
            </div>
            <div class="modal-body">
            <p class="card-text text-muted" style="text-align:left;" >Reference No.: <?php echo $randomgen?></p>
            <p class="card-text text-muted" style="text-align:left;" >Date of Submission: <?php echo $date?> </p>  
            <br>    
            <?php
                // Check if remarks contain "incomplete" to show the "Update" button
                if (!empty($remarks) || !empty($comments)) {
                    echo "<p style='text-align:left; color:maroon;'><strong>Remarks: </strong>$remarks</p>";
                    echo "<p style='text-align:left; color:maroon;'><strong>Comments: </strong>$comments</p>";

                    // Check if remarks contain "incomplete" to show the "Update" button
                    if (strpos($remarks, "Incomplete") !== false) {
                        echo "<button id='updateButton' class='btn btn-primary' style='float:right;'>Update</button>";
                    }
                    
                   
                }
                ?>

                  

                    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><a href="logout.php" style="text-decoration: none; color:white;">Sign out</a></button>
            </div>
        </div>
    </div>
</div>





<!-- Update Modal -->
<div class="modal fade bd-example-modal-lg" id="updateModal" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="top: -13%; overflow-y:hidden; height: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Update Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            <form action="brupdate_application.php" method="post" enctype="multipart/form-data">

                    <input type="hidden" name="branch_mode" value="branch">

                    <div class="form-group row">
                        <label for="school_year" class="col-sm-2 form-label">School Year</label>
                        
                        <input type="text" class="form-control" required name="school_year" placeholder="<?php echo htmlspecialchars($schoolYear); ?>" value="<?php echo htmlspecialchars($schoolYear); ?>" readonly>
                        
                    </div>

                    <div class="form-group row">
                        <label for="author" class="col-sm-2 form-label" >Author/s:</label>
                        <input type="text" class="form-control" required name="author" placeholder="" value="<?php echo $author; ?>">
                    </div>
                    <br>

                    <div class="form-group row">
                        <label for="thesis_title" class="col-sm-2 form-label" >Thesis Title:</label>
                        <input type="text" class="form-control" name="thesis_title" required value="<?php echo $thesistitle; ?>">
                    </div>
                    <br>

                    <div class="form-group row">
                        <label for="adviser" class="col-sm-2 form-label">Adviser</label>
                        <input type="text" class="form-control" required id="adviser" name="adviser" value="<?php echo $adviser; ?>">
                    </div>
                    <br>

                    <div class="form-group row">
                    <label for="branchesDropdown" class="col-sm-3 col-form-label">Select Branch:</label>
                    <select id="branchesDropdownUpdate" class="form-control" name="branchesDropdown" id="branchesDropdown" style="margin-left:1%;" required value="<option value='$branchID' $selected>$bc_name</option>">
                </select>
                </div>

                <div class="form-group row">
                    <label for="bcProgramsDropdown" class="col-sm-3 col-form-label">Select Program:</label>
                    <select id="bcProgramsDropdownUpdate" class="form-control" name="bcProgramsDropdown" id="bcProgramsDropdown" style="margin-left:1%;" value="<option value='$BCprogramID' $selected>$BCprogramName</option>">
                
                </select>
                </div>
       
                <br>
                    <div class="mb-3" style="display: flex; align-items:center;">
                        <label for="file_01" class="col-sm form-label">Record of Copyright Application signed by applicant: </label>
                        <input type="file" id="file" class="form-control" name="file_01" required>

                    </div>
                    <div class="mb-3" style="display: flex; align-items:center;">
                        <label for="file_02" class="col-sm form-label">Manuscript in Journal Publication Format:</label>
                        <input type="file" id="file" class="form-control" name="file_02" required><br><br>
                    </div>
                    <div class="mb-3" style="display: flex; align-items:center;">
                        <label for="file_03" class="col-sm form-label">Certificate of Copyright Application:</label>
                        <input type="file" id="file" class="form-control" name="file_03"><br><br>
                    </div>
                    <div class="mb-3" style="display: flex; align-items:center;" required>
                        <label for="file_04" class="col-sm form-label">Copyright Application with Signature of the Members:</label>
                        <input type="file" id="file" class="form-control" name="file_04" required><br><br>
                    </div>
                    <div class="mb-3" style="display: flex; align-items:center;">
                        <label for="file_05" class="col-sm form-label">Original Receipt:</label>
                        <input type="file" id="file" class="form-control" name="file_05" required><br><br>
                    </div>
                    <div class="mb-3" style="display: flex; align-items:center;">
                        <label for="file_06" class="col-sm form-label">Approval Sheet with signature:</label>
                        <input type="file" id="file" class="form-control" name="file_06" required><br><br>
                    </div>

                    <input type="submit" class="btn btn-primary" name="updatedata" id="updatedata" value="Update Copyright Application" style="float: right; margin-bottom: 5px;" >  
                    <br> <br> <br>
                </form>   

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Populate the Branch dropdown in Update Modal
        $.ajax({
            url: 'get_branches.php',
            type: 'GET',
            dataType: 'json',
            success: function (branches) {
                var branchesDropdownUpdate = $('#branchesDropdownUpdate');
                branchesDropdownUpdate.append('<option selected disabled>Select Branch</option>');
                $.each(branches, function (index, branch) {
                    branchesDropdownUpdate.append('<option value="' + branch.BranchID + '">' + branch.BC_Name + '</option>');
                });
            }
        });

        // Branch dropdown change event in Update Modal
        $('#branchesDropdownUpdate').on('change', function () {
            var branchID = $(this).val();
            var bcProgramsDropdownUpdate = $('#bcProgramsDropdownUpdate');

            if (branchID !== '') {
                bcProgramsDropdownUpdate.prop('disabled', true);
                bcProgramsDropdownUpdate.html('<option value="">Loading...</option>');
                // Populate the BC Program dropdown in Update Modal
                $.ajax({
                    url: 'get_bcprogram.php',
                    type: 'GET',
                    data: { BranchID: branchID },
                    dataType: 'json',
                    success: function (bcPrograms) {
                        bcProgramsDropdownUpdate.html('<option selected disabled>Select BC Program</option>');
                        $.each(bcPrograms, function (index, bcProgram) {
                            bcProgramsDropdownUpdate.append('<option value="' + bcProgram.BCProgramID + '">' + bcProgram.BCProgram_Name + '</option>');
                        });
                        bcProgramsDropdownUpdate.prop('disabled', false);
                    }
                });
            } else {
                bcProgramsDropdownUpdate.prop('disabled', true);
                bcProgramsDropdownUpdate.html('<option value="">Select BC Program</option>');
            }
        });

        // Initialize the Branch dropdown change event on page load
        $('#branchesDropdownUpdate').trigger('change');
      // Handle the close button click in updateModal
      $('#updateModal').on('hidden.bs.modal', function () {
                    $('#remarksStatusModal').modal('show');
                });
            });
</script>
              
            </div>
        </div>
    </div>
</div>





<!--- MODAL --->
<div class="pdfModal">
    <div class="pdfContent">
    <iframe src="sample files/record.pdf" id="pdfFrame" width="100%" height="90%"></iframe>
            <div class="modal-footer">   
            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closeModal()">
    <a href="#" style="text-decoration: none; color:black;">Close</a>
</button>  
            </div>
    </div> 
</div>

<div class="journal">
    <div class="pdfContent">
    <iframe src="sample files/journal.pdf" id="pdfFrame"width="100%" height="90%"></iframe>
            <div class="modal-footer">   
            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closeModal()">
    <a href="#" style="text-decoration: none; color:black;">Close</a>
</button>
            </div>
    </div> 
</div>

<div class="certificate">
    <div class="pdfContent">
    <iframe src="sample files/certificate.pdf"  width="100%" height="90%"></iframe>
    <div class="modal-footer">   
    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closeModal()">
    <a href="#" style="text-decoration: none; color:black;">Close</a>
</button>
            </div>
    </div> 
</div>

<div class="notarized">
    <div class="pdfContent">
    <iframe src="sample files/notarized.pdf"  width="100%" height="90%"></iframe>
    <div class="modal-footer">   
    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closeModal()">
    <a href="#" style="text-decoration: none; color:black;">Close</a>
</button>
            </div>
    </div> 
</div>

<div class="or">
    <div class="pdfContent">
    <iframe src="sample files/original receipt.pdf" width="100%" height="90%"></iframe>
    <div class="modal-footer">   
    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closeModal()">
    <a href="#" style="text-decoration: none; color:black;">Close</a>
</button>
            </div>
    </div> 
</div>

<div class="approval" id="approvalModal">
    <div class="pdfContent">
                    <iframe src="sample files/approval.pdf" width="100%" height="90%"></iframe>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closeModal()">
    <a href="#" style="text-decoration: none; color:black;">Close</a>
</button>
                </div>
            </div>
            </div>


 

<script>
    /* Certificate */
    document.getElementById('preview_certificate').addEventListener('click', function(){
        document.querySelector('.certificate').style.display = 'flex';
    });

    /* record */
    document.getElementById('preview_record').addEventListener('click', function(){
        document.querySelector('.pdfModal').style.display = 'flex';
    });

    /* JOURNAL */
    document.getElementById('preview_journal').addEventListener('click', function(){
        document.querySelector('.journal').style.display = 'flex';
    });

    /* NOTARIZED */
    document.getElementById('preview_notarized').addEventListener('click', function(){
        document.querySelector('.notarized').style.display = 'flex';
    });

    /* OR */
    document.getElementById('preview_or').addEventListener('click', function(){
        document.querySelector('.or').style.display = 'flex';
    });

    /* APPROVAL */
    document.getElementById('preview_approval').addEventListener('click', function(){
        document.querySelector('.approval').style.display = 'flex';
    });

    // Update, remarks, and status modal
    $(document).ready(function() {
        $('#updateButton').click(function() {
            $('#updateModal').modal('show');
            $('#remarksStatusModal').modal('hide');
        });

        // Check if remarks or comments are not empty to show the modal
        var remarks = "<?php echo $remarks; ?>";
        var comments = "<?php echo $comments; ?>";

        if (remarks.trim() !== "" || comments.trim() !== "") {
            $('#remarksStatusModal').modal('show');
        }
    });
    
</script>

<script>
    function closeModal() {
        document.querySelector('.pdfModal').style.display = 'none';
        document.querySelector('.journal').style.display = 'none';
        document.querySelector('.certificate').style.display = 'none';
        document.querySelector('.notarized').style.display = 'none';
        document.querySelector('.or').style.display = 'none';
        document.querySelector('.approval').style.display = 'none';

    }
</script>
   
</body>
</html>


