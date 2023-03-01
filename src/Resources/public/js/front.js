window.addEventListener('load', function () {
  'use strict';

  let btnCategories = document.querySelectorAll('.sherlockode-faq-btn-category');

  btnCategories.forEach(btn => {
    btn.addEventListener('click', () => displayCategory(btn));
  });

  document.querySelectorAll('.sherlockode-faq-btn-collapse').forEach(btn => {
    btn.addEventListener('click', function (e) {
      let section = document.querySelector('#' + btn.getAttribute('aria-controls'));
      let isCollapsed = section.getAttribute('data-collapsed') === 'true';

      if (isCollapsed) {
        expandSection(section);
        section.setAttribute('data-collapsed', 'false');
        btn.setAttribute('aria-expanded', 'true');
      } else {
        collapseSection(section);
        btn.setAttribute('aria-expanded', 'false');
      }
    });
  });

  document.querySelector('.sherlockode-faq-category-mobile').addEventListener('click', () => {
    document.body.classList.add('sherlockode-faq-modal-active');
  });

  let closeModalBtn = document.querySelector('.sherlockode-faq-modal-btn-close');
  closeModalBtn.addEventListener('click', (e) => {
    closeModal();
  });

  function closeModal() {
    let timer = closeModalBtn.getAttribute('data-animationdelay');

    if (!isNaN(timer)) {
      document.body.classList.add('closing');

      setTimeout(() => {
        document.body.classList.remove('closing', 'sherlockode-faq-modal-active');
      }, timer);
    } else {
      document.body.classList.remove('sherlockode-faq-modal-active');
    }
  }

  function displayCategory(btn) {
    let items = document.querySelectorAll('.sherlockode-category-question-items');
    let categories = document.querySelectorAll('.sherlockode-faq-btn-category');
    let btns = document.querySelectorAll('.sherlockode-faq-btn-category[data-target="' + btn.dataset.target + '"]');

    document.querySelector('.sherlockode-faq-category-mobile').innerHTML = btn.innerHTML;
    closeModal();

    categories.forEach(item => {
      item.classList.remove('active');
    });

    btns.forEach(btn => {
      btn.classList.add('active');
    });

    items.forEach(item => {
      item.style.display = 'none';
    });

    document.querySelector(btn.dataset.target).style.display = 'block';
  }

  function collapseSection(element) {
    let sectionHeight = element.scrollHeight;
    let elementTransition = element.style.transition;

    element.style.transition = '';
    requestAnimationFrame(function() {
      element.style.height = sectionHeight + 'px';
      element.style.transition = elementTransition;

      requestAnimationFrame(function() {
        element.style.height = 0 + 'px';
      });
    });

    element.setAttribute('data-collapsed', 'true');
  }

  function expandSection(element) {
    let sectionHeight = element.scrollHeight;
    let onTransitionEnd = () => {
      element.removeEventListener('transitionend', onTransitionEnd);
      element.style.height = null;
    };

    element.style.height = sectionHeight + 'px';
    element.addEventListener('transitionend', onTransitionEnd);
    element.setAttribute('data-collapsed', 'false');
  }
});
