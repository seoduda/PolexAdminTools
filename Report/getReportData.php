<?php
/**
 * Created by PhpStorm.
 * User: Duda
 * Date: 17/03/2016
 * Time: 17:18
 */

include_once "Report.php";
//print_r($_POST);

if (isset($_GET['rep_type'])){
    $rep_type = $_GET['rep_type'];
}else $rep_type = "";
if (!$rep_type){
    echo "<br>";
    echo "<N>";
    echo "Erro! Tipo de Relatório inválido.";
    echo "</N>";
    echo "<br>";
}else {

    $polexReport = new Report($rep_type);
    $grafico = $polexReport->getJsonReportData();

// Enviar dados na forma de JSON
    header('Content-Type: application/json; charset=UTF-8');
    echo $grafico;
    exit(0);
}

/*
function printTableMonthlyReportData($monthlyReportData){

    echo '<table border="1" >';//style="width:60%"
    echo '<tr><td>Data</td><td>Assinaturas Revista_site</td><td>Assinaturas Site</td></tr>';
    foreach ($monthlyReportData as $item){
        echo '<tr>';
        echo'<td>'.$item->date.'</td>';
        echo'<td>'.$item->revista_site_Subscriptions.'</td>';
        echo'<td>'.$item->site_Subscriptions.'</td>';
        echo '</tr>';
        echo '<BR>';
    }

}
*/