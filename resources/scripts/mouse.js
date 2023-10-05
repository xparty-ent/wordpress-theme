import gsap from 'gsap';

const mouse = {
    register: () => {
        const mouseElement = $('.mouse').first();
        const mouseOuterElement = $('.mouse-outer').first();

        $(document).on('mousemove', event => {
            const scroll = $(window).scrollTop();
            const position = {
                x: event.clientX,
                y: event.clientY + scroll,
            };

            const outerPosition = {
                x: event.clientX,
                y: event.clientY + scroll,
            }

            mouseElement.css({
                left: position.x,
                top: position.y
            });

            gsap.to(mouseOuterElement, {
                left: outerPosition.x,
                top: outerPosition.y,
                duration: 0.5,
                ease: 'ease-in'
            });
        });
    }
};

export default mouse;