/**
 * Created by Duda on 03/04/2016.
 */

function draw_Reports(){
    document.getElementById("parag2").innerHTML = "JS quem mudou.";
}

function polex_adm_draw_ReportsCharts(){
    google.load('visualization', '1', {packages:['table']});
    google.load('visualization','1', {packages: ['corechart']});
    var pluginUrl = '<?php echo plugins_url(); ?>' ;
    console.log(pluginUrl);
    google.setOnLoadCallback(getReports);
}




function getReports(){
    getReport("SQLActiveSubscriptionsFromDate","first","PieChart");
    getReport("SQLRenewedSubscriptionsFromDate","second","PieChart");
    getReport("SQLEndingNext30DaysSubscriptionsFromDate","third","PieChart");
    getReport("MonthlyReport","report_div","ColumnChart");

    drawTable("MonthlyReport");

}


function getReport(reportName,divName,chartType){
    var pluginUrl = '<?php echo plugins_url(); ?>' ;
    console.log(pluginUrl);
    var jUrl = "getReportData.php?rep_type="+reportName+"&t=5";

    console.log(jUrl);

    var json_text = $.ajax({url: jUrl , dataType:"json", async: false}).responseText;
    //console.log(json_text);
    var json = eval("(" + json_text + ")");
    //console.log(json.data);
    var col;
    var row;
    var dataT = new google.visualization.DataTable();

    var i;

    for (i = 0; i < json.data.cols.length; i++) {
        col = json.data.cols[i];
        dataT.addColumn(col.type, col.label);
    }

    for (j = 0; j < json.data.rows.length; j++) {
        row = json.data.rows[j];
        if (i==3) {
            dataT.addRow([String(row[0]), Number(row[1]), Number(row[2])]);
        }else{
            dataT.addRow([String(row[0]), Number(row[1])]);
        }
    }

    var chart_div = document.getElementById(divName);
    console.log(chart_div);
    //var chart = new google.visualization.ColumnChart(chart_div);
    if (chartType == "ColumnChart")
        var chart = new google.visualization.ColumnChart(chart_div);
    else
        var chart = new google.visualization.PieChart(chart_div);
    /*
     // Wait for the chart to finish drawing before calling the getImageURI() method.
     google.visualization.events.addListener(chart, 'ready', function () {
     chart_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
     //console.log(chart_div.innerHTML);
     });

     //console.log(dataT);
     */

    // Instantiate and draw our chart, passing in some options.
    //var chart = new google.visualization.ColumnChart());
    chart.draw(dataT, json.config);
}



function pa_todaydate(){
    var today_date= new Date();
    var myyear=today_date.getYear();
    var mymonth=today_date.getMonth()+1;
    var mytoday=today_date.getDate();
    document.write(myyear+"/"+mymonth+"/"+mytoday);
    document.write("hey a fun~ção 2");
    document.write("<BR>");
}