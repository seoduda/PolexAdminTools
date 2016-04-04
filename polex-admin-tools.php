<?php

/*
Plugin Name: Polex Admin Tools
Plugin URI: https://github.com/seoduda/PolexAdminTools
Description: Ferramentas de gestão de assinaturas e geração de relatórios
Version: 1.0.5
Author: Duda
Author URI: https://github.com/seoduda
License:GPL2
*/


define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
include_once( MY_PLUGIN_PATH . 'includes/polex_admin_assinatura.php');
include_once( MY_PLUGIN_PATH . 'includes/polex_admin_produtos.php');
//include( MY_PLUGIN_PATH . 'includes/classes.php');


$polex_adm_vars = array();

add_action('admin_menu', 'polex_admin_setup_menu');


function polex_admin_setup_menu(){
    add_menu_page( 'Polex Admin Plugin Page', 'Polex Admin', 'manage_options', 'polex_adm-plugin', 'polex_admin_init' );
}


function wptuts_scripts_basic()
{
    // Register the script like this for a plugin:
    wp_register_script( 'custom-script', plugins_url( '/js/polex_adm_draw_charts.js', __FILE__ ) );
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'custom-script' );
    wp_register_script( 'custom-script2', plugins_url( '/js/jsapi.js', __FILE__ ) );
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'custom-script2' );

}
add_action( 'wp_enqueue_scripts', 'wptuts_scripts_basic' );






function polex_admin_init()
{
    $polex_adm_plugin_dir = plugins_url().'/PolexAdminTools';
    wp_enqueue_script('polex_adm_draw_charts',$polex_adm_plugin_dir.'/js/polex_adm_draw_charts.js', array('jquery'));
    wp_enqueue_script('jsapi','https://www.google.com/jsapi', array('jquery'));

    echo "<h1>Ferramentas de gestão de assinaturas</h1>";
    if (isset($_POST['polex_adm_id_pedido'])) $polex_adm_id_pedido = $_POST['polex_adm_id_pedido'];
    else $polex_adm_id_pedido = "";
    if (isset($_POST['polex_adm_start_prd_id'])) $polex_adm_start_prd_id = $_POST['polex_adm_start_prd_id'];
    else $polex_adm_start_prd_id = 0;
    if (isset($_POST['polex_adm_ass_periodo_anos'])) $polex_adm_ass_periodo_anos = $_POST['polex_adm_ass_periodo_anos'];
    else $polex_adm_ass_periodo_anos = 0;


    $polex_adm_vars['polex_adm_id_pedido'] = $polex_adm_id_pedido;
    $polex_adm_vars['polex_adm_start_prd_id'] = $polex_adm_start_prd_id;
    $polex_adm_vars['polex_adm_ass_periodo_anos'] = $polex_adm_ass_periodo_anos;


    // Tabs
    $tab = (!empty($_GET['tab'])) ? esc_attr($_GET['tab']) : 'first';
    polex_admin_page_tabs($tab);
    $polex_adm_vars['tab'] = $tab;

    switch ($polex_adm_vars['tab']) {
        case "second":
            polex_admin_print_tab2_content($polex_adm_vars);
            break;
        case "third":
            polex_admin_print_tab3_content();
            break;
        default:
            polex_admin_print_tab1_content($polex_adm_vars);

    }
}

function polex_admin_page_tabs($current = 'first') {
    $tabs = array(
        'first'   => __("Alterar Revistas", 'plugin-textdomain'),
        'second'  => __("Alterar Período", 'plugin-textdomain'),
        'third'  => __("Relatório", 'plugin-textdomain')
    );
    $html =  '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ($tab == $current) ? 'nav-tab-active' : '';
        $html .=  '<a class="nav-tab ' . $class . '" href="?page=polex_adm-plugin&tab=' . $tab . '">' . $name . '</a>';
    }
    $html .= '</h2>';
    echo $html;
}

function polex_admin_print_tab1_content($polex_adm_vars){
    echo "<h3>Alterar Revistas da Assinatura</h3>";
    echo "Entre o número do pedido:";
    $polex_adm_input1_value = ($polex_adm_vars['polex_adm_id_pedido']>0)?$polex_adm_vars['polex_adm_id_pedido']:'';
    echo "<form method='post' action='".admin_url( '?page=polex_adm-plugin' )."'>";
    echo'<input type="number" name ="polex_adm_id_pedido" value="'.$polex_adm_input1_value.'" >';
    echo'<input type="submit">';
    echo'</form>';
    if ($polex_adm_vars['polex_adm_id_pedido']){
        $pa_assinatura = new polex_admin_assinatura($polex_adm_vars['polex_adm_id_pedido']);
        if (count($pa_assinatura->produtos_asssinaturas) < 1) {
            echo 'Esse pedido não tem confirmação de pagamento, não é possível alterar essa assinatura. <br>';
        } else {

            if ($polex_adm_vars['polex_adm_start_prd_id'] != 0) {
                $pa_assinatura->altera_vigencia($polex_adm_vars['polex_adm_start_prd_id']);
                $pa_assinatura->get_wbdb_data();
            }
            echo '<hr>';
            $pa_assinatura->print_table_assinatura();
            echo '<hr>';
            $prods = new polex_admin_produtos();
            echo "<form method='post' action='" . admin_url('?page=polex_adm-plugin') . "'>";
            echo 'Escolha o volume inicial da assinatura:<br>';
            echo '<select name="polex_adm_start_prd_id">';
            for ($i = 0; $i < count($prods->listaprodutos); $i++) {
                $prod = $prods->listaprodutos[$i];
                echo '<option value="' . $prod->prd_id . '">' . $prod->prd_id . '-' . $prod->data['prd_nome'] . '</option>';
            }
            echo '</select>';
            // echo '<br>*Somente assinaturas de 4 volumes podem ser atualizadas por enquanto.<br>';

            echo '<input type="hidden" name ="polex_adm_id_pedido" value="' . $polex_adm_vars['polex_adm_id_pedido'] . '">';
            //<input type="hidden" name="Language" value="English">
            echo '  <input type="submit" value="Atualizar Assinatura">';
            echo '</form>';
        }

    }
}

function polex_admin_print_tab2_content($polex_adm_vars)
{
    echo "<h3>Altera Período da Assinatura</h3>";
    echo "Entre o número do pedido:";
    $polex_adm_input1_value = ($polex_adm_vars['polex_adm_id_pedido'] > 0) ? $polex_adm_vars['polex_adm_id_pedido'] : '';
    echo "<form method='post' action='" . admin_url('?page=polex_adm-plugin') ."&tab=second'>";
    echo '<input type="number" name ="polex_adm_id_pedido" value="' . $polex_adm_input1_value . '" >';
    echo '<input type="submit">';
    echo '</form>';



    if ($polex_adm_vars['polex_adm_id_pedido']) {
        $pa_assinatura = new polex_admin_assinatura($polex_adm_vars['polex_adm_id_pedido']);
        if ($polex_adm_vars['polex_adm_ass_periodo_anos'] != 0) {
            $pa_assinatura->altera_periodo($polex_adm_vars['polex_adm_ass_periodo_anos']);
            $pa_assinatura->get_wbdb_data();
        }

        if (count($pa_assinatura->produtos_asssinaturas) > 0) {
            echo 'Esse pedido já tem confirmação de pagamento, não é possível alterar período da assinatura. <br>';
            echo 'Por favor escolher outro pedido, ou criar um novo. <br>';
        }else {

            if ($pa_assinatura->ass_id) {
                echo '<hr>';
                $pa_assinatura->print_table_assinatura();
                echo '<hr>';
                polex_admin_exibeFormPeriodoAssinatura($pa_assinatura);
            } else {
                echo 'Não foi encontrada assinatura para o pedido de número: ' . $polex_adm_vars['polex_adm_id_pedido'] . '<br>';
            }
        }
    }
}
function polex_admin_exibeFormPeriodoAssinatura($assinatura){
    $i = 1;

    echo "<form method='post' action='" . admin_url('?page=polex_adm-plugin') ."&tab=second'>";
    echo 'Escolha o período da assinatura:<br>';
    echo '<select name="polex_adm_ass_periodo_anos">';
    foreach ($assinatura->periodos_asssinatura as $periodo) {
        echo '<option value="' . $i . '">' . $periodo . '</option>';
        $i++;
    }
    echo '</select>';

    echo '<input type="hidden" name ="polex_adm_id_pedido" value="'.$assinatura->ped_id.'">';
    echo '  <input type="submit" value="Atualizar Assinatura">';
    echo '</form>';
}

function polex_admin_print_tab3_content()
{
    echo '<h3>Relatórios da Assinaturas</h3>';
    echo '<p>Acesse '.plugins_url( 'Report/PolexAssinaturasDashboard.html', __FILE__ ).'</p>';

}
/*

function fix_path($file_path){
if (DIRECTORY_SEPARATOR == '/'){
    $s   = str_replace('\\', '/', $file_path);
}else{
    $s   = str_replace('/','\\', $file_path);
}
return $s;
}

*/