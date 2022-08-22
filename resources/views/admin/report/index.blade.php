@extends('adminlte::page')

@section('title', ' | ' . __('messages.Report') )

@section('content_header')
    <h1 class="m-0 text-dark">{!! __('messages.Report') !!}</h1>
@stop

@section('content')

<link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">

            <div class="panel panel-default" style="margin-bottom: 20px;background-color: #fff;border: 1px solid transparent;border-radius: 4px;-webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 5%);box-shadow: 0 1px 1px rgb(0 0 0 / 5%);">
            <div class="panel-heading" style="padding: 10px 15px;border-bottom: 1px solid transparent;border-top-left-radius: 3px;border-top-right-radius: 3px;">
              <div class="row">
                <div class="col-md-2">
                  <label>Search By Days</label>
                   <select id="filterByDate" name="filterByDate" class="form-control">
                                <option value="">Chose Option</option>
                                <option value="2">before 2 days</option>
                                <option value="5">before 5 days</option>
                                <option value="7">before 7 days</option>
                                <option value="15">before 15 days</option>
                                <option value="30">before 30 days</option>
                                <option value="60">before 60 days</option>
                                <option value="90">before 90 days</option>
                    </select>
                </div>
                <div class="col-md-2">
                <label>From</label>
                  <input type="date" class="form-control" name="from" id="from" value="<?php echo date('Y').'-01-01'; ?>" required="">
                </div>
                <div class="col-md-2">
                  <label>To</label>
                  <input type="date" class="form-control" name="to" id="to" value="<?php echo date('Y-m-d'); ?>" required="">
                </div>
                <div class="col-md-1">
                   <label>Search</label>
                   <button class="form-control" type="submit" onclick="myFunction()"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
            <hr>
            <div class="panel-body" >
             <div class="row rowCard">
               <div class="col-md-6">
                  <div class="ibox float-e-margins ">
                      <div class="ibox-content brd">
                          <div class="row">
                              <div class="col-lg-12">
                                <div class="text-center">Rental Revenue</div>  
                                <div class="text-center" id="rentalRevenue"></div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="ibox float-e-margins ">
                      <div class="ibox-content brd">
                          <div class="row">
                              <div class="col-lg-12">
                                <div class="text-center">Night Booked</div>  
                                <div class="text-center" id="nightBook"></div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
                
            </div>
            </div>
          </div>
        
</br>          
  </br>  
        
          <div class="panel panel-default" style="margin-bottom: 20px;background-color: #fff;border: 1px solid transparent;border-radius: 4px;-webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 5%);box-shadow: 0 1px 1px rgb(0 0 0 / 5%);height:400px;">
            <div class="panel-heading" style="padding: 10px 15px;border-bottom: 1px solid transparent;border-top-left-radius: 3px;border-top-right-radius: 3px;">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="m-0 text-dark">Rental Revenue</h3>
                </div>
               
               
              </div>
            </div>
            <div class="panel-body" >
              <div id="chart_div3" style="height:400px; width:100%;"></div>
            </div>
          </div>
        
</br>          
  </br>

        <div class="panel panel-default" style="margin-bottom: 20px;background-color: #fff;border: 1px solid transparent;border-radius: 4px;-webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 5%);box-shadow: 0 1px 1px rgb(0 0 0 / 5%);height:400px;">
            <div class="panel-heading" style="padding: 10px 15px;border-bottom: 1px solid transparent;border-top-left-radius: 3px;border-top-right-radius: 3px;">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="m-0 text-dark">Night Booked Per Channel</h3>
                </div>
              </div>
            </div>
            <div class="panel-body" >
              <div id="chart_div4" style="height:400px; width:100%;"></div>
            </div>
          </div>
        
           </br>          
  </br>

           <div class="panel panel-default" style="margin-bottom: 20px;background-color: #fff;border: 1px solid transparent;border-radius: 4px;-webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 5%);box-shadow: 0 1px 1px rgb(0 0 0 / 5%);height:400px;">
            <div class="panel-heading" style="padding: 10px 15px;border-bottom: 1px solid transparent;border-top-left-radius: 3px;border-top-right-radius: 3px;">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="m-0 text-dark">Reservation Per Channel</h3>
                </div>
                
              </div>
            </div>
            <div class="panel-body" >
              <div id="piechart" style="height:400px; width:100%;"></div>
            </div>
          </div>
        
             
             
  </br>          
  </br>
  <div class="panel panel-default" style="margin-bottom: 20px;background-color: #fff;border: 1px solid transparent;border-radius: 4px;-webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 5%);box-shadow: 0 1px 1px rgb(0 0 0 / 5%);">
            <div class="panel-heading" style="padding: 10px 15px;border-bottom: 1px solid transparent;border-top-left-radius: 3px;border-top-right-radius: 3px;">
              <div class="row">
                <div class="col-md-9">
                  <h3 class="m-0 text-dark">Reservations</h3>
                </div>
                
              </div>
            </div>
            <div class="panel-body" >
              <table class="table table-bordered data-table" id="datatableexam">
        <thead>
            <tr>
                <th>Name</th>
                <th>Property Name</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>First Name</th>
                <th>Service</th>
                <th>Total Price</th>
                <th>Payment Staus</th>
                <th>Created At</th>
                
            </tr>
        </thead>
    </table>
            </div>
          </div>
          
 
   </div>  </div>
 
  
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
  google.charts.load('current', {'packages': ['corechart'], 'callback': drawCharts});
  
  function drawCharts() {
     let from = $("#from").val();
     let to = $("#to").val();
     loadfirstcall(from,to);
     load_revenue_per_cha_data(from,to,'Rental Reveue Per Channel');
     load_night_booked_data(from,to,'Night Booked Per Channel');
     load_reservation_data(from,to, 'Reservation Per Channel');
  }
  google.charts.setOnLoadCallback();
</script>


 <script type="text/javascript">

function loadfirstcall(from,to)
{
    $.ajax({
        url:"https://bookingfwi.com/admin/firstcall?from="+from+"&to="+to,
        dataType:"JSON",
        success:function(data)
        {
            $('#rentalRevenue').empty();
            $('#nightBook').empty();
            $('#rentalRevenue').text(data.totalrevenue);
            $('#nightBook').text(data.nightbooked);
        }
    });
}
    
function load_revenue_per_cha_data(from,to,title)
{
    var temp_title = title;
    
    $.ajax({
        url:"https://bookingfwi.com/admin/rentalrevenue?from="+from+"&to="+to,
        dataType:"JSON",
        success:function(data)
        {
            drawRevenuePerChart(data, temp_title);
        }
    });
}

function drawRevenuePerChart(chart_data, chart_main_title)
{
    
    var jsonData = chart_data;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Name');
    data.addColumn('number', 'Revenue');
    data.addColumn({type: 'string',role: 'style'});
    $.each(jsonData, function(i, jsonData){
        var month = jsonData.service;
        var profit = parseFloat($.trim(jsonData.total));
        var colour = jsonData.colour;
        data.addRows([[month, profit,colour]]);
    });
    var paddingHeight = 20;
    var rowHeight = data.getNumberOfRows() * 15;
    var chartHeight = rowHeight + paddingHeight;
    var options = {
     legend: { position: "none" },
      hAxis: {
        title: "",
      },
      vAxis: {
        title: 'Rental Reveue'
      },
      chartArea: {
        width: '70%',
        height: '70%',
      },
      animation:{
        duration: 1000,
        easing: 'out',
        startup:true,
      },
    }
    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div3'));
    chart.draw(data, options);
}
function load_night_booked_data(from,to,title)
{
    var temp_title = title;
    $.ajax({
        url:"https://bookingfwi.com/admin/nightbooked?from="+from+"&to="+to,
        dataType:"JSON",
        success:function(data)
        {
            drawNightBookedPerChart(data, temp_title);
        }
    });
}
function drawNightBookedPerChart(chart_data, chart_main_title)
{
   
    var jsonData = chart_data;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Name');
    data.addColumn('number', 'Night');
    data.addColumn({type: 'string',role: 'style'});
    $.each(jsonData, function(i, jsonData){
        var month = jsonData.servicename;
        var profit = parseFloat($.trim(jsonData.nightbooked));
        var colour = jsonData.colourcode;
        //console.log(colour);
        data.addRows([[month, profit,colour]]);
    });
    var options = {
      legend: { position: "none" },
      hAxis: {
        title: "",
      },
      vAxis: {
        title: 'Night Booked'
      },
      chartArea: {
        width: '70%',
        height: '70%',
      },
      animation:{
        duration: 1000,
        easing: 'out',
        startup:true,
      },
    }
    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div4'));
    chart.draw(data, options);
}
//ReservationPerChannel
function load_reservation_data(from,to,title)
{
    var temp_title = title;
    $.ajax({
        url:"https://bookingfwi.com/admin/reservationperchannel?from="+from+"&to="+to,
        dataType:"JSON",
        success:function(data)
        {
            drawReservationChart(data, temp_title);
        }
    });
}
function drawReservationChart(chart_data, chart_main_title)
{
    var jsonData = chart_data;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Month');
    data.addColumn('number', 'Profit');
    
    $.each(jsonData, function(i, jsonData){
        var month = jsonData.servicename;
        var profit = parseFloat($.trim(jsonData.servicecount));
        data.addRows([[month, profit]]);
    });
    var options = {
          title: 'Reservation Per Channel',
          colors: ['#ff585d','#ff7c51','#00a2be','#f7c31e','#116db3','#464342'],
          pieHole: 0.4,
          chartArea: {width: '90%'},
          animation:{
        duration: 1000,
        easing: 'out',
        startup:true,
      },
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
}
</script>
 <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
 <script type="text/javascript">
var table = $('#datatableexam').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "https://bookingfwi.com/admin/ajaxcall",
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'reservation_check_in' },
            { data: 'reservation_check_out' },
            { data: 'contact_first_name' },
            { data: 'service' },
            { data: 'total_price' },
            { data: 'payment_status' },
            { data: 'created_at' },
         ]
    });
</script>

<script type="text/javascript">
$('#filterByDate').change(function() {
  var days = $(this).val();
  var date = new Date();
  if(days){
  //yyyy-MM-dd
    var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
    //var monthL=last.getMonth()+1;
    //var monthD=date.getMonth()+1;
    var fromDate = last.getFullYear() +'-'+ ('0' + (last.getMonth()+1)).slice(-2) + '-'+ ('0' + last.getDate()).slice(-2);
    var toDate = date.getFullYear() +'-'+ ('0' + (date.getMonth()+1)).slice(-2) + '-' +('0' + date.getDate()).slice(-2);
    console.log("fromDate===>"+fromDate);
     console.log("toDate===>"+toDate);
    $("#from").val(fromDate);
    $("#to").val(toDate);
  }else{
    var fromDate=date.getFullYear()+"-01-01";
    //var month=date.getMonth()+1;
    var toDate = date.getFullYear()+'-'+('0' + (date.getMonth()+1)).slice(-2)+'-'+date.getDate();
     console.log("fromDate===>"+fromDate);
     console.log("toDate===>"+toDate);
    $("#from").val(fromDate);
    $("#to").val(toDate);
  }
   myFunction();
})

function myFunction(){
     let from = $("#from").val();
     let to = $("#to").val();
     console.log("From===>"+from);
     console.log("to===>"+to);
     if(from){
       loadfirstcall(from,to);
       load_revenue_per_cha_data(from,to,'Rental Reveue Per Channel');
       load_night_booked_data(from,to,'Night Booked Per Channel');
       load_reservation_data(from,to, 'Reservation Per Channel'); 
     }else{
       alert("Please select from date");
     }
  }
</script>


@stop
