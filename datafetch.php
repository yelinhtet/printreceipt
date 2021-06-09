<?php
require_once 'Classes/PHPExcel.php';

//we can combine this with file upload
if( !empty($_FILES) ){
	//load excel file using PHPExcel's IOFactory
	//change filename to tmp_name of uploaded file
	$excel = PHPExcel_IOFactory::load($_FILES['excel']['tmp_name']);

	//set active sheet to first sheet
	$excel->setActiveSheetIndex(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1000DC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
  	.tbl-box{
  		margin-top: 100px;
  	}
  	.head-text{
  		text-align: center;
  	}
  </style>
</head>
<body>
  <div>
  	<div class="container">
  		<div class="row">
  			<h2 class="head-text">Download Receipt</h2>
  		</div>
  		<div class="row">
  			<button class="btn btn-info read-excel" onclick="clearInputFile();" >Read Excel</button>
  		</div>
        <div class="row tbl-box">
          	<div class="col-12 col-lg-12 col-xl-12 table-responsive">
	            <table id="show_tbl" class="table table-striped table-hover dt-responsive display nowrap"  style="width:100%">
	              	<thead>
		                <tr>
		                	<th>ReceiptNo</th>
		                  	<th>Name</th>
		                  	<th>Donate Date</th>
		                  	<th>Amount</th>
		                  	<th>Actions</th>
		                </tr>
	              	</thead>
	              	<tbody>
						<?php
		              	//first row of data series
							$i = 2;
							//loop until the end of data series(cell contains empty string)
							while( 	$excel->getActiveSheet()->getCell('A'.$i)->getValue() != "" && 
									$excel->getActiveSheet()->getCell('A'.$i)->getValue() != "Total Funds" 
									){
								if($excel->getActiveSheet()->getCell('C'.$i)->getValue() != "Unknown" &&
									 $excel->getActiveSheet()->getCell('C'.$i)->getValue() != ""){
								//get cells value
						?>
						<tr>
							<td>
								<span class="receiptno<?php echo $i?>">
									<?php echo $excel->getActiveSheet()->getCell('B'.$i)->getValue(); ?>
								</span>
							</td>
							<td>
								<span class="name<?php echo $i?>">
									<?php echo $excel->getActiveSheet()->getCell('C'.$i)->getValue(); ?>
								</span>
							</td>

							<td> 
								<span class="ddate<?php echo $i?>">
									<?php echo date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($excel->getActiveSheet()->getCell('D'.$i)->getValue()));?>
								</span>
							</td>
							<td>
								<span class="amt<?php echo $i?>">
									<?php 
										if( $excel->getActiveSheet()->getCell('G'.$i)->getValue() == "-" ||
											$excel->getActiveSheet()->getCell('G'.$i)->getValue() == "")
											echo $excel->getActiveSheet()->getCell('H'.$i)->getValue().' MMK';
										else{
											echo 'JPY '.$excel->getActiveSheet()->getCell('G'.$i)->getValue();
										}
									?>
								</span>
							</td>
							<td>
								<button id="print<?php echo $i?>" class="print_btn">Download</button>
							</td>
							<?php
									}
								$i++;
							}

						?>
	              	</tbody>
	            </table>
          	</div>
        </div>
        <div class="row">
        	<button class="btn btn-success mx-auto d-block downloadall" id="<?php echo $i?>">Download All Receipts</button>
        </div>
    </div>
  	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
  	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
      	$(document).ready(function() {
       		$('#show_tbl').DataTable();
      	});
      	$('.print_btn').click(function(){  
          	var printID = $(this).attr("id");
          	var noID = printID.replace("print","");
          	//alert(noID);
          	var receiptno = $('.receiptno'+noID).text().replace(/\t/g,"");
          	var name = $('.name'+noID).text().replace(/\t/g,"");
          	var donate_date = $('.ddate'+noID).text().replace(/\t/g,"");
          	var amount = $('.amt'+noID).text().replace(/\t/g,"");
          	//alert(name);
          	//console.log(name);
          	$.ajax({  
                url:"print.php",  
                method:"post",  
                data:{	receiptno : receiptno,
                		name:name,
                		donate_date: donate_date,
                		amount: amount,
                	  	param: "printreceipt"},  
                success:function(data){
                  	console.log(data);
                  	alert("Donation Receipt Downloaded");
                }  
          });  
        });
        $('.downloadall').click(function(){  
          	var lastID = $(this).attr("id");

          	AllData = [];
          	for (var i = 0; i <=lastID-2; i++) {
          		var j=i+2;
          		var receiptno = $('.receiptno'+j).text().replace(/\t/g,"").trim();
          		var name = $('.name'+j).text().replace(/\t/g,"").trim();
          		var donate_date = $('.ddate'+j).text().replace(/\t/g,"").trim();
          		var amount = $('.amt'+j).text().replace(/\t/g,"").trim();
          		//var receiptno = $('.receiptno'+j).text().trim();
          		//var name = $('.name'+j).text().trim();
          		//var donate_date = $('.ddate'+j).text().trim();
          		//var amount = $('.amt'+j).text().trim();
          		if((name != "" && donate_date != "" && amount != "") ||
          			(name != null && donate_date != null && amount != null)){
          			AllData[i] = [receiptno,name,donate_date,amount];	
          		}          		
          	}
          	//console.log(AllData);
          	$.ajax({  
                url:"print.php",  
                method:"post",
                cache: false,  
                data:{	AllData: JSON.stringify(AllData),
                	  	param: "allprintreceipt"},  
                success:function(data){
                  	console.log(data);
                  	alert("Donation Receipt Downloaded");
                }  
          });  
        });
        function clearInputFile(){
        	window.location.href = 'index.php';
		}
    </script>
</body>
</html>
<?php } ?>
