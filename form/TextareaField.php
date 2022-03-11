<?php

namespace google85\phpmvc\form;

/**
 * Class TextareaField
 * 
 * @author   google85 <bpfcomp2005@gmail.com>
 * @package  google85\phpmvc\form
 *
 */
class TextareaField extends BaseField {

    public $type;                           //public string $type;
    public $model;                          //public Model $model;                  //PHP >=7.4 - type property
    public $attribute;                      //public string $attribute

    public function __construct($model, $attribute) {
        parent::__construct($model, $attribute);
    }

    public function renderInput(): string {
        return sprintf('<textarea name="%s" class="form-control%s">%s</textarea>',
            $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->{$this->attribute}
        );
    }

}