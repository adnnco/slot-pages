/**
 * Slots Grid Block
 *
 * Displays a grid of slot posts with configurable options.
 */

const {registerBlockType} = wp.blocks;
const {InspectorControls} = wp.blockEditor;
const {PanelBody, RangeControl, SelectControl, Placeholder, Icon} = wp.components;
const {__} = wp.i18n;

/**
 * Register the block
 */
registerBlockType('slot-pages/slots-grid', {
    title: __('Slots Grid', 'slot-pages'),
    icon: 'grid-view',
    category: 'widgets',
    description: __('Displays a grid of slot posts with options for limit and sorting.', 'slot-pages'),
    keywords: [__('slots', 'slot-pages'), __('grid', 'slot-pages'), __('casino', 'slot-pages')],
    supports: {
        html: false,
        align: ['wide', 'full'],
    },
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

    /**
     * Edit function
     */
    edit: ({attributes, setAttributes}) => {
        const {limit, columns, order} = attributes;

        return [
            <InspectorControls key="inspector">
                <PanelBody title={__('Grid Settings', 'slot-pages')}>
                    <RangeControl
                        label={__('Number of Slots', 'slot-pages')}
                        value={limit}
                        onChange={(value) => setAttributes({limit: value})}
                        min={1}
                        max={20}
                    />
                    <RangeControl
                        label={__('Columns', 'slot-pages')}
                        value={columns}
                        onChange={(value) => setAttributes({columns: value})}
                        min={1}
                        max={6}
                        help={__('Number of columns in the grid', 'slot-pages')}
                    />
                    <SelectControl
                        label={__('Sorting', 'slot-pages')}
                        value={order}
                        options={[
                            {label: __('Recent', 'slot-pages'), value: 'recent'},
                            {label: __('Random', 'slot-pages'), value: 'random'}
                        ]}
                        onChange={(value) => setAttributes({order: value})}
                    />
                </PanelBody>
            </InspectorControls>,
            <Placeholder
                key="placeholder"
                icon={<Icon icon="grid-view"/>}
                label={__('Slots Grid', 'slot-pages')}
                instructions={__('Displays a grid of slot posts. This block is rendered on the frontend.', 'slot-pages')}
            >
                <div style={{marginTop: '1em'}}>
                    <p>
                        <strong>{__('Current Settings:', 'slot-pages')}</strong>
                    </p>
                    <ul style={{margin: '0.5em 0 0 1em', listStyle: 'disc'}}>
                        <li>{__('Limit:', 'slot-pages')} {limit}</li>
                        <li>{__('Columns:', 'slot-pages')} {columns}</li>
                        <li>{__('Order:', 'slot-pages')} {order === 'recent' ? __('Recent', 'slot-pages') : __('Random', 'slot-pages')}</li>
                    </ul>
                </div>
            </Placeholder>
        ];
    },

    /**
     * Save function (null for server-side rendering)
     */
    save: () => {
        return null;
    }
});
