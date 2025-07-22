<?php
class Song{
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance(); 
    }

    public function addSong($title, $artist, $genre, $file, $thumbnail)
    {
        $stmt = $this->db->prepare("INSERT INTO songs (title, artist, genre, file, thumbnail) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $artist, $genre, $file, $thumbnail]);
    }

    public function getSongsPaginated($limit, $offset, $keyword = null)
    {
        if ($keyword) {
            $stmt = $this->db->prepare("
            SELECT * FROM songs 
            WHERE title LIKE :keyword
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
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM songs WHERE title LIKE :keyword");
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

}

?>