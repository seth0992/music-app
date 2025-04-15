<?php
require_once(__CLS_PATH . "cls_mysql.php");

class cls_Notifications {
    private cls_Mysql $data_provider;
    
    public function __construct() {
        $this->data_provider = new cls_Mysql();
    }
    
    /**
     * Crea una nueva notificación para un usuario
     * @param int $user_id ID del usuario que recibirá la notificación
     * @param string $message Mensaje de notificación
     * @return bool True si se creó correctamente, false en caso contrario
     */
    public function create_notification(int $user_id, string $message): bool {
        // Verificar que tengamos datos válidos
        if (empty($user_id) || empty($message)) {
            return false;
        }
        
        // Usando consulta preparada para mayor seguridad
        return $this->data_provider->sql_execute_prepared_dml(
            "INSERT INTO tbl_notifications (user_id, message, created_at) 
             VALUES (?, ?, ?)",
            "iss",
            [
                $user_id,
                $message,
                date('Y-m-d H:i:s')
            ]
        );
    }
    
    /**
     * Obtiene todas las notificaciones de un usuario
     * @param int $user_id ID del usuario
     * @return array Array con las notificaciones
     */
    public function get_notifications(int $user_id): array {
        $result = $this->data_provider->sql_execute_prepared(
            "SELECT id, message, is_read, created_at
             FROM tbl_notifications
             WHERE user_id = ?
             ORDER BY created_at DESC",
            "i",
            [$user_id]
        );
        
        if ($result === false) {
            return [];
        }
        
        return $this->data_provider->sql_get_rows_assoc($result);
    }
    
    /**
     * Marca una notificación como leída
     * @param int $notification_id ID de la notificación
     * @param int $user_id ID del usuario (para verificar que le pertenece)
     * @return bool True si se actualizó correctamente, false en caso contrario
     */
    public function mark_as_read(int $notification_id, int $user_id): bool {
        return $this->data_provider->sql_execute_prepared_dml(
            "UPDATE tbl_notifications 
             SET is_read = 1 
             WHERE id = ? AND user_id = ?",
            "ii",
            [$notification_id, $user_id]
        );
    }
    
    /**
     * Cuenta las notificaciones no leídas de un usuario
     * @param int $user_id ID del usuario
     * @return int Número de notificaciones no leídas
     */
    public function count_unread(int $user_id): int {
        $result = $this->data_provider->sql_execute_prepared(
            "SELECT COUNT(*) as count
             FROM tbl_notifications
             WHERE user_id = ? AND is_read = 0",
            "i",
            [$user_id]
        );
        
        if ($result === false) {
            return 0;
        }
        
        $row = $this->data_provider->sql_get_fetchassoc($result);
        return (int)$row['count'];
    }
}
?>