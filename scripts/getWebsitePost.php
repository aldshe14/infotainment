<?php
    require_once "connection.php";
    
    function fixBadUnicodeForJson($str) {
        $str = preg_replace_callback(
        '/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/',
        function($matches) { return chr(hexdec("$1")).chr(hexdec("$2")).chr(hexdec("$3")).chr(hexdec("$4")); },
        $str
    );
        $str = preg_replace_callback(
        '/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/',
        function($matches) { return chr(hexdec("$1")).chr(hexdec("$2")).chr(hexdec("$3")); },
        $str
    );
        $str = preg_replace_callback(
        '/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/',
        function($matches) { return chr(hexdec("$1")).chr(hexdec("$2")); },
        $str
    );
        $str = preg_replace_callback(
        '/\\\\u00([0-9a-f]{2})/',
        function($matches) { return chr(hexdec("$1")); },
        $str
    );
        return $str;
    }

    $sql = "SELECT * 
    FROM  tb_infotainment_website_posts 
    order by datum desc limit 1 ;";
    $pdo = $con->prepare($sql);

    $title = "";

    try {
        $pdo->execute();
        $post = $pdo->fetchAll(PDO::FETCH_ASSOC);
        //echo $result['city_id'];
        foreach ($post as $row){
            $title = $row['title'];
        }
    }catch (PDOException $e) {
    	//header("Location: getWebsitePost.php");
    }
   
    $sql = "SELECT * 
    FROM  tb_infotainment_apisettings limit 1 ;";
    $pdo = $con->prepare($sql);
    
    $weburl = "";
    try {
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        //echo $result['city_id'];
        foreach ($result as $row){
            $weburl= $row['website_url'];
        }
    }catch (PDOException $e) {
    	header("Location: getWebsiteData.php");
    }

    $url= $weburl."/wp-json/wp/v2/posts";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);

    $result=curl_exec($ch);

    $posts = json_decode($result, true);

    foreach ($posts as $post) { 
        if($post['title']['rendered'] != $title){
            
            $sql = "INSERT INTO `tb_infotainment_website_posts`(`title`, `content`, `image`, `datum`) 
            VALUES(:title, :content, :image, :datum)";

            $sth = $con->prepare($sql);

            $blob = file_get_contents($post['jetpack_featured_media_url']);

            $sth->bindValue(':title', fixBadUnicodeForJson($post['title']['rendered']));
            $sth->bindValue(':content', fixBadUnicodeForJson($post['excerpt']['rendered']));
            $sth->bindParam(':image', $blob, PDO::PARAM_LOB);
            $sth->bindValue(':datum', $post['date']);

            try {
                $sth->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                break;
            }
        }else{
            break;
        }
    }
    

?>
