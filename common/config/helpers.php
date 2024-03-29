<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Helpers
 *
 * @author core
 */
function base_url() {
    return Yii::app()->baseUrl;
}

function domainUrl() {
    return 'http://' . $_SERVER['SERVER_NAME'] . Yii::app()->baseUrl;
}

function beforeCreate($model) {
    $model->id = create_guid();
    $model->created_by = Yii::$app->user->id;
    $model->modified_by = Yii::$app->user->id;
    $model->deleted = 0;
    $model->status = 1;
    $model->date_entered = date("Y-m-d H:i:s");
    $model->date_modified = date("Y-m-d H:i:s");
    return $model;
}

function beforeUpdate($model) {
    $model->modified_by = Yii::$app->user->id;
    $model->date_modified = date("Y-m-d H:i:s");
    return $model;
}

function create_guid() {
    $microTime = microtime();
    list($a_dec, $a_sec) = explode(" ", $microTime);
    $dec_hex = dechex($a_dec * 1000000);
    $sec_hex = dechex($a_sec);
    ensure_length($dec_hex, 5);
    ensure_length($sec_hex, 6);
    $guid = "";
    $guid .= $dec_hex;
    $guid .= create_guid_section(3);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= $sec_hex;
    $guid .= create_guid_section(6);
    return $guid;
}

function ensure_length(&$string, $length) {
    $strlen = strlen($string);
    if ($strlen < $length) {
        $string = str_pad($string, $length, "0");
    } else if ($strlen > $length) {
        $string = substr($string, 0, $length);
    }
}

function create_guid_section($characters) {
    $return = "";
    for ($i = 0; $i < $characters; $i++) {
        $return .= dechex(mt_rand(0, 15));
    }
    return $return;
}

function current_user_id() {
    return Yii::app()->user->id;
}

function current_username() {
    return Yii::app()->user->name;
}

function checkPaymentStatus($id, $table) {
    $sql = "SELECT payment_status FROM $table WHERE id = '$id' LIMIT 1";
    $result = BaseModel::executeQuery($sql);
    return $result[0]['payment_status'];
}

function createS3bucket($name) {
    Yii::app()->s3->setAuth(Yii::app()->params['access_key_id'], Yii::app()->params['secret_access_key']);
    Yii::app()->s3->putBucket($name, 'public-read', 'US');
    return true;
}

function uploadGalleryImage($images, $path) {
    $base_path = Yii::app()->params['upload_path'];
    for ($i = 1; $i < sizeof($images) + 1; $i++) {
        $name = $images[$i][0];
        $target_path = $base_path . $path . "/";
        $type = $images[$i][1];
        $tmp_name = $images[$i][2];
        $allowedImageTypes = array("image/jpeg", "image/jpg", "image/png", "image/x-png", "image/gif");
        if (in_array($type, $allowedImageTypes)) {
            $target_path = $target_path . basename($name);
            move_uploaded_file($tmp_name, $target_path) or die("error!");
        }
    }
    return true;
}

function uploadThumb($name, $type, $tmp_name, $path) {
    // Where the file is going to be placed 
    $base_path = Yii::app()->params['upload_path'];
    $target_path = $base_path . $path . "/thumb/";
    $tmp = explode('.', $name);
    $extension = end($tmp);
    $randomName = create_guid() . '.' . $extension;
    /* Add the original filename to our target path.  
      Result is "images/uploads/filename.extension" */
    $target_path = $target_path . basename($randomName);
    $allowedImageTypes = array("image/jpeg", "image/jpg", "image/png", "image/x-png", "image/gif");
    if (in_array($type, $allowedImageTypes)) {
        move_uploaded_file($tmp_name, $target_path) or die("error in thumbnail upload!");
    }
//            echo $randomName;
    return $randomName;
}

function validateImageType($type) {
    $allowedImageTypes = array("image/jpeg", "image/jpg", "image/png", "image/x-png", "image/gif");
    if (in_array($type, $allowedImageTypes)) {
        return true;
    } else {
        return false;
    }
}

function validateAttachmentType($type) {
    $allowedImageTypes = array("application/pdf","application/msword","image/jpeg", "image/jpg", "image/png", "image/x-png", "image/gif");
    if (in_array($type, $allowedImageTypes)) {
        return true;
    } else {
        return false;
    }
}

function getRandomName($name) {
    $tmp = explode('.', $name);
    $extension = end($tmp);
    $randomName = create_guid() . '.' . $extension;
    return $randomName;
}

function uploadCompanyProfilePic($tmp_name, $filename) {
    $base_path = 'images/companies/';
    $target_path = $base_path;
    $target_path = $target_path . basename($filename);
    move_uploaded_file($tmp_name, $target_path);
}

function uploadInspectionAttachment($tmp_name, $filename) {
    $base_path = 'images/attachment/';
    $target_path = $base_path;
    $target_path = $target_path . basename($filename);
    move_uploaded_file($tmp_name, $target_path);
}

function uploadImage($name, $type, $tmp_name) {
    // Where the file is going to be placed 
    $base_path = Yii::app()->params['upload_path'];
    $target_path = $base_path;
    $tmp = explode('.', $name);
    $extension = end($tmp);
    $randomName = create_guid() . '.' . $extension;
    /* Add the original filename to our target path.  
      Result is "images/uploads/filename.extension" */
    $target_path = $target_path . basename($randomName);
    $allowedImageTypes = array("image/jpeg", "image/jpg", "image/png", "image/x-png", "image/gif");
    if (in_array($type, $allowedImageTypes)) {
        move_uploaded_file($tmp_name, $target_path) or die("error in main image upload!");
    }
    return $randomName;
}

function uploadFile($name, $type, $tmp_name, $path) {
    // Where the file is going to be placed 
    $base_path = Yii::app()->params['upload_path'];
    $target_path = $base_path . $path . "/";
    $tmp = explode('.', $name);
    $extension = end($tmp);
    $randomName = rand(123456, 1234567890) . '.' . $extension;
//            echo $target_path;
//            die("here");
    /* Add the original filename to our target path.  
      Result is "images/uploads/filename.extension" */
    $target_path = $target_path . basename($randomName);
    $allowedImageTypes = array("application/octet-stream",
        "text/plain", "image/jpeg",
        "image/jpg", "image/png",
        "image/x-png", "image/gif",
        "application/pdf",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "application/zip",
        "video/quicktime",
        "video/mpeg",
        "video/flv",
        "video/avi");
    if (in_array($type, $allowedImageTypes)) {
        move_uploaded_file($tmp_name, $target_path) or die("error!");
    }
    return $randomName;
}

function trimString($string, $len = 200) {
    $string = strip_tags($string);

    if (strlen($string) > $len) {

        // truncate string
        $stringCut = substr($string, 0, $len);

        // make sure it ends in a word so assassinate doesn't become ass...
        $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
    }
    return $string;
}

function getBrowser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    // Next get the name of the useragent yes separately and for good reason.
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
    // Finally get the correct version number.
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // See how many we have.
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }
    // Check if we have a number.
    if ($version == null || $version == "") {
        $version = "?";
    }
    return array(
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'pattern' => $pattern
    );
}

function pre($val, $exit = false) {
    echo '<pre>';
    print_r($val);
    if ($exit)
        exit();
}

/**
 * 
 * @param array $to
 * @param array $from
 */
function send_email($to, $subject, $body_html, $from = '') {
//    die("here");
    Yii::import('ext.phpmailer.PHPMailer');
    $mail = new PHPMailer;
    $mail->IsSMTP();
    /* $mail->Host = 'smtp.sendgrid.net';
      $mail->SMTPAuth = true;
      $mail->Username = 'info@corelynx.com';
      $mail->Password = 'g00dLuck$4$'; */
    $mail->Host = 'smtp.1and1.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tester@corelynx.com';
    $mail->Password = 'Gr@$$h0pper$';
    if ($from != '') {
        $mail->SetFrom($from['email'], $from['name']);
    } else {
        $mail->SetFrom('tester@corelynx.com', 'Corelynx Tester');
    }
    $mail->Subject = $subject;
    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
    $mail->MsgHTML($body_html);
    foreach ($to as $email) {
        $mail->AddAddress($email['email'], $email['name']);
    }
    $mail->Send();
    $msg = 'Mail Sent Successfully';
    return $msg;
}

function examplesMenu() {
    Yii::import("application.modules.stores.models.Stores", true);
    Yii::import("application.modules.examples.models.Examples", true);
    $store_limit = Yii::app()->params['store_example_limit'];
    $example_limit = Yii::app()->params['before_after_limit'];
    $stores_examples = Stores::model()->findAll(
            array('limit' => "$store_limit", 'order' => "sequence", "condition" => "publish = 1 AND store_type='example' AND deleted = 0 "));
    $examples = Examples::model()->findAll(
            array("condition" => "publish = 1 AND status = 1 AND deleted = 0 LIMIT $example_limit"));
    return array('stores' => $stores_examples, 'examples' => $examples);
}

function fixturesMenu() {
    Yii::import("application.modules.categories.models.*", true);
    return $data = Categories::model()->findAll();
}

function cmsPageInMenu($page) {
    Yii::import("application.modules.webcontents.models.*", true);
    $data = Webcontents::model()->getCmsPageData($page);
    return $data[0];
}

function logged_in_user_id() {
//    return array('id' => Yii::app()->user->id,'name' => Yii::app()->user->name);
    return Yii::app()->user->id;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function accessoriesMenu() {
    Yii::import("application.modules.products.models.*", true);
    //return ProductGallery::model()->findAll();
    return Products::model()->getAllAccessories();
}

function slugCreator($str) {
    $slug = preg_replace('@[\s!:;_\?=\\\+\*/%&#]+@', '-', $str);
    //this will replace all non alphanumeric char with '-'
    $slug = mb_strtolower($slug);
    //convert string to lowercase
    $slug = trim($slug, '-');
    return $slug;
}

function getParam($param_key) {
    return Yii::$app->params[$param_key];
}

function isFrontUserLoggedIn() {
    if (!empty(Yii::app()->session['user_name'])) {
        return true;
    } else {
        return false;
    }
}

function frontUserId() {
    if (isset(Yii::app()->session['user_id'])) {
        return Yii::app()->session['user_id'];
    } else {
        return FALSE;
    }
}

function frontUserRole() {
    if (isset(Yii::app()->session['role'])) {
        return Yii::app()->session['role'];
    } else {
        return FALSE;
    }
}

function encryption($str) {
    return md5($str);
}

// function for uploading files to S3
// if $thumb = true then thumbnail will also be stored
function uploadToS3($file_tmp_name, $file_name, $bucket) {
    Yii::app()->s3->setAuth(Yii::app()->params['access_key_id'], Yii::app()->params['secret_access_key']);
    if (Yii::app()->s3->putObjectFile($file_tmp_name, $bucket, $file_name, S3::ACL_PRIVATE)) {
        return true;
    } else {
        return false;
    }
}

function uploadToS3Thumb($image_tmp_name, $user_image) {
    Yii::app()->s3->setAuth(Yii::app()->params['access_key_id'], Yii::app()->params['secret_access_key']);
    $randomName = $user_image;
    if (Yii::app()->s3->putObjectFile($image_tmp_name, "tbrs3", $randomName, S3::ACL_PUBLIC_READ)) {
        return $randomName;
    } else {
        echo "Something went wrong while uploading your file... sorry.";
    }
}

// function for deleting the file from S3 
function deleteFromS3($file) {
    Yii::app()->s3->setAuth(Yii::app()->params['access_key_id'], Yii::app()->params['secret_access_key']);
    Yii::app()->s3->deleteObject("tbrs3", $file);
}

// function for getting the information of an object on server

function get_object_info($file) {
    Yii::app()->s3->setAuth(Yii::app()->params['access_key_id'], Yii::app()->params['secret_access_key']);
    return Yii::app()->s3->getObjectInfo("tbrs3", $file);
}

function callGateway($orderId, $package, $user, $post) {
    $twoCheckout = new Twocheckout;
    $twoCheckout->privateKey('E5FC384C-50A5-443B-B51E-4077F76927AC');
    $twoCheckout->sellerId('901271052');
    $twoCheckout->verifySSL(false);
    $twoCheckout->sandbox(true);
    try {
        $params = array(
            "merchantOrderId" => $orderId,
            "token" => $post['token'],
            "currency" => 'USD',
            "total" => $package->amount,
            "billingAddr" => array(
                "name" => $post['ccFname'] . ' ' . $post['ccLname'],
                "email" => $user->email,
                "addrLine1" => $post['addressLine1'],
                "city" => $post['city'],
                "state" => $post['state'],
                "zipCode" => $post['zip'],
                "country" => $post['country'],
                "phoneNumber" => $user->phone
            )
        );
        $charge = Twocheckout_Charge::auth($params);
        if ($charge['response']['responseCode'] == 'APPROVED') {
            return $charge;
        }
    } catch (Twocheckout_Error $e) {
        return $e->getMessage();
    }
}

function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city" => @$ipdat->geoplugin_city,
                        "state" => @$ipdat->geoplugin_regionName,
                        "country" => @$ipdat->geoplugin_countryName,
                        "country_code" => @$ipdat->geoplugin_countryCode,
                        "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

function getSubStr($str) {
    $final_string = $str;
    $len = strlen($final_string);
    if ($len > 80) {
        $final_string = substr($str, 0, 80) . "...";
    }
    return $final_string;
}

function checkAmenity($amenity_id, $property_amenities_model) {

    foreach ($property_amenities_model as $model) {
        if ($model->amenity_id == $amenity_id) {
            return "checked";
        }
    }
}

function countryList() {
    return CHtml::listData(BaseModel::getAll('Country'), 'id', 'name');
}

function crypto_rand_secure($min, $max) {
    $range = $max - $min;
    if ($range < 1)
        return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}

function getToken($length) {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet) - 1;
    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max)];
    }
    return $token;
}

// Get the Unique Code
function getUniqueCode($school_id, $feild_name) {
    Yii::import("application.modules.school.models.Format", true);


    switch (strtolower($feild_name)) {
        case 'receipt_no':
            $ind = 1;
            break;
    }

    $y = date('y');

    $format_model = Format::model()->find(array("condition" => "school_id = '$school_id' AND year = '$y' "));

    // new entry in the table
    if (empty($format_model)) {
        $school = Schools::model()->find(array("condition" => "id = '$school_id'"));
        $school_arr = explode(' ', strtoupper($school->name));
        $slug = '';
        foreach ($school_arr as $school) {
            $slug = $slug . substr($school, 0, 1);
        }

        $year = date('y');
        for ($i = 0; $i <= 10; $i++) {
            $format = new Format;
            $format->school_id = $school_id;
            $format->school_slug = $slug;
            $format->year = $year;
            $format->receipt_no = 0;
            $format->save();
            $year++;
        }
        $format_model = Format::model()->find(array("condition" => "school_id = '$school_id' AND year = '$y' "));
    }



    $format_model->receipt_no = $format_model->receipt_no + 1;
    $format_model->save();
    $sequence = $format_model->receipt_no;
    $school_slug = $format_model->school_slug;
    $id = $format_model->id;

    $Code = $school_slug . $id . $ind . $y . str_pad($sequence, 3, "0", STR_PAD_LEFT);
    return $Code;
}

function getSongBPM($file) {
    $taskUrl = 'analyze/tempo';
    $parameters = array();
    $parameters['access_id'] = getParam('sonic_api');
    $parameters['format'] = 'json';

    $parameters['input_file'] = $file;
    // $parameters['detailed_result'] = 'true';
    // important: the calls require the CURL extension for PHP
    $ch = curl_init('https://api.sonicAPI.com/' . $taskUrl);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
    // you can remove the following line when using http instead of https, or
    // you point curl to the CA certificate
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $httpResponse = curl_exec($ch);
    $infos = curl_getinfo($ch);
    curl_close($ch);

    $response = json_decode($httpResponse);
    // pre($response,true);
    return round($response->auftakt_result->overall_tempo_straight);
    // if ($infos['http_code'] == 200) {
    //     echo "Task succeeded, analysis result:<br />" . json_encode($response);
    // } else {
    //     $errorMessages = array_map(function($error) { return $error->message; }, $response->errors);
    //     echo 'Task failed, reason: ' . implode('; ', $errorMessages);
    // }
}

function getSongKey($file) {
    $taskUrl = 'analyze/key';
    $parameters = array();
    $parameters['access_id'] = getParam('sonic_api');
    $parameters['format'] = 'json';

    $parameters['input_file'] = $file;

    // important: the calls require the CURL extension for PHP
    $ch = curl_init('https://api.sonicAPI.com/' . $taskUrl);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
    // you can remove the following line when using http instead of https, or
    // you point curl to the CA certificate
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $httpResponse = curl_exec($ch);
    $infos = curl_getinfo($ch);
    curl_close($ch);

    $response = json_decode($httpResponse);
    return $response->tonart_result->key;
}

function elipsis($string, $repl, $limit) {

    if (strlen($string) > $limit) {
        $html = '<span title="' . $string . '">';
        return $html = $html . substr($string, 0, ($limit - 2)) . $repl . '</span>';
    } else {
        return $string;
    }
}

function getPlanDurationLabelPaypal($duration) {
    $label = '';
    if ($duration == 'day') {
        $label = "D";
    } else if ($duration == 'week') {
        $label = "W";
    } else if ($duration == 'month') {
        $label = "M";
    } else if ($duration == 'year') {
        $label = "Y";
    }
    return $label;
}

// function for removing the special characters from the string 

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $string)); // Removes special chars.
}

// function for getting the label of the status

function getStatusLabel($status) {
    $return_arr = array();
    if ($status == 1) {
        $return_arr['label'] = 'Active';
        $return_arr['class'] = 'label-success';
    } else if ($status == 0) {
        $return_arr['label'] = 'Inactive';
        $return_arr['class'] = 'label-danger';
    }
    return $return_arr;
}

// function for getting the label of the payment status

function getPaymentStatus($status) {
    $return_arr = array();
    if ($status == 1) {
        $return_arr['label'] = 'Yes';
        $return_arr['class'] = 'label-success';
    } else if ($status == 0) {
        $return_arr['label'] = 'No';
        $return_arr['class'] = 'label-danger';
    }
    return $return_arr;
}

// function for getting the label of the status

function getPaymentStatusLabel($status) {
    $return_arr = array();
    if ($status == 'pending') {
        $return_arr['label'] = 'Pending';
        $return_arr['class'] = 'label-warning';
    } else if ($status == 'paid') {
        $return_arr['label'] = 'Paid';
        $return_arr['class'] = 'label-success';
    }
    return $return_arr;
}

// function for checking whether the menu will be active or not

function isMenuActive($arr, $controller) {
    if (in_array($controller, $arr)) {
        return 'active';
    }
}

// function for getting the label of the slider type

function getSliderType($slider_type) {
    $label = '';
    if ($slider_type == 'normal_with_background_image') {
        $label = 'Normal With Background Image';
    } else if ($slider_type == 'normal_with_color_code') {
        $label = 'Normal With Color Code';
    } else if ($slider_type == 'video') {
        $label = 'Video';
    } else if ($slider_type == 'image') {
        $label = 'Image';
    }
    return $label;
}

function mailsend($to, $from, $subject, $message) {
    $mail = Yii::app()->Smtpmail;
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->IsHTML(true);
    $mail->SetFrom($from);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AddAddress($to);
//    if (!$mail->Send()) {
//        echo "Mailer Error: " . $mail->ErrorInfo;
//    } else {
//        echo "Message has been sent";
//    }
    return $mail->Send();
}

function ownerEmail($name, $phone, $email, $cv_url) {
    $email = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>The Big Rentals | Verify Email</title>
                <style type="text/css">
                    /* Client-specific Styles */
                    #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
                    body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
                    /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
                    .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
                    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing. */
                    #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
                    img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
                    a img {border:none;}
                    .image_fix {display:block;}
                    p {margin: 0px 0px !important;}
                    table td {border-collapse: collapse;}
                    table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
                    a {color: #33b9ff;text-decoration: none;text-decoration:none!important;}
                    /*STYLES*/
                    table[class=full] { width: 100%; clear: both; }
                    /*IPAD STYLES*/
                    @media only screen and (max-width: 640px) {
                        a[href^="tel"], a[href^="sms"] {
                            text-decoration: none;
                            color: #0a8cce; /* or whatever your want */
                            pointer-events: none;
                            cursor: default;
                        }
                        .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                            text-decoration: default;
                            color: #0a8cce !important;
                            pointer-events: auto;
                            cursor: default;
                        }
                        table[class=devicewidth] {width: 440px!important;text-align:center!important;}
                        table[class=devicewidthmob] {width: 420px!important;text-align:center!important;}
                        table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
                        img[class=banner] {width: 440px!important;height:157px!important;}
                        img[class=col2img] {width: 440px!important;height:330px!important;}
                        table[class="cols3inner"] {width: 100px!important;}
                        table[class="col3img"] {width: 131px!important;}
                        img[class="col3img"] {width: 131px!important;height: 82px!important;}
                        table[class="removeMobile"]{width:10px!important;}
                        img[class="blog"] {width: 420px!important;height: 162px!important;}
                    }
                    /*IPHONE STYLES*/
                    @media only screen and (max-width: 480px) {
                        a[href^="tel"], a[href^="sms"] {
                            text-decoration: none;
                            color: #0a8cce; /* or whatever your want */
                            pointer-events: none;
                            cursor: default;
                        }
                        .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                            text-decoration: default;
                            color: #0a8cce !important; 
                            pointer-events: auto;
                            cursor: default;
                        }
                        table[class=devicewidth] {width: 280px!important;text-align:center!important;}
                        table[class=devicewidthmob] {width: 260px!important;text-align:center!important;}
                        table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
                        img[class=banner] {width: 280px!important;height:100px!important;}
                        img[class=col2img] {width: 280px!important;height:210px!important;}
                        table[class="cols3inner"] {width: 260px!important;}
                        img[class="col3img"] {width: 280px!important;height: 175px!important;}
                        table[class="col3img"] {width: 280px!important;}
                        img[class="blog"] {width: 260px!important;height: 100px!important;}
                        td[class="padding-top-right15"]{padding:15px 15px 0 0 !important;}
                        td[class="padding-right15"]{padding-right:15px !important;}
                    }
                </style>
                </head>
                <body>
                    <!-- Start of preheader -->
                    <table width="100%" bgcolor="#dbdbdb" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="preheader">
                        <tbody>
                            <tr>
                                <td>
                                    <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td width="100%">
                                                    <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                                        <tbody>
                                                            <!-- Spacing -->
                                                            <tr>
                                                                <td width="100%" height="10"></td>
                                                            </tr>
                                                            <!-- Spacing -->
                                                            <tr>
                                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 10px;color: #303030;text-align:center;" st-content="viewonline">
                                                                    If you can’t read this email.Please 
                                                                    <a href="#" style="text-decoration: none; color: #7a6e67">view online</a> 
                                                                </td>
                                                                <!-- Spacing -->
                                                            </tr>
                                                            <tr>
                                                                <td width="100%" height="10"></td>
                                                            </tr>
                                                            <!-- Spacing -->
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End of preheader -->      
                    <!-- Start of header -->
                    <table width="100%" bgcolor="#d8d8d8" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
                        <tbody>
                            <tr>
                                <td>
                                    <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td width="100%">
                                                    <table bgcolor="#FFF" width="560" cellpadding="0" cellspacing="0" border="0" align="center" style="border-top-left-radius:5px;border-top-right-radius:5px;" class="devicewidth">
                                                        <tbody>
                                                            <!-- Spacing -->
                                                            <tr>
                                                                <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- Spacing -->
                                                            <tr>
                                                                <td>
                                                                    <!-- logo -->
                                                                    <table width="194" align="left" border="0" cellpadding="0" cellspacing="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td width="20"></td>
                                                                                <td width="174" height="60" align="left">
                                                                                    <div class="imgpop">
                                                                                        <a target="_blank" href="#">
                                                                                            <img src="' . domainUrl() . '/images/logo.png" alt="" border="0" width="145" height="25" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                                        </a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <!-- end of logo -->
                                                                    <table border="0" cellpadding="0" cellspacing="0" align="right" class="deviceWidth">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="center" style="font-size: 13px; color: #272727; font-weight: light; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 20px 0px 15px 0px;">
                                                                                    <a href="#" style="text-decoration: none; color: #3b3b3b;"></a>
                                                                                    &nbsp; &nbsp;
                                                                                    <a href="#" style="text-decoration: none; color: #3b3b3b;"></a>
                                                                                    &nbsp; &nbsp;
                                                                                    <a href="#" style="text-decoration: none; color: #3b3b3b;"></a>                            
                                                                                </td>
                                                                                <td width="20"></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <!-- Spacing -->
                                                            <tr>
                                                                <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- Spacing -->
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End of Header -->
                    <!-- Start of main-banner -->
                    <table width="100%" bgcolor="#d8d8d8" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="banner">
                        <tbody>
                            <tr>
                                <td>
                                    <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td width="100%">
                                                    <table width="560" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                                                        <tbody>
                                                            <tr>
                                                                <!-- start of image -->
                                                                <td align="center" st-image="banner-image" bgcolor="#3498db">
                                                                    <table class="full" align="center" width="540" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"> 
                                                                        <tbody>
                                                                            <tr> 
                                                                                <td width="100%" height="30"> &nbsp;</td> 
                                                                            </tr> 
                                                                            <!-- START OF HEADING--> 
                                                                            <tr> 
                                                                                <td class="center" align="center" style="margin: 0; padding: 0 15px 15px 15px; margin:0; text-transform: uppercase; font-family: Helvetica, Arial; font-size: 23px; color: #ffffff; line-height: 34px; mso-line-height-rule: exactly;"> 
                                                                                    <span> User Request Details</span> 
                                                                                </td> 
                                                                            </tr> 
                                                                            <!-- END OF HEADING--> 
                                                                            <!-- START OF TEXT--> 
                                                                            <tr> 
                                                                                <td class="center" align="center" style="margin: 0; padding:0 30px 0 30px; margin:0; font-size:14px ; color:#ffffff; font-family: Helvetica, Arial, sans-serif; line-height: 20px;mso-line-height-rule: exactly;">
                                                                                    <span>
                                                                                        Booking Request Detail from an user below
                                                                                    </span> 
                                                                                </td> 
                                                                            </tr> 
                                                                            <!-- END OF TEXT--> 
                                                                            <!-- START OF BUTTON-->
                                                                            <tr> 
                                                                                <td>
                                                                                    <table>
                                                                                        <tr>  
                                                                                            <th style="font-weight: bold;padding:0 30px 0 30px; margin:0; font-size:14px ; color:#ffffff; font-family: Helvetica, Arial, sans-serif; line-height: 20px;mso-line-height-rule: exactly;text-align:right;">Name: </th>
                                                                                            <td style="padding:0 30px 0 30px; margin:0; font-size:14px ; color:#ffffff; font-family: Helvetica, Arial, sans-serif; line-height: 20px;mso-line-height-rule: exactly;text-align:left;">' . $name . '</td>        
                                                                                        </tr>
                                                                                        <tr>  
                                                                                            <th style="font-weight: bold;padding:0 30px 0 30px; margin:0; font-size:14px ; color:#ffffff; font-family: Helvetica, Arial, sans-serif; line-height: 20px;mso-line-height-rule: exactly;text-align:right;">Email: </th>
                                                                                            <td style="padding:0 30px 0 30px; margin:0; font-size:14px ; color:#ffffff; font-family: Helvetica, Arial, sans-serif; line-height: 20px;mso-line-height-rule: exactly;text-align:left;">' . $email . '</td>        
                                                                                        </tr> 
                                                                                        <tr>  
                                                                                            <th style="font-weight: bold;padding:0 30px 0 30px; margin:0; font-size:14px ; color:#ffffff; font-family: Helvetica, Arial, sans-serif; line-height: 20px;mso-line-height-rule: exactly;text-align:right;">Phone: </th>
                                                                                            <td style="padding:0 30px 0 30px; margin:0; font-size:14px ; color:#ffffff; font-family: Helvetica, Arial, sans-serif; line-height: 20px;mso-line-height-rule: exactly;text-align:left;">' . $phone . '</td>        
                                                                                        </tr> 
                                                                                        <tr>  
                                                                                            <th style="font-weight: bold;padding:0 30px 0 30px; margin:0; font-size:14px ; color:#ffffff; font-family: Helvetica, Arial, sans-serif; line-height: 20px;mso-line-height-rule: exactly;text-align:right;">Phone: </th>
                                                                                            <td style="padding:0 30px 0 30px; margin:0; font-size:14px ; color:#ffffff; font-family: Helvetica, Arial, sans-serif; line-height: 20px;mso-line-height-rule: exactly;text-align:left;"><a href="' . $cv_url . '">Download CV</a></td>        
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr> 
                                                                            <!-- START OF BUTTON--> 
                                                                            <tr> 
                                                                                <td width="100%" height="30"> &nbsp;</td> 
                                                                            </tr> 
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- end of image -->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End of main-banner -->
                    <!-- Start of footer -->
                    <table width="100%" bgcolor="#d8d8d8" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="footer">
                        <tbody>
                            <tr>
                                <td>
                                    <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td width="100%">
                                                    <table bgcolor="#3498db" width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                                        <tbody>
                                                            <!-- Spacing -->
                                                            <tr>
                                                                <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- Spacing -->
                                                            <tr>
                                                                <td>
                                                                    <!-- logo -->
                                                                    <table width="194" align="left" border="0" cellpadding="0" cellspacing="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td width="20"></td>
                                                                                <td width="174" height="40" align="left" style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #FFF; text-align:left; line-height: 24px;">
                                                                                    FOLLOW US ONLINE:
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <!-- end of logo -->
                                                                    <!-- start of social icons -->
                                                                    <table width="60" height="40" align="right" vaalign="middle" border="0" cellpadding="0" cellspacing="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td width="22" height="22" align="left">
                                                                                    <div class="imgpop">
                                                                                        <a target="_blank" href="#">
                                                                                            <img src="' . domainUrl() . '/images/facebook.png" alt="" border="0" width="22" height="22" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                                        </a>
                                                                                    </div>
                                                                                </td>
                                                                                <td align="left" width="10" style="font-size:1px; line-height:1px;">&nbsp;</td>
                                                                                <td width="22" height="22" align="right">
                                                                                    <div class="imgpop">
                                                                                        <a target="_blank" href="#">
                                                                                            <img src="' . domainUrl() . '/images/twitter.png" alt="" border="0" width="22" height="22" style="display:block; border:none; outline:none; text-decoration:none;">
                                                                                        </a>
                                                                                    </div>
                                                                                </td>
                                                                                <td align="left" width="20" style="font-size:1px; line-height:1px;">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <!-- end of social icons -->
                                                                </td>
                                                            </tr>
                                                            <!-- Spacing -->
                                                            <tr>
                                                                <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                            </tr>
                                                            <!-- Spacing -->
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End of footer -->
                    <!-- Start of postfooter -->
                    <table width="100%" bgcolor="#dbdbdb" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="preheader">
                        <tbody>
                            <tr>
                                <td>
                                    <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                        <tbody>
                                            <tr>
                                                <td width="100%">
                                                    <table bgcolor="#ffffff" width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                                                        <tbody>
                                                            <!-- Spacing -->
                                                            <tr>
                                                                <td width="100%" height="10"></td>
                                                            </tr>
                                                            <!-- Spacing -->
                                                            <tr>
                                                                <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 13px;color: #7a6e67;text-align:center;" st-content="viewonline">
                                                                    If you wish not to receive further updates.Please 
                                                                    <a href="#" style="text-decoration: none; color: #303030">Unsubscribe</a> 
                                                                </td>
                                                            </tr>
                                                            <!-- Spacing -->
                                                            <tr>
                                                                <td width="100%" height="10"></td>
                                                            </tr>
                                                            <!-- Spacing -->
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End of postfooter -->
                </body>
                </html>';

    return $email;
}

?>
