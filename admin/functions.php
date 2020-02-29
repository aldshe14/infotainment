<?php

    function getDayName($dayofweek){
        
        if($dayofweek & 2){
            $dayofweek-=2;
            if($dayofweek>0)
                echo "Mon, ";
            else
                echo "Mon";
        }
        if($dayofweek & 4){
            $dayofweek-=4;
            if($dayofweek>0)
                echo "Tue, ";
            else
                echo "Tue";
        }
        if($dayofweek& 8){
            $dayofweek-=8;
            if($dayofweek>0)
                echo "Wed, ";
            else
                echo "Wed";
        }
        if($dayofweek& 16){
            $dayofweek-=16;
            if($dayofweek>0)
                echo "Thu, ";
            else
                echo "Thu";
        }
        if($dayofweek& 32){
            $dayofweek-=32;
            if($dayofweek>0)
                echo "Fri, ";
            else
                echo "Fri";
        }
        if($dayofweek& 64){
            $dayofweek-=64;
            if($dayofweek>0)
                echo "Sat, ";
            else
                echo "Sat";
        }
        if($dayofweek& 1){
            $dayofweek-=1;
            if($dayofweek>0)
                echo "Sun, ";
            else
                echo "Sun";
        }
        
        
    }

    function getHeaderOptions(){
        $options = ['header'];
        $optionsDetails = ['Test'];
        selectOption($options,$optionsDetails,"header");
    }

    function getBodyOptions(){
        $options = ['supplierplan','image'];
        $optionsDetails = ['Supplierplan','Image','Stundenplan','Leere Klassen'];
        selectOption($options,$optionsDetails,"body");

    }

    function getWidget1Options(){
        $options = ['weather.php','website.php'];
        $optionsDetails = ['Weather','Website Post'];
        selectOption($options,$optionsDetails,"widget1");
    }

    function getWidget2Options(){
        $options = ['weather.php','website.php'];
        $optionsDetails = ['Weather','Website Post'];
        selectOption($options,$optionsDetails,"widget2");
    }

    function getFooterOptions(){
        $options = ['text.php'];
        $optionsDetails = ['Moving Text'];
        selectOption($options,$optionsDetails,"footer");
    }

    function selectOption($option,$details,$text){
        echo '<div class="form-row">
        <div class="form-group col-md-6">
        <label>Content</label>';
        echo '<select name="'.$text.'" id="'.$text.'" class="form-control">';
        for($i=0; $i<sizeof($details); $i++){
            echo '<option value="'.$option[$i].'">'.$details[$i].'</option>';
        }
        echo '</select>
        </div></div>';
        selectAssets($text);
    }

    function selectAssets($text){
        if($text == "body"){
            echo '<div class="form-row">';
            echo '<div class="form-group col-md-12" id="'.$text.'image">';
            echo '<label>Image</label>';
            echo '<input list="image" name="image" class="form-control" required>';
            echo '<datalist id="image">';
            global $con;
            global $stmt, $pdo;
            $pdo->closeCursor();
            $sql = "SELECT name
                    from tb_infotainment_images";
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $images= $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($images as $image){
                echo '<option>'.$image['name'].'</option>';
            }
            echo '</datalist>';
            echo '</div></div>';
        }
    }

    function generateStrongPassword(){
        $length = 13; 
        $add_dashes = false;
        $available_sets = 'luds';
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';
    
        $all = '';
        $password = '';
        foreach($sets as $set)
        {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
    
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];
    
        $password = str_shuffle($password);
    
        if(!$add_dashes)
            return $password;
    
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }
?>