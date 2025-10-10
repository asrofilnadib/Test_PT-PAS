'use strict';
const formAuthentication = document.querySelector('#formAuthentication');

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    if (formAuthentication) {
      const fv = FormValidation.formValidation(formAuthentication, {
        fields: {
          username: {
            validators: {
              notEmpty: {
                message: 'Please enter your username'
              },
              stringLength: {
                min: 1,
                message: 'Username must be at least 3 characters'
              }
            }
          },
          password: {
            validators: {
              notEmpty: {
                message: 'Please enter your password'
              },
              stringLength: {
                min: 1,
                message: 'Password must be at least 6 characters'
              }
            }
          }
        },
        plugins: {
          // Trigger validation events
          trigger: new FormValidation.plugins.Trigger(),

          // Integrasi dengan Bootstrap 5
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.mb-3'
          }),

          // Biarkan tombol submit aktif setelah valid
          submitButton: new FormValidation.plugins.SubmitButton(),

          // Kalau valid, kirim form ke server Laravel
          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),

          // Fokus otomatis ke field invalid
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }
  })();
});
