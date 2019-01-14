<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if($this->customer and false){
    $i=0;
    foreach ($this->customer as $c){
       if($c->getCountFactOrder() == 0){
           $c->setBonus(100);
           $c->save();
           echo $i.' : '.$c->getFullname().' - '.$c->getCountFactOrder().'<br>';
           $i++;
       }
       
        
    }
    
}
?>
<div class="main-teaser-long__item--redesign">
<style>
                        .main-teaser-long__item--redesign {
                            position: relative;
                        }

                        .teaser-long {
                            display: block;
                            /*width: 960px;*/
                            height: 135px;
                            position: relative;
                            overflow: hidden;
                            background-color: #000;
                            background-position: center top;
                            background-size: cover;
                            background-repeat: no-repeat;
                        }
                        .html_wide .teaser-long {
                            width: 1206px;
                            height: 170px;
                        }

                        .teaser-long:after {
                            content: '';
                            display: block;
                            width: 100%;
                            height: 100%;
                            background: #000;
                            opacity: 0;
                            transition: opacity .4s ease;
                            position: absolute;
                            top: 0;
                            left: 0;
                            z-index: 1;
                        }

                        .teaser-long__button {
                            display: block;
                            width: 160px;
                            height: 32px;
                            text-align: center;
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            margin: -16px 0 0 -80px;
                            transition: all .2s linear;
                            font: 12px/32px "ProximaNova Light", "Arial";
                            background: #fff;
                            color: #333;
                            opacity: 0;
                            text-decoration: none !important;
                            z-index: 1;
                        }
                        .html_wide .teaser-long__button {
                            width: 200px;
                            height: 40px;
                            margin: -20px 0 0 -100px;
                            font: 14px/40px "ProximaNova Light", "Arial";
                        }

                        .teaser-long__button[data-button-style="white"] {
                            background: #fff;
                            color: #333;
                        }

                        .teaser-long__button[data-button-style="black"] {
                            background: #333;
                            color: #fff;
                        }

                        
                        .teaser-display-other,
                        .teaser-display-retina {
                            position: relative;
                            overflow: hidden;
                            height: inherit;
                        }

                        .teaser-display__img {
                            position: absolute;
                            z-index: 0;
                            width: 100%;
                            height: 100%;
                            background-position: center top;
                            background-size: cover;
                            background-repeat: no-repeat;
                        }

                        .html_phone.html_wide .inViewport .teaser-display-other,
                        .html_tablet.html_wide .inViewport .teaser-display-other,
                        .html_desktop.html_wide .inViewport .teaser-display-other,
                        .html_phone.html_retina .inViewport .teaser-display-other,
                        .html_tablet.html_retina .inViewport .teaser-display-other,
                        .html_desktop.html_retina .inViewport .teaser-display-other,
                        .teaser-display-other,
                        .teaser-display-retina {
                            display: none;
                        }

                        .html_desktop .inViewport .teaser-display-other,
                        .html_tablet .inViewport .teaser-display-other,
                        .html_phone .inViewport .teaser-display-other,
                        .html_wide .inViewport .teaser-display-retina,
                        .html_retina .inViewport .teaser-display-retina {
                            display: block;
                        }
                        

                        .main-teaser-long__item--redesign:hover .teaser-long:after {
                            opacity: .2;
                        }

                        .main-teaser-long__item--redesign:hover .teaser-long__button {
                            opacity: 1;
                        }



                        
                        /* Countdown timer */
                        .countdown {
                            font-family: "ProximaNova Regular", Helvetica, Arial, sans-serif;
                            font-size: 13px;
                            text-align: center;
                            color: #000000;
                            letter-spacing: .015em;
                            white-space: nowrap;

                            -webkit-transform: translate(-50%, -50%);
                            -moz-transform: translate(-50%, -50%);
                            -o-transform: translate(-50%, -50%);
                            transform: translate(-50%, -50%);
                        }

                        .html_wide .countdown {
                            font-size: 16px;
                        }
                        .countdown .countdown__title {
                            display: inline-block;
                            height: 22px;
                            line-height: 22px;
                            font-size: 100%;
                            margin-right: 0;
                        }
                        .countdown .countdown-values {
                            display: inline-block;
                        }
                        .countdown__value {
                            display: inline-block;
                            line-height: 20px;
                            margin-left: 12px;
                            text-align: left;
                            white-space: nowrap;
                            font-size: 100%;
                            position: relative;
                            font-family: inherit !important;
                        }
                        .countdown__value_hidden + .countdown__value {
                            margin-left: 10px;
                        }
                        .countdown__value:after {
                            padding-left: 3px;
                            font-family: inherit;
                        }
                        .countdown__value_days:after {
                            content: 'д'
                        }
                        .countdown__value_hours:after {
                            content: 'ч'
                        }
                        .countdown__value_minutes:after {
                            content: 'м'
                        }
                        .countdown__value_seconds:after {
                            content: 'с';
                            position: absolute;
                            top: 0;
                            right: -8px;
                        }
                        .countdown__value_seconds {
                            display: inline-block;
                            width: 41px;
                        }
                        .countdown__value_hidden {
                            display: none;
                        }
                        .countdown__value:before {
                            content: '';
                            display: inline-block;
                            height: 10px;
                            border-left: 1px solid;
                            margin-right: 16px;
                        }
                        .html_wide .countdown__value:before {
                            height: 13px;
                        }
                        .countdown__value_days:before {
                            border-left: none;
                            margin-right: 0;
                        }
                        .countdown__value:first-child {
                            margin-left: 11px;
                        }
                        .countdown__value:first-child:before,
                        .countdown__value_hidden + .countdown__value:before {
                            display: none;
                        }

                        .lmda-dragger-elem {
                            display: block;
                            width: 0;
                            height: 0;
                            position: absolute;
                            z-index: 3;
                        }
                        
                    </style>
<a class="teaser-long js-lazy-image inViewport" data-version="resizer" href="/bs-click/747355?hit_id=6741506876094047830&amp;slot_name=w_text_long&amp;aim=/bf2018/">
<div class="teaser-display-other">
<div class="teaser-display__img" style="background-image: url(//a.lmcdn.ru/pi/bs1206x170/4/67/4_bf_preview_w_ge.jpg);"></div>
</div>
<div class="teaser-display-retina">
<div class="teaser-display__img" style="background-image: url(//a.lmcdn.ru/bs2/4/67/4_bf_preview_w_ge.jpg);"></div>
</div>
</a>
<a class="teaser-long__button" data-button-style="white" href="/bs-click/747355?">Узнать больше</a>
<div class="lmda-dragger-elem" style="top: 68.79%; left: 35.74%; z-index: 0;">
    <div class="countdown countdown--0" data-server-time="<?=date('Y/m/d H:i:s')?>" data-time-end="2018-11-23T00:00" style="color: #ffffff;">
<div class="countdown__title">До начала осталось:</div>
<div class="countdown-values">
<div class="countdown__value countdown__value_days"></div>
<div class="countdown__value countdown__value_hours"></div>
<div class="countdown__value countdown__value_minutes"></div>
<div class="countdown__value countdown__value_seconds countdown__value_hidden"></div>
</div>
</div>
</div>
<script>
                        (function(){
                            var countdown   = document.querySelector('.countdown');
                            var daysEl      = document.querySelector('.countdown__value_days');
                            var hoursEl     = document.querySelector('.countdown__value_hours');
                            var minEl       = document.querySelector('.countdown__value_minutes');
                            var secEl       = document.querySelector('.countdown__value_seconds');
                            var hiddenClass = 'countdown__value_hidden';
                            var timeEnd     = countdown.dataset.timeEnd;
                            var serverTime  = countdown.dataset.serverTime;

                            timeEnd = timeEnd.replace(/-/g,'/');
                            timeEnd = timeEnd.replace(/t/i, ' ');

                           // serverTime = serverTime.split(' ');
                           // serverTime[1] = serverTime[1].split(':');
                            //serverTime[1][0] = parseInt(serverTime[1][0]) ; // +3 - количество часов по Киевскому времени.
                           // serverTime[1] = serverTime[1].join(':');
                           // serverTime = serverTime.join(' ');

                            var serverTimeCounter = Date.parse(new Date(serverTime));

                            setTime(getTimeRemaining(timeEnd, serverTimeCounter), daysEl, hoursEl, minEl, secEl);

                            var timeInterval = setTimeout(function t() {
                                serverTimeCounter = serverTimeCounter + 1000;

                                var time = getTimeRemaining(timeEnd, serverTimeCounter);

                                setTime(time, daysEl, hoursEl, minEl, secEl);

                                if (time.total <= 0) {
                                    clearTimeout(timeInterval);

                                    return;
                                }

                                timeInterval = setTimeout(t, 1000);

                            }, 1000);

                            function setTime(time, daysEl, hoursEl, minEl, secEl) {
                                manageVisibleBlocks(time, daysEl, secEl);

                                daysEl.innerText  = (time.total <= 0) ? 0 : time.days;
                                hoursEl.innerText = (time.total <= 0) ? 0 : time.hours;
                                minEl.innerText   = (time.total <= 0) ? 0 : time.minutes;
                                secEl.innerText   = (time.total <= 0) ? 0 : ('0' + time.seconds).slice(-2);
                            }

                            function manageVisibleBlocks(time, daysEl, secEl) {
                                if (time.days == 0) {
                                    secEl.classList.remove(hiddenClass);
                                    daysEl.classList.add(hiddenClass);
                                } else {
                                    secEl.classList.add(hiddenClass);
                                }
                            }

                            function getTimeRemaining(endTime, serverTimeCounter) {
                                var t       = Date.parse(endTime) - serverTimeCounter;
                                var seconds = Math.floor( (t/1000) % 60 );
                                var minutes = Math.floor( (t/1000/60) % 60 );
                                var hours   = Math.floor( (t/(1000*60*60)) % 24 );
                                var days    = Math.floor( t/(1000*60*60*24) );
                                return {
                                    'total'  : t,
                                    'days'   : days,
                                    'hours'  : hours,
                                    'minutes': minutes,
                                    'seconds': seconds
                                };
                            }
                        })();                    
                    </script>

</div>
<p id="demo"></p>

<script>
// Set the date we're counting down to
var countDownDate = new Date("2018-11-07 00:00:00").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get todays date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;
// If the count down is finished, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }else{
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  if(days === 0){
      document.getElementById("demo").innerHTML =  hours + "h "
  + minutes + "m " + seconds + "s ";
  }else if(deys == 0 && hours === 0){
  document.getElementById("demo").innerHTML =  minutes + "m " + seconds + "s ";
  }else{
      document.getElementById("demo").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
  }
  }
  
}, 1000);
</script>

