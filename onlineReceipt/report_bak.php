<!DOCTYPE html>
<html lang="en">
<head>
  <title>customerreceipt</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }
    
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 450px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;} 
    }
  </style>
</head>
<body>
 
<div class="container-fluid text-center">    
  <div class="row content">
    <div class="col-sm-2 ">

    </div>
    <div class="col-sm-8 text-left"> 
      <!-- <h1 class="text-center" id="Nametsr"></h1> -->
      <p class="text-center" id="Nametsr"> </p>
	  <!-- <p class="text-center" id="taxnumber"> </p>
	  <p class="text-center" id="Tel"></p>
	  <p class="text-center" id="receptext">ใบเสร็จรับเงิน/ใบกำกับภาษีอย่างย่อ</p>
	  <p class="text-center" id="datemoney"></p>
	  <p class="text-center"  id="number">เลขที่ </p>
	  <p class="text-center" id="promistnumber">เลขที่สัญญา</p>
	  <p class="text-center" id="customer">ชื่อลูกค้า </p>
	  <p class="text-center" id="water_filter">เครื่องกรองน้ำ</p> -->
  
	</div>
	<!-- <div class="row">
  <div class="col-sm-3"></div>

  <div class="col-sm-3" id="Period">งวด</div><div class="col-sm-3" id="PeriodMoney">ราคา  </div>

  <div class="col-sm-3"></div>
</div>
<div class="row">
  <div class="col-sm-3"></div>

  <div class="col-sm-3"></div><div class="col-sm-3" id="result">ราคา  </div>

  <div class="col-sm-3"></div>
</div>
<div class="row">
  <div class="col-sm-3"></div>

  <div class="col-sm-6" id="receptM"> ผู้รับเงิน</div>

  <div class="col-sm-3"></div>
</div> -->
    <!-- <div class="col-sm-2 sidenav">
   
    </div> -->
  </div>
</div>
<script>
	$(document).ready(function () {

   var empids = "<?php echo $_GET["CONTNO"]; ?>";

   if(empids==" "){
    var empids = "F0008302";
   }
	 //$('#taxnumber').append(empids);
	//  $('#number').append(empids);
	//  $('#promistnumber').append(empids);
	//  $('#customer').append(empids);
	//  $('#datemoney').append("วันที่รับเงิน 26/04/2562 เวลา 11.50 น.");
	//  $('#water_filter').append(" Safe รุ่น UV");
	//  $('#Period').append(" 1 (ชำระครบ)");
	//  $('#PeriodMoney').append(" 1 บาท");
	//  $('#result').append(" 1 บาท");
	//  $('#receptM').append(" _____ทรงผล คนบ้านไห____ ");

	
	// var obj = JSON.parse('{ "name":"John", "age":30, "city":"New York"}');
	// alert(obj.name);
  $.ajax({
    url: "getjson.php",  
    ContentType: 'application/json', 
    dataType: "json",
    type: 'post',
    data: {
      id: empids
                },
    success: function(json){
        //here inside json variable you've the json returned by your PHP
        for(var i=0;i<json.length;i++){
            //$('#items_container').append(json[i].item_id)
            
            $calRe = Number(json[i].PaymentPeriodNumber+json[i].balance)-Number(json[i].PaymentPeriodNumber+json[i].Amount);
            
            $('#Nametsr').append(" <img src='https://www.thaijob.com/upload/employer/72Q8AePnPTgQYd6vUFF20180216160626.jpg' height='100px'><br>"+"<br><br>"+"เลขประจำตัวเสียภาษี"+" 0107556000213"+"<br>"+"โทร.	1210"+"<br>"+"ใบเสร็จรับเงิน/ใบกำกับภาษีอย่างย่อ "+"<br><br>"+"วันที่รับเงิน&nbsp;&nbsp;&nbsp; "+json[i].DatePayment.date+"<br>"+"เลขที่&nbsp;&nbsp;"+json[i].ReceiptCode+"<br>"
            +"เลขที่สัญญา&nbsp;&nbsp;&nbsp;"+json[i].CONTNO+"<br>"+"ชื่อลูกค้า "+json[i].CustomerName+"<br>"+json[i].ProductName+"<br>"
            +"ชำระงวดที่ "+json[i].PaymentPeriodNumber+"\t &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+json[i].Amount+"      บาท<br>"
            +"คงเหลือ งวดที่"+json[i].minPeriod+" ถึง "+json[i].maxPeriod+" เป็นเงิน "+json[i].balance+"      บาท<br>"
            +"________________________________"+"ผู้รับเงิน<br><br><br><br><br>"
            );
      
        }
        
    }
});


	});





</script>

</body>
</html>
