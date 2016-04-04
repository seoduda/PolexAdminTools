<?php
/**
 * Created by PhpStorm.
 * User: Duda
 * Date: 27/03/2016
 * Time: 17:57
 */

include_once( MY_PLUGIN_PATH . 'includes/polex_admin_produtos_assinatura.php');
include_once( MY_PLUGIN_PATH . 'includes/polex_admin_produto.php');


class polex_admin_assinatura {
    public $ass_id;
    public $cli_id;
    public $ped_id;
    public $prd_id_inicio;
    public $prd_id_termino;
    private $pais_id;
    private $ass_id_renov_anterior;
    private $ass_id_renov_seguinte;
    private $ass_renovacao;
    private $ass_anos;
    private $ass_total_edicoes;
    private $ass_edicoes_enviadas;
    private $ass_valor;
    private $ass_valor_desconto;
    private $ass_valor_venda;
    public $ass_nome;
    public $ass_tipo;
    public $ass_data_inicio;
    public $ass_data_termino;
    public $ass_data_termino_assinatura;
    public $end_nome_destinatario;
    public $end_endereco;
    private $end_numero;
    private $end_complemento;
    private $end_endereco_linha2;
    private $end_bairro;
    private $end_cidade;
    private $end_estado;
    private $end_pais;
    private $end_cep;
    private $ass_renovavel;
    private $ass_status;
    private $ass_status_renovacao;
    private $ass_data_cadastro;
    private $ass_data_aprovacao;
    private $ass_data_dispon_renovacao;
    private $ass_data_encerramento;
    private $ass_aviso_renovacao;
    public $produtos_asssinaturas;
    public $periodos_asssinatura = array("Assinatura revista + site - 1 ano", "Assinatura revista + site - 2 anos",
        "Assinatura revista + site - 3 anos","Assinatura revista + site - 4 anos");
    public $numero_revistas_por_ano = 4;



    /**
     * @param $ped_id
     */
    function __construct($pedido_id)
    {
        if ($pedido_id && $pedido_id >0){
            $this->ped_id = $pedido_id;
            $this->get_wbdb_data();
        }
    }

    public function get_wbdb_data(){
        global $wpdb;
        $assinatura=$wpdb->get_row('SELECT  * from '.$wpdb->loja_assinaturas.' WHERE ped_id ='.$this->ped_id, ARRAY_A);
        $this->load_ass_data($assinatura);
        if ($this->produtos_asssinaturas){
            unset($this->produtos_asssinaturas);
        }
        $this->produtos_asssinaturas = $this->get_produtos_asssinaturas();

    }

    private function load_ass_data($assinatura){
        $this->ass_id = $assinatura['ass_id'];
        $this->cli_id = $assinatura['cli_id'];
        $this->prd_id_inicio = $assinatura['prd_id_inicio'];
        $this->prd_id_termino = $assinatura['prd_id_termino'];
        $this->pais_id = $assinatura['pais_id'];
        $this->ass_id_renov_anterior = $assinatura['ass_id_renov_anterior'];
        $this->ass_id_renov_seguinte = $assinatura['ass_id_renov_seguinte'];
        $this->ass_renovacao = $assinatura['ass_renovacao'];
        $this->ass_anos = $assinatura['ass_anos'];
        $this->ass_total_edicoes = $assinatura['ass_total_edicoes'];
        $this->ass_edicoes_enviadas = $assinatura['ass_edicoes_enviadas'];
        $this->ass_valor = $assinatura['ass_valor'];
        $this->ass_valor_desconto = $assinatura['ass_valor_desconto'];
        $this->ass_valor_venda = $assinatura['ass_valor_venda'];
        $this->ass_nome = $assinatura['ass_nome'];
        $this->ass_tipo = $assinatura['ass_tipo'];
        $this->ass_data_inicio = $assinatura['ass_data_inicio'];
        $this->ass_data_termino = $assinatura['ass_data_termino'];
        $this->ass_data_termino_assinatura = $assinatura['ass_data_termino_assinatura'];
        $this->end_nome_destinatario = $assinatura['end_nome_destinatario'];
        $this->end_endereco = $assinatura['end_endereco'];
        $this->end_numero = $assinatura['end_numero'];
        $this->end_complemento = $assinatura['end_complemento'];
        $this->end_endereco_linha2 = $assinatura['end_endereco_linha2'];
        $this->end_bairro = $assinatura['end_bairro'];
        $this->end_cidade = $assinatura['end_cidade'];
        $this->end_estado = $assinatura['end_estado'];
        $this->end_pais = $assinatura['end_pais'];
        $this->end_cep = $assinatura['end_cep'];
        $this->ass_renovavel = $assinatura['ass_renovavel'];
        $this->ass_status = $assinatura['ass_status'];
        $this->ass_status_renovacao = $assinatura['ass_status_renovacao'];
        $this->ass_data_cadastro = $assinatura['ass_data_cadastro'];
        $this->ass_data_aprovacao = $assinatura['ass_data_aprovacao'];
        $this->ass_data_dispon_renovacao = $assinatura['ass_data_dispon_renovacao'];
        $this->ass_data_encerramento = $assinatura['ass_data_encerramento'];
        $this->ass_aviso_renovacao = $assinatura['ass_aviso_renovacao'];
    }



    public function print_table_assinatura(){
        if ($this->ass_id) {
            echo '<table border="1" >';//style="width:60%"
            echo '<tr><td>ass_id:</td> <td>' . $this->ass_id . '</td></tr>';
            echo '<tr><td>cli_id:</td> <td> ' . $this->cli_id . '</td></tr>';
            echo '<tr><td>ped_id:</td> <td> ' . $this->ped_id . '</td></tr>';
            echo '<tr><td>prd_id_inicio:</td> <td> ' . $this->prd_id_inicio . '</td></tr>';
            echo '<tr><td>prd_id_termino:</td> <td> ' . $this->prd_id_termino . '</td></tr>';
            echo '<tr><td>ass_nome:</td> <td> ' . $this->ass_nome . '</td></tr>';
            echo '<tr><td>ass_anos:</td> <td> ' . $this->ass_anos . '</td></tr>';
            echo '<tr><td>ass_total_edicoes:</td> <td> ' . $this->ass_total_edicoes . '</td></tr>';
            echo '<tr><td>ass_tipo:</td> <td> ' . $this->ass_tipo . '</td></tr>';
            echo '<tr><td>ass_data_inicio:</td> <td> ' . $this->ass_data_inicio . '</td></tr>';
            echo '<tr><td>ass_data_termino:</td> <td> ' . $this->ass_data_termino . '</td></tr>';
            echo '<tr><td>ass_data_termino_assinatura:</td> <td> ' . $this->ass_data_termino_assinatura . '</td></tr>';;
            echo '<tr><td>end_nome_destinatario:</td> <td> ' . $this->end_nome_destinatario . '</td></tr>';;
            echo '<tr><td>end_endereco:</td> <td> ' . $this->end_endereco . '</td></tr>';;
            echo '</table >';
            $this->print_table_produtos_assinatura();
        }else{
            echo 'Não foi encontrada assinatura para o pedido de número: '.$this->ped_id . '<br>';
        }
    }
    public function print_table_produtos_assinatura(){
        echo '<table border="1" >';//style="width:60%"
        for ($i=0; $i< count($this->produtos_asssinaturas);$i++){
            $pa= $this->produtos_asssinaturas[$i];
            echo '<tr>';
            echo '<td>ap_id: '.$pa->data['ap_id'].'</td>';
            echo '<td>ass_id: '.$pa->data['ass_id'].'</td>';
            echo '<td>prd_id: '.$pa->data['prd_id'].'</td>';
            echo '<td>prd_volume: '.$pa->data['prd_volume'].'</td>';
            echo '<td>prd_edicao: '.$pa->data['prd_edicao'].'</td>';
            echo '<td>prd_nome: '.$pa->data['prd_nome'].'</td>';
            echo '<td>ap_ordem: '.$pa->data['ap_ordem'].'</td>';
            echo '<td>ap_data_envio: '.$pa->data['ap_data_envio'].'</td>';
            echo '<td>ap_status: '.$pa->data['ap_status'].'</td>';
            echo '</tr>';
        }
        echo '</table >';

    }

    function get_produtos_asssinaturas(){
        global $wpdb;
        $get_pas_sql= 'SELECT ap_id, ass_id, cli_id, prd_id, exp_id, pais_id, prd_volume, prd_edicao, prd_nome, end_nome_destinatario, end_endereco, end_numero, end_complemento, end_endereco_linha2, end_bairro, end_cidade, end_estado, end_pais, end_cep, ap_ordem, ap_data_envio, ap_status '.
            'FROM '.$wpdb->loja_assinaturas_produtos.' WHERE ass_id = '.$this->ass_id;

        $get_prod_assinaturas=$wpdb->get_results($get_pas_sql, ARRAY_A);
        $prod_assinaturas=array();
        if($get_prod_assinaturas){
            foreach($get_prod_assinaturas as $getPa){
                $pa = new polex_admin_produtos_assinatura();
                $pa->data = $getPa;
                $prod_assinaturas[] = $pa;
            }
        }
        return $prod_assinaturas;
    }

    function altera_vigencia($startProdId)
    {
        if (count($this->produtos_asssinaturas) < 1) {
            echo 'Esse pedido não tem confirmação de pagamento, não é possível alterar essa assinatura. <br>';
        } else {
            global $wpdb;
            $endProdId = ($startProdId + $this->ass_total_edicoes) - 1;
            $updated = $wpdb->update(
                $wpdb->loja_assinaturas,
                array(
                    'prd_id_inicio' => $startProdId,
                    'prd_id_termino' => $endProdId
                ),
                array(
                    'ass_id' => $this->ass_id
                )
            );
            for ($i = 0; $i < count($this->produtos_asssinaturas); $i++) {
                $pa = $this->produtos_asssinaturas[$i];
                $prd_id = $startProdId + $i;
                $this->altera_produto_assinatura($pa, $prd_id);
            }
        }
    }

    function altera_produto_assinatura($produto_asssinatura, $prd_id)
    {
        global $wpdb;
        $prod = new polex_admin_produto($prd_id);
        $prod->get_data();

        //$prod_data = $prod->data;
        $prd_id = $prod->data['prd_id'];
        $prd_volume = $prod->data['prd_volume'];
        $prd_edicao = $prod->data['prd_edicao'];
        $prd_nome = $prod->data['prd_nome'];
        $ap_id = $produto_asssinatura->data['ap_id'];

        $updated=$wpdb->update(
            $wpdb->loja_assinaturas_produtos,
            array(
                'prd_id'=> $prd_id,
                'prd_volume'=> $prd_volume,
                'prd_edicao'=> $prd_edicao,
                'prd_nome'=> $prd_nome
            ),
            array(
                'ap_id'=>$ap_id
            )
        );
    }

    function altera_periodo($anos_periodo)
    {
        $edições_periodo = ($anos_periodo * $this->numero_revistas_por_ano);
        $nome_assinatura_periodo = $this->periodos_asssinatura[$anos_periodo -1];
        global $wpdb;

        if (count ($this->produtos_asssinaturas)>0){
            echo 'Esse pedido já tem confirmação de pagamento, não é possível alterar período da assinatura. <br>';
            echo 'Por favor escolher outro pedido, ou criar um novo. <br>';
        }elseif ($anos_periodo >0){
            $updated=$wpdb->update(
                $wpdb->loja_assinaturas,
                array(
                    'ass_anos'=> $anos_periodo,
                    'ass_total_edicoes'=> $edições_periodo,
                    'ass_nome'=> $nome_assinatura_periodo
                ),
                array(
                    'ass_id'=>$this->ass_id
                )
            );
        }
    }




}