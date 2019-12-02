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
* {box-sizing: border-box}
body {font-family: Verdana, sans-serif; margin:0}
.mySlides {display: none}
img {vertical-align: middle;}

/* Slideshow container */
.slideshow-container {
  max-width: 350px;
  position: relative;
  margin: auto;
}

/* Next & previous buttons */
.prev, .next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -22px;
  color: white;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover, .next:hover {
  background-color: rgba(0,0,0,0.8);
}

/* Caption text */
.text {
  color: #f2f2f2;
  font-size: 15px;
  padding: 8px 12px;
  position: absolute;
  bottom: 8px;
  width: 100%;
  text-align: center;
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* The dots/bullets/indicators */
.dot {
  cursor: pointer;
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}

.active, .dot:hover {
  background-color: #717171;
}

/* Fading animation */
.fade {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 1.5s;
  animation-name: fade;
  animation-duration: 1.5s;
}

@-webkit-keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

@keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

/* On smaller screens, decrease text size */
@media only screen and (max-width: 300px) {
  .prev, .next,.text {font-size: 11px}
}
</style> 
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
		 <script language="javascript" type="text/javascript">
        function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              "<html><head><title></title></head><body>" + 
              divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
            document.body.innerHTML = oldPage;

          
        }
    </script>
<div class="slideshow-container" id="Nametsr">


<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
<a class="next" onclick="plusSlides(1)">&#10095;</a>

</div>
<br>




<script>
	

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
 // alert(slideIndex);
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
   var empids = "<?php echo $_GET["contno"]; ?>";


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
//alert("ชำระงวดที่ "+json[i].PaymentPeriodNumber);
            $calRe = Number(json[i].PaymentPeriodNumber+json[i].balance)-Number(json[i].PaymentPeriodNumber+json[i].Amount);

            $('#Nametsr').append("<div class='mySlides' style='text-align:center' id='page"+i+"'> <img src='https://www.thaijob.com/upload/employer/72Q8AePnPTgQYd6vUFF20180216160626.jpg' height='80px'><br>"+"<br><br>"+"เลขประจำตัวเสียภาษี"+" 0107556000213"+"<br>"+"โทร.	1210"+"<br>"+"ใบเสร็จรับเงิน/ใบกำกับภาษีอย่างย่อ "+"<br><br>"+"วันที่รับเงิน&nbsp;&nbsp;&nbsp; "+json[i].DatePayment.date+"<br>"+"เลขที่&nbsp;&nbsp;"+json[i].ReceiptCode+"<br>"
            +"เลขที่สัญญา&nbsp;&nbsp;&nbsp;"+json[i].CONTNO+"<br>"+"ชื่อลูกค้า "+json[i].CustomerName+"<br>"+json[i].ProductName+"<br>"
            +"ชำระงวดที่ "+json[i].PaymentPeriodNumber+"\t &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+json[i].Amount+"      บาท<br>"
            +"คงเหลือ งวดที่"+json[i].minPeriod+" ถึง "+json[i].maxPeriod+" เป็นเงิน "+json[i].balance+"      บาท<br><br>"
            +" ผู้รับเงิน&nbsp;&nbsp; A00074 นายพรชัย	สิทธิศักดิ์"+"</div>"
            );

        }
		
		var slideIndex = 1;
       currentSlide(1);

    }
});






</script>

<div style="text-align:center">
<a href='report_en.php?contno=<?php echo $_GET["contno"]; ?>'>English Version</a><br><br>
  <span class="dot" onclick="currentSlide(1)"></span> 
  <span class="dot" onclick="currentSlide(2)"></span> 
  <span class="dot" onclick="currentSlide(3)"></span> 
    <span class="dot" onclick="currentSlide(4)"></span> 
	  <span class="dot" onclick="currentSlide(5)"></span> 
</div> 

<script>



</script>
</body>
</html>
