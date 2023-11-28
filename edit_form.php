<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = $_GET['id'];

    if ($type === 'college') {
        $table = 'college';
        $id_column = 'CollegeID';
        $name_column = 'College_Name';
    } elseif ($type === 'program') {
        $table = 'program';
        $id_column = 'ProgramID';
        $name_column = 'Program_Name';
    }

    $sql = "SELECT * FROM $table WHERE $id_column = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_name = $row[$name_column];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit College or Program</title>
    
    <title>Select College or Program to Edit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="imgs/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.bundle.min.js / bootstrap.bundle.js" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> 
   <style>
             .card-footer{
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                text-align: center;
            }
            .card{
             width: 100%;
             margin-left: 27%;
             margin-top: 10%;
             background: #f2f2f2;
            }
            .card-title{
                text-align: center;
            }
            
            .btn{
                background:maroon;
                color:white;
            }
            .card-link{
                color:maroon;
                line-height: 3;
            }
            
        .navbar{
            background-color: maroon;

        }
         #dropbtns {
            color: white;
            font-size: 16px;
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

            .dropdowns-content a:hover {background-color: gray; }

            .dropdowns:hover .dropdowns-content {
            display: block;
            }
    </style>

</head>
<body>
<?php include 'new-header-admin.php'; ?>
<!-- BODY -->
<div class="card" style="width:35rem;">
  <div class="card-body">   
    <div class="card-title">
    <h2>Edit <?php echo ucfirst($type); ?></h2>
        </div>

    <form action="process_edit.php" method="post">
                <input type="hidden" name="type" value="<?php echo $type; ?>">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="form-group row">
                <label for="new_name">Enter New <?php echo ucfirst($type); ?> Name:</label>
                <input type="text" id="new_name" class="form-control" name="new_name" value="<?php echo $current_name; ?>" required>
                <input type="submit" value="Update" class="btn" style="background: maroon; margin-top: 15px;">
                
    </form>
    </div>
</body>
</html>