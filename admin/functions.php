<?php
    require_once('connection.php');

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
        $optionsDetails = ['Supplierplan','Image'];
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
        echo '<label>Content</label>';
        echo '<select name="'.$text.'" class="form-control" onchange="document.forms["frmAdm"].submit();">';
        for($i=0; $i<sizeof($details); $i++){
            echo '<option value="'.$option[$i].'">'.$details[$i].'</option>';
           
        }       
        echo '</select>';
    }
?>