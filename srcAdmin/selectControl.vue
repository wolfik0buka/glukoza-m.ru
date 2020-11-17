<template>
    <div v-on-clickaway="close" class="relative width-100" :class="{open: show}">
        <label v-if="label" class="form-label">{{ label }}</label>
        <div class="form-select"
             :class="{ 'form-select-borderless': borderless }"
             @click="toggle">

            <div v-if="multiple">
                <span class="multiple_item" v-if=selected v-for="(selectedItem, index) in selected">
                    {{ selectedItem.name }}
                    <i @click.prevent="removeFromMultiple(index)" class="delete_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                            <path d="M0 0h24v24H0z" fill="none"/>
                        </svg>
                    </i>
                </span>
                <span v-if="!selected">
                    <span v-if=danger class="text-danger">
                        <i class="fa right-3 fa-exclamation-circle"></i> {{ placeholder }}
                    </span>
                    <span v-if=!danger >{{ placeholder }}</span>
                </span>
            </div>


            <div v-if="!multiple">
                <span v-if=selected>{{ selected.name }}</span>
                <span v-if=!selected>
                    <span v-if=danger class="text-danger">
                        <i class="fa right-3 fa-exclamation-circle"></i> {{ placeholder }}
                    </span>
                    <span v-if=!danger>{{ placeholder }}</span>
                </span>
            </div>

            <span class="dropdown-caret">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28px" height="28px" viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve">
                    <path d="M7.41,8.59L12,13.17l4.59-4.58L18,10l-6,6l-6-6L7.41,8.59z"/>
                    <path fill="none" d="M0,0h24v24H0V0z"/>
                </svg>
            </span>
        </div>

        <ul class="form-select-dropdown">
            <div class="form-select-dropdown__search">
                <input type="text" v-model="searchQuery" placeholder="Поиск..">
            </div>
            <!--<li @click="clear" class="dropdown-item">Очистить</li>-->
            <template  v-for="option in getChildsOfDropdownItem({id:1})">
                <li v-if="optionVisible(option)" @click="select(option)" class="dropdown-item">
                    {{ option.name }}
                </li>
                <template v-if="nested & optionVisible(suboption)" v-for="suboption in getChildsOfDropdownItem(option)">
                    <li @click="select(suboption)" class="dropdown-item sub">{{ suboption.name }}</li>
                    <template v-if="nested & optionVisible(subsuboption)" v-for="subsuboption in getChildsOfDropdownItem(suboption)">
                        <li @click="select(subsuboption)" class="dropdown-item subsub">{{ subsuboption.name }}</li>
                    </template>
                </template>
            </template>
        </ul>
    </div>
</template>

<script>
    module.exports = {
        name: 'formSelect',
        props: [
            'label',
            'value',
            'placeholder',
            'options',
            'danger',
            'borderless',
            'multiple',
            'except',
            'nested'
        ],
        data() {
            return {
                show: false,
                selected: false,
                searchQuery: ''
            }
        },
        watch: {
            searchQuery(val, oldVal) {
                this.$emit('searchQueryChanged', val)
            }
        },
        computed: {
            exceptedIds() {
                let exceptedIds = []
                _.each(this.except, item => {
                    _.isArray(item)
                        ? _.each(item, subitem => { exceptedIds.push(subitem) })
                        : exceptedIds.push(item)
                })
                return exceptedIds
            },
        },
        methods: {
            toggle() {
                if (this.show) {
                    this.show = false
                } else {
                    this.open()
                    this.focusInput()
                }
            },
            open() {
                this.show = true
                setTimeout(function() {
                    $('ul.form-select-dropdown').scrollTop(0)
                }, 100)
            },
            close() {
                this.show = false
            },
            clear() {
                this.show = false
                this.selected = false
                this.$emit('clear')
            },
            select(option) {
                this.show = false
                this.multiple ? this.selected.push(option) : this.selected = option
                this.$emit('selected', this.selected)
            },
            getOptionById(id) {
                return _.find(this.options, {id:id})
            },
            removeFromMultiple(index) {
                this.selected.splice(index, 1)
                this.show = false
                this.$emit('selected', this.selected)
            },
            focusInput() {
                this.$el.querySelector("input").focus()
            },
            getChildsOfDropdownItem(item) {
                if (this.nested) {
                    return _.filter(this.options, option => {
                        return parseInt(option.parent) === parseInt(item.id)
                    })
                } else {
                    return this.options
                }
            },
            optionVisible(option) {
                let passFilter = true

                if (option.name && this.searchQuery) {
                    passFilter = option.name.toLowerCase().indexOf(this.searchQuery.toLowerCase()) !== -1
                }

                if (passFilter && this.except) {
                    passFilter = this.exceptedIds.indexOf(option.id) !== -1
                }

                if (passFilter && this.selected) {
                    passFilter = _.findIndex(this.selected, { id: option.id }) !== 0
                }

                let childOptions = this.getChildsOfDropdownItem(option)

                if (!passFilter && childOptions.length && this.searchQuery) {
                    passFilter = _.filter(childOptions, childOption => {
                        return childOption.name.toLowerCase().indexOf(this.searchQuery.toLowerCase()) !== -1
                    }).length > 0
                }

                return passFilter
            },
        },
        beforeMount() {
            if (this.value === 0 || this.value) {
                if (this.multiple) {
                    this.selected = []
                    this.value.forEach( item => { this.selected.push(this.getOptionById(item)) })
                } else {
                    this.selected = typeof this.value == "object"
                        ? this.getOptionById(this.value.id)
                        : this.getOptionById(this.value)
                }
            }
        }
    };
</script>


<style lang="scss">

    $color-border-card: #dedede;
    $formElementBorderColor: #dedede;
    $formElementBgActive: #f4f4f4;
    $formSelectHeight: 37px;
    $formSelectPadding: 5px 30px 5px 10px;
    $brand-danger: #ff0000;
    $text-color: #222;
    $color-text-muted: #999;

    .form-label{
        font-size: 14px;
        color: $color-text-muted;
        margin-bottom: 2px;
    }
    .form-select {
        width: 100%;
        display: block;
        min-height: $formSelectHeight;
        padding: $formSelectPadding;
        line-height: 130%;
        border: 1px solid $color-border-card;
        position: relative;
        background: white;
        font-size: 16px;
        border-radius: 3px;
        z-index: 20;
        transition: all 0.12s linear;
        .multiple_item{
            font-size: 14px;
            background: #e3e3e3;
            display: inline-block;
            vertical-align: top;
            line-height: 20px;
            padding: 2px 5px 2px 7px;
            border-radius: 3px;
            margin: 7px 7px 0 0;
            &:first-of-type{
                margin-left: -5px;
            }
            .delete_icon{
                margin: 1px 0 0 -1px;
                font-size: 13px;
                opacity: .5;
                svg{
                    height: 13px;
                    width: 13px;
                }
                &:hover{
                    color: $brand-danger;
                    opacity: 1;
                }
            }
        }

        .dropdown-caret {
            position: absolute;
            right: 10px;
            top: 5px;
            font-size: 20px;
            color: $text-color;
            transition: all 0.12s linear;
        }
        &:hover {
            border: 1px solid $formElementBorderColor;
            cursor: pointer;
        }
    }

    .form-select-borderless{
        border-color: transparent;
        .dropdown-caret{
            color: transparent;
        }
        &:hover{
            .dropdown-caret{
                color: $text-color;
            }
        }
    }

    .form-select-dropdown {
        position: absolute;
        top: 100%;
        background: white;
        border: 0;
        width: 100%;
        overflow: scroll;
        z-index: 25;
        max-height: 0;
        transition: all 0.12s ease-in;
        padding: 0;
        box-shadow: 0 3px 20px rgba(0, 0, 0, 0.47);
        .dropdown-item{
            &.sub{
                border-left: 20px solid #fff;
            }
            &.subsub{
                border-left: 40px solid #fff;
            }
        }
        &__search{
            position: absolute;
            height: 34px;
            line-height: 34px;
            top: 0;
            left: 0;
            right: 0;
            input{
                width: 100%;
                height: 34px;
                line-height: 120%;
                font-size: 15px;
                border: 0;
                border-bottom: 1px solid #f4f4f4;
                padding: 5px 10px;
                &, &:active, &:focus{
                    outline: none;
                    box-shadow: none;
                }
            }
        }
        li {
            padding: 5px 10px;
            line-height: 120%;
            transition: all 0.06s linear;
            font-size: 15px;
            &:hover {
                cursor: pointer;
                background: $formElementBgActive;
                color: $text-color;
            }
        }
    }

    .open {
        .form-select {
            border: 1px solid $formElementBorderColor;
            background: $formElementBgActive;
            .dropdown-caret{
                color: $text-color;
            }
        }
        .form-select-dropdown {
            max-height: 40vh;
            list-style: none;
            padding: 40px 0 0;
        }
    }

    .form-error {
        .form-label {
            color: $brand-danger;
        }
        .form-input {
            border: 1px solid $brand-danger;
        }
    }

</style>