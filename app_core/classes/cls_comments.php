<?php
require_once(__CLS_PATH . "cls_mysql.php");

class cls_Comments {
    private cls_Mysql $data_provider;
    
    public function __construct() {
        $this->data_provider = new cls_Mysql();
    }
    
    /**
     * Obtiene todos los comentarios de una canción
     * @param int $song_id ID de la canción
     * @return array Array con los comentarios
     */
    public function get_comments(int $song_id): array {
        $result = $this->data_provider->sql_execute_prepared(
            "SELECT c.id, c.comment, c.created_at, 
                    u.id as user_id, u.username, u.full_name, u.profile_image
             FROM tbl_comments c
             JOIN tbl_users u ON c.user_id = u.id
             WHERE c.song_id = ?
             ORDER BY c.created_at DESC",
            "i",
            [$song_id]
        );
        
        if ($result === false) {
            return [];
        }
        
        return $this->data_provider->sql_get_rows_assoc($result);
    }
    
    /**
     * Añade un nuevo comentario
     * @param int $song_id ID de la canción
     * @param int $user_id ID del usuario
     * @param string $comment Texto del comentario
     * @return bool True si se insertó correctamente, false en caso contrario
     */
    public function add_comment(int $song_id, int $user_id, string $comment): bool {
        if (empty($comment) || $song_id <= 0 || $user_id <= 0) {
            return false;
        }
        
        return $this->data_provider->sql_execute_prepared_dml(
            "INSERT INTO tbl_comments (song_id, user_id, comment, created_at) 
             VALUES (?, ?, ?, ?)",
            "iiss",
            [
                $song_id,
                $user_id,
                $comment,
                date('Y-m-d H:i:s')
            ]
        );
    }
    
    /**
     * Verifica si un usuario puede eliminar un comentario
     * @param int $comment_id ID del comentario
     * @param int $user_id ID del usuario
     * @return bool True si el usuario puede eliminar el comentario, false en caso contrario
     */
    public function can_user_delete_comment(int $comment_id, int $user_id): bool {
        $result = $this->data_provider->sql_execute_prepared(
            "SELECT 1 FROM tbl_comments WHERE id = ? AND user_id = ?",
            "ii",
            [$comment_id, $user_id]
        );
        
        if ($result === false) {
            return false;
        }
        
        $row = $this->data_provider->sql_get_fetchassoc($result);
        return $row !== null;
    }
    
    /**
     * Elimina un comentario
     * @param int $comment_id ID del comentario
     * @param int $user_id ID del usuario
     * @return bool True si se eliminó correctamente, false en caso contrario
     */
    public function delete_comment(int $comment_id, int $user_id): bool {
        if (!$this->can_user_delete_comment($comment_id, $user_id)) {
            return false;
        }
        
        return $this->data_provider->sql_execute_prepared_dml(
            "DELETE FROM tbl_comments WHERE id = ?",
            "i",
            [$comment_id]
        );
    }
    
    /**
     * Obtiene el usuario que publicó una canción
     * @param int $song_id ID de la canción
     * @return array|null Datos del usuario o null si no existe
     */
    public function get_song_author(int $song_id): ?array {
        $result = $this->data_provider->sql_execute_prepared(
            "SELECT u.id, u.username, u.full_name, u.email
             FROM tbl_songs s
             JOIN tbl_users u ON s.user_id = u.id
             WHERE s.id = ?",
            "i",
            [$song_id]
        );
        
        if ($result === false) {
            return null;
        }
        
        return $this->data_provider->sql_get_fetchassoc($result);
    }
}
?>