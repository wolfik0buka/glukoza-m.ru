<template>
    <div class="text_editor">

        <textarea
            rows="1"
            spellcheck="false"
            class="sm_editor"
            @dblclick="isEdit = true"
            @keydown="autosize"
            :placeholder="placeholder ? placeholder : 'Empty..'"
            :readonly="!isEdit"
            v-model.trim="content"
            :class="textAreaStyle">
        </textarea>

        <button
          class="text_editor__btn"
          type="button"
          v-show="!isEdit"
          @click="isEdit=true">
            <i class="fa fa-pencil"></i>
        </button>

        <button
          class="text_editor__btn active"
          type="button"
          v-show="isEdit"
          @click="save">
            <i class="fa fa-check"></i>
        </button>

    </div>
</template>

<script>
module.exports = {
    name: 'textEditor',
    props: [
        'text',
        'addclass',
        'bordered',
        'data',
        'placeholder'
    ],
    data(){
        return {
            isEdit: false,
            content: ''
        }
    },
    computed: {
        textAreaStyle() {
            let addclass = this.bordered
                    ? 'bordered '+this.addclass
                    : this.addclass

            return this.isEdit ? 'active '+addclass : addclass
        }
    },
    watch: {
        isEdit() {
            setTimeout(this.autosize, 100)
        }
    },
    beforeMount() {
        this.content = this.text
    },
    mounted() {
        this.autosize()
    },
    methods: {
        save() {
            this.isEdit = false
            let payload = this.data ? {content: this.content, data: this.data} : this.content
            this.$emit('save', payload)
        },
        autosize() {
            if (this.$el) {
                let textarea = this.$el.getElementsByTagName('textarea')[0]
                textarea.style.cssText = 'height:auto; padding:0'
                textarea.style.cssText = '-moz-box-sizing:content-box'
                textarea.style.cssText = 'height:' + textarea.scrollHeight + 'px'
            } else {
                setTimeout(this.autosize, 300)
            }
        }
    }
}
</script>


<style lang="scss">

    $height: 30px;

    .text_editor {
        display: flex;
        flex-direction: row;
        flex-wrap:nowrap;
        align-items: stretch;
        justify-content: flex-start;
        textarea.sm_editor {
            &, &:focus, &:hover, &[readonly] {
                display: block;
                width: 100%;
                border: 1px solid transparent;
                border-radius: 5px 0 0 5px;
                resize: none;
                padding: 6px 10px;
                margin: 0;
                font-size: 15px;
                background: transparent;
                height: ($height - 12px);
                line-height: ($height - 12px);
                overflow-y: hidden;
                transition: .1s all ease;
                color: #444;
                outline: none;
            }
            &.active {
                //overflow-y: scroll;
                padding: 6px 10px;
                background: #eee;
                resize: none;
                overflow-y: hidden;
                border: 1px solid #eee;
            }
            &.bordered {
                border: 1px solid #eee;
            }
        }
        &__btn{
            flex:none;
            height: $height;
            width: $height;
            background: #e3e3e3;
            border: 0;
            font-size: 16px;
            border-radius: 0 5px 5px 0;
            &.active{
                background: #0098e2;
                color: #fff;
            }
        }
    }
</style>
