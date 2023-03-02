<?php
if (is_file('configuration/Database.php'))
{
    require_once('configuration/Database.php');
} else {
    require_once('../configuration/Database.php');
}

Class Category extends Database 
{
    private $table = 'category';
    private $game_table = 'games';
    private $id;
    private $name;
    private $logo;

    function setFields($field_array, $files = null)
    {
        $this->id           = isset($field_array['id']) ? $field_array['id'] : null;
        $this->name         = isset($field_array['name']) ? $field_array['name'] : null;

        if (isset($files['logo']) && file_exists($files['logo']['tmp_name'])) {
            $this->logo = $files['logo'];
        }
    }

    function mightyGetRecord()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
            $http = "https://";
        } else {
            $http = "http://";   
        }

        $host = $http.$_SERVER['HTTP_HOST'];
        
        $host = str_replace('model','', $host.substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])));
        
        $result = $this->mightyQuery("SELECT * FROM $this->table ");
        
        $records = [];
        while($row = $this->mightyFetchArray($result))
        {
            $row['logo'] = $host.'upload/category/'.$row['logo'];
            $records[] = $row;
        }
        return $records;
    }

    function mightySave()
    {
        $image = 'default.png';
        $is_upload = false;
        if (isset($this->logo) && file_exists($this->logo['tmp_name'])) {
            $path = '../upload/category';
            $image = time().'-'.$this->logo['name'];
            move_uploaded_file($this->logo['tmp_name'], $path."/".$image);
            $is_upload = true;
        }
        if( $this->id == null )
        {
            $record = "INSERT INTO $this->table VALUES(NULL,'".$this->name."','".$image."')";
            $message = "Category has been saved successfully";
        } else {
            
            $result = $this->mightyGetByID($this->id);

            if($is_upload == false)
            {
                $image = $result['logo'];
            }
            $record = "UPDATE $this->table SET `name` = '$this->name', `logo` = '$image' WHERE `id` = '".$this->id."' ";
            $message = "Category has been updated successfully";
        }
        try {
            $this->mightyQuery($record);
            $_SESSION['success'] = $message;
        } catch (Exception $e) {
            $_SESSION['error'] = "Failed";
        }
        
        echo '<script> location.href = "index.php?page=category"; </script>';
        die;
    }

    function mightyGetByID($id)
    {
        $query = "SELECT * FROM $this->table WHERE `id` = '".$id."'";
        return $this->mightyFetchArray($this->mightyQuery($query));
    }

    function mightyDelete()
    {
        $result = $this->mightyGetByID($this->id);
        
        $logo = $result['logo'];
        
        $query = "DELETE FROM $this->table WHERE `id` = '".$this->id."' ";

        $path = '../upload/category/'.$logo;
        
        if( $logo != 'default.png' ) {
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $message = 'Category has been deleted.';
        try {
            $this->mightyQuery($query);
            $game_result = $this->mightyQuery("SELECT * FROM $this->game_table WHERE `category_id` = '".$this->id."' ");
        
            $records = [];
            while($row = $this->mightyFetchArray($game_result)) {
                $filename = '../upload/game/'.$row['logo'];
                if( $row['logo'] != 'default.png' ) {
                    if (file_exists($filename)) {
                        unlink($filename);
                    }
                }
            }
            $this->mightyQuery("DELETE FROM $this->game_table WHERE `category_id` = '".$this->id."' ");
            
            $_SESSION['success'] = $message;
        } catch (Exception $e) {
            $_SESSION['error'] = "Failed";
        }
        echo '<script> location.href = "index.php?page=category"; </script>';
        die;
    }
}