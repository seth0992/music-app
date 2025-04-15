<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/music-app/global.php");
require_once(__CLS_PATH . "cls_comments.php");
require_once(__CTR_PATH . "ctr_notifications.php");

class ctr_Comments {
    private cls_Comments $comments;
    private ctr_Notifications $notifications;
    
    public function __construct() {
        $this->comments = new cls_Comments();
        $this->notifications = new ctr_Notifications();
    }
    
    /**
     * Obtiene todos los comentarios de una canción
     * @param int $song_id ID de la canción
     * @return array Array con los comentarios
     */
    public function get_comments(int $song_id): array {
        return $this->comments->get_comments($song_id);
    }
    
    /**
     * Añade un nuevo comentario y notifica al autor de la canción
     * @param int $song_id ID de la canción
     * @param int $user_id ID del usuario que comenta
     * @param string $comment Texto del comentario
     * @param string $user_name Nombre del usuario que comenta
     * @return bool True si se añadió correctamente, false en caso contrario
     */
    public function add_comment(int $song_id, int $user_id, string $comment, string $user_name): bool {
        // Añadir el comentario
        $result = $this->comments->add_comment($song_id, $user_id, $comment);
        
        if ($result) {
            // Obtener información del autor de la canción
            $author = $this->comments->get_song_author($song_id);
            
            // Notificar al autor si no es el mismo usuario
            if ($author && $author['id'] != $user_id) {
                $this->notifications->notify(
                    $author['id'],
                    "El usuario {$user_name} ha comentado en tu canción.",
                    $author['email'],
                    $user_name
                );
            }
        }
        
        return $result;
    }
    
    /**
     * Elimina un comentario
     * @param int $comment_id ID del comentario
     * @param int $user_id ID del usuario
     * @return bool True si se eliminó correctamente, false en caso contrario
     */
    public function delete_comment(int $comment_id, int $user_id): bool {
        return $this->comments->delete_comment($comment_id, $user_id);
    }
}
?>