<template>
    <div class="single_upload_wrapper" v-cloak>

        <div class="alert alert-danger" v-if="isError">{{ isError }}</div>

        <div v-if="isFileExist">
            <div class="pic">
                <img v-if="!isLoading" :src="file+randomString">
            </div>
        </div>

        <form v-show="isFormShow" class="upload" :class="{ 'drag_over' : isOver, 'files_uploading' : toUpload }">

            <input type="file"
                id="fileUploadInput"
                @change="upload"
                @dragover="isOver = true"
                @dragleave="isOver = false"
                @mouseout="isOver = false"
                @mouseleave="isOver = false">

            <label v-if="name">{{ name }}</label>

            <p class="text-muted">
                {{ label.text }} <br> <span class="link">{{ label.link }}</span>
            </p>
        </form>
    </div>
</template>

<script>
    module.exports = {
        name: 'singleUpload',
        data() {
            return {
                isOver: false,
                label: {
                    text: 'Перетащите файл',
                    link: 'или выберите с компьютера'
                },
                isFileExist: false,
                isFormShow: true,
                isError: false,
                isLoading: false,
                randomString: ''
            }
        },
        props: {
            name: {
                type: String,
                default: null
            },
            file: '',
            path: {
                type: String,
                default: ''
            }
        },
        mounted() {
            this.checkFile();
        },
        computed: {
            toUpload() {
                return this.input
                    ? this.input.files
                    : false
            },
            input() {
                return this.$el
                    ? this.$el.querySelector('input')
                    : false
            },
        },
        methods: {
            checkFile() {
                if (this.file) {
                    axios.head(this.file).then((response) => {
                        this.generateRandomString()
                        this.isFileExist = response.status == 200;
                    }).catch(() => {
                        // no image
                    })
                }
            },
            upload(event) {
                let file = event.target.files[0]

                if (this.validateFile(file)) {
                    let payload = new FormData()
                    payload.append('file', file)
                    this.isLoading = true

                    axios.post(this.path, payload).then((response) => {
                        this.isLoading = false
                        this.checkFile();
                    })
                }
            },
            validateFile(file) {
                if (file.type === 'image/jpeg') {
                    this.isError = false;
                    return true
                } else {
                    this.isError = 'Для загрузки разрешен только формат jpg!';
                }
            },
            generateRandomString() {
                this.randomString = '?v='+Math.random().toString(36).substring(2, 15)
            }

        }
    }
</script>

<style lang="scss" scoped>

    $color-border: #e4e4e4;
    [v-cloak] { display: none; }

    .single_upload_wrapper{
        margin: 0 auto;
        overflow: hidden;
        .pic{
            background: white;
            text-align: center;
            padding: 15px 0 10px;
            img{
                display: inline-block;
                vertical-align: top;
                max-width: 100%;
            }
        }

        .btn-group{
            .btn{
                display: block !important;
                width: 100%;
                font-weight: normal;
                background: #f4f4f4;
            }
        }

        form.upload{
            position: relative;
            border-radius: 5px;
            border: 2px dashed $color-border;
            cursor: pointer;
            transition: .3s all ease;
            &:before{
                content: '';
                display: block;
                height: 30px;
                margin: 20px auto 15px;
                box-sizing: border-box;
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center center;
                opacity: .4;
                background-image: url('data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMS4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQ4Ni4zIDQ4Ni4zIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0ODYuMyA0ODYuMzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI2NHB4IiBoZWlnaHQ9IjY0cHgiPgo8Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0zOTUuNSwxMzUuOGMtNS4yLTMwLjktMjAuNS01OS4xLTQzLjktODAuNWMtMjYtMjMuOC01OS44LTM2LjktOTUtMzYuOWMtMjcuMiwwLTUzLjcsNy44LTc2LjQsMjIuNSAgICBjLTE4LjksMTIuMi0zNC42LDI4LjctNDUuNyw0OC4xYy00LjgtMC45LTkuOC0xLjQtMTQuOC0xLjRjLTQyLjUsMC03Ny4xLDM0LjYtNzcuMSw3Ny4xYzAsNS41LDAuNiwxMC44LDEuNiwxNiAgICBDMTYuNywyMDAuNywwLDIzMi45LDAsMjY3LjJjMCwyNy43LDEwLjMsNTQuNiwyOS4xLDc1LjljMTkuMywyMS44LDQ0LjgsMzQuNyw3MiwzNi4yYzAuMywwLDAuNSwwLDAuOCwwaDg2ICAgIGM3LjUsMCwxMy41LTYsMTMuNS0xMy41cy02LTEzLjUtMTMuNS0xMy41aC04NS42QzYxLjQsMzQ5LjgsMjcsMzEwLjksMjcsMjY3LjFjMC0yOC4zLDE1LjItNTQuNywzOS43LTY5ICAgIGM1LjctMy4zLDguMS0xMC4yLDUuOS0xNi40Yy0yLTUuNC0zLTExLjEtMy0xNy4yYzAtMjcuNiwyMi41LTUwLjEsNTAuMS01MC4xYzUuOSwwLDExLjcsMSwxNy4xLDNjNi42LDIuNCwxMy45LTAuNiwxNi45LTYuOSAgICBjMTguNy0zOS43LDU5LjEtNjUuMywxMDMtNjUuM2M1OSwwLDEwNy43LDQ0LjIsMTEzLjMsMTAyLjhjMC42LDYuMSw1LjIsMTEsMTEuMiwxMmM0NC41LDcuNiw3OC4xLDQ4LjcsNzguMSw5NS42ICAgIGMwLDQ5LjctMzkuMSw5Mi45LTg3LjMsOTYuNmgtNzMuN2MtNy41LDAtMTMuNSw2LTEzLjUsMTMuNXM2LDEzLjUsMTMuNSwxMy41aDc0LjJjMC4zLDAsMC42LDAsMSwwYzMwLjUtMi4yLDU5LTE2LjIsODAuMi0zOS42ICAgIGMyMS4xLTIzLjIsMzIuNi01MywzMi42LTg0QzQ4Ni4yLDE5OS41LDQ0Ny45LDE0OS42LDM5NS41LDEzNS44eiIgZmlsbD0iIzAwMDAwMCIvPgoJCTxwYXRoIGQ9Ik0zMjQuMiwyODBjNS4zLTUuMyw1LjMtMTMuOCwwLTE5LjFsLTcxLjUtNzEuNWMtMi41LTIuNS02LTQtOS41LTRzLTcsMS40LTkuNSw0bC03MS41LDcxLjVjLTUuMyw1LjMtNS4zLDEzLjgsMCwxOS4xICAgIGMyLjYsMi42LDYuMSw0LDkuNSw0czYuOS0xLjMsOS41LTRsNDguNS00OC41djIyMi45YzAsNy41LDYsMTMuNSwxMy41LDEzLjVzMTMuNS02LDEzLjUtMTMuNVYyMzEuNWw0OC41LDQ4LjUgICAgQzMxMC40LDI4NS4zLDMxOC45LDI4NS4zLDMyNC4yLDI4MHoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K')
            }
            label{
                font-family: 'Helvetica', 'Arial', sans-serif;
                font-size: 17px;
                text-align: center;
                display: block;
                padding-bottom: 15px;
                padding-top: 0;
            }
            p{
                font-size: 15px;
                width: 100%;
                text-align: center;
                font-family: 'Helvetica', 'Arial', sans-serif;
                line-height: 160%;
                color: #444;
                margin-bottom: 15px;
                span{
                    &.link{
                        text-decoration: underline;
                        cursor: pointer;
                    }
                }
            }
            input{
                position: absolute;
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                outline: none;
                opacity: 0;
                top: 0;
                cursor: pointer;
            }
            &.drag_over, &:hover{
                background: rgba(0,0,0,.05);
                border-color: darken($color-border, 10%);
            }
            &.files_uploading{
                border-color: #ff0000;
            }
        }
        .files_list{
            margin-top: 15px;
            .file{
                padding: 10px 15px;
                border-bottom: 1px solid #fafafa;
            }
        }
        .btn-group{
            display: block;
            font-size: 0;
            .btn{
                display: inline-block;
                vertical-align: top;
                float:none;
            }
        }
    }
</style>