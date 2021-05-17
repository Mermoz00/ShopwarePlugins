(function () {
    let breite= document.getElementById("summary").clientWidth;
    document.getElementById("box").style.width=breite+'px';
    document.getElementById("box").style.height=(screen.height/2)+'px';
})(jQuery, window);
