/*=========================================================================================
  File Name: footer.js
  Description: Template footer js.
  ----------------------------------------------------------------------------------------
  Item Name: Frest HTML Admin Template
 Version: 1.0
  Author: Pixinvent
  Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/

//Check to see if the window is top if not then display button
$(document).ready(function(){
    $(window).scroll(function(){
        if ($(this).scrollTop() > 400) {
            $('.scroll-top').fadeIn();
        } else {
            $('.scroll-top').fadeOut();
        }
    });

    //Click event to scroll to top
    $('.scroll-top').click(function(){
        $('html, body').animate({scrollTop : 0},1000);
    });

    createCaptcha()

});


function createCaptcha() {
    const canvas = document.getElementById('captcha-div')
    
    if (canvas) {
        if (canvas.children.length > 0) canvas.innerHTML= ''

        // const charsArray = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
        const charsArray = 'ABCDEFGHKLMNPRSTUVWYZ23456789'
        const lengthOtp = 6
        let captcha = []
    
        for (let i = 0; i < lengthOtp; i++) {
            let index = Math.floor(Math.random() * charsArray.length + 1)
            if (captcha.indexOf(charsArray[index]) == -1)
                captcha.push(charsArray[index])
            else i--
        }
    
        const canv = document.createElement('canvas')
        canv.id = 'captcha'
        canv.width = canvas.clientWidth
        canv.height = 45
        /*canv.dir = 'ltr'*/
    
        let ctx = canv.getContext('2d')
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'center'
        ctx.font = 'normal bold 32px serif'
        ctx.fillText(captcha.join(''), (canvas.clientWidth / 2), 25)
    
        document.getElementById("captcha-div").appendChild(canv);
    }
}