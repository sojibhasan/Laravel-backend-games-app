<?php
if (is_file('configuration/Database.php'))
{
    require_once('configuration/Database.php');
} else {
    require_once('../configuration/Database.php');
}

Class Game extends Database
{
    private $table = 'games';
    
    private $id;
    private $name;
    private $category_id;
    private $logo;
    private $url;
    private $is_featured;

    function setFields($field_array, $files = null)
    {
        $this->id           = isset($field_array['id']) ? $field_array['id'] : null;
        $this->name         = isset($field_array['name']) ? $field_array['name'] : null;
        $this->category_id  = isset($field_array['category_id']) ? $field_array['category_id'] : null;
        $this->url          = isset($field_array['url']) && $field_array['url'] != null ? $field_array['url'] : NULL;
        $this->is_featured  = (isset($field_array['is_featured']) && $field_array['is_featured'] == 'on') ? 1 : 0;

        if (isset($files['logo']) && file_exists($files['logo']['tmp_name'])) {
            $this->logo = $files['logo'];
        }
    }

    function mightyGetRecord()
    {
                
        $result = $this->mightyQuery("SELECT * FROM $this->table ");
        
        $records = [];
        while($row = $this->mightyFetchArray($result))
        {
            $category_result = $this->mightyQuery("SELECT `name` FROM `category` WHERE `id` = '".$row['category_id']."' ");

            $category_name = '';
            if($category_result->num_rows > 0)
            {
                $category_data = $this->mightyFetchArray($category_result);
                $category_name = $category_data['name'];
            }
            $row['logo'] = $this->mightyHost().'upload/game/'.$row['logo'];
            $row['category_name'] = $category_name;
            $records[] = $row;
        }
        return $records;
    }

    function mightySave()
    {
        $image = 'default.png';
        $is_upload = false;
        if (isset($this->logo) && file_exists($this->logo['tmp_name'])) {
            $path = '../upload/game';
            $image = time().'-'.$this->logo['name'];
            move_uploaded_file($this->logo['tmp_name'], $path."/".$image);
            $is_upload = true;
        }
        if( $this->id == null )
        {
            $record = "INSERT INTO $this->table VALUES(NULL,'".$this->name."','".$this->category_id."','".$image."','".$this->url."','".$this->is_featured."')";
            $message = "Game has been saved successfully";
        } else {
            
            $result = $this->mightyGetByID($this->id);
            if($is_upload == false)
            {
                $image = $result['logo'];
            }
            $record = "UPDATE $this->table SET `name` = '$this->name', `category_id` = '$this->category_id', `url` = '$this->url', `logo` = '$image' , `is_featured` = '$this->is_featured' WHERE `id` = '".$this->id."' ";
            $message = "Game has been updated successfully";
        }
        try {
            $this->mightyQuery($record);
            $_SESSION['success'] = $message;
        } catch (Exception $e) {
            $_SESSION['error'] = "Failed";
        }
        echo '<script> location.href = "index.php?page=game"; </script>';
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

        $path = '../upload/game/'.$logo;
        
        if( $logo != 'default.png' ) {
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $message = 'Game has been deleted.';
        try {
            $this->mightyQuery($query);
            $_SESSION['success'] = $message;
        } catch (Exception $e) {
            $_SESSION['error'] = "Failed";
        }
        echo '<script> location.href = "index.php?page=game"; </script>';
        die;
    }

    function mightyGetFeaturedRecord()
    {        
        $result = $this->mightyQuery("SELECT * FROM $this->table WHERE `is_featured` = 1 ");
        
        $records = [];
        while($row = $this->mightyFetchArray($result))
        {
            $category_result = $this->mightyQuery("SELECT `name` FROM `category` WHERE `id` = '".$row['category_id']."' ");

            $category_name = '';
            if($category_result->num_rows > 0)
            {
                $category_data = $this->mightyFetchArray($category_result);
                $category_name = $category_data['name'];
            }
            $row['logo'] = $this->mightyHost().'upload/game/'.$row['logo'];
            $row['category_name'] = $category_name;
            $records[] = $row;
        }
        return $records;
    }

    function mightyGetModuleCategoryRecord($category_id)
    {        
        $result = $this->mightyQuery("SELECT * FROM $this->table WHERE `category_id` = '".$category_id."' ");
        
        $records = [];
        while($row = $this->mightyFetchArray($result))
        {
            $row['logo'] = $this->mightyHost().'upload/game/'.$row['logo'];
            $records[] = $row;
        }
        return $records;
    }

    function mightyHost()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
            $http = "https://";
        } else {
            $http = "http://";   
        }
        
        $host = $http.$_SERVER['HTTP_HOST'];
        
        $host = str_replace('model','', $host.substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])));
        return $host;
    }
}

