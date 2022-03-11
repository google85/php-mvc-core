<?php

namespace google85\phpmvc;
use google85\phpmvc\db\DbModel;

/**
 * Class UserModel
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc
 *
 */
abstract class UserModel extends DbModel {

    abstract public function getDisplayName(): string;

}