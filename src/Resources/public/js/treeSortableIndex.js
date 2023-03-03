$(document).ready(function () {
  'use strict';

  const treeSelector = '#sherlockode-faq-category-tree';
  const leftSortable = new TreeSortablePlugin({
    treeSelector: treeSelector,
    maxLevel: 2,
  });

  const data = $(treeSelector).data('json');
  const leftTree = $(treeSelector);
  const content = data.map(leftSortable.createBranch);
  leftTree.html(content);
  leftSortable.run();
});
