<?php
class Song
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function addSong($title, $artist, $genre, $file, $thumbnail, $created_by)
    {
        $stmt = $this->db->prepare("INSERT INTO songs (title, artist, genre, file, thumbnail, created_by) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $artist, $genre, $file, $thumbnail, $created_by]);
    }

    public function getSongsPaginated($limit, $offset, $keyword = null)
    {
        if ($keyword) {
            $stmt = $this->db->prepare("
            SELECT * FROM songs 
            WHERE title LIKE :keyword OR artist LIKE :keyword OR genre LIKE :keyword
            ORDER BY id DESC 
            LIMIT :limit OFFSET :offset
        ");
            $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        } else {
            $stmt = $this->db->prepare("
            SELECT * FROM songs
            ORDER BY id DESC
            LIMIT :limit OFFSET :offset
        ");
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function countSongs($keyword = null)
    {
        if ($keyword) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM songs WHERE title LIKE :keyword OR artist LIKE :keyword");
            $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM songs");
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM songs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateSong($id, $title, $artist, $genre, $file, $thumbnail)
    {
        $stmt = $this->db->prepare("UPDATE songs SET title = ?, artist = ?, genre = ?, file = ?, thumbnail = ? WHERE id = ?");
        return $stmt->execute([$title, $artist, $genre, $file, $thumbnail, $id]);
    }
    public function deleteSong($id)
    {
        $stmt = $this->db->prepare("DELETE FROM songs WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function incrementViewCount($id)
    {
        $sql = "UPDATE songs SET view_count = view_count + 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
    }

    public function getRandomSongs($limit = 9)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM songs
        ORDER BY RAND()
        LIMIT :limit
    ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopSongs($limit = 9)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM songs
        ORDER BY view_count DESC
        LIMIT :limit
    ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFavoriteSongsByUser($userId, $limit = 9)
    {
        $stmt = $this->db->prepare("
        SELECT s.* FROM songs s
        INNER JOIN likes f ON s.id = f.song_id
        WHERE f.user_id = :user_id
        ORDER BY f.created_at DESC
        LIMIT :limit
    ");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNewestSongs($limit = 9) {
        $stmt = $this->db->prepare("SELECT * FROM songs ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isLikedByUser($userId, $songId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE user_id = ? AND song_id = ?");
        $stmt->execute([$userId, $songId]);
        return $stmt->fetchColumn() > 0;
    }

    public function addLike($userId, $songId) {
        // Kiểm tra xem đã like chưa
        if ($this->isLikedByUser($userId, $songId)) {
            return false; // Đã like rồi
        }
        
        $stmt = $this->db->prepare("INSERT INTO likes (user_id, song_id, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([$userId, $songId]);
    }

    public function removeLike($userId, $songId) {
        $stmt = $this->db->prepare("DELETE FROM likes WHERE user_id = ? AND song_id = ?");
        return $stmt->execute([$userId, $songId]);
    }

    public function getMySongs($userId, $limit = 9)
    {
        $stmt = $this->db->prepare("SELECT * FROM songs WHERE created_by = :user_id ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMySongsPaginated($userId, $limit, $offset)
    {
        $stmt = $this->db->prepare("SELECT * FROM songs WHERE created_by = :user_id ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countMySongs($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM songs WHERE created_by = :user_id");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

}

?>