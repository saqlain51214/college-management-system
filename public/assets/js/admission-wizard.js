(function () {
  var form = document.getElementById('admissionWizard');
  if (!form) return;

  function parseJsonScript(id) {
    var el = document.getElementById(id);
    if (!el) return {};

    try {
      return JSON.parse(el.textContent || '{}');
    } catch (e) {
      return {};
    }
  }

  var validationConfig = parseJsonScript('admissionValidationConfig');
  var serverErrors = parseJsonScript('admissionServerErrors');
  var patterns = validationConfig.patterns || {};
  var messages = validationConfig.messages || {};
  var stepFields = validationConfig.step_fields || {};
  var panels = form.querySelectorAll('.adm-panel');
  var pills = document.querySelectorAll('.adm-step-pill');
  var btnBack = document.getElementById('admBack');
  var btnNext = document.getElementById('admNext');
  var btnSubmit = document.getElementById('admSubmit');
  var err = document.getElementById('admFormError');
  var success = document.getElementById('admSuccess');
  var successMessage = success ? success.querySelector('[data-adm-success-message]') : null;
  var entryPath = document.getElementById('entryPath');
  var fsMatric = document.getElementById('admFsMatric');
  var fsHssc = document.getElementById('admFsHssc');
  var wrapInter = document.getElementById('wrapProgInter');
  var wrapUnder = document.getElementById('wrapProgUnder');
  var selInter = document.getElementById('selProgInter');
  var selUnder = document.getElementById('selProgUnder');
  var step = 0;
  var lastStep = panels.length - 1;

  function normalizeFieldName(name) {
    return (name || '').replace(/\[(.*?)\]/g, '.$1');
  }

  function setErr(msg) {
    if (!msg) {
      err.classList.add('hidden');
      err.textContent = '';
      return;
    }

    err.textContent = msg;
    err.classList.remove('hidden');
  }

  function isFieldDisabled(field) {
    if (!field) return true;

    if (field.disabled) return true;

    if (field.matches) {
      return field.matches(':disabled');
    }

    return false;
  }

  function setSectionEnabled(fieldset, isEnabled) {
    if (!fieldset) return;

    fieldset.classList.toggle('hidden', !isEnabled);
    fieldset.disabled = !isEnabled;

    Array.prototype.forEach.call(fieldset.querySelectorAll('input, select, textarea'), function (field) {
      field.disabled = !isEnabled;
      if (!isEnabled && field.name) {
        clearFieldError(field.name);
      }
    });
  }

  function findFieldsByName(name) {
    var normalizedName = normalizeFieldName(name);
    var elements = Array.prototype.slice.call(form.elements || []);
    var matches = elements.filter(function (field) {
      return field && normalizeFieldName(field.name) === normalizedName;
    });
    var activeMatches = matches.filter(function (field) {
      return !isFieldDisabled(field);
    });

    return activeMatches.length ? activeMatches : matches;
  }

  function getContainer(field) {
    if (!field) return null;
    return field.closest ? field.closest('label') || field.parentNode : field.parentNode;
  }

  function ensureFieldMessage(field) {
    var container = getContainer(field);
    if (!container) return null;
    var normalizedName = normalizeFieldName(field.name);

    var message = container.querySelector('.adm-field-error[data-error-for="' + normalizedName + '"]');
    if (message) return message;

    message = document.createElement('p');
    message.className = 'adm-field-error mt-1.5 text-xs font-medium text-red-700';
    message.setAttribute('data-error-for', normalizedName);
    container.appendChild(message);

    return message;
  }

  function clearFieldError(name) {
    var normalizedName = normalizeFieldName(name);
    findFieldsByName(name).forEach(function (field) {
      var container = getVisualFieldContainer(field);

      field.classList.remove('border-red-500', 'bg-red-50/40', 'text-red-900', 'placeholder:text-red-300', 'focus:ring-red-200');
      field.classList.add('border-stone-200');
      if (container && container !== field) {
        container.classList.remove('border-red-500', 'bg-red-50/40');
        container.classList.add('border-stone-200');
      }
      field.removeAttribute('aria-invalid');
      field.setCustomValidity('');

      var messageContainer = getContainer(field);
      if (!messageContainer) return;

      var message = messageContainer.querySelector('.adm-field-error[data-error-for="' + normalizedName + '"]');
      if (message) {
        message.textContent = '';
        message.classList.add('hidden');
      }
    });
  }

  function setFieldError(name, messageText) {
    findFieldsByName(name).forEach(function (field) {
      var container = getVisualFieldContainer(field);

      field.classList.remove('border-stone-200');
      field.classList.add('border-red-500', 'bg-red-50/40', 'text-red-900', 'placeholder:text-red-300', 'focus:ring-red-200');
      if (container && container !== field) {
        container.classList.remove('border-stone-200');
        container.classList.add('border-red-500', 'bg-red-50/40');
      }
      field.setAttribute('aria-invalid', 'true');

      var message = ensureFieldMessage(field);
      if (message) {
        message.textContent = messageText;
        message.classList.remove('hidden');
      }
    });
  }

  function clearAllFieldErrors() {
    var elements = Array.prototype.slice.call(form.elements || []);
    elements.forEach(function (field) {
      if (field && field.name) clearFieldError(field.name);
    });
  }

  function getVisualFieldContainer(field) {
    if (!field || !field.closest) return null;
    return field.closest('.flex.rounded-md.border') || field;
  }

  function stepForField(fieldName) {
    var normalized = normalizeFieldName(fieldName);
    var index;

    for (index in stepFields) {
      if (!Object.prototype.hasOwnProperty.call(stepFields, index)) continue;
      if ((stepFields[index] || []).indexOf(normalized) !== -1) {
        return parseInt(index, 10);
      }
    }

    return 0;
  }

  function firstErrorStep(errors) {
    var keys = Object.keys(errors || {});
    if (!keys.length) return 0;

    return keys.reduce(function (lowest, key) {
      return Math.min(lowest, stepForField(key));
    }, lastStep);
  }

  function firstErrorField(errors) {
    var keys = Object.keys(errors || {});
    if (!keys.length) return null;

    var firstKey = keys[0];
    var fields = findFieldsByName(firstKey);
    return fields.length ? fields[0] : null;
  }

  function lookupMessage(name, suffix, fallback) {
    return messages[normalizeFieldName(name) + '.' + suffix] || fallback;
  }

  function applyPatternValidation(input) {
    var patternKey = input.getAttribute('data-validate-pattern');
    if (!patternKey || !patterns[patternKey]) {
      input.setCustomValidity('');
      return true;
    }

    var value = (input.value || '').trim();
    if (!value) {
      input.setCustomValidity('');
      return true;
    }

    var regex = new RegExp(patterns[patternKey]);
    if (!regex.test(value)) {
      input.setCustomValidity(lookupMessage(input.name, 'regex', 'Please enter a valid value.'));
      return false;
    }

    input.setCustomValidity('');
    return true;
  }

  function clientMessage(input) {
    if (!input || isFieldDisabled(input)) return '';

    applyPatternValidation(input);

    if (input.type === 'checkbox' && input.required && !input.checked) {
      return lookupMessage(input.name, 'accepted', 'Please accept this field.');
    }

    if (input.validity.valueMissing) {
      return lookupMessage(input.name, 'required', 'This field is required.');
    }

    if (input.validity.typeMismatch && input.type === 'email') {
      return lookupMessage(input.name, 'email', 'Please enter a valid email address.');
    }

    if (input.validity.customError) {
      return input.validationMessage || 'Please enter a valid value.';
    }

    if (!input.checkValidity()) {
      return input.validationMessage || 'Please enter a valid value.';
    }

    return '';
  }

  function validatePanelClient(stepIndex) {
    var panel = form.querySelector('[data-adm-step="' + stepIndex + '"]');
    if (!panel) return true;

    var inputs = panel.querySelectorAll('input, select, textarea');
    var firstInvalid = null;

    Array.prototype.forEach.call(inputs, function (input) {
      if (!input.name || isFieldDisabled(input)) return;
      clearFieldError(input.name);

      var messageText = clientMessage(input);
      if (!messageText) return;

      if (!firstInvalid) firstInvalid = input;
      setFieldError(input.name, messageText);
    });

    if (firstInvalid) {
      setErr('Please complete the required fields in this step before continuing.');
      if (firstInvalid.scrollIntoView) {
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      firstInvalid.focus();
      return false;
    }

    return true;
  }

  function validateAllClient() {
    var i;

    for (i = 0; i <= lastStep; i += 1) {
      if (!validatePanelClient(i)) {
        showStep(i);
        setErr('Please fix the highlighted admission form errors and submit again.');
        return false;
      }
    }

    return true;
  }

  function setButtonsBusy(isBusy, submitMode) {
    btnBack.disabled = isBusy;
    btnNext.disabled = isBusy;
    btnSubmit.disabled = isBusy;

    if (submitMode) {
      btnSubmit.textContent = isBusy ? 'Submitting...' : 'Submit application';
    } else {
      btnNext.textContent = isBusy ? 'Checking...' : 'Next step';
    }
  }

  function syncEntryPath() {
    var value = entryPath ? entryPath.value : '';
    var isInter = value === 'intermediate';
    var isUnder = value === 'undergraduate';

    if (wrapInter && selInter) {
      wrapInter.classList.toggle('hidden', !isInter);
      selInter.disabled = !isInter;
      if (isInter) {
        selInter.setAttribute('required', 'required');
      } else {
        selInter.removeAttribute('required');
      }
    }

    if (wrapUnder && selUnder) {
      wrapUnder.classList.toggle('hidden', !isUnder);
      selUnder.disabled = !isUnder;
      if (isUnder) {
        selUnder.setAttribute('required', 'required');
      } else {
        selUnder.removeAttribute('required');
      }
    }

    setSectionEnabled(fsMatric, isInter);
    setSectionEnabled(fsHssc, isUnder);
  }

  function showStep(index) {
    step = index;

    Array.prototype.forEach.call(panels, function (panel, panelIndex) {
      var active = panelIndex === index;
      panel.classList.toggle('hidden', !active);
      panel.hidden = !active;
    });

    Array.prototype.forEach.call(pills, function (pill, pillIndex) {
      var active = pillIndex === index;
      pill.classList.toggle('border-brand', active);
      pill.classList.toggle('bg-brand', active);
      pill.classList.toggle('text-white', active);
      pill.classList.toggle('border-stone-200', !active);
      pill.classList.toggle('bg-white', !active);
      pill.classList.toggle('text-stone-500', !active);
    });

    btnBack.classList.toggle('hidden', index === 0);
    btnNext.classList.toggle('hidden', index === lastStep);
    btnSubmit.classList.toggle('hidden', index !== lastStep);
    syncEntryPath();
  }

  function requestServerValidation(mode, currentStep) {
    var formData = new FormData(form);
    formData.set('validation_mode', mode);

    if (typeof currentStep === 'number') {
      formData.set('current_step', String(currentStep));
    }

    return fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin'
    }).then(function (response) {
      return response.json().catch(function () {
        return {};
      }).then(function (data) {
        if (response.ok) return data;

        if (response.status === 422) {
          throw {
            type: 'validation',
            data: data
          };
        }

        throw {
          type: 'request',
          data: data
        };
      });
    });
  }

  function applyServerErrors(errors, formMessage) {
    clearAllFieldErrors();

    Object.keys(errors || {}).forEach(function (name) {
      var fieldMessages = errors[name] || [];
      if (fieldMessages.length) {
        setFieldError(name, fieldMessages[0]);
      }
    });

    showStep(firstErrorStep(errors));
    setErr(formMessage || 'Please fix the highlighted admission form errors and submit again.');

    var firstField = firstErrorField(errors);
    if (firstField) {
      firstField.focus();
    }
  }

  function showSuccess(message) {
    if (!success) return;

    if (successMessage) {
      successMessage.textContent = message;
    }

    form.classList.add('hidden');
    success.classList.remove('hidden');
    setErr('');
    window.scrollTo({ top: success.offsetTop - 30, behavior: 'smooth' });
  }

  function bindFieldListeners() {
    var elements = Array.prototype.slice.call(form.elements || []);

    elements.forEach(function (field) {
      if (!field || !field.name) return;

      var eventName = field.tagName === 'SELECT' || field.type === 'checkbox' ? 'change' : 'input';

      field.addEventListener(eventName, function () {
        clearFieldError(field.name);
        setErr('');
        if (field === entryPath) {
          syncEntryPath();
          clearFieldError('program_id');
        }

        if (field.getAttribute('data-validate-pattern')) {
          applyPatternValidation(field);
        }
      });

      field.addEventListener('blur', function () {
        var messageText = clientMessage(field);
        if (messageText) {
          setFieldError(field.name, messageText);
        } else {
          clearFieldError(field.name);
        }
      });
    });
  }

  if (entryPath) {
    entryPath.addEventListener('change', function () {
      syncEntryPath();
    });
  }

  ['phone', 'student_phone'].forEach(function (fieldName) {
    var phoneField = form.querySelector('[name="' + fieldName + '"]');
    if (!phoneField) return;

    phoneField.addEventListener('input', function () {
      var value = (phoneField.value || '').replace(/[^\d-]/g, '');

      if (value.indexOf('92') === 0) {
        value = value.slice(2);
      }

      if (value.indexOf('0') === 0) {
        value = value.slice(1);
      }

      phoneField.value = value;
    });
  });

  btnNext.addEventListener('click', function () {
    syncEntryPath();
    setErr('');

    if (!validatePanelClient(step)) return;

    setButtonsBusy(true, false);
    requestServerValidation('step', step)
      .then(function () {
        if (step < lastStep) {
          showStep(step + 1);
        }
      })
      .catch(function (error) {
        if (error.type === 'validation') {
          applyServerErrors(error.data.errors || {}, error.data.message);
          return;
        }

        setErr('We could not validate this step right now. Please try again.');
      })
      .finally(function () {
        setButtonsBusy(false, false);
      });
  });

  btnBack.addEventListener('click', function () {
    setErr('');
    if (step > 0) showStep(step - 1);
  });

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    syncEntryPath();
    setErr('');

    if (!validateAllClient()) return;

    setButtonsBusy(true, true);
    requestServerValidation('submit')
      .then(function (data) {
        showSuccess(data.message || 'Your admission application has been submitted successfully.');
      })
      .catch(function (error) {
        if (error.type === 'validation') {
          applyServerErrors(error.data.errors || {}, error.data.message);
          return;
        }

        setErr('We could not submit your admission application right now. Please try again.');
      })
      .finally(function () {
        setButtonsBusy(false, true);
      });
  });

  bindFieldListeners();
  syncEntryPath();

  if (success && !success.classList.contains('hidden')) {
    form.classList.add('hidden');
    return;
  }

  if (Object.keys(serverErrors || {}).length) {
    applyServerErrors(serverErrors, 'Please fix the highlighted admission form errors and submit again.');
    return;
  }

  showStep(0);
})();
