<template>
    <div class="expandCat__level2_wrap">
        <a :class="{ active: isActiveS }" :href="$store.state.catUrl + cat.slug"
            >{{ cat.name
            }}<i v-if="isIssetChilds & !isMobile" class="fa fa-angle-right"></i
        ></a>

        <div
            v-if="!isMobile && isIssetChilds"
            class="expandCat__level2 expandCat__level3 child_cat"
        >
            <div class="cat_title">{{ cat.name }}</div>
            <a
                v-for="child in childs"
                :href="$store.state.catUrl + child.slug"
                :key="child.id"
                >{{ child.name }}</a
            >
        </div>
    </div>
</template>
<script>
module.exports = {
    name: "ExpandCatSecond",
    props: ["cat", "isMobile"],
    data() {
        return {
            componentKey: 0,
        };
    },
    computed: {
        isActiveS() {
            return this.$store.state.catLevelSecondActive == this.cat.id;
        },
        childs() {
            let _res = _(this.$store.state.catalog)
                .chain()
                .filter((item) => {
                    return item.parent == this.cat.id;
                });
            if (this.cat.id != 42) {
                _res = _res.sortBy("name");
            }
            return _res.value();
        },
        isIssetChilds() {
            return this.childs.length > 0;
        },
    },
    methods: {},
};
</script>
