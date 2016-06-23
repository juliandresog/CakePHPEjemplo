<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Auth;

use Cake\Auth\AbstractPasswordHasher;

/**
 * Description of LegacyPasswordHasher
 *
 * @author josorio
 */
class LegacyPasswordHasher extends AbstractPasswordHasher {

    public function hash($password) {
        return sha1($password);
    }

    public function check($password, $hashedPassword) {
        return sha1($password) === $hashedPassword;
    }

}
