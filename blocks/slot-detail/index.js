(function (blocks, element, components, data, blockEditor) {
    var el = element.createElement;
    var useSelect = data.useSelect;
    var SelectControl = components.SelectControl;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var __ = wp.i18n.__;

    blocks.registerBlockType('slot-pages/slot-detail', {
        title: 'Slot Detail',
        icon: 'admin-post',
        category: 'widgets',
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            // Fetch all slots
            var slots = useSelect(function (select) {
                return select('core').getEntityRecords('postType', 'slot', { per_page: -1 });
            }, []);

            // Create options for select control
            var slotOptions = [];
            if (slots) {
                slotOptions = slots.map(function (slot) {
                    return {
                        label: slot.title.rendered,
                        value: slot.id
                    };
                });

                // Add a default option
                slotOptions.unshift({
                    label: __('Select a slot', 'slot-pages'),
                    value: 0
                });
            }

            return [
                // Inspector controls for the sidebar
                el(InspectorControls, { key: 'inspector' },
                    el(PanelBody, {
                        title: __('Slot Selection', 'slot-pages'),
                        initialOpen: true
                    },
                    el(SelectControl, {
                        label: __('Select Slot', 'slot-pages'),
                        value: attributes.selectedSlot,
                        options: slotOptions,
                        onChange: function (newSlot) {
                            setAttributes({ selectedSlot: parseInt(newSlot) });
                        }
                    })
                    )
                ),
                // Block preview in editor
                el('div', { className: 'slot-detail-block-editor' },
                    el('p', {}, attributes.selectedSlot > 0 
                        ? __('Slot Detail Block – Displaying slot: ', 'slot-pages') + 
                          (slots ? slots.find(s => s.id === attributes.selectedSlot)?.title.rendered : '')
                        : __('Slot Detail Block – Please select a slot in the block settings.', 'slot-pages')
                    )
                )
            ];
        },
        save: function () {
            return null;
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.data,
    window.wp.blockEditor
);
