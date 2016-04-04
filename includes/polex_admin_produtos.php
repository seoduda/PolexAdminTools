<?php
/**
 * Created by PhpStorm.
 * User: Duda
 * Date: 27/03/2016
 * Time: 21:09
 */

include_once( MY_PLUGIN_PATH . 'includes/polex_admin_produto.php');

class polex_admin_produtos {
    public $listaprodutos = array();

    function __construct()
    {
        $this->get_produtos();
    }

    function get_produtos(){
        global $wpdb;
        $get_prods_sql= 'select * from '.$wpdb->loja_produtos;
        $get_prods=$wpdb->get_results($get_prods_sql, ARRAY_A);

        if($get_prods){
            foreach($get_prods as $get_prod){
                $prod = new polex_admin_produto($get_prod['prd_id']);
                $prod->set_data($get_prod);
                $this->listaprodutos[] = $prod;
            }
        }
    }
}