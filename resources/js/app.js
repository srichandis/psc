import Alpine from 'alpinejs';

/**
 * Bulk row selection for the admin tables and card grids.
 *
 * Selection is page-local: `ids` is the collection of record ids currently
 * rendered, so "select all" never reaches rows the user cannot see.
 *
 * Selected ids are mirrored into hidden inputs inside the bulk action form by
 * the view, which is how they reach the server.
 */
Alpine.data('bulkSelection', ({ ids = [] } = {}) => ({
    ids,
    selected: [],

    get count() {
        return this.selected.length;
    },

    get allSelected() {
        return this.ids.length > 0 && this.selected.length === this.ids.length;
    },

    /** True when a partial selection is active, for the header checkbox state. */
    get someSelected() {
        return this.selected.length > 0 && ! this.allSelected;
    },

    isSelected(id) {
        return this.selected.includes(id);
    },

    toggle(id) {
        this.isSelected(id) ? this.deselect(id) : this.selected.push(id);
    },

    deselect(id) {
        this.selected.splice(this.selected.indexOf(id), 1);
    },

    toggleAll(checked) {
        this.selected = checked ? [...this.ids] : [];
    },

    clear() {
        this.selected = [];
    },
}));

/**
 * Repeatable content blocks for the specialist profile page.
 *
 * Each block becomes one headed section on the public profile. Blocks are
 * submitted as `profile_sections[index][heading|type|items]`, so the array
 * order in the browser is the render order on the page.
 */
Alpine.data('profileSections', (initial = []) => ({
    blocks: [],
    nextUid: 0,

    init() {
        this.blocks = initial.map((block, index) => ({
            uid: index,
            heading: block.heading ?? '',
            type: block.type ?? 'list',
            items: block.items ?? '',
        }));

        this.nextUid = this.blocks.length;
    },

    add() {
        this.blocks.push({ uid: this.nextUid++, heading: '', type: 'list', items: '' });
    },

    remove(index) {
        this.blocks.splice(index, 1);
    },

    move(index, direction) {
        const target = index + direction;

        if (target < 0 || target >= this.blocks.length) {
            return;
        }

        const [block] = this.blocks.splice(index, 1);
        this.blocks.splice(target, 0, block);
    },
}));

window.Alpine = Alpine;

Alpine.start();
