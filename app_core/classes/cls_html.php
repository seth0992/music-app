<?php
class cls_Html {
    /**
     * Genera una etiqueta de script para un archivo JavaScript
     * @param string $script_path Ruta al archivo JavaScript
     * @return string Etiqueta script
     */
    public function html_js_header(string $script_path): string {
        return "<script defer src='{$script_path}'></script>\n";
    }
    
    /**
     * Genera una etiqueta de enlace para un archivo CSS
     * @param string $css_path Ruta al archivo CSS
     * @param string $media Tipo de media
     * @return string Etiqueta link para CSS
     */
    public function html_css_header(string $css_path, string $media): string {
        return "<link rel='stylesheet' type='text/css' href='{$css_path}' media='{$media}'>\n";
    }
    
    /**
     * Genera una etiqueta form de apertura
     * @param string $id ID del formulario
     * @param string $class Clase CSS del formulario
     * @param string $action Acción del formulario
     * @param string $method Método del formulario
     * @return string Etiqueta form de apertura
     */
    public function html_form_tag(string $id, string $class, string $action, string $method): string {
        return "<form id='{$id}' class='{$class}' action='{$action}' method='{$method}'>\n";
    }
    
    /**
     * Genera una etiqueta form de cierre
     * @return string Etiqueta form de cierre
     */
    public function html_form_end(): string {
        return "</form>\n";
    }
    
    /**
     * Genera un campo de entrada de texto
     * @param string $type Tipo de input
     * @param string $name Nombre del input
     * @param string $id ID del input
     * @param string $class Clase CSS del input
     * @param string $value Valor del input
     * @param string $placeholder Texto de marcador de posición
     * @param string $required Si es requerido o no
     * @return string Elemento input
     */
    public function html_input_text(string $type, string $name, string $id, string $class, string $value, string $placeholder, string $required): string {
        return "<input type='{$type}' name='{$name}' id='{$id}' class='{$class}' value='{$value}' placeholder='{$placeholder}' {$required}>\n";
    }
    
    /**
     * Genera un select
     * @param string $name Nombre del select
     * @param string $id ID del select
     * @param string $class Clase CSS del select
     * @param array $options Opciones del select
     * @param string $selected Valor seleccionado
     * @param string $required Si es requerido o no
     * @return string Elemento select
     */
    public function html_select(string $name, string $id, string $class, array $options, string $selected, string $required): string {
        $select = "<select name='{$name}' id='{$id}' class='{$class}' {$required}>\n";
        
        foreach ($options as $value => $text) {
            $isSelected = ($value == $selected) ? 'selected' : '';
            $select .= "<option value='{$value}' {$isSelected}>{$text}</option>\n";
        }
        
        $select .= "</select>\n";
        return $select;
    }
    
    /**
     * Genera un área de texto
     * @param string $name Nombre del área de texto
     * @param string $id ID del área de texto
     * @param string $class Clase CSS del área de texto
     * @param string $value Valor del área de texto
     * @param string $placeholder Texto de marcador de posición
     * @param int $rows Número de filas
     * @param string $required Si es requerido o no
     * @return string Elemento textarea
     */
    public function html_textarea(string $name, string $id, string $class, string $value, string $placeholder, int $rows, string $required): string {
        return "<textarea name='{$name}' id='{$id}' class='{$class}' placeholder='{$placeholder}' rows='{$rows}' {$required}>{$value}</textarea>\n";
    }
    
    /**
     * Genera un botón de entrada
     * @param string $type Tipo de botón
     * @param string $name Nombre del botón
     * @param string $id ID del botón
     * @param string $class Clase CSS del botón
     * @param string $value Valor del botón
     * @param string $icon Ícono del botón
     * @return string Elemento button
     */
    public function html_button(string $type, string $name, string $id, string $class, string $value, string $icon): string {
        return "<button type='{$type}' name='{$name}' id='{$id}' class='{$class}'><i class='{$icon}'></i> {$value}</button>\n";
    }
    
    /**
     * Genera un campo oculto
     * @param string $name Nombre del campo
     * @param string $id ID del campo
     * @param string $value Valor del campo
     * @return string Elemento input hidden
     */
    public function html_hidden(string $name, string $id, string $value): string {
        return "<input type='hidden' name='{$name}' id='{$id}' value='{$value}'>\n";
    }

    /**
     * Genera un grupo de radio buttons para valoraciones
     * @param string $name Nombre del grupo de radio buttons
     * @param int $selected Valor seleccionado
     * @param string $required Si es requerido o no
     * @return string HTML para el grupo de radio buttons
     */
    public function html_rating_stars(string $name, int $selected, string $required): string {
        $html = "<div class='rating-stars'>\n";
        
        for ($i = 5; $i >= 1; $i--) {
            $isChecked = ($i == $selected) ? 'checked' : '';
            $html .= "<input type='radio' id='{$name}_{$i}' name='{$name}' value='{$i}' {$isChecked} {$required}>\n";
            $html .= "<label for='{$name}_{$i}' title='{$i} estrellas'><i class='fas fa-star'></i></label>\n";
        }
        
        $html .= "</div>\n";
        return $html;
    }
}
?>