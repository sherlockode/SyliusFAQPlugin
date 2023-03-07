$(document).ready(function () {
  'use strict';

  let inputValue = $('#sherlockode-faq-resource-order');
  const treeSelector = '#sherlockode-faq-category-tree';
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
});
