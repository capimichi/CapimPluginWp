<?php
namespace CapimPluginWP;

class FormBuilder{

    /**
     * @var array
     */
    protected $fields;

    /**
     * FormBuilder constructor.
     */
    public function __construct()
    {
        $this->fields = [];
    }

    /**
     * @param string $name
     * @param string $type Valid types: text, select, radio, checkbox, number, date
     * @param string|null $value
     * @param string|null $label
     * @param array $options Options for select as associative array of label => value
     * @param array $attributes All html attributes (id, class, value, ecc.)
     * @param array $containerAttributes All html attributes (id, class, value, ecc.)
     */
    public function addField($name, $type, $value, $label = null, array $options = array(), array $attributes = array(), $containerAttributes = array()){
        $field = array(
            "name" => $name,
            "type" => $type,
            "value" => $value,
            "label" => $label,
            "options" => $options,
            "attributes" => $attributes,
            "containerAttributes" => $containerAttributes,
        );
        $this->fields[] = $field;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setFieldValue($key, $value){
        $this->fields[$key]["value"] = $value;
    }


}