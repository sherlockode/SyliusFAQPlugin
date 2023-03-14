$(document).ready(function () {
  'use strict';

  let inputValue = $('#sherlockode-faq-resource-order');
  const treeSelector = '#sherlockode-faq-tree';
  const treeSortable = new TreeSortablePlugin({
    treeSelector: treeSelector,
    maxLevel: 2,
  });

  const data = $(treeSelector).data('json');
  const tree = $(treeSelector);
  const content = data.map(treeSortable.createBranch);
  tree.html(content);
  treeSortable.run();

  treeSortable.onSortCompleted(() => {
    inputValue.val(JSON.stringify(tree.sortable('toArray')));
    inputValue.trigger('change');
  });

  inputValue.on('change', function() {
    let form = $(this).closest('form');

    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: form.serialize(),
    });
  });

  $('body').find('[data-requires-confirmation]').requireConfirmation();
  tree.on('click', '.sherlockode-faq-category-show-question', function() {
    $(this).toggleClass('open');
    hideQuestions($(this).closest('li').next());
  });

  function hideQuestions(el) {
    if (false === el.is('li')) {
      return;
    }

    if(el.data('level') === 2) {
      el.toggleClass('hide');
      hideQuestions(el.next());
    }
  }
});
