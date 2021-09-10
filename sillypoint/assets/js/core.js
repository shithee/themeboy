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
    })
});