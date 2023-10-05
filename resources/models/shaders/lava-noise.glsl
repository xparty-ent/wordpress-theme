// Degree of symmetry.
const int K = 5;

// Wave frequency.
const int NUM_STRIPES = 10;

// Period in seconds.
const float PERIOD = 3.0;

// Whether to use polar or cartesian coordinates.
const bool POLAR = false;

// The slow phase shift results in apparent motion (even though
// the animation is periodic over time), which can be compensated by shifting
// the pattern. Because quasicrystals are aperiodic for degrees of symmetry
// other than 2, 3, 4, and 6 (the ones for which the place can be periodically
// tiled), motion compensation means the center of rotational symmetry moves
// over time, so it is only enabled by default for polar coordinates, where
// the apparent motion is a rotation and zoom.
const bool MOTION_COMPENSATION_IN_CARTESIAN = false;

// Zoom effect when using polar coordinates.
// 1.0 zooms in, -1.0 zooms out, 0.0 stays still.
const float POLAR_ZOOM = 0.0;

// Scaling factor for r in polar coordinates.
const float POLAR_SCALING = 1.0;

// Scaling factor for cartesian coordinates.
const float CARTESIAN_SCALING = 3.0;

// Use sawtooth instead of cosine waves.
const bool SAWTOOTH = false;

// Plot contours instead of absolute values.
const bool CONTOUR = false;

// Line width when using contours.
const float CONTOUR_WIDTH = 16.;

// Color the values with a radial rainbow gradient.
const bool RAINBOW_COLOR = false;

// If not using rainbow colors, use this base color.
const vec3 BASE_COLOR = vec3(1.0, 0.0, 1.0);

const float PI = 4.0 * atan(1.0);

float smoothe(float x) {
    return x * x * (3.0 - 2.0 * x);
}

vec3 smoothe(vec3 x) {
    return x * x * (3.0 - 2.0 * x);
}

vec3 hsv2rgbSmooth(float hue, float saturation, float value) {
	vec3 rgb = clamp(abs(mod(hue*6.0+vec3(0.0,4.0,2.0),6.0)-3.0)-1.0, 0.0, 1.0);

	return value * mix(vec3(1.0), smoothe(rgb), clamp(saturation, 0.0, 1.0));
}

float roundToFraction(float x, int frac) {
    return floor(x * float(frac) + 0.5) / float(frac);
}

float arg(vec2 z) {
    return atan(z.y, z.x);
}

float sawtooth(float x) {
    return 2. * abs(mod(x / PI, 2.) - 1.) - 1.;
}

uniform float iTime;

void main() {
	vec2 xy = gl_FragCoord.xy;
	float phase = 2.0 * PI * iTime / PERIOD;

    float k = float(K);

    vec2 coords;
    float scale;
    if (POLAR) {
        scale = POLAR_SCALING;
        float theta = arg(xy);
        float r = log(dot(xy, xy)) * scale;
        coords = vec2(theta, r);
    } else {
        scale = CARTESIAN_SCALING * 2.0;
    	coords = xy * scale;
    }
	coords *= float(NUM_STRIPES);
    
    // Compensate for apparent motion.
    if (POLAR || MOTION_COMPENSATION_IN_CARTESIAN) {
        float motionCompensation = phase;
        coords.x -= motionCompensation / k * 2.;

        if (POLAR) {
            // Adjust the compensation for the y coordinate to give a
            // zoom effect if desired. If POLAR_ZOOM is -1, the compensation is
            // set to zero, which results in the natural apparent motion
            // (zooming out).
            motionCompensation *= POLAR_ZOOM + 1.0;
        }
        coords.y -= motionCompensation;
    }

	float c = 0.0;
    vec2 g;
    if (SAWTOOTH) {
        g = vec2(1);
    } else {
        g = vec2(0);
    }
	for (int t = 0; t < K; t++) {
		float tScaled = PI*float(t)/k;
        vec2 omega = vec2(cos(tScaled), sin(tScaled));
        if (POLAR) {
            // When using polar coordinates, round to the nearest fraction of
            // NUM_STRIPES to ensure the wave has a period of 2 pi.
            omega.x = roundToFraction(omega.x, NUM_STRIPES);
        }
        float p = dot(coords, omega) + phase;
        if (SAWTOOTH) {
			c += sawtooth(p);
        } else {
            c += cos(p);
            g -= omega * sin(p);
        }
	}
    
	float val;
    if (CONTOUR) {
        float d = abs(c) / length(g);
        val = 1. - smoothstep(0., CONTOUR_WIDTH * scale, d);
    } else {
        val = smoothe((c+k)/(k*2.0));
    }
   
    vec3 color;
    if (RAINBOW_COLOR) {
        color = hsv2rgbSmooth(arg(xy) / (2. * PI), length(xy) / 25., val);
    } else {
    	color = BASE_COLOR * val;
    }
	gl_FragColor = vec4(color, 1.0);
}