<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/music-app/global.php");
require_once(__CLS_PATH . "cls_notifications.php");
require_once(__CLS_PATH . "cls_mail.php");

class ctr_Notifications {
    private cls_Notifications $notifications;
    private cls_Mail $mail;
    
    public function __construct() {
        $this->notifications = new cls_Notifications();
        $this->mail = new cls_Mail();
    }
    
    /**
     * Crea una notificación y envía un correo electrónico
     * @param int $user_id ID del usuario que recibe la notificación
     * @param string $message Mensaje de la notificación
     * @param string $email Correo electrónico del usuario
     * @param string $from_name Nombre del remitente
     * @return bool True si se creó correctamente, false en caso contrario
     */
    public function notify(int $user_id, string $message, string $email, string $from_name): bool {
        // Crear la notificación en la base de datos
        $result = $this->notifications->create_notification($user_id, $message);
        
        if ($result) {
            // Enviar correo electrónico
            $this->mail->create_Mail(
                $email,
                'noreply@musicapp.com',
                'Nueva notificación de MusicApp',
                '<html><body>
                <h2>Hola, tienes una nueva notificación de MusicApp</h2>
                <p><strong>De:</strong> ' . htmlspecialchars($from_name) . '</p>
                <p><strong>Mensaje:</strong> ' . htmlspecialchars($message) . '</p>
                <p>Inicia sesión en MusicApp para ver todas tus notificaciones.</p>
                </body></html>'
            );
            
            $this->mail->send_Mail();
        }
        
        return $result;
    }
    
    /**
     * Obtiene todas las notificaciones de un usuario
     * @param int $user_id ID del usuario
     * @return array Array con las notificaciones
     */
    public function get_notifications(int $user_id): array {
        return $this->notifications->get_notifications($user_id);
    }
    
    /**
     * Marca una notificación como leída
     * @param int $notification_id ID de la notificación
     * @param int $user_id ID del usuario
     * @return bool True si se actualizó correctamente, false en caso contrario
     */
    public function mark_as_read(int $notification_id, int $user_id): bool {
        return $this->notifications->mark_as_read($notification_id, $user_id);
    }
    
    /**
     * Cuenta las notificaciones no leídas de un usuario
     * @param int $user_id ID del usuario
     * @return int Número de notificaciones no leídas
     */
    public function count_unread(int $user_id): int {
        return $this->notifications->count_unread($user_id);
    }
}
?>