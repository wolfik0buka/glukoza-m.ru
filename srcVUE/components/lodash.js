__.increase = function(a,b) { return a+a*b/100};
__.decrease = function(a,b) { return a-a*b/100};
__.percentof=function(a,b) { return ( parseInt(a) / parseInt(b) ) * 100 };
__.parseDateTime=function(date){ return fecha.parse(date, 'YYYY-MM-DD HH:mm:ss'); };
__.parseDate=function(date){ return fecha.parse(date, 'YYYY-MM-DD 00:00:00'); };
__.parseDateFromMonth=function(month){ return fecha.parse(month, 'MM'); };
__.nowDateTime=function(){ return fecha.format(new Date(), 'YYYY-MM-DD HH:mm:ss'); };
__.dateTime=function(date){ return fecha.format(date, 'YYYY-MM-DD HH:mm:ss'); };

__.humanDateTime = function (datetime, format) {
    return fecha.format(__.parseDateTime(datetime), format);
};

__.wordForm = function(number, titles) {
    cases = [2, 0, 1, 1, 1, 2];
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
}


// MEDIA QUERIES

__.isMediaTn = function() { return window.matchMedia('all and (max-width:359px)').matches; }
__.isMediaXS = function() { return window.matchMedia('all and (max-width: 479px)').matches; }
__.isMediaSM = function() { return window.matchMedia('all and (max-width: 767px)').matches; }
__.isMediaMD = function() { return window.matchMedia('all and (max-width: 1059px)').matches; }
__.isMediaLG = function() { return window.matchMedia('all and (min-width: 1060px)').matches; }


__.fixBody = function() { document.body.style.overflow = 'hidden' }
__.unFixBody = function() { document.body.style.overflow = '' }

__.copyTextToClipboard=function(text) {
    var textArea = document.createElement("textarea");
    textArea.style.position = 'fixed';
    textArea.style.top = 0;
    textArea.style.left = 0;
    textArea.style.width = '2em';
    textArea.style.height = '2em';
    textArea.style.padding = 0;
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';
    textArea.style.background = 'transparent';
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.select();
    try {
        var successful = document.execCommand('copy');
        var msg = successful ? 'successful' : 'unsuccessful';
        console.log('Copying text command was ' + msg);
    } catch (err) {
        console.log('Oops, unable to copy');
    }
    document.body.removeChild(textArea);
};


__.track = {
    time: 0,
    start: function(name){
        this.time = performance.now();
    },
    stop: function(name){
        console.log(performance.now() - this.time);
    }
};