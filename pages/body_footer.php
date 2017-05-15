<script type="text/javascript" src="plugins/datepicker/locales/bootstrap-datepicker.th.js" charset="UTF-8"></script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
    $(".select2 span").addClass('needsclick');

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    //$('#reservation').daterangepicker();  //backup
    $('#reservation').daterangepicker({format: 'DD-MM-YYYY'});
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button

    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );


    //Date picker
    $('#datepicker1').datepicker({
      autoclose: true , language: 'th',format: 'dd-mm-yyyy'
    });
    $('#datepicker2').datepicker({
      autoclose: true , language: 'th-th',format: 'dd-mm-yyyy',setDate: new Date(),isBuddhist: true,
        todayHighlight: true
    });
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });

    $('.timepicker1').timepicker({
      showInputs: false,
      showMeridian: false,
      minuteStep: 30
    });
    $('.timepicker2').timepicker({
      showInputs: false,
      showMeridian: false,
      minuteStep: 30
    });
  });

  $('#leave_type').change(function() {

    var val = $("#leave_type option:selected").text();
    //var SumDay = (daydiff(parseDate($('#startDate1').val()), parseDate($('#endDate1').val()))+$("#useDay").val());
    if (val == "ลาพักร้อน") {
      //alert(val);
      //document.getElementById("option_leave1").style.display = 'none';
      //document.getElementById("option_leave2").style.display = '';
      document.getElementById("div1").style.display = '';
      document.getElementById("div2").style.display = 'none';
    }else {
      //document.getElementById("option_leave1").style.display = '';
      //document.getElementById("option_leave2").style.display = 'none';
      var val1 = $("#leave_date_type option:selected").text();
      //alert(val);
      if (val1 == "ลาทั้งวัน") {
        document.getElementById("div1").style.display = '';
        document.getElementById("div2").style.display = 'none';
      }else {
        document.getElementById("div1").style.display = 'none';
        document.getElementById("div2").style.display = '';
      }
    }
    sumdate();
  });

  $('#leave_date_type').change(function() {
    var val = $("#leave_date_type option:selected").text();
    //alert(val);
    if (val == "ลาทั้งวัน") {
      document.getElementById("div1").style.display = '';
      document.getElementById("div2").style.display = 'none';
    }else {
      document.getElementById("div1").style.display = 'none';
      document.getElementById("div2").style.display = '';
    }
  });

  $('#endDate1').change(function() {
    sumdate();
  });


  $('#endTime').change(function() {
    sumdate();
  });

  function sumdate(){
    //วันลา
    var startDate1 = $("#startDate1").val();
    var endDate1 = $("#endDate1").val();
    var timeDate = $("#datepickerStart").val();
    var SumDay,sumDaytotal,sumHour,sumHourTotal,datediff,timeDiffMin;
    //ประเภทลา
    var leave_type = $("#leave_type option:selected").text();
    //รูปแบบการลา
    var leave_date_type = $("#leave_date_type option:selected").text();
    if (leave_date_type == "ลาทั้งวัน") {
      datediff = daydiff(parseDate($('#startDate1').val()), parseDate($('#endDate1').val()))+1;
    }else {
      var timeDiff,timeDiff1,timeDiff2;
      var startTime = parseTime(timeDate,$("#startTime").val());
      var endTime = parseTime(timeDate,$("#endTime").val());
      //เช็คเวลาพัก
      //alert($("#startTime").val()+" == "+ $("#timeBreakEnd").val());
      if (($("#startTime").val() < $("#timeBreakEnd").val()) && ($("#endTime").val() > $("#timeBreakStart").val())) {
        if ($("#startTime").val() < $("#timeBreakStart").val()) {
          timeDiff1 = Math.abs(parseTime(timeDate,$("#startTime").val()) - parseTime(timeDate,$("#timeBreakStart").val()));
        }else {
          timeDiff1 = 0;
        }
        if ($("#endTime").val() > $("#timeBreakEnd").val()) {
          timeDiff2 = Math.abs(parseTime(timeDate,$("#timeBreakEnd").val()) - parseTime(timeDate,$("#endTime").val()));
        }else {
          timeDiff2 = 0;
        }
        timeDiff = timeDiff1+timeDiff2;
      }else {
        timeDiff = Math.abs(startTime - endTime);
      }

      //timeDiff = Math.abs(startTime - endTime);

      //var timeDiff = Math.abs(startTime - endTime);

      //alert("Time Diff- " + startTime);
      var hh = Math.floor(timeDiff / 1000 / 60 / 60);
      /*
      if(hh < 10) {
          hh = '0' + hh;
      }
      */
      timeDiff -= hh * 1000 * 60 * 60;
      var mm = Math.floor(timeDiff / 1000 / 60);
      /*
      if(mm < 10) {
          mm = '0' + mm;
      }
      */
      timeDiff -= mm * 1000 * 60;
      var ss = Math.floor(timeDiff / 1000);
      /*
      if(ss < 10) {
          ss = '0' + ss;
      }
      */
      if (mm > 0) {
        timeDiffMin = hh+.5;
      }else {
        timeDiffMin = hh;
      }

      //alert("Time Diff- " + hh + ":" + mm + ":" + $("#timeWork").val());
    }

    if (isNaN(datediff)) {
      datediff = 0;
    }
    if (isNaN(timeDiffMin)) {
      timeDiffMin = 0;
    }

    // คำนวนวันลาคงเหลือ
    if (leave_type == "ลากิจ") {
      SumDay = parseInt(datediff)+parseInt(useDay[1]);
      sumDaytotal = parseInt(totalDay[1])-parseInt(SumDay);
      sumHour = useHour[1];
      sumHourTotal = totalHour[1];
    }else if (leave_type == "ลาอุปสมบท") {
      SumDay = parseInt(datediff)+parseInt(useDay[2]);
      sumDaytotal = parseInt(totalDay[2])-parseInt(SumDay);
      sumHour = useHour[2];
      sumHourTotal = totalHour[2];
    }else if (leave_type == "ลาคลอด") {
      SumDay = parseInt(datediff)+parseInt(useDay[3]);
      sumDaytotal = parseInt(totalDay[3])-parseInt(SumDay);
      sumHour = useHour[3];
      sumHourTotal = totalHour[3];
    }else if (leave_type == "ลาไปรับราชการทหาร") {
      SumDay = parseInt(datediff)+parseInt(useDay[4]);
      sumDaytotal = parseInt(totalDay[4])-parseInt(SumDay);
      sumHour = useHour[4];
      sumHourTotal = totalHour[4];
    }else if (leave_type == "ลาฝึกอบรม") {
      SumDay = parseInt(datediff)+parseInt(useDay[5]);
      sumDaytotal = parseInt(totalDay[5])-parseInt(SumDay);
      sumHour = useHour[5];
      sumHourTotal = totalHour[5];
    }else if (leave_type == "ลาพักร้อน") {
      SumDay = parseInt(datediff)+parseInt(useDay[6]);
      sumDaytotal = parseInt(totalDay[6])-parseInt(SumDay);
      sumHour = useHour[6];
      sumHourTotal = totalHour[6];
    }else if (leave_type == "ลาทำหมัน") {
      SumDay = parseInt(datediff)+parseInt(useDay[7]);
      sumDaytotal = parseInt(totalDay[7])-parseInt(SumDay);
      sumHour = useHour[7];
      sumHourTotal = totalHour[7];
    }else{
      SumDay = parseInt(datediff)+parseInt(useDay[0]);
      sumHour = parseFloat(useHour[0])+timeDiffMin;
      sumDaytotal = (parseInt(totalDay[0]) + parseInt(useDay[0]))-parseInt(SumDay);

      if (sumHour  >=  parseFloat($("#timeWork").val())) {
        if (sumHour  >=  parseFloat($("#timeWork").val())) {
          if (sumHour  ==  parseFloat($("#timeWork").val())) {
            sumHour -= parseFloat($("#timeWork").val());
            SumDay +=1;
            sumHourTotal -= parseFloat($("#timeWork").val());
          }else {
            timeDiffMin -= $("#timeWork").val();
            sumDaytotal -= 1;
            sumHour -= parseFloat($("#timeWork").val());
            SumDay +=1;
            sumHourTotal -= parseFloat($("#timeWork").val());
          }
        }
      }

      if (totalHour[0] > sumHour) {
        sumHourTotal = totalHour[0] - timeDiffMin;
      }else {
        if (totalHour[0] < sumHour) {
          if (totalHour[0] < timeDiffMin) {
            sumDaytotal -= 1;
          }
          sumHourTotal = parseFloat($("#timeWork").val() - sumHour);
        }else {
          if (totalHour[0] == sumHour) {
            //sumHourTotal = sumHour;
            sumHourTotal = parseFloat($("#timeWork").val() - sumHour);
          }else {
            sumHourTotal = parseFloat($("#timeWork").val() - sumHour);
          }
        }
      }
    }


    showAlert(SumDay,sumDaytotal,leave_type,sumHour,sumHourTotal);
  }

  function parseDate(str) {
      var mdy = str.split('-');
      //return new Date(mdy[2], mdy[0]-1, mdy[1]);
      return new Date(mdy[2], mdy[1], mdy[0]);
  }

  function parseTime(str,time) {
      var mdy = str.split('-');
      //return new Date(mdy[2], mdy[0]-1, mdy[1]);
      //alert(mdy[1]+" "+ mdy[0] +" "+ mdy[2]+" "+ time);
      return new Date(mdy[1]+" "+ mdy[0] +" "+ mdy[2]+" "+ time);
  }

  function daydiff(first, second) {
      return Math.round((second-first)/(1000*60*60*24));
  }

</script>
