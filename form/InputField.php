<?php

namespace google85\phpmvc\form;

/**
 * Class InputField
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc\form
 *
 */
class InputField extends BaseField {

    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    public $type;                           //public string $type;
    public $model;                          //public Model $model;                  //PHP >=7.4 - type property
    public $attribute;                      //public string $attribute

    public function __construct($model, $attribute) {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    public function passwordField() {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function renderInput(): string {
        return sprintf('<input type="%s" name="%s" value="%s" class="form-control%s"/>',
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? ' is-invalid' : ''
        );
    }

}