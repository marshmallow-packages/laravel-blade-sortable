<script>
    const laravelBladeSortable = () => {
        return {
            sortOrder: [],
            sortOrderSet: [],
            animation: 150,
            ghostClass: '',
            dragHandle: null,
            group: null,

            wireComponent: null,
            wireOnSortOrderChange: null,
            wireOnSortOrderRemove: null,
            wireOnSortOrderAdd: null,

            init() {
                this.sortOrder = [].slice.call(this.$refs.root.children)
                    .map(child => child.dataset.sortKey)
                    .filter( function(e) { return e })
                    .filter(sortKey => sortKey)
                this.sortOrderSet = [].slice.call(this.$refs.root.children)
                    .map(child => child.dataset.sortKey)
                    .filter( function(e) { return e })
                    .filter(sortKey => sortKey)

                window.Sortable.create(this.$refs.root, {
                    sort: this.group == 'selected',
                    handle: this.dragHandle,
                    animation: this.animation,
                    ghostClass: this.ghostClass,
                    group: {
                        name: this.group,
                        put: function (to, from) {
                            return from.options.group.name == 'selected' || to.options.group.name == 'selected';
                        },
                        pull: function (to, from) {
                            return from.options.group.name == 'selected' || to.options.group.name == 'selected';
                        },
                    },
                    onEnd: evt => {
                        if (!this.wireComponent) {
                            return;
                        }

                        if(!this.wireOnSortOrderChange) {
                            return;
                        }

                        this.sortOrder = [].slice.call(evt.from.children)
                            .map(child => child.dataset.sortKey)
                            .filter(sortKey => sortKey);

                        this.wireComponent.call(this.wireOnSortOrderChange, this.sortOrder)
                    },
                    onRemove: evt => {
                        if (!this.wireComponent) {
                            return;
                        }

                        if(!this.wireOnSortOrderRemove) {
                            return;
                        }

                        this.sortOrder = [].slice.call(evt.from.children)
                            .map(child => child.dataset.sortKey)
                            .filter(sortKey => sortKey);

                        this.wireComponent.call(this.wireOnSortOrderRemove, this.sortOrder)
                    },
                    onAdd: evt => {
                        if (!this.wireComponent) {
                            return
                        }

                        if(!this.wireOnSortOrderAdd) {
                            return
                        }

                        let slices = [].slice.call(evt.to.children)
                                        .filter( function(e) { return e })
                                        .map(child => child.dataset.sortKey)
                                        .filter(sortKey => sortKey);
                        slices = slices.filter(function(value, index){ return slices.indexOf(value) == index });

                        this.sortOrderSet = slices;

                        this.wireComponent.call(this.wireOnSortOrderAdd, this.sortOrderSet)
                    }
                });
            }
        }
    }
</script>
