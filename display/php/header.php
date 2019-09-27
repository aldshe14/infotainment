<div class="logo">
    <a href="https://htl-shkoder.com/" title="Österreichische Schule &quot;Peter Mahringer&quot;" rel="home">
        <img src="img/logo.png" alt="Site Logo" />
    </a>
</div>
<div class="time">
    <h4><span id="time_span"></span></h4>
</div>
<script>

timer();

function timer(){
var currentTime = new Date()
var hours = currentTime.getHours()
var minutes = currentTime.getMinutes()
var sec = currentTime.getSeconds()
var day = currentTime.getDay()
var date = currentTime.getDate()
var month = new Array();
month[0] = "January";
month[1] = "February";
month[2] = "March";
month[3] = "April";
month[4] = "May";
month[5] = "June";
month[6] = "July";
month[7] = "August";
month[8] = "September";
month[9] = "October";
month[10] = "November";
month[11] = "December";
var month_str = month[currentTime.getMonth()];
var year = currentTime.getFullYear()
if(day==1){
    day_str = "Montag";
} else if(day==2){
    day_str = "Dienstag";
} else if(day==3){
    day_str = "Mittwoch";
} else if(day==4){
    day_str = "Dienstag";
} else if(day==5){
    day_str = "Freitag";
} else if(day==6){
    day_str = "Samstag";
} else{
    day_str = "Sonntag";
}



if (minutes < 10){
    minutes = "0" + minutes
}
if (sec < 10){
    sec = "0" + sec
}
var t_str = day_str + ", " + date + " " + month_str + " " + year + " " + hours + ":" + minutes + ":" + sec + " ";
if(hours > 11){
    t_str += "PM";
} else {
   t_str += "AM";
}
 document.getElementById('time_span').innerHTML = t_str;
 setTimeout(timer,1000);
}

</script>