<?php
include './config/config.php';
$arrayEquipment = array();
$sqlEquipment = "SELECT * FROM equipment WHERE equipment_status='Active'";
$resultEquipment = mysqli_query($con, $sqlEquipment);
while ($objEquipment = mysqli_fetch_object($resultEquipment)) {
    $arrayEquipment[] = $objEquipment;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Alook Refrigeration CO. Ltd | Equipment List</title>
       <?php include basePath('header_script.php'); ?>

        <style>
            @media only screen and (max-width: 800px) {
                #no-more-tables table, 
                #no-more-tables thead, 
                #no-more-tables tbody, 
                #no-more-tables th, 
                #no-more-tables td, 
                #no-more-tables tr { 
                    display: block; 
                }
                #no-more-tables thead tr { 
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }
                #no-more-tables tr { border: 1px solid #333; }
                #no-more-tables td { 
                    border: none;
                    border-bottom: 1px solid #333; 
                    position: relative;
                    padding-left: 50%; 
                    white-space: normal;
                    text-align:left;
                }
                #no-more-tables td:before { 
                    position: absolute;
                    top: 6px;
                    left: 6px;
                    width: 45%; 
                    padding-right: 10px; 
                    white-space: nowrap;
                    text-align:left;
                    font-weight: bold;
                }
                #no-more-tables td:before { 
                    content: attr(data-title); 
                }
            }
        </style>
    </head>
    <body id="boxed">
        <div class="boxed-wrapper">
            <?php include basePath('header.php'); ?>
            <div class="breadcrumb-wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 hidden-xs">
                            <h4>EQUIPMENT LIST</h4>
                        </div>
                        <div class="col-sm-6 hidden-xs text-right">
                            <ol class="breadcrumb">
                                <li><a href="<?php echo baseUrl(); ?>index.php">HOME</a></li>
                                <li>EQUIPMENT LIST</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
<!--            <div class="container-fluid" style="padding-right: 0px; padding-left: 0px;">
                <h2 style="text-align: center;color: white;font-weight: 600;text-transform: uppercase;letter-spacing: 1px;font-size: 25px;border: 1px solid #367DAB; background-color: #367DAB;">EQUIPMENT LIST</h2>
            </div>-->
            <div class="container">
                <div class="divide40"></div>
                <div class="row">
                    <div class="col-md-12">
                        
                        <?php if (count($arrayEquipment) > 0): ?>
                            <div id="no-more-tables">
                                <table class="col-md-12 table-bordered table-striped table-condensed">
                                    <thead class="" style="background-color: #53565D;color: white">
                                        <tr>
                                            <th style="width: 10%;">SL. No</th>
                                            <th style="width: 40%;">Name of item</th>
                                            <th style="width: 10%;">Qty</th>
                                            <th style="width: 40%;">Short Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; ?>
                                        <?php foreach ($arrayEquipment AS $equipment): ?>
                                            <tr>
                                                <td data-title="SL No"><?php echo $count; ?></td>
                                                <td data-title="Name of item"><?php echo $equipment->equipment_name; ?></td>
                                                <td data-title="Qty"><?php echo $equipment->equipment_qty; ?></td>
                                                <td data-title="Description"><?php echo $equipment->equipment_details; ?></td>
                                            </tr>
                                            <?php $count++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p style="text-align: center;">No equipment found in record.</p>
                        <?php endif; ?>
                    </div>
                </div>               
            </div>        
            <div style="height: 20px;"></div>
            <?php include basePath('footer.php'); ?>
        </div>
        <?php include basePath('footer_script.php'); ?>
    </body>
</html>
