<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use App\Error\AppError;

$errorHandler = new AppError();
$errorHandler->register();

namespace App\Error;

use Cake\Error\BaseErrorHandler;

/**
 * Description of AppError
 *
 * @author josorio
 */
class AppError extends BaseErrorHandler {

    public function _displayError($error, $debug) {
        return 'There has been an error!';
    }

    public function _displayException($exception) {
        return 'There has been an exception!';
    }

    public function handleFatalError($code, $description, $file, $line) {
        return 'A fatal error has happened';
    }

}
