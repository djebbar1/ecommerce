document
  .querySelectorAll('.dropdown-menu .dropdown-toggle[href="#"]')
  .forEach(function (element) {
    element.addEventListener("click", function (event) {
      event.stopPropagation();
    });
  });
