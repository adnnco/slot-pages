(function (blocks) {
    var el = wp.element.createElement;
    blocks.registerBlockType('slot-pages/slot-detail', {
        title: 'Slot Detail',
        icon: 'admin-post',
        category: 'widgets',
        edit: function () {
            return el('p', {}, 'Slot Detail Block – rendered on frontend only.');
        },
        save: function () {
            return null;
        }
    });
})(window.wp.blocks);
