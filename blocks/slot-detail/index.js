/**
 * Slot Detail Block
 *
 * Displays detailed information about a single slot.
 */

const {registerBlockType} = wp.blocks;
const {Placeholder, Icon} = wp.components;
const {__} = wp.i18n;

/**
 * Register the block
 */
registerBlockType('slot-pages/slot-detail', {
    title: __('Slot Detail', 'slot-pages'),
    icon: 'admin-post',
    category: 'widgets',
    description: __('Displays detailed information about a single slot.', 'slot-pages'),
    keywords: [__('slot', 'slot-pages'), __('detail', 'slot-pages'), __('casino', 'slot-pages')],
    supports: {
        html: false,
        align: ['wide', 'full'],
    },

    /**
     * Edit function
     */
    edit: () => {
        return (
            <Placeholder
                icon={<Icon icon="admin-post"/>}
                label={__('Slot Detail', 'slot-pages')}
                instructions={__('Displays detailed information about the current slot. This block is rendered on the frontend and should be used on single slot pages.', 'slot-pages')}
            />
        );
    },

    /**
     * Save function (null for server-side rendering)
     */
    save: () => {
        return null;
    }
});
