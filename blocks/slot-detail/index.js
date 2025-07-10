(function (blocks, editor, components, data) {
    const {registerBlockType} = blocks;
    const {InspectorControls} = editor;
    const {PanelBody, SelectControl} = components;
    const {useSelect} = data;
    const __ = wp.i18n.__;

    registerBlockType('slot-pages/slot-detail', {
        title: 'Slot Detail',
        icon: 'admin-post',
        category: 'widgets',
        attributes: {
            selectedSlot: {
                type: 'number',
                default: 0
            }
        },
        edit: function (props) {
            const {attributes, setAttributes} = props;
            const {selectedSlot} = attributes;

            // Fetch all slots
            const slots = useSelect(function (select) {
                return select('core').getEntityRecords('postType', 'slot', { per_page: -1 });
            }, []);

            // Create options for select control
            const slotOptions = [];
            if (slots) {
                slotOptions.push({
                    label: __('Select a slot', 'slot-pages'),
                    value: 0
                });

                slots.forEach(function(slot) {
                    slotOptions.push({
                        label: slot.title.rendered,
                        value: slot.id
                    });
                });
            }

            return [
                wp.element.createElement(
                    InspectorControls,
                    null,
                    wp.element.createElement(
                        PanelBody,
                        {title: __('Slot Selection', 'slot-pages')},
                        wp.element.createElement(SelectControl, {
                            label: __('Select Slot', 'slot-pages'),
                            value: selectedSlot,
                            options: slotOptions,
                            onChange: (val) => setAttributes({selectedSlot: parseInt(val)})
                        })
                    )
                ),
                wp.element.createElement(
                    'div',
                    {className: 'slot-detail-block-editor'},
                    wp.element.createElement(
                        'p',
                        {},
                        selectedSlot > 0
                            ? __('Slot Detail Block – Displaying slot: ', 'slot-pages') +
                            (slots ? slots.find(s => s.id === selectedSlot)?.title.rendered : '')
                            : __('Slot Detail Block – Please select a slot in the block settings.', 'slot-pages')
                    )
                )
            ];
        },
        save: function () {
            return null;
        }
    });
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.data);