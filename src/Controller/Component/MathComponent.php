<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;

/**
 * Description of MathComponent
 *
 * @author josorio
 */
class MathComponent extends Component {

    public function doComplexOperation($amount1, $amount2) {
        return $amount1 + $amount2;
    }

}
