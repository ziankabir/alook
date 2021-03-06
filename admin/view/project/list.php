<?php
include '../../../config/config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Alook Refrigeration CO. Ltd | Project List</title>
        <?php include basePath('admin/header_script.php'); ?>
    </head>
    <body class="skin-blue">
        <div class="wrapper">
            <?php include basePath('admin/header.php'); ?>
            <aside class="main-sidebar">
                <?php include basePath('admin/site_menu.php'); ?>
            </aside>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>Project List</h1>
                    <ol class="breadcrumb">
                        <li><i class="fa fa-newspaper-o"></i>&nbsp;Project Settings</li>
                        <li class="active">Project List</li>
                    </ol>
                </section>
                <section class="content">
                    <?php include basePath('admin/message.php'); ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-primary">
                                <div class="k-grid  k-secondary" data-role="grid">
                                    <div class="k-toolbar k-grid-toolbar">
                                        <a class="k-button k-button-icontext k-grid-add" href="<?php echo baseUrl('admin/view/project/add.php'); ?>">
                                            <span class="k-icon k-add"></span>
                                            Add Project
                                        </a>
                                    </div>
                                </div>
                                <div id="grid"></div>
                                <script id="customtemplate" type="text/x-kendo-template">
                                    <a class="k-button k-button-icontext k-grid-edit" href="<?php echo baseUrl('admin/view/project/edit.php'); ?>?id=#= project_id#"><span class="k-icon k-edit"></span>Edit</a>
                                    <a class="k-button k-button-icontext k-grid-delete" onclick="javascript:deleteFunction(#= project_id #);" ><span class="k-icon k-delete"></span>Delete</a>
                                </script>
                                <script type="text/javascript">
                                    function deleteFunction(project_id) {
                                        var c = confirm("Are you sure you want to delete this record?");
                                        if (c === true) {
                                            $.ajax({
                                                type: "POST",
                                                dataType: "json",
                                                url: baseUrl + "admin/controller/project/list.php",
                                                data: {project_id: project_id},
                                                success: function (result) {
                                                    if (result === true) {
                                                        $(".k-i-refresh").click();
                                                    }
                                                }
                                            });
                                        }
                                    }
                                </script>
                                <script type="text/x-kendo-template" id="template">
                                    <div class="tabstrip">
                                    <ul>
                                    <li class="k-state-active">
                                    Project Image
                                    </li>
                                    <li>
                                    Project Details
                                    </li>

                                    </ul>
                                    <div>
                                    <div class='general-info'>
                                    # if(project_image !=""){#
                                    <div style="margin-left:10px;"><img src="<?php echo baseUrl("upload/project_image/") ?>#=project_image#" height='100' width='100'</div>
                                    </div>
                                    # } else { #
                                    <div><img src='<?php echo baseUrl("upload/project_image/no_image.jpg") ?>' height='100' width='100'</div>
                                    </div>
                                    # } #
                                    </div>
                                    </div>
                                    <div>
                                    # if(project_details != ""){ #
                                    <h5 style="margin-left:10px;">#=project_details #</h5>
                                    # }else{#
                                    <h5 style="margin-left:10px;"> No project short details available now !!!</h5>
                                    # } #
                                    </div>

                                </script>
                                
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        var dataSource = new kendo.data.DataSource({
                                            pageSize: 10,
                                            transport: {
                                                read: {
                                                    url: baseUrl + "admin/controller/project/list.php",
                                                    type: "GET"
                                                },
                                                destroy: {
                                                    url: baseUrl + "admin/controller/project/list.php",
                                                    type: "POST"
                                                }
                                            },
                                            autoSync: false,
                                            schema: {
                                                data: "data",
                                                total: "data.length",
                                                model: {
                                                    id: "project_id",
                                                    fields: {
                                                        project_id: {editable: false, nullable: true},
                                                        project_name: {type: "string"},
                                                        project_image: {type: "string"},
                                                        project_type: {type: "string"},
                                                        project_date: {type: "string"},
                                                        project_progress: {type: "string"},
                                                        project_details: {type: "string"},
                                                        project_status: {type: "string"},
                                                        project_created_on: {type: "string"}
                                                    }
                                                }
                                            }
                                        });
                                        $("#grid").kendoGrid({
                                            dataSource: dataSource,
                                            filterable: true,
                                            pageable: {
                                                refresh: true,
                                                input: true,
                                                numeric: false,
                                                pageSizes: true,
                                                pageSizes: [10, 20, 50, 100, 200],
                                            },
                                            sortable: true,
                                            groupable: true,
                                            columns: [
                                                {field: "project_name", title: "Name Of The Job", width: "150px"},
                                                {field: "project_type", title: "Project Type", width: "120px"},
                                                {field: "project_date", title: "Commencement Date", width: "150px"},
                                                {field: "project_progress", title: "Progress (%)", width: "120px"},
                                                {field: "project_status", title: "Status", width: "120px"},
                                                {field: "project_created_on", title: "Created On", width: "130px"},
                                                {
                                                    title: "Action", width: "180px",
                                                    template: kendo.template($("#customtemplate").html())
                                                }
                                            ],
                                            detailTemplate: kendo.template(jQuery("#template").html()),
                                            detailInit: detailInit,
                                            dataBound: function () {
                                                this.expandRow(this.tbody.find("tr.k-master-row").first());
                                            }
                                        });
                                        function detailInit(e) {
                                            var detailRow = e.detailRow;
                                            detailRow.find(".tabstrip").kendoTabStrip({
                                                animation: {
                                                    open: {effects: "fadeIn"}
                                                }
                                            });
                                        }
                                    });

                                </script>
                                <style scoped>
                                    .k-detail-cell .k-tabstrip .k-content {
                                        padding: 0.2em;
                                    }
                                    .general-info ul
                                    {
                                        list-style:none;
                                        font-style:italic;
                                        margin: 5px;
                                        padding: 0;
                                    }
                                    .general-info ul li
                                    {
                                        margin: 0;
                                        line-height: 1.7em;
                                    }
                                    .general-info label
                                    {
                                        display:inline-block;
                                        width:150px;
                                        padding-right: 10px;
                                        text-align: right;
                                        font-style:normal;
                                        font-weight:bold;
                                    }
                                </style>
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
        <?php include basePath('admin/footer_script.php'); ?>
    </body>
</html>