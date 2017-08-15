<?php
App::uses('AppHelper', 'View/Helper');

class BootstrapFormHelper extends AppHelper
{
    public $helpers = array("Form", "Html");

    /**
     * Cria o formulário
     * @param string $model Nome do model
     * @param array $options Opções
     * @return string
     */
    public function create($model = null, $options = array())
    {
        $options["inputDefaults"] = array(
            'class' => 'form-control',
            'div' => array('class' => 'form-group [error]'),
            'label' => array('class' => 'control-label'),
            'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-block')),
        );

        return $this->Form->create($model, $options);
    }

    /**
     * Cria um input somente leitura
     * @param string $fieldName Nome do campo
     * @param array $options Opções
     * @return string
     */
    public function staticInput($fieldName = true, $options = array())
    {
        $model = $this->_View->viewVars['model'];

        if (isset($options["label"])) {
            $label = $options["label"];
        } else {
            if (isset($model::$labels[$fieldName])) {
                $label = $model::$labels[$fieldName];
            } else {
                $label = $fieldName;
            }
        }

        $value = $this->request->data[$model][$fieldName];

        if (!empty($options["options"])) {
            $value = $options["options"][$this->request->data[$model][$fieldName]];
        }

        $p = $this->Html->tag("p", $value, array("class" => "form-control-static"));
        $label_tag = $this->Html->tag("label", $label, array("class" => "control-label"));

        return $this->Html->tag("div", $label_tag . $this->Html->tag("div", $p), array("class" => "form-group"));
    }

    /**
     * Cria um input no formulário
     * @param string $fieldName Nome do campo
     * @param array $options Opções
     * @return string
     */
    public function input($fieldName, $options = array())
    {
        $model = $this->_View->viewVars['model'];

        if (isset($options["label"])) {
            $options["label"] = array("text" => $options["label"], "class" => "control-label");
        } else {
            if (isset($model::$labels[$fieldName])) {
                $options["label"] = array("text" => $model::$labels[$fieldName], "class" => "control-label");
            } else {
                $options["label"] = array("text" => $fieldName, "class" => "control-label");
            }
        }

        if (!isset($options["between"])) {
            $options["between"] = "";
        }

        // File View Icon
        if (isset($this->request->data[$model][$fieldName . "_path"]) && isset($options["type"]) && $options["type"] == "file") {
            $file_path = $this->request->data[$model][$fieldName . "_path"];

            if (!empty($file_path)) {
                $icon = $this->Html->tag("span", "", array("class" => "glyphicon glyphicon-search"));
                $options["between"] .= $this->Html->link($icon, Router::url($file_path, true), array(
                    "escape" => false,
                    "target" => "blank",
                    "class" => "showtooltip pull-right",
                    "title" => "Clique para visualizar o arquivo"
                ));

                $options["between"] .= "<input type='checkbox' name='data[{$model}][{$fieldName}][remove]' class='showtooltip pull-right' title='Marque para excluir o arquivo'>";
            }
        }

        // Help Icon
        if (property_exists($model,"tips") && isset($model::$tips[$fieldName])) {
            $icon = $this->Html->tag("span", "", array("class" => "glyphicon glyphicon-question-sign"));
            $options["between"] .= $this->Html->link($icon, "javascript:void(0)", array(
                "escape" => false,
                "target" => "blank",
                "class" => "showtooltip pull-right",
                "title" => $model::$tips[$fieldName]
            ));
        }

        if (isset($options["class"])) {
            $options["class"] .= " form-control";
        } else {
            $options["class"] = "form-control";
        }

        $field = $this->Form->input($fieldName, $options);

        if ($this->Form->error($fieldName)) {
            return str_replace("[error]","has-error",$field);
        } else {
            return str_replace("[error]","",$field);
        }
    }

    /**
     * Cria um checkbox no formulário
     * @param string $fieldName Nome do campo
     * @param array $options Opções
     * @return string
     */
    public function checkbox($fieldName, $options = array())
    {
        $model = $this->_View->viewVars['model'];

        if (isset($options["label"])) {
            $options["after"] = array("text" => $options["label"], "class" => "control-label");
        } else {
            if (isset($model::$labels[$fieldName])) {
                $options["after"] = $model::$labels[$fieldName];
            } else {
                $options["after"] = $fieldName;
            }
        }

        $options["div"] = false;
        $options["label"] = false;
        $options["class"] = "";
        $label = $this->Html->tag("label", $this->Form->input($fieldName, $options));

        return $this->Html->tag("div", $label, array("class" => "checkbox"));
    }

    /**
     * Cria um campo autocomple no formulário
     * @param string $fieldName Nome do campo
     * @param string $model Nome do model para autocompletar
     * @param string $field Nome do campo para autocompletar
     * @param array $conditions Condições para a pesquisa do autocompletar (Igual a conditions)
     * @param array $options
     * @return string
     */
    public function autocomplete($fieldName, $model, $field, $conditions = array(), $options = array())
    {
        $url = Router::url("/admin/" . $this->params["controller"] . "/autocomplete", true);

        if (isset($options["class"])) {
            $options["class"] .= " autocomplete";
        } else {
            $options["class"] = "autocomplete";
        }

        $default_options = array();

        $default_options["type"] = 'text';
        $default_options["data-url"] = $url;
        $default_options["data-model"] = $model;
        $default_options["data-field"] = $field;
        $default_options["data-conditions"] = base64_encode(serialize($conditions));
        $default_options["placeholder"] = '...';
        $default_options['autocomplete'] = 'off';

        $options = array_merge($default_options, $options);

        return $this->input($fieldName, $options);
    }

    /**
     * Cria um campo autocompletar do ide de um modelo
     * @param string $fieldName Nome do campo
     * @param string $model Nome do model para autocompletar
     * @param string $field Nome do campo para autocompletar
     * @param array $conditions Condições para a pesquisa do autocompletar (Igual a conditions)
     * @param array $options
     * @return string
     */
    public function autocompleteBelongsTo($fieldName, $model, $field, $conditions = array(), $options = array())
    {
        $uniqid = uniqid();

        $url = Router::url("/admin/" . $this->params["controller"] . "/autocompleteBelongsTo", true);

        $default_options = array();

        $default_options["type"] = 'text';
        $default_options["class"] = "autocomplete-belongs-to form-control";
        $default_options["data-link"] = $uniqid;
        $default_options["data-url"] = $url;
        $default_options["data-model"] = $model;
        $default_options["data-field"] = $field;
        $default_options["data-conditions"] = base64_encode(serialize($conditions));
        $default_options["placeholder"] = '...';
        $default_options['autocomplete'] = 'off';
        $default_options["div"] = false;
        $default_options["label"] = false;
        $default_options["error"] = false;

        $options = array_merge($default_options, $options);

        $hidden_options = array(
            'label' => false,
            'style' => 'display: none;',
            'type' => 'text',
            'data-linked' => $uniqid,
            'after' => $this->Form->input($fieldName . "_autocomplete", $options)
        );

        return $this->input($fieldName, $hidden_options);
    }
}
