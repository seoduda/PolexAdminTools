<?php
/**
 * Created by PhpStorm.
 * User: Duda
 * Date: 19/03/2016
 * Time: 12:51
 */

class ReportChart {

    public $data = array();
    public $cols = array();
    public $rows = array();
    public $config;


    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->config = new ReportChartConfig();
    }

    private function define_constants() {
//        $upload_dir = wp_upload_dir();
//        $this->define( 'WC_PLUGIN_FILE', __FILE__ );
//        $this->define( 'WC_ROUNDING_PRECISION', 4 );
    }
    /**
     * Define constant if not already set
     * @param  string $name
     * @param  string|bool $value
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }
    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        include_once( 'ReportChartConfig.php' );
        include_once( 'ReportChartColumm.php' );
//        $this->query = include( 'includes/class-wc-query.php' );                // The main query class
   }

    public function getJsonReportChart(){

        $this->data = array('cols' => $this->cols,'rows' => $this->rows);
        $reportChart = array('data' => $this->data,'config' => $this->config);
        return json_encode($reportChart);
    }

    public function setConfig($title,$width,$height){
        $this->config->title = $title;
        $this->config->width = $width;
        $this->config->height = $height;
    }
    public function addColumm($label, $type){
        $this->cols[] = new ReportChartColumm($label, $type);

    }


    /*
        // Estrutura basica do grafico
    $grafico = array(
    'data' => array(
    'cols' => array(
    array('type' => 'string', 'label' => 'Data'),
    array('type' => 'number', 'label' => 'Revista_site'),
    array('type' => 'number', 'label' => 'Site')
    ),
    'rows' => array()
    ),
    'config' => array(
    'title' => 'Número de assinaturas ativas por mês',
    'width' => 800,
    'height' => 500
    )

    */

} 