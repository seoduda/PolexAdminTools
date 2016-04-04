<?php
/**
 * Created by PhpStorm.
 * User: Duda
 * Date: 27/03/2016
 * Time: 21:10
 */

class polex_admin_produto {
    /*`prd_id`,`term_tax_id`,`prd_nome`,`prd_ano`,`prd_volume`,`prd_edicao`,`prd_codigo`,`prd_valor`,`prd_promocao`,`prd_valor_promocional`,
  `prd_prazo_manuseio`,`prd_quantidade_tipo`,`prd_estoque`,`prd_estoque_reservado`,`prd_estoque_alerta`,`prd_peso`,`prd_altura`,`prd_largura`,
  `prd_comprimento`,`prd_status`,`prd_data_lancamento`,`prd_data_alerta`
*/
    public $prd_id;
    public $data;

    function __construct($prod_id){
        $this->prd_id = $prod_id;
    }

    function set_data($prod_data){
        $this->data = $prod_data;
    }
    function get_data(){
        global $wpdb;
        $prd=$wpdb->get_row('select * from '.$wpdb->loja_produtos.' where prd_id='.$this->prd_id, ARRAY_A);
        $this->data = $prd;
    }


} 