<div class="alert alert-{{ $type }} alert-dismissible fade show" id="alert" role="alert">
  {{ $message }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<script>
  function removeAlertElement(alertElement) {
    alertElement.addEventListener('transitionend', () => {
      alertElement.remove();
    });
    alertElement.classList.remove('show');
  }

  setTimeout(function () {
    const alertElement = document.getElementById('alert');
    if (alertElement) {
      removeAlertElement(alertElement);
    }
  }, 3000);

  document.querySelector('.btn-close').addEventListener('click', function () {
    const alertElement = document.getElementById('alert');
    if (alertElement) {
      removeAlertElement(alertElement);
    }
  });
</script>
