const scroll = {
    _scrollKeys: [
        32, // spacebar
        33, // page up
        34, // page down
        35, // end
        36, // home
        37, // left
        38, // up 
        39, // right
        40, // down
    ],

    _supportPassive: false,

    _scrollThreshold: 600,

    _scrollValue: 0,

    _clearTimeout: null,

    _touches: {},

    _preventScrollKeys(event) {
        if(this._scrollKeys.indexOf(event.keyCode) < 0)
            return;
        
        event.preventDefault();
        return false;
    },

    _onTouchStart(event) {
        for(const touch of event.touches) {
            this._touches[touch.identifier] = touch;
            this._touches[touch.identifier].xp = {
                lastX: touch.screenX,
                lastY: touch.screenY
            };
        }
    },

    _onTouchEnd(event) {
        for(const touch of event.changedTouches) {
            delete this._touches[touch.identifier];
        }
    },

    _onScroll(delta) {
        if(!this._clearTimeout)
            clearTimeout(this._clearTimeout);

        setTimeout(() => {
            $(window).trigger('scroll-trigger-reset');
            this._scrollValue = 0;
        }, 1000);

        if(this._scrollValue > 0 && delta < 0) {
            this._scrollValue = 0;
            return;
        } else if(this._scrollValue < 0 && delta > 0) {
            this._scrollValue = 0;
            return;
        }

        this._scrollValue += delta;

        if(Math.abs(this._scrollValue) > this._scrollThreshold) {
            if(this._scrollValue > 0) {
                $(window).trigger('scroll-next');
            } else {
                $(window).trigger('scroll-prev');
            }
            this._scrollValue = 0;
        }

        console.log(this._scrollValue);
    },

    _onTouchMove(event) {
        for(const touch of event.changedTouches) {
            if(!this._touches.hasOwnProperty(touch.identifier))
                continue;

            const deltaX = this._touches[touch.identifier].xp.lastX - touch.screenX;
            const deltaY = this._touches[touch.identifier].xp.lastY - touch.screenY;
            
            this._onScroll(deltaY);
        }
    },

    _onMouseWheel(event) {
        const delta = event.originalEvent.deltaY;

        this._onScroll(delta);
    },

    register() {
        try {
            window.addEventListener('test', 
                null,
                Object.defineProperty({}, 'passive', {
                    get: () => { 
                        this._supportPassive = true;
                    } 
                })
            );
        } catch(e) {
            console.log(e);
        }

        $(window).on('wheel mousewheel', event => this._onMouseWheel(event));
        
        $(window).on('touchstart', event => this._onTouchStart(event));
        $(window).on('touchend', event => this._onTouchEnd(event));
        $(window).on('touchmove', event => this._onTouchMove(event));
    },

    disable() {
        window.addEventListener('DOMMouseScroll', 
            event => event.preventDefault(), 
            false
        );

        window.addEventListener('wheel', 
            event => event.preventDefault(), 
            this._supportPassive ? { passive: false } : false
        );

        window.addEventListener('mousewheel', 
            event => event.preventDefault(), 
            this._supportPassive ? { passive: false } : false
        );
        
        window.addEventListener('touchmove', 
            event => event.preventDefault(), 
            this._supportPassive ? { passive: false } : false
        );
        
        window.addEventListener('keydown', 
            event => this._preventScrollKeys(event), 
            this._supportPassive ? { passive: false } : false
        );

        $('body').css({
            overflow: 'hidden'
        });
    },

    enable() {
        window.removeEventListener('DOMMouseScroll', 
            event => event.preventDefault(), 
            false
        );

        window.removeEventListener('wheel', 
            event => event.preventDefault(), 
            this._supportPassive ? { passive: false } : false
        );

        window.removeEventListener('mousewheel', 
            event => event.preventDefault(), 
            this._supportPassive ? { passive: false } : false
        );
        
        window.removeEventListener('touchmove', 
            event => event.preventDefault(), 
            this._supportPassive ? { passive: false } : false
        );
        
        window.removeEventListener('keydown', 
            event => this._preventScrollKeys(event), 
            this._supportPassive ? { passive: false } : false
        );

        
        $('body').css({
            overflow: 'unset'
        });
    }
};

export default scroll;