(function () {
  var track = document.getElementById('testimonialTrack');
  var prevBtn = document.getElementById('testimonialPrev');
  var nextBtn = document.getElementById('testimonialNext');
  var dotsRoot = document.getElementById('testimonialDots');
  if (!track || !prevBtn || !nextBtn || !dotsRoot) return;

  var n = 4;
  var idx = 0;
  var dotBase =
    'h-2 rounded-full transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 ';

  function slide() {
    var pct = (100 / n) * idx;
    track.style.transform = 'translateX(-' + pct + '%)';
  }

  function syncDots() {
    var dots = dotsRoot.querySelectorAll('button');
    for (var i = 0; i < dots.length; i++) {
      dots[i].className = dotBase + (i === idx ? 'w-6 bg-brand' : 'w-2 bg-stone-300');
      dots[i].setAttribute('aria-selected', i === idx ? 'true' : 'false');
    }
  }

  for (var i = 0; i < n; i++) {
    (function (j) {
      var b = document.createElement('button');
      b.type = 'button';
      b.setAttribute('role', 'tab');
      b.setAttribute('aria-label', 'Slide ' + (j + 1));
      b.addEventListener('click', function () {
        idx = j;
        slide();
        syncDots();
      });
      dotsRoot.appendChild(b);
    })(i);
  }

  prevBtn.addEventListener('click', function () {
    idx = (idx - 1 + n) % n;
    slide();
    syncDots();
  });
  nextBtn.addEventListener('click', function () {
    idx = (idx + 1) % n;
    slide();
    syncDots();
  });

  slide();
  syncDots();
})();
