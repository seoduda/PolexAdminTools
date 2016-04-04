<?php
/**
 * Created by PhpStorm.
 * User: Duda
 * Date: 19/03/2016
 * Time: 13:10
 */

class ReportChartColumm {
    public $type,$label;

    function __construct($type,$label)
    {
        $this->label = $label;
        $this->type = $type;
    }

}