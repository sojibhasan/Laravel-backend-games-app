<?php
    require_once('../configuration/Connection.php');
    require_once('../model/AppSetting.php');

    $app_setting = new AppSetting();

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $result = $app_setting->mightyGetByKey('onesignal_configuration');
        if( $result != null ) {
            $value = json_decode($result['value']);
        } else {
            $value = null;
        }
        $app_id = isset($value) && isset($value->app_id) ? $value->app_id : '';
        $rest_api_key = isset($value) && isset($value->rest_api_key) ? $value->rest_api_key : '';
        
        $title = $_POST['title'];
        $message = $_POST['message'];
        $url = $_POST['url'];

        $heading = [
            "en" => $title,
        ];
        $content = [
            "en" => $message,
        ];

        $fields = [
            'app_id' => $app_id,
            'included_segments' => array('All'), // array('Test')
            'data' => [ 'foo' => 'bar' ],
            'contents' => $content,
            'headings' => $heading,
        ];
    
        if (isset($url)) {
            $fields['url'] = $url;
        }

        if (isset($_FILES['image']) && file_exists($_FILES['image']['tmp_name'])) {

            
            move_uploaded_file($_FILES['image']['tmp_name'], '../upload/onesignal.png');
            
            $host = str_replace("/view/index.php?page=onesignal_send",'',$_SERVER['HTTP_REFERER'] );
            $image_url = $host.'/upload/onesignal.png';

            $fields['big_picture'] = $image_url;
        }
        
        $sendContent = json_encode($fields, JSON_UNESCAPED_UNICODE);
        // print_r($sendContent);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER,
                            array('Content-Type: application/json; charset=utf-8',
                                    'Authorization: Basic '.$rest_api_key
                            ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendContent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        // print_r($response);
        $_SESSION['success'] = "Notification sent successfully.";
    
        echo '<script> location.href = "../view/index.php?page=onesignal_send"; </script>';
        die;
    }
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Send Onesignal Notification</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="new-user-info">
                        <form method="post" action="" enctype="multipart/form-data">
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" value ="" placeholder="Enter title" required="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="message">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" name="message" placeholder="Enter message" rows="2" required=""></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="image" class="form-label">Image</label>
                                    <div class="custom-file mb-3">
                                        <input type="file" class="custom-file-input" id="customFile" accept="image/*" name="image">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="url" class="form-label">URL</label>
                                    <input type="url" class="form-control" name="url" id="url" value ="" placeholder="Enter URL">
                                </div>
                            </div>
                            <hr>
                            <input type="submit" class="btn btn-primary" value="Save">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>