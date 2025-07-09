(function (blocks, editor, components) {
    const {registerBlockType} = blocks;
    const {InspectorControls} = editor;
    const {PanelBody, RangeControl, SelectControl} = components;

    registerBlockType('slot-pages/slots-grid', {
        title: 'Slots Grid',
        icon: 'grid-view',
        category: 'widgets',
        attributes: {
            limit: {
                type: 'number',
                default: 6
            },
            columns: {
                type: 'number',
                default: 2
            },
            order: {
                type: 'string',
                default: 'recent'
            }
        },
        edit: function (props) {
            const {attributes, setAttributes} = props;
            const {limit, columns, order} = attributes;

            return [
                wp.element.createElement(
                    InspectorControls,
                    null,
                    wp.element.createElement(
                        PanelBody,
                        {title: 'Grid Settings'},
                        wp.element.createElement(RangeControl, {
                            label: 'Number of Slots',
                            value: limit,
                            onChange: (val) => setAttributes({limit: val}),
                            min: 1,
                            max: 20
                        }),
                        wp.element.createElement(RangeControl, {
                            label: 'Columns',
                            value: columns,
                            onChange: (val) => setAttributes({columns: val}),
                            min: 1,
                            max: 6,
                            help: 'Number of columns in the grid'
                        }),
                        wp.element.createElement(SelectControl, {
                            label: 'Sorting',
                            value: order,
                            options: [
                                {label: 'Recent', value: 'recent'},
                                {label: 'Random', value: 'random'}
                            ],
                            onChange: (val) => setAttributes({order: val})
                        })
                    )
                ),
                wp.element.createElement('p', {}, 'Slots Grid Block – rendered on frontend.')
            ];
        },
        save: function () {
            return null;
        }
    });
})(window.wp.blocks, window.wp.blockEditor, window.wp.components);