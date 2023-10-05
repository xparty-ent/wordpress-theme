@extends('layouts.app')

@section('page-content')
<div class="home-container">
    <div class="renderer"></div>
    <section class="tile main active">
        <div class="main-headline">
            {!! $heading !!}
        </div>
        <div class="down-arrow"></div>
    </section>
    <section class="tile middle">
    </section>
    <section class="tile last">
    </section>
</div>
@endsection

@push('post-app-script')
<script type="module">
(function() {
    const sceneTimeline = gsap.timeline();
    const modelTimeline = gsap.timeline();
    const torusTimeline = gsap.timeline();

    var tile1Animation = (scene, model, torus) => {
        sceneTimeline.clear();
        modelTimeline.clear();
        torusTimeline.clear();

        gsap.to(scene.camera.position, {
            z: 300,
            duration: 5,
            ease: 'back',
            onUpdate: event => {
                scene.camera.lookAt(0, 0, 0);
            }
        });

        gsap.to(torus.position, {
            x: 0,
            y: 0,
            z: 0,
            duration: 2.5,
            ease: 'sin'
        });

        torusTimeline.to(torus.rotation, {
            x: 2 * Math.PI,
            y: 2 * Math.PI,
            duration: 30,
            repeat: -1,
            ease: 'none'
        });

        gsap.to(model.gltf.scene.position, {
            x: 0,
            y: 0,
            z: 0,
            duration: 1,
            ease: 'sin'
        });

        gsap.to(model.gltf.scene.rotation, {
            y: 1,
            duration: 2.5,
            ease: 'sin'
        });

        modelTimeline.to(model.gltf.scene.rotation, {
            x: 2 * Math.PI,
            y: 2 * Math.PI,
            z: 2 * Math.PI,
            duration: 10,
            repeat: -1,
            ease: 'none'
        });

        
        torusTimeline.play();
        modelTimeline.play();
        sceneTimeline.play();
    }

    var tile2Animation = (scene, model, torus) => {
        sceneTimeline.clear();
        modelTimeline.clear();
        torusTimeline.clear();

        const posX = scene.getWorldSize().x / 2;


        gsap.to(model.gltf.scene.rotation, {
            x: 0,
            y: 0,
            z: 0,
            duration: 2.5,
            ease: 'back'
        });

        modelTimeline.to(model.gltf.scene.position, {
            x: posX,
            duration: 2.5,
            ease: 'back'
        });
        
        modelTimeline.to(model.gltf.scene.rotation, {
            y: 2 * Math.PI,
            duration: 10,
            repeat: -1,
            ease: 'none'
        });

        gsap.to(torus.rotation, {
            x: 0,
            y: 1.55,
            z: 0,
            duration: 2.5,
            ease: 'back'
        });

        torusTimeline.to(torus.position, {
            x: posX,
            duration: 2.5,
            ease: 'back'
        });
        
        torusTimeline.to(torus.rotation, {
            y: 0,
            duration: 2.5,
            ease: 'back'
        });

        torusTimeline.to(torus.rotation, {
            z: 2 * Math.PI,
            duration: 30,
            repeat: -1,
            ease: 'sin'
        });

        torusTimeline.play();
        modelTimeline.play();
        sceneTimeline.play();
    }

    var createTorus = () => {
        const geometry = new THREE.TorusGeometry(1.5, 0.1, 13, 137, Math.PI * 2)
        const material = new THREE.PointsMaterial( { size: 0.5, color: 0x212121 } ); 
        const torus = new THREE.Points( geometry, material );
        scene.scene.add(torus);
        return torus;
    }

    var nextTile = () => {
        const tiles = $('.tile');

        if(tiles.length < 2)
            return null;

        for(let i = 0; i < tiles.length - 1; i++)
            if($(tiles[i]).hasClass('active'))
                return $(tiles[i + 1]);

        return null;
    }

    var prevTile = () => {
        const tiles = $('.tile');

        if(tiles.length < 2)
            return null;

        for(let i = 1; i < tiles.length; i++)
            if($(tiles[i]).hasClass('active'))
                return $(tiles[i - 1]);

        return null;
    }

    var onWheel = (event) => {
        
    }

    var onTileChanging = (tile) => {
        if(tile.hasClass('main')) {
            tile1Animation(window.scene, window.model, window.torus);
        } else if(tile.hasClass('middle')) {
            tile2Animation(window.scene, window.model, window.torus);
        }
    }

    var onScrollNext = (event) => {
        const state = {
            top: $(window).scrollTop()
        };

        const next = nextTile();

        if(!next) return;
        $('.tile.active').removeClass('active');

        onTileChanging(next);

        gsap.to(state, {
            top: next.position().top,
            duration: 1.5,
            ease: 'ease-in',
            onUpdate: e => {
                $(window).scrollTop(state.top);
            },
            onComplete: e => {
                next.addClass('active');
            }
        })
    }
    

    var onScrollPrev = (event) => {
        const state = {
            top: $(window).scrollTop()
        };

        const next = prevTile();

        if(!next) return;
        
        $('.tile.active').removeClass('active');
        onTileChanging(next);
        
        gsap.to(state, {
            top: next.position().top - next.offset().top,
            duration: 1.5,
            ease: 'ease-in',
            onUpdate: e => {
                $(window).scrollTop(state.top);
            },
            onComplete: e => {
                next.addClass('active');
            }
        })
    }


    var init = () => {
        window.scroll.disable();
        $(window).on('wheel', event => onWheel(event));
        $(window).on('scroll-next', event => onScrollNext(event));
        $(window).on('scroll-prev', event => onScrollPrev(event));
        
        const scene = xp.renderer.create($('.renderer'));
        window.scene = scene;



        scene.addModel("{{ asset('/models/xp-icosphere.glb') }}")
            .then(model => {
                model.gltf.scene.scale.x /= 2;
                model.gltf.scene.scale.y /= 2;
                model.gltf.scene.scale.z /= 2;

                model.gltf.scene.traverse(object => {
                    if(!object.material) return;
                    object.material.emissive.r = 0x21 / 255;
                    object.material.emissive.g = 0x21 / 255;
                    object.material.emissive.b = 0x21 / 255;
                });

                window.model = model;
                window.torus = createTorus();
                $(window).scrollTop(0);
                tile1Animation(scene, model, torus);
            });
    }

    $(window).on('load', () => init());
}());
</script>
@endpush