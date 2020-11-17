<template>
    <div>
        <a @mouseover="setActive"
           :class="{ 'active' : isActive }"
           :href="$store.state.catUrl+cat.slug">{{ cat.name }}<i v-if="isIssetChilds & !isMobile" class="fa fa-angle-right"></i></a>

        <div v-if="isActive && !isMobile && isIssetChilds" class="expandCat__level2 child_cat">
            <div class="cat_title">{{ cat.name }}</div>
            <a v-for="child in childs" :href="$store.state.catUrl+child.slug">{{ child.name }}</a>
        </div>

    </div>
</template>
<script>
    module.exports = {
        name: 'ExpandCatItem',
        props: ['cat', 'isMobile'],
        data(){
            return {
                empty : false
            }
        },
        computed: {
            isActive() {
                return this.$store.state.catLevelOneActive == this.cat.id
            },
            childs() {
                let _res = _(this.$store.state.catalog)
                .chain()
                .filter( item => { return item.parent == this.cat.id; });
                if (this.cat.id != 42) { _res = _res.sortBy('name') }
                return _res.value();
            },
            isIssetChilds() {
                return this.childs.length > 0;
            },
        },
        methods: {
            setActive() {
                this.$store.commit('setExpandMenuActiveCat', this.cat.id);
            },
        },
    }
</script>
