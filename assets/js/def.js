function createTippy(element, options) {
    return new Promise(resolve => {
        tippy(element, Object.assign({}, {
            allowHTML: true,
            interactive: true,
            animation: 'scale',
            theme: 'light',
        }, options));
        resolve();
    });
}

function cardCarousel(object) {
    return $('#card-carousel').not('.slick-initialized').slick(object);
}
