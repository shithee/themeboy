$(document).ready(function() {
    var i = 0;
    var height = (window.innerHeight - 50);
    $('.image-container').on('click', function() {
        i = i - 1;
        i = (i < 1) ? 0 : i;
        let css = `transform : translateY(${i * -1 * height}px);`;
        $('#home').attr('style', css);
    })
    $('.post-wrapper').on('click', function() {
        i = i + 1;
        i = (i > 3) ? 0 : i;
        let css = `transform : translateY(${i * -1 * height}px);`;
        $('#home').attr('style', css);
    })
    $('.header').click(function() {
        $('#home').toggle();
        $('#menu').toggle();
    })
    $('.darkmode').change(function() {
        (this.checked) ? $('body').addClass('theme-dark'): $('body').removeClass('theme-dark');
    });
    $('.btn1').click(function(){
        let cont = $('.maincontainer');
        let el = cont.find('section.active');
        el = (!el.length || el.hasClass('footer')) ? cont.find('section').first() : el.next(); 
        cont.find('section').not('.footer').hide().removeClass('active');
        el.addClass('active').show();
    });
    $('.btn3').click(function(){
        let el = $('body');
        el.removeClass('themedark');
        $(this).hide().siblings('.btn2').show();
    });
    $('.btn2').click(function(){
        let el = $('body');
        el.addClass('themedark');
        $(this).hide().siblings('.btn3').show();
    });
    $('.btn4').click(function(){
        let el = $('body');
        el.removeClass('themegrid');
        $(this).hide().siblings('.btn5').show();
    });
    $('.btn5').click(function(){
        let el = $('body');
        el.addClass('themegrid');
        $(this).hide().siblings('.btn4').show();
    });
});
// Preloaders.net for gif