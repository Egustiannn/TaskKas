/* TaskKas — objek chrome silver (Three.js). Varian: auth | dash | hero */
(function () {
  'use strict';
  var canvas = document.getElementById('scene');
  if (!canvas) return;

  function hasWebGL() {
    try { var c = document.createElement('canvas'); return !!(c.getContext('webgl') || c.getContext('experimental-webgl')); }
    catch (e) { return false; }
  }
  if (!window.THREE || !hasWebGL()) { document.body.classList.add('no-webgl'); return; }

  var THREE = window.THREE;
  var variant = canvas.dataset.variant || 'auth';
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- renderer (HD, retina-aware) ---------- */
  var renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true, powerPreference: 'high-performance' });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
  renderer.outputEncoding = THREE.sRGBEncoding;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.05;
  renderer.setClearColor(0x000000, 0);

  var scene = new THREE.Scene();
  var camera = new THREE.PerspectiveCamera(32, 1, 0.1, 100);
  camera.position.z = 12;

  /* ---------- environment studio (pantulan chrome) ---------- */
  function buildEnv() {
    var s = new THREE.Scene();
    s.add(new THREE.Mesh(new THREE.BoxGeometry(24, 24, 24), new THREE.MeshBasicMaterial({ color: 0x040405, side: THREE.BackSide })));
    function panel(w, h, pos, k, hex) {
      var col = new THREE.Color(hex || 0xffffff).multiplyScalar(k);
      var m = new THREE.Mesh(new THREE.PlaneGeometry(w, h), new THREE.MeshBasicMaterial({ color: col, side: THREE.DoubleSide }));
      m.position.set(pos[0], pos[1], pos[2]); m.lookAt(0, 0, 0); s.add(m);
    }
    panel(10, 3, [0, 9, 2], 7);                 // softbox atas
    panel(3, 10, [-9, 0, 1], 4.5);              // softbox kiri
    panel(3, 9, [9, 1, -2], 3.5, 0xcfe0ff);     // aksen dingin kanan
    panel(8, 2, [0, -8, 4], 2.2, 0xffd9b8);     // pantulan hangat bawah
    panel(12, 1.4, [0, 2, -10], 6);             // strip belakang
    panel(2, 7, [7, -2, 7], 2.6, 0xff7a1a);     // aksen oranye (senada tab aktif)
    var pm = new THREE.PMREMGenerator(renderer);
    var tex = pm.fromScene(s, 0.02).texture;
    pm.dispose();
    return tex;
  }
  scene.environment = buildEnv();

  /* ---------- material ---------- */
  function chrome(rough) {
    return new THREE.MeshStandardMaterial({ color: 0xffffff, metalness: 1, roughness: rough == null ? 0.07 : rough, envMapIntensity: 1.25 });
  }

  // Material "cairan": bola dideformasi di vertex shader (halus, 60fps) + normal dihitung ulang
  var GLSL_FN = [
    'uniform float uTime; uniform float uAmp; uniform float uFreq;',
    'vec3 disp(vec3 p){',
    '  vec3 q = p * uFreq;',
    '  float n = sin(q.x*1.7 + uTime*0.55) * sin(q.y*1.9 - uTime*0.45) * sin(q.z*1.5 + uTime*0.5);',
    '  n += 0.55 * sin(q.x*3.1 - uTime*0.6 + q.y*2.3) * sin(q.z*2.9 + uTime*0.35 + q.x);',
    '  n += 0.25 * sin(q.y*5.3 + uTime*0.8 + q.z*3.1);',
    '  return p * (1.0 + uAmp * n);',
    '}'
  ].join('\n');
  var GLSL_NORMAL = [
    'vec3 n0 = normalize(position);',
    'vec3 upv = abs(n0.y) > 0.99 ? vec3(1.0,0.0,0.0) : vec3(0.0,1.0,0.0);',
    'vec3 t1 = normalize(cross(n0, upv));',
    'vec3 t2 = cross(n0, t1);',
    'float eps = 0.012;',
    'vec3 p0 = disp(n0);',
    'vec3 pa = disp(normalize(n0 + t1*eps));',
    'vec3 pb = disp(normalize(n0 + t2*eps));',
    'vec3 objectNormal = normalize(cross(pa - p0, pb - p0));',
    '#ifdef USE_TANGENT',
    '  vec3 objectTangent = vec3(tangent.xyz);',
    '#endif'
  ].join('\n');

  function liquid(amp, freq, rough) {
    var u = { uTime: { value: 0 }, uAmp: { value: amp }, uFreq: { value: freq } };
    var mat = chrome(rough);
    mat.onBeforeCompile = function (sh) {
      sh.uniforms.uTime = u.uTime; sh.uniforms.uAmp = u.uAmp; sh.uniforms.uFreq = u.uFreq;
      sh.vertexShader = sh.vertexShader
        .replace('#include <common>', '#include <common>\n' + GLSL_FN)
        .replace('#include <beginnormal_vertex>', GLSL_NORMAL)
        .replace('#include <begin_vertex>', 'vec3 transformed = disp(normalize(position));');
    };
    mat.userData.u = u;
    return mat;
  }

  /* ---------- objek per varian ---------- */
  var items = [];
  function add(obj, cfg) { cfg.obj = obj; cfg.base = new THREE.Vector3(); scene.add(obj); items.push(cfg); return cfg; }

  function makeKnot(p, q) { return new THREE.Mesh(new THREE.TorusKnotGeometry(1, 0.36, 360, 72, p, q), chrome(0.06)); }
  function makeBlob(amp, freq) { return new THREE.Mesh(new THREE.IcosahedronGeometry(1, 42), liquid(amp, freq, 0.07)); }
  function makeRing() { return new THREE.Mesh(new THREE.TorusGeometry(1, 0.3, 140, 280), chrome(0.05)); }
  function makeOrbs() {
    var g = new THREE.Group(), m = chrome(0.04), r = [0.42, 0.26, 0.16];
    r.forEach(function (rad) { var s = new THREE.Mesh(new THREE.SphereGeometry(rad, 72, 72), m); g.add(s); });
    return g;
  }

  if (variant === 'auth') {
    // pojok kanan-atas: pita terpilin | pojok kiri-bawah: blob cair  (seperti referensi login)
    add(makeKnot(2, 3), { fx: 0.34, fy: 0.30, z: 0.0, size: 0.20, depth: 1.0, kind: 'knot' });
    add(makeBlob(0.20, 1.0), { fx: -0.37, fy: -0.33, z: 0.6, size: 0.27, depth: 1.5, kind: 'blob' });
  } else if (variant === 'dash') {
    // dashboard: cincin berputar + blob bergelombang + tiga bola mengorbit
    add(makeRing(), { fx: 0.40, fy: 0.34, z: -1.0, size: 0.15, depth: 0.8, kind: 'ring' });
    add(makeBlob(0.30, 1.35), { fx: -0.43, fy: -0.30, z: -1.0, size: 0.20, depth: 1.2, kind: 'blob2' });
    add(makeOrbs(), { fx: 0.36, fy: -0.34, z: -0.5, size: 0.17, depth: 1.6, kind: 'orbs' });
  } else { // hero (landing)
    add(makeKnot(2, 3), { fx: 0.24, fy: 0.02, z: 0.0, size: 0.30, depth: 1.0, kind: 'knot' });
    add(makeBlob(0.22, 1.1), { fx: -0.44, fy: -0.38, z: -0.6, size: 0.20, depth: 1.4, kind: 'blob' });
    add(makeOrbs(), { fx: 0.42, fy: -0.30, z: 0.8, size: 0.13, depth: 1.8, kind: 'orbs' });
  }

  /* ---------- ukuran / posisi responsif ---------- */
  var W = 1, H = 1, vw = 1, vh = 1;
  function layout() {
    var m = Math.min(W, H * 1.15), small = vw < 700 ? 0.7 : 1;
    items.forEach(function (it) {
      it.base.set(W * it.fx * (vw < 700 ? 0.85 : 1), H * it.fy, it.z);
      it.s = m * it.size * small;
      it.obj.scale.setScalar(it.s);
    });
  }
  function resize() {
    vw = window.innerWidth; vh = window.innerHeight;
    renderer.setSize(vw, vh, false);
    camera.aspect = vw / vh; camera.updateProjectionMatrix();
    H = 2 * Math.tan(THREE.MathUtils.degToRad(camera.fov / 2)) * camera.position.z;
    W = H * camera.aspect;
    layout();
  }
  window.addEventListener('resize', resize);
  resize();

  /* ---------- interaksi: parallax mouse (di-damping) ---------- */
  var tx = 0, ty = 0, mx = 0, my = 0;
  window.addEventListener('pointermove', function (e) {
    tx = (e.clientX / vw) * 2 - 1; ty = (e.clientY / vh) * 2 - 1;
  }, { passive: true });

  /* ---------- kontrol putar/jeda + progress bar ---------- */
  var running = !reduceMotion, DUR = 30;
  var btn = document.getElementById('animToggle'), bar = document.getElementById('animBar');
  function syncBtn() { if (btn) btn.classList.toggle('paused', !running); }
  if (btn) btn.addEventListener('click', function () { running = !running; syncBtn(); });
  syncBtn();

  /* ---------- animasi ---------- */
  var t = 0, clock = new THREE.Clock();
  var sin = Math.sin, cos = Math.cos;

  function animate(it) {
    var o = it.obj, k = it.kind;
    var px = it.base.x + mx * 0.38 * it.depth, py = it.base.y - my * 0.26 * it.depth;
    if (k === 'knot') {
      o.rotation.set(0.6 + t * 0.16, t * 0.21, sin(t * 0.3) * 0.35);
      o.position.set(px + sin(t * 0.5) * 0.14, py + cos(t * 0.42) * 0.18, it.base.z);
    } else if (k === 'blob' || k === 'blob2') {
      o.material.userData.u.uTime.value = t * (k === 'blob2' ? 1.25 : 1);
      o.rotation.set(t * 0.05, t * 0.08, 0);
      o.position.set(px + sin(t * 0.35) * 0.12, py + cos(t * 0.3) * 0.16, it.base.z);
    } else if (k === 'ring') {
      o.rotation.set(t * 0.28 + 0.9, t * 0.36, t * 0.1);
      o.position.set(px + sin(t * 0.4) * 0.18, py + cos(t * 0.5) * 0.12, it.base.z);
    } else if (k === 'orbs') {
      o.position.set(px, py, it.base.z);
      o.children.forEach(function (c, i) {
        var a = t * (0.55 + i * 0.22) + i * 2.1, r = 1.2 + i * 0.55;
        c.position.set(cos(a) * r, sin(a * 1.3) * r * 0.55, sin(a) * r * 0.6);
      });
    }
  }

  function frame() {
    requestAnimationFrame(frame);
    var dt = Math.min(clock.getDelta(), 0.05);
    if (running) t += dt;
    var f = 1 - Math.pow(0.001, dt);            // damping independen FPS
    mx += (tx - mx) * f; my += (ty - my) * f;
    for (var i = 0; i < items.length; i++) animate(items[i]);
    if (bar) bar.style.width = ((t % DUR) / DUR * 100).toFixed(2) + '%';
    renderer.render(scene, camera);
  }
  frame();
})();
