<?php
class db {
    public function connect()
    {
        $db_host = '127.0.0.1';
        $db_user = 'root';
        $db_password = 'root';
        $db_db = 'facebook';
        $db_port = 8889;

        $mysqli = mysqli_connect(
            $db_host,
            $db_user,
            $db_password,
            $db_db,
            $db_port
        );

        return $mysqli;
    }

    public function insertComments (Array $data)
    {
        try {

            $db = self::connect();
            $query = $db->query("INSERT INTO live_videos (video_id, comment_id, message, name) 
                VALUES ('". $data['video_id']."', '". $data['comment_id'] ."', '". $data['message'] ."', '". $data['name']."')");
            return $query;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }  
    }

    public function getComments ()
    {
        try {
            $db = self::connect();
            $query = mysqli_query($db, "SELECT * FROM live_videos");
            return mysqli_fetch_all($query, MYSQLI_ASSOC);

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}