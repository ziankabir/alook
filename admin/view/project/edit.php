<?php
include '../../../config/config.php';
$project_name = '';
$project_image = '';
$project_status = '';
$project_type = '';
$project_date = '';
$project_progress = '';
$project_details = '';
$project_updated_by = getSession('admin_id');
$project_id = '';
$renameFile = '';
$flag = 0;
$countRow = 0;
$dateShow = 'display:none;';
$progressShow = 'display:none;';
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
}
$sqlImage = "SELECT project_image FROM project WHERE project_id= $project_id";
$resultImage = mysqli_query($con, $sqlImage);
if ($resultImage) {
    while ($ImageObj = mysqli_fetch_object($resultImage)) {
        $project_image = $ImageObj->project_image;
    }
} else {
    if (DEBUG) {
        $error = "resultImage error: " . mysqli_error($con);
    } else {
        $error = "resultImage query failed.";
    }
}
if (isset($_POST['project_name'])) {
    extract($_POST);

    $project_name = validateInput($project_name);
    $project_details = validateInput($project_details);
    $project_date = validateInput($project_date);
    $project_status = validateInput($project_status);
    $project_type = validateInput($project_type);

    // check product priority and exist
    $sql_check = "SELECT * FROM project WHERE project_name='$project_name' AND project_id NOT IN (" . $project_id . ")";
    $result_check = mysqli_query($con, $sql_check);
    $count = mysqli_num_rows($result_check);
    if ($count > 0) {
        $error = "Project already exists in record";
    } else {

        // Image upload code start
        if ($_FILES['project_image']['name']) { // Check if image file posted or not
            $targetDirectory = $config['IMAGE_UPLOAD_PATH'] . '/project_image/'; // Target directory where image will save or store
            $targetFile = '';
            echo $fileType = pathinfo(basename($_FILES['project_image']['name']), PATHINFO_EXTENSION); // File type such as .jpg, .png, .jpeg, .gif
            if ($fileType != 'jpg' && $fileType != 'png' && $fileType != 'jpeg' && $fileType != 'gif') { // Check file is in mentioned format or not
                $flag++;
                $error = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed';
            } else {
                if ($_FILES['project_image']['size'] > (1024000)) { // Check file size. File size must be less than 1MB
                    $flag++;
                    $error = 'Image size is too large. Must be less than 1MB';
                } else {

                    $renameFile = "PRI" . date('YmdHis') . '.' . $fileType; // Rename the file name
                    $targetFile = $targetDirectory . $renameFile; // Target image file
                    move_uploaded_file($_FILES['project_image']['tmp_name'], $targetFile);
                    $flag = 0;
                }
            }
        }
        // Image upload code end
        if ($flag == 0) {
            $custom_array = '';
            $custom_array .= 'project_name = "' . $project_name . '"';
            $custom_array .= ',project_details = "' . $project_details . '"';
            if ($_FILES["project_image"]["tmp_name"] != '') {
                $custom_array .= ',project_image = "' . $renameFile . '"';
            }
            $custom_array .= ',project_date = "' . $project_date . '"';
            $custom_array .= ',project_type = "' . $project_type . '"';
            $custom_array .= ',project_progress = "' . $project_progress . '"';
            $custom_array .= ',project_status = "' . $project_status . '"';
            $custom_array .= ',project_updated_by = "' . $project_updated_by . '"';


            $sql = "UPDATE project SET $custom_array WHERE project_id = $project_id";
            $result = mysqli_query($con, $sql);
            if ($result) {
                $success = 'Project information updated successfully';
            $link = baseUrl() . "admin/view/project/list.php?success=" . base64_encode($success);
            redirect($link);
            } else {
                if (DEBUG) {
                    $error = 'result query failed for ' . mysqli_error($con);
                } else {
                    $error = 'Something went wrong';
                }
            }
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
// getting product data
$sqlData = "SELECT * FROM project WHERE project_id = $project_id";
$resultData = mysqli_query($con, $sqlData);
$countRow = mysqli_num_rows($resultData);
if ($countRow > 0) {
    $obj = mysqli_fetch_object($resultData);
    $project_name = $obj->project_name;
    $project_status = $obj->project_status;
    $project_details = $obj->project_details;
    $project_date = $obj->project_date;
    $project_type = $obj->project_type;
    $project_progress = $obj->project_progress;

    if ($project_type == 'Inhand') {
        $dateShow = 'display:block;';
        $progressShow = 'display:block';
    }
    if ($project_type == 'Completed') {
        $dateShow = 'display:block;';
    }
} else {
    
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Alook Refrigeration CO. Ltd | Edit Project</title>
        <?php include basePath('admin/header_script.php'); ?>
        <style>
            .example-modal .modal {
                position: relative;
                top: auto;
                bottom: auto;
                right: auto;
                left: auto;
                display: block;
                z-index: 1;
            }
            .example-modal .modal {
                background: transparent!important;
            }
        </style>
    </head>
    <body class="skin-blue">
        <div class="wrapper">
            <?php include basePath('admin/header.php'); ?>

            <aside class="main-sidebar">
                <?php include basePath('admin/site_menu.php'); ?>
            </aside>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>Edit Project</h1>
                    <ol class="breadcrumb">
                        <li><i class="fa fa-laptop"></i>&nbsp;Project Settings</li>
                        <li class="active">Edit Project</li>
                    </ol>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-primary">
                                <div class="example-modal">
                                    <div class="modal">
                                        <div class="modal-dialog">
                                            <?php include basePath('admin/message.php'); ?>
                                            <div class="modal-content">
                                                <form method="POST" id="productForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <input type="hidden" id="project_id" name="project_id" value="<?php echo $project_id; ?>" />
                                                        <div class="form-group">
                                                            <label for="project_name">Name Of The Job &nbsp;&nbsp;<span style="color:red;">*</span></label>
                                                            <input type="text" class="form-control" id="project_name" name="project_name" value="<?php echo $project_name; ?>" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="project_image">Project Image</label>
                                                            <input type="file" id="project_image" name="project_image" />
                                                        </div>
                                                        <div>
                                                            <?php if ($project_image != ''): ?>
                                                                <img src="<?php echo baseUrl(); ?>upload/project_image/<?php echo $project_image; ?>" id="show_image" style="height: 70px; width: 80px;" />
                                                            <?php else: ?>
                                                                <img src="<?php echo baseUrl(); ?>upload/no_image.jpg" style="height: 70px; width: 80px;" />
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="project_details">Short Particular Of The Projects</label>
                                                            <textarea style="overflow: auto;resize: vertical;" rows="3" cols="30" class="form-control" id="project_details" name="project_details"><?php echo html_entity_decode($project_details, ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="project_type">Project Type&nbsp;&nbsp;<span style="color:red;">*</label>
                                                            <select id="project_type" name="project_type" class="form-control">
                                                                <option value="0">Select Type</option>
                                                                <option value="Completed"
                                                                <?php
                                                                if ($project_type == 'Completed') {
                                                                    echo "selected";
                                                                }
                                                                ?>>Completed
                                                                </option>
                                                                <option value="Inhand"<?php
                                                                if ($project_type == 'Inhand') {
                                                                    echo "selected";
                                                                }
                                                                ?>>Inhand
                                                                </option>
                                                                <option value="Maintainance"<?php
                                                                if ($project_type == 'Maintainance') {
                                                                    echo "selected";
                                                                }
                                                                ?>>Maintainance
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group" id="showMe" style="<?php echo $dateShow; ?>">
                                                            <label for="project_date">Date Of Commencement &nbsp;&nbsp;<span style="color:red;">*</span></label>
                                                            <div data-provide="datepicker" class="input-group date">
                                                                <input type="text" value="<?php echo $project_date; ?>" name="project_date" id="project_date" class="form-control">
                                                                <div class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-th"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group"  id="showMe1" style="<?php echo $progressShow; ?>">
                                                            <label for="project_progress">Project Progress&nbsp;<span style="color:red;">*</span><span style="color: green;">[Between 0-100]</span></label>
                                                            <input type="number" min="0" max="100" class="form-control" id="project_progress" name="project_progress" value="<?php echo $project_progress; ?>"/>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label for="project_status">Project Status&nbsp;&nbsp;<span style="color:red;">*</label>
                                                            <select id="project_status" name="project_status" class="form-control">
                                                                <option value="0">Select Status</option>
                                                                <option value="Active"
                                                                <?php
                                                                if ($project_status == 'Active') {
                                                                    echo "selected";
                                                                }
                                                                ?>>Active
                                                                </option>
                                                                <option value="Inactive"<?php
                                                                if ($project_status == 'Inactive') {
                                                                    echo "selected";
                                                                }
                                                                ?>>Inactive
                                                                </option>
                                                            </select>
                                                        </div>                                                       
                                                        <div class="form-group">
                                                            <p id="errorShow" style="display: none;background-color: #ea2e49;color: white; padding: 4px 4px 2px 4px;font-size: 12px;position: relative;">
                                                                Please fill up required (*) fields
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" id="btnSave" name="btnSave" class="btn btn-primary"><i class="fa fa-edit"></i> Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php include basePath('admin/footer.php'); ?>
        </div>
        <script type="text/javascript">
            $("#projectActive").addClass("active");
            $("#projectActive").parent().parent().addClass("treeview active");
            $("#projectActive").parent().addClass("in");
        </script>
        
        <script>
            $(document).ready(function () {

                $("#project_type").change(function () {
                    $("#project_type option:selected").each(function () {
                        if ($(this).attr("value") == "Completed") {
                            $("#showMe").show();
                            $("#showMe1").hide();
                        }
                        if ($(this).attr("value") == "Inhand") {
                            $("#showMe").show();
                            $("#showMe1").show();
                        }
                        if ($(this).attr("value") == "Maintainance") {
                            $("#showMe1").hide();
                            $("#showMe").hide();
                        }
                    });
                }).change();
            });
        </script>
        <?php include basePath('admin/footer_script.php'); ?>
        <script>
            $(document).ready(function () {
                $("#btnSave").click(function () {

                    var project_name = $("#project_name").val();
                    var project_type = $("#project_type option:selected").val();
                    var project_date = $("#project_date").val();
                    var project_progress = $("#project_progress").val();
                    var project_status = $("#project_status option:selected").val();

                    var status = 0;

                    if (project_name == '') {
                        status++;
                        $("#errorShow").show();
                        $("#project_name").css({
                            "border": "1px solid red"
                        });
                    }


                    if (project_type == '0') {
                        status++;
                        $("#errorShow").show();
                        $("#project_type").css({
                            "border": "1px solid red"
                        });
                    }
                    if (project_name != '') {
                        $("#project_name").css({
                            "border": "1px solid #d2d6de"
                        });
                    }
                    if (project_type != '0') {
                        $("#project_type").css({
                            "border": "1px solid #d2d6de"
                        });
                    }
                    if (project_type === 'Completed') {
                        if (project_date == '') {
                            status++;
                            $("#errorShow").show();
                            $("#project_date").css({
                                "border": "1px solid red"
                            });
                        }
                    }
                    if (project_type === 'Inhand') {
                        if (project_date == '') {
                            status++;
                            $("#errorShow").show();
                            $("#project_date").css({
                                "border": "1px solid red"
                            });
                        }
                        if (project_progress == '') {
                            status++;
                            $("#errorShow").show();
                            $("#project_progress").css({
                                "border": "1px solid red"
                            });
                        }

                        if (project_progress != '0') {
                            $("#project_progress").css({
                                "border": "1px solid #d2d6de"
                            });
                        }
                    }
                    if (project_progress != '' && project_progress > 100) {
                        status++;
                        $("#errorShow").show();
                        $("#project_progress").css({
                            "border": "1px solid red"
                        });
                    }
                    if (project_status == '0') {
                        status++;
                        $("#errorShow").show();
                        $("#project_status").css({
                            "border": "1px solid red"
                        });
                    }
                    if (project_status != '0') {
                        $("#project_status").css({
                            "border": "1px solid #d2d6de"
                        });
                    }

                    if (status == 0) {
                        $("#errorShow").hide();
                        $("#productForm").submit();
                    }
                });
            });
        </script>

    </body>
</html>