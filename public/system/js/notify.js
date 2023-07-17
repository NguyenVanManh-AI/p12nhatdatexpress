function playNotify(src){
    var sound =new Howl({
        src: [src],
        autoplay: true,
        loop: false,
        volume: 0.8
    });
    sound.play();
}
