<?php

  if(!isset($_GET['did'])){
    $sql = "SELECT *
    FROM tb_infotainment_display
    where name not like '-';";
    $pdo = $con->prepare($sql);
    $pdo->execute();
    $display = $pdo->fetchAll();
    $did = $display[0]['d_id'];
  }else{
    $did = $_GET['did'];
  }

  $sql = "call sp_getTimetableLayout(:did,@min,@max)";
  $pdo = $con->prepare($sql);
  $pdo->bindParam(':did',$did);
  try{
    $pdo->execute();
    $timetable = $pdo->fetchAll(PDO::FETCH_ASSOC);
    $pdo->closeCursor();
    $st = "SELECT @min as min, @max as max;";
    $stmt = $con->prepare($st);
    try{
      $stmt->execute();
      $result = $stmt->fetchAll();
      $min = $result[0]['min'];
      $max = $result[0]['max'];
    }catch (PDOException $e) {
      echo "<div id='hide' class=\"alert alert-danger \">";
      echo "<p>Ndodhi nje gabim ju lutem provoni perseri!".$e->getMessage()."</p>";
      echo "</div>";
      die();
    }

  }catch (PDOException $e) {
    echo "<div id='hide' class=\"alert alert-danger \">";
    echo "<p>Ndodhi nje gabim ju lutem provoni perseri!".$e->getMessage()."</p>";
    echo "</div>";
    die();
  }

?>

<div class="container">  
  <div class="cd-schedule cd-schedule--loading margin-top-lg margin-bottom-lg js-cd-schedule">
    <div class="cd-schedule__timeline">
      <ul>
        <?php
          // if(date("H",strtotime($min))!="00"){
          //   echo '<li><span>00:00</span></li>';
          // }
          $myfile = fopen("event-abs-circuit.html", "w+") or die("Unable to open file!");
          $txt = '<div class="cd-schedule-modal__event-info">
          <div>Abs Circuit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, unde, nulla. Vel unde deleniti, distinctio inventore quis molestiae perferendis, eum quo harum dolorum reiciendis sunt dicta maiores similique! Officiis repellat iure odio debitis enim eius commodi quae deserunt quam assumenda, ab asperiores reiciendis minima maxime odit laborum, libero veniam non? </div>
        </div>';
          fwrite($myfile, $txt);
          fclose($myfile);
          for($i=date("H",strtotime($min));$i<=date("h",strtotime($max))+1;$i++){
            //echo '<li><span>'.$i.':00</span></li>';
          //echo '<li><span>'.$max.'</span></li>';
          }
          // if(date("H",strtotime($max))!="00"){
          //   echo '<li><span>00:00</span></li>';
          // }
        ?>
        <li><span>00:01</span></li>
        <li><span>01:00</span></li>
        <li><span>02:00</span></li>
        <li><span>03:00</span></li>
        <li><span>04:00</span></li>
        <li><span>05:00</span></li>
        <li><span>06:00</span></li>
        <li><span>07:00</span></li>
        <li><span>08:00</span></li>
        <li><span>09:00</span></li>
        <li><span>10:00</span></li>
        <li><span>11:00</span></li>
        <li><span>12:00</span></li>
        <li><span>13:00</span></li>
        <li><span>14:00</span></li>
        <li><span>15:00</span></li>
        <li><span>16:00</span></li>
        <li><span>17:00</span></li>
        <li><span>18:00</span></li>
        <li><span>19:00</span></li>
        <li><span>20:00</span></li>
        <li><span>21:00</span></li>
        <li><span>22:00</span></li>
        <li><span>23:00</span></li>
        <li><span>23:59</span></li>
      </ul>
    </div> <!-- .cd-schedule__timeline -->
  
    <div class="cd-schedule__events">
      <ul>
        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Monday</span></div>
  
          <ul>
            
            <?php 
              foreach($timetable as $event){
                if($event['dayofweek'] & 2){
                  echo '<li class="cd-schedule__event">
                          <a data-start="'.$event['von'].'" data-end="'.$event['bis'].'" data-content="event-abs-circuit" data-event="event-1" href="#0">
                          <em class="cd-schedule__name">Abs Circuit</em>
                        </a>
                      </li>';
                }
              }
            ?>
              
          </ul>
        </li>
  
        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Tuesday</span></div>
  
          <ul>
          <?php 
              foreach($timetable as $event){
                if($event['dayofweek'] & 4){
                  echo '<li class="cd-schedule__event">
                          <a data-start="'.$event['von'].'" data-end="'.$event['bis'].'" data-content="event-abs-circuit" data-event="event-1" href="#0">
                          <em class="cd-schedule__name">Abs Circuit</em>
                        </a>
                      </li>';
                }
              }
            ?>
          </ul>
        </li>
  
        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Wednesday</span></div>
  
          <ul>
          <?php 
              foreach($timetable as $event){
                if($event['dayofweek'] & 8){
                  echo '<li class="cd-schedule__event">
                          <a data-start="'.$event['von'].'" data-end="'.$event['bis'].'" data-content="event-abs-circuit" data-event="event-1" href="#0">
                          <em class="cd-schedule__name">Abs Circuit</em>
                        </a>
                      </li>';
                }
              }
            ?>
          </ul>
        </li>
  
        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Thursday</span></div>
  
          <ul>
            <?php 
              foreach($timetable as $event){
                if($event['dayofweek'] & 16){
                  echo '<li class="cd-schedule__event">
                          <a data-start="'.$event['von'].'" data-end="'.$event['bis'].'" data-content="event-abs-circuit" data-event="event-1" href="#0">
                          <em class="cd-schedule__name">Abs Circuit</em>
                        </a>
                      </li>';
                }
              }
            ?>
          </ul>
        </li>
  
        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Friday</span></div>
  
          <ul>
          <?php 
              foreach($timetable as $event){
                if($event['dayofweek'] & 32){
                  echo '<li class="cd-schedule__event">
                          <a data-start="'.$event['von'].'" data-end="'.$event['bis'].'" data-content="event-abs-circuit" data-event="event-1" href="#0">
                          <em class="cd-schedule__name">Abs Circuit</em>
                        </a>
                      </li>';
                }
              }
            ?>
          </ul>
        </li>
        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Saturday</span></div>
  
          <ul>
          <?php 
              foreach($timetable as $event){
                if($event['dayofweek'] & 64){
                  echo '<li class="cd-schedule__event">
                          <a data-start="'.$event['von'].'" data-end="'.$event['bis'].'" data-content="event-abs-circuit" data-event="event-1" href="#0">
                          <em class="cd-schedule__name">Abs Circuit</em>
                        </a>
                      </li>';
                }
              }
            ?>
          </ul>
        </li>
        <li class="cd-schedule__group">
          <div class="cd-schedule__top-info"><span>Sunday</span></div>
  
          <ul>
          <?php 
              foreach($timetable as $event){
                if($event['dayofweek'] & 1){
                  echo '<li class="cd-schedule__event">
                          <a data-start="'.$event['von'].'" data-end="'.$event['bis'].'" data-content="event-abs-circuit" data-event="event-1" href="#0">
                          <em class="cd-schedule__name">Abs Circuit</em>
                        </a>
                      </li>';
                }
              }
            ?>
          </ul>
        </li>
      </ul>
    </div>
  
    <div class="cd-schedule-modal">
      <header class="cd-schedule-modal__header">
        <div class="cd-schedule-modal__content">
          <span class="cd-schedule-modal__date"></span>
          <h3 class="cd-schedule-modal__name"></h3>
        </div>
  
        <div class="cd-schedule-modal__header-bg"></div>
      </header>
  
      <div class="cd-schedule-modal__body">
        <div class="cd-schedule-modal__event-info"></div>
        <div class="cd-schedule-modal__body-bg"></div>
      </div>
  
      <a href="#0" class="cd-schedule-modal__close text-replace">Close</a>
    </div>
  
    <div class="cd-schedule__cover-layer"></div>
  </div> <!-- .cd-schedule -->
</div>
	<script>document.getElementsByTagName("html")[0].className += " js";</script>
  <script src="timetable/js/util.js"></script> <!-- util functions included in the CodyHouse framework -->
  <script src="timetable/js/main.js"></script>
