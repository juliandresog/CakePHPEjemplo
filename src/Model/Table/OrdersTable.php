<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Table;

/**
 * Description of OrdersTable
 * Ejemplo de lanzamiento de evento que se podria usar para que otras partes del proyecto envie correo o notificaciones
 * despues de guardar una orden.
 * @author josorio
 */
class OrdersTable extends Table {

    public function place($order) {
        if ($this->save($order)) {
            $this->Cart->remove($order);
            $event = new Event('Model.Order.afterPlace', $this, [
                'order' => $order
            ]);
            $this->eventManager()->dispatch($event);
            return true;
        }
        return false;
    }

}
