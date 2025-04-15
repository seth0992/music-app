<?php
require_once(__CLS_PATH . "cls_mysql.php");

class cls_Music {
    private cls_Mysql $data_provide;
    
    public function __construct() {
        $this->data_provide = new cls_Mysql();
    }
    
    /**
     * Obtiene todas las canciones ordenadas por ID descendente
     * @return array Array con las canciones
     */
    public function get_songs(): array {
        $result = $this->data_provide->sql_execute(
            "SELECT s.id, s.title, s.artist, s.genre, s.review, s.rating, s.created_at, 
                    u.id as user_id, u.username, u.full_name, u.profile_image
             FROM tbl_songs s
             JOIN tbl_users u ON s.user_id = u.id 
             ORDER BY s.id DESC"
        );
        
        if ($result === false) {
            return [];
        }
        
        return $this->data_provide->sql_get_rows_assoc($result);
    }
    
    /**
     * Obtiene una canción por su ID
     * @param int $id ID de la canción
     * @return array|null Datos de la canción o null si no existe
     */
    public function get_song_by_id(int $id): ?array {
        $result = $this->data_provide->sql_execute_prepared(
            "SELECT s.id, s.title, s.artist, s.genre, s.review, s.rating, s.created_at, s.user_id,
                    u.username, u.full_name, u.profile_image
             FROM tbl_songs s
             JOIN tbl_users u ON s.user_id = u.id
             WHERE s.id = ?",
            "i",
            [$id]
        );
        
        if ($result === false) {
            return null;
        }
        
        return $this->data_provide->sql_get_fetchassoc($result);
    }
    
    /**
     * Inserta una nueva canción en la base de datos
     * @param array $songdata Datos de la canción a insertar
     * @return bool True si se insertó correctamente, false en caso contrario
     */
    public function insert_song(array $songdata): bool {
        // Verificar que tengamos todos los datos necesarios
        if (empty($songdata['title']) || empty($songdata['artist']) || 
            empty($songdata['genre']) || empty($songdata['review']) || 
            empty($songdata['rating']) || empty($songdata['user_id'])) {
            return false;
        }
        
        // Usando consulta preparada para mayor seguridad
        return $this->data_provide->sql_execute_prepared_dml(
            "INSERT INTO tbl_songs (title, artist, genre, review, rating, user_id, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            "ssssiis",
            [
                $songdata['title'],
                $songdata['artist'],
                $songdata['genre'],
                $songdata['review'],
                $songdata['rating'],
                $songdata['user_id'],
                date('Y-m-d H:i:s')
            ]
        );
    }
    
    /**
     * Actualiza una canción existente
     * @param array $songdata Datos de la canción a actualizar
     * @return bool True si se actualizó correctamente, false en caso contrario
     */
    public function update_song(array $songdata): bool {
        // Verificar que tengamos todos los datos necesarios
        if (empty($songdata['id']) || empty($songdata['title']) || 
            empty($songdata['artist']) || empty($songdata['genre']) || 
            empty($songdata['review']) || empty($songdata['rating'])) {
            return false;
        }
        
        // Verificar que el usuario tenga permisos para editar esta canción
        if (!$this->can_user_edit_song($songdata['id'], $songdata['user_id'])) {
            return false;
        }
        
        // Usando consulta preparada para mayor seguridad
        return $this->data_provide->sql_execute_prepared_dml(
            "UPDATE tbl_songs 
             SET title = ?, artist = ?, genre = ?, review = ?, rating = ? 
             WHERE id = ?",
            "ssssis",
            [
                $songdata['title'],
                $songdata['artist'],
                $songdata['genre'],
                $songdata['review'],
                $songdata['rating'],
                $songdata['id']
            ]
        );
    }
    
    /**
     * Verifica si un usuario puede editar una canción
     * @param int $song_id ID de la canción
     * @param int $user_id ID del usuario
     * @return bool True si el usuario puede editar la canción, false en caso contrario
     */
    public function can_user_edit_song(int $song_id, int $user_id): bool {
        $result = $this->data_provide->sql_execute_prepared(
            "SELECT 1 FROM tbl_songs WHERE id = ? AND user_id = ?",
            "ii",
            [$song_id, $user_id]
        );
        
        if ($result === false) {
            return false;
        }
        
        $row = $this->data_provide->sql_get_fetchassoc($result);
        return $row !== null;
    }
    
    /**
     * Elimina una canción por su ID
     * @param int $id ID de la canción a eliminar
     * @param int $user_id ID del usuario que intenta eliminar
     * @return bool True si se eliminó correctamente, false en caso contrario
     */
    public function delete_song(int $id, int $user_id): bool {
        // Verificar que el usuario tenga permisos para eliminar esta canción
        if (!$this->can_user_edit_song($id, $user_id)) {
            return false;
        }
        
        // Usando consulta preparada para mayor seguridad
        return $this->data_provide->sql_execute_prepared_dml(
            "DELETE FROM tbl_songs WHERE id = ?",
            "i",
            [$id]
        );
    }
    
    /**
     * Busca canciones que coincidan con un término de búsqueda
     * @param string $searchTerm Término de búsqueda
     * @return array Array con las canciones encontradas
     */
    public function search_songs(string $searchTerm): array {
        $searchTerm = "%{$searchTerm}%";
        
        $result = $this->data_provide->sql_execute_prepared(
            "SELECT s.id, s.title, s.artist, s.genre, s.review, s.rating, s.created_at, 
                    u.id as user_id, u.username, u.full_name, u.profile_image
             FROM tbl_songs s
             JOIN tbl_users u ON s.user_id = u.id
             WHERE s.title LIKE ? OR s.artist LIKE ? OR s.genre LIKE ?
             ORDER BY s.id DESC",
            "sss",
            [$searchTerm, $searchTerm, $searchTerm]
        );
        
        if ($result === false) {
            return [];
        }
        
        return $this->data_provide->sql_get_rows_assoc($result);
    }
}
?>