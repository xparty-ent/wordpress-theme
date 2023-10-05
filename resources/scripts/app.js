import domReady from '@roots/sage/client/dom-ready';
import gsap from 'gsap';
import * as THREE from 'three';
import mouse from '@scripts/mouse';
import navbar from '@scripts/navbar';
import preloader from '@scripts/preloader';
import scroll from '@scripts/scroll';
import xp from '@scripts/xp';

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
if (import.meta.webpackHot) import.meta.webpackHot.accept(console.error);

/**
 * Application entrypoint
 */
domReady(async () => {
  mouse.register();
  navbar.register();
  preloader.register();
  scroll.register();
});


/**
 * libraries
 */
window.gsap = gsap;
window.$ = window.jQuery;
window.THREE = THREE;

/**
 * page scripts
 */
window.mouse = mouse;
window.navbar = navbar;
window.preloader = preloader;
window.scroll = scroll;

/**
 * #x-party scripts
 */
window.xp = xp;
