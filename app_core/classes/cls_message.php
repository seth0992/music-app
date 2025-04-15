<?php
class cls_Message {
    // Almacena los mensajes para mostrarlos todos juntos
    private static array $message_stack = [];
    
    /**
     * Añade un mensaje a la pila de mensajes
     * @param string $message Mensaje a mostrar
     * @param string $type Tipo de mensaje (success, error, warning, info)
     * @param string $action Acción adicional
     */
    public static function add_message(string $message, string $type, string $action): void {
        $messageText = $message;
        
        if (empty($message)) {
            switch ($action) {
                case 'success_insert':
                    $messageText = "La información ha sido ingresada correctamente";
                    break;
                case 'success_update':
                    $messageText = "La información ha sido actualizada correctamente";
                    break;
                case 'success_delete':
                    $messageText = "La información ha sido eliminada correctamente";
                    break;
                default:
                    $messageText = "Operación completada";
            }
        }
        
        self::$message_stack[] = [
            'message' => $messageText,
            'type' => $type
        ];
        
        // También guardar en sesión para persistencia entre redirecciones
        $_SESSION['message_stack'] = self::$message_stack;
    }
    
    /**
     * Muestra un mensaje al usuario inmediatamente
     * @param string $message Mensaje a mostrar
     * @param string $type Tipo de mensaje (success, error, warning, info)
     * @param string $action Acción adicional
     * @return void
     */
    public static function show_message(string $message, string $type, string $action): void {
        $messageText = $message;
        
        if (empty($message)) {
            switch ($action) {
                case 'success_insert':
                    $messageText = "La información ha sido ingresada correctamente";
                    break;
                case 'success_update':
                    $messageText = "La información ha sido actualizada correctamente";
                    break;
                case 'success_delete':
                    $messageText = "La información ha sido eliminada correctamente";
                    break;
                default:
                    $messageText = "Operación completada";
            }
        }
        
        $cssClass = match($type) {
            'success' => 'alert alert-success',
            'error' => 'alert alert-danger',
            'warning' => 'alert alert-warning',
            'info' => 'alert alert-info',
            default => 'alert alert-info'
        };
        
        echo "<div class='{$cssClass}'>{$messageText}</div>";
    }
    
    /**
     * Muestra todos los mensajes en la pila y después la vacía
     */
    public static function display_all_messages(): void {
        // Recuperar mensajes de la sesión si existen
        if (isset($_SESSION['message_stack']) && !empty($_SESSION['message_stack'])) {
            self::$message_stack = $_SESSION['message_stack'];
        }
        
        if (!empty(self::$message_stack)) {
            echo '<div class="message-stack">';
            foreach (self::$message_stack as $message) {
                self::show_message($message['message'], $message['type'], "");
            }
            echo '</div>';
            
            // Limpiar la pila de mensajes
            self::$message_stack = [];
            $_SESSION['message_stack'] = [];
        }
    }
}
?>