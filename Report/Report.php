<?php

include_once "SubscriptionReportData.php";
include_once "ReportChart.php";

/**
 * Created by PhpStorm.
 * User: Duda
 * Date: 01/07/2015
 * Time: 23:57
 */

define("SQLMonthlyReport","SELECT (ASS.ass_tipo), COUNT(*) AS 'Numero de assinaturas' FROM site1376498500.pex_loja_assinaturas as ASS JOIN site1376498500.pex_loja_pedidos as PED ON ASS.ped_id = PED.ped_id ".
    "WHERE PED.ped_status <> 'aguardando pagamento' AND PED.ped_status <> 'cancelado' ".
    "AND ASS.ass_data_inicio <= :data1 AND  ASS.ass_data_termino >= :data2 ".
    "GROUP BY ASS.ass_tipo;");
define("SQLActiveSubscriptionsFromDate","SELECT (ASS.ass_tipo), COUNT(*) AS 'Numero de assinaturas' ".
    "FROM site1376498500.pex_loja_assinaturas as ASS JOIN site1376498500.pex_loja_pedidos as PED ON ASS.ped_id = PED.ped_id ".
    "WHERE PED.ped_status <> 'aguardando pagamento' AND PED.ped_status <> 'cancelado' ".
    "AND ASS.ass_data_inicio <= :data1 AND  ASS.ass_data_termino >= :data2 ".
    "GROUP BY ASS.ass_tipo;");
define("SQLRenewedSubscriptionsFromDate","SELECT (ASS.ass_tipo), COUNT(*) AS 'Numero de assinaturas' ".
    "FROM site1376498500.pex_loja_assinaturas as ASS JOIN site1376498500.pex_loja_pedidos as PED ON ASS.ped_id = PED.ped_id ".
    "WHERE PED.ped_status <> 'aguardando pagamento' AND PED.ped_status <> 'cancelado' ".
    "AND ASS.ass_data_inicio <= :data1 AND  ASS.ass_data_termino >= :data2 ".
    "AND ass_id_renov_anterior > 0 ".
    "GROUP BY ASS.ass_tipo;");
define("SQLEndingNext30DaysSubscriptionsFromDate","SELECT (ASS.ass_tipo), COUNT(*) AS 'Numero de assinaturas' ".
    "FROM site1376498500.pex_loja_assinaturas as ASS JOIN site1376498500.pex_loja_pedidos as PED ON ASS.ped_id = PED.ped_id ".
    "WHERE PED.ped_status <> 'aguardando pagamento' AND PED.ped_status <> 'cancelado' ".
    "AND ASS.ass_data_termino >= :data1 AND  ASS.ass_data_termino <= :data2 ".
    "GROUP BY ASS.ass_tipo;");
define("MonthlyReportStartDate","2013-07-01");



class Report
{
    public $name, $report_type, $sql, $chartData ;

    //$CLIENTES_SQL = "SELECT * FROM site1376498500.pex_loja_clientes";
    //const ASSINATURAS_SQL ="SELECT * FROM site1376498500.pex_loja_assinaturas";

    function __construct($rep_type)
    {
        date_default_timezone_set('America/Sao_Paulo');
        //echo $rep_type;
        $this->report_type = $rep_type;

        $this->chartData = new ReportChart();
        switch ($rep_type) {
            /*
            case 'clientes':
                $this->sql = "SELECT * FROM site1376498500.pex_loja_clientes";
                $this->name = strtoupper($this->report_type) . "_" . date("Ymd");
                break;
            case "assinaturas":
                $this->sql = "SELECT * FROM site1376498500.pex_loja_assinaturas";
                $this->name = strtoupper($this->report_type) . "_" . date("Ymd");
                break;
            */
            case 'MonthlyReport':
                $this->sql = SQLMonthlyReport;
                $this->name = 'Número de assinaturas ativas por mês' . "_" . date("Ymd");
                $this->chartData->setConfig($this->name, 800, 400);
                $this->chartData->addColumm('string','Data');
                $this->chartData->addColumm('number','Revista_site');
                $this->chartData->addColumm('number','Site');

                break;
            case 'SQLActiveSubscriptionsFromDate':
                $this->sql = SQLActiveSubscriptionsFromDate;
                $this->name = "Número de assinaturas (por tipo de assinatura)" . "_" . date("Ymd");
                $this->chartData->setConfig($this->name, 350, 300);
                $this->chartData->addColumm('string','Tipo de Assinatura');
                $this->chartData->addColumm('number','Assinaturas');

                break;
            case 'SQLRenewedSubscriptionsFromDate':
                $this->sql = SQLRenewedSubscriptionsFromDate;
                $this->name = "Número de assinaturas renovadas (por tipo de assinatura)" . "_" . date("Ymd");
                $this->chartData->setConfig($this->name, 350, 300);
                $this->chartData->addColumm('string','Tipo de Assinatura');
                $this->chartData->addColumm('number','Assinaturas');

                break;
            case 'SQLEndingNext30DaysSubscriptionsFromDate':
                $this->sql = SQLEndingNext30DaysSubscriptionsFromDate;
                $this->name = "Número de assinaturas terminando nos próximos 30 dias (por tipo de assinatura)" . "_" . date("Ymd");
                $this->chartData->setConfig($this->name, 350, 300);
                $this->chartData->addColumm('string','Tipo de Assinatura');
                $this->chartData->addColumm('number','Assinaturas');

                break;
            default:
                $this->report_type = "";
                break;
        }

    }

    function getSQL()
    {
        return $this->sql;
    }

    function formatRow($row)
    {
        $s = "";
        if (!$row) {
            return $s;
        }

        $rowCount = count($row);
        for ($i = 0; $i < $rowCount; $i++) {
            $s .= $row[$i] . ";";
        }
        return $s;
    }


    function saveData()
    {
        $filename = $this->name . ".csv";
        $myfile = fopen($filename, "w") or die("Unable to open file!");

        if ($this->data) {
            foreach ($this->data as $linha) {
                fwrite($myfile, $this->formatRow($linha) . "\n");
            }
        }
        fclose($myfile);
        return $filename;
    }

    function printData()
    {

        if ($this->data) {
            foreach ($this->data as $linha) {
                echo $linha . '<BR>';

            }
        }
    }

    private function getConn(){
        /*
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "cdcol";
        */
        $servername = "mysql01.site1376498500.hospedagemdesites.ws";
        $username = "site1376498500";
        $password = "c7HmgP0LXt";
        $dbname = "site1376498500";


        $conn = new PDO('mysql:host='.$servername.';dbname='.$dbname.';charset=utf8',$username, $password);
        if(!$conn){
            die('Erro ao criar a conexão');
        }

        return $conn;
    }


    function getJsonReportData()
    {
        if (!$this->report_type) {
            return "";
        }
        if ($this->report_type == 'MonthlyReport') {
            $conn = $this->getConn();
            $this->getMonthlyReportData(MonthlyReportStartDate, $conn);
            return $this->chartData->getJsonReportChart();

        } else {
            $conn = $this->getConn();
            $today = date("Ymd");
            if ($this->report_type == 'SQLEndingNext30DaysSubscriptionsFromDate'){
                $next_month = date("Ymd", strtotime("+1 month", strtotime($today)));
                $this->getDailyReportData($today, $next_month, $conn);
            }else{
                $this->getDailyReportData($today, $today, $conn);
            }
            return $this->chartData->getJsonReportChart();
        }


    }

    function getMonthlyReportData($startDate, $conn)
    {
        $date = $startDate;
        while (strtotime($date) <= time()) {
            $msrd = $this->getMonthlySubscriptionReportData($date, $conn);
            $this->chartData->rows[] = array($msrd->date,
                (int)$msrd->revista_site_Subscriptions,
                (int)$msrd->site_Subscriptions);
            $date = date("Y-m-d", strtotime("+1 month", strtotime($date)));
        }
    }

    function getMonthlySubscriptionReportData($monthDate, $conn)
    {
        $msrd = new SubscriptionReportData($monthDate);
        $stmt = $conn->prepare($this->sql);

        $stmt->bindParam(':data1', $monthDate);
        $stmt->bindParam(':data2', $monthDate);


        /* Execute statement */
        $stmt->execute();
        /* Fetch result to array */
        $result = $stmt->fetchAll();
        /* Fetch result to array */

        foreach($result as $row){
            //print_r($row);
            if ($row['ass_tipo'] == 'revista_site') {
                $msrd->setRevistaSiteSubscriptions($row['Numero de assinaturas']);
            }
            if ($row['ass_tipo'] == 'site') {
                $msrd->setSiteSubscriptions($row['Numero de assinaturas']);
            }
        }

        return $msrd;

    }

    function getDailyReportData($start_date,$end_date, $conn)
    {

        $stmt = $conn->prepare($this->sql);


        $stmt->bindParam(':data1', $start_date);
        $stmt->bindParam(':data2', $end_date);


        /* Execute statement */
        $stmt->execute();
        /* Fetch result to array */
        $result = $stmt->fetchAll();
        /* Fetch result to array */

        foreach($result as $row){
            //print_r($row);
            if ($row['ass_tipo'] == 'revista_site') {
                $this->chartData->rows[] = array('Revista_site',$row['Numero de assinaturas']);
            }
            if ($row['ass_tipo'] == 'site') {
                $this->chartData->rows[] = array('Site',$row['Numero de assinaturas']);
            }
        }

        //echo $this->formatRow($row).'<BR>';
        //array_push($a_data, $row);


    }


    function geraGrafico($largura, $altura, $valores, $referencias, $tipo = "p3"){
        $valores = implode(',', $valores);
        $referencias = implode('|', $referencias);

        return "http://chart.apis.google.com/chart?chs=". $largura ."x". $altura . "&amp;chd=t:" . $valores . "&amp;cht=p3&amp;chl=" . $referencias;
    }

}


?>