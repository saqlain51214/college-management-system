(function () {
  var form = document.getElementById('admissionForm');
  if (!form) return;
  var step = 1;
  var total = 4;
  var labels = ['Programme & session', 'Applicant details', 'Guardian & emergency', 'Academic record & uploads'];
  var stepIndicator = document.getElementById('stepIndicator');
  var bar = document.getElementById('stepProgressBar');
  var btnPrev = document.getElementById('admBtnPrev');
  var btnNext = document.getElementById('admBtnNext');
  var btnSubmit = document.getElementById('admBtnSubmit');
  var errEl = document.getElementById('admFormError');
  var successEl = document.getElementById('admSuccess');
  var refEl = document.getElementById('admRefNo');

  function showErr(msg) {
    errEl.textContent = msg;
    errEl.classList.remove('hidden');
  }
  function clearErr() {
    errEl.classList.add('hidden');
    errEl.textContent = '';
  }

  function updateUI() {
    for (var i = 1; i <= total; i++) {
      var pane = document.getElementById('adm-step-' + i);
      if (pane) pane.classList.toggle('hidden', i !== step);
    }
    stepIndicator.textContent = 'Step ' + step + ' of 4 — ' + labels[step - 1];
    bar.style.width = (100 * step) / total + '%';
    btnPrev.classList.toggle('hidden', step === 1);
    btnNext.classList.toggle('hidden', step === total);
    btnSubmit.classList.toggle('hidden', step !== total);
  }

  function validatePane(n) {
    var pane = document.getElementById('adm-step-' + n);
    if (!pane) return true;
    var fields = pane.querySelectorAll('.adm-req');
    for (var i = 0; i < fields.length; i++) {
      var el = fields[i];
      var v = (el.value || '').trim();
      if (!v) {
        el.focus();
        showErr('Please complete all required fields in this step.');
        return false;
      }
      if (el.type === 'email' && el.value && el.validity && !el.validity.valid) {
        el.focus();
        showErr('Enter a valid email address.');
        return false;
      }
    }
    clearErr();
    return true;
  }

  btnPrev.addEventListener('click', function () {
    if (step > 1) {
      step--;
      updateUI();
      clearErr();
    }
  });

  btnNext.addEventListener('click', function () {
    if (validatePane(step) && step < total) {
      step++;
      updateUI();
    }
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (!validatePane(4)) return;
    if (
      !document.getElementById('chk_declare_truth').checked ||
      !document.getElementById('chk_agree_rules').checked
    ) {
      showErr('Please accept both declarations to submit.');
      return;
    }
    for (var s = 1; s <= total; s++) {
      if (!validatePane(s)) return;
    }
    clearErr();
    var ref =
      'MS-' +
      new Date().getFullYear() +
      '-' +
      Math.random().toString(36).substring(2, 8).toUpperCase();
    refEl.textContent = ref;
    form.classList.add('hidden');
    successEl.classList.remove('hidden');
    successEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

  updateUI();
})();
