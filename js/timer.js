function getTimeRemaining(endtime) {
  var t = Date.parse(endtime) - Date.parse(new Date());
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  var days = Math.floor(t / (1000 * 60 * 60 * 24));
  return {
    'total': t,
    'days': days,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}
/**
 * Блок в который нужно вставить таймер, должен быть с id
 * @param {type} id - элемента в который записать таймер
 * @param {type} endtime - до какой даты таймер. (пример 2018-01-01 00:00:00)
 * @returns {Завершена или таймер}
 */
function initializeClock(id, endtime) {
  var clock = document.getElementById(id);
  function updateClock() {
    var t = getTimeRemaining(endtime);
    if (t.total <= 0) {
      clearInterval(timeinterval);
      clock.innerHTML = '<span class="end_time text-uppercase ">Завершена</span>';
    }else{
        var m = '';
        if(t.days > 0) { m = m+'<span class="days btn btn-secondary btn-sm">'+t.days + ' д </span> : ';}
        if(t.hours > 0) { m= m+'<span class="hours btn btn-secondary btn-sm">' + ('0' + t.hours).slice(-2) + ' ч </span> : ';}
        if(true) {m= m+'<span class="minutes btn btn-secondary btn-sm">' + ('0' + t.minutes).slice(-2) + ' м </span> : '; }
        if(true){ m= m+'<span class="seconds btn btn-secondary btn-sm">' + ('0' + t.seconds).slice(-2) + ' с </span>';}
        
    clock.innerHTML = m;
}
  }
  updateClock();
  var timeinterval = setInterval(updateClock, 1000);
}
function initializeClockNewYear(id, endtime) {
  var clock = document.getElementById(id);
  function updateClock() {
    var t = getTimeRemaining(endtime);
    if (t.total <= 0) {
      clearInterval(timeinterval);
      clock.innerHTML = '<span class="end_time text-uppercase ">Уже Новый год!</span>';
    }else{
       var m = '';
        if(t.days > 0) { m = m+'<span class="days btn btn-secondary btn-sm">'+t.days + ' д </span> : ';}
        if(t.hours > 0) { m= m+'<span class="hours btn btn-secondary btn-sm">' + ('0' + t.hours).slice(-2) + ' ч </span> : ';}
        if(true) {m= m+'<span class="minutes btn btn-secondary btn-sm">' + ('0' + t.minutes).slice(-2) + ' м </span> : '; }
        if(true){ m= m+'<span class="seconds btn btn-secondary btn-sm">' + ('0' + t.seconds).slice(-2) + ' с </span>';}
       // m = m+'</div>';
    clock.innerHTML = m;
}
  }
  updateClock();
  var timeinterval = setInterval(updateClock, 1000);
}

