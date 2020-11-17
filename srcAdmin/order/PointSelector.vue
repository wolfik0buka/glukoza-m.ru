<template>
    <div>
        <div class="pointSelector">
            <div class="pointSelector__panel">
                <div class="container-fluid">
                    <button @click="$emit('close')" class="btn btn-default">Закрыть</button>
                </div>
            </div>
            <div v-show="points" class="pointSelector__map" id="mapWithPickupPoints"></div>
            <div v-show="!points" class="pointSelector__empty">
                <div class="loader"></div>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: 'PointSelector',
        props: [
            'order'
        ],
        data() {
            return {
                points: false,
                instance: false,
                objectManager: false,
                center: false,
            }
        },
        computed: {
        },
        mounted() {
            window.componentPickupPoints = this

                this.createMap()
                if (this.order.city_id) {
                    this.getCityPoints()
                }

        },
        methods: {
            getCityPoints() {
                axios.get(`/services/city/points/${this.order.city_id}`).then(response => {
                    let disabledPoints = []
                    this.points = _.chain(response.data)
                    .map((point, index) => {
                        return {
                            index: index,
                            id_obl: '',
                            city_id: point.city_id,
                            city_name: this.order.city_name,
                            point_id: point.point_id,
                            point_address: point.address,
                            point_name: point.name,
                            point_phone: '',
                            point_way: '',
                            point_work: point.work,
                            point_coord: point.coord.split(','),
                            time: point.time,
                            expanded: false
                        }
                    })
                    .filter( point => {
                        return disabledPoints.indexOf(point.point_id) === -1
                    })
                    .sortBy('point_name')
                    .value()

                    ymaps.ready(() => {
                        this.addPointsToMap()
                        setTimeout(this.centerMap(), 1000)
                    })
                })
            },
            createMap() {
                ymaps.ready(() => {
                    this.instance = new window.ymaps.Map("mapWithPickupPoints", {
                        center: [59.941679,30.317928],
                        zoom: 9,
                        controls: ["zoomControl", "fullscreenControl"]
                    })
                    this.objectManager = new ymaps.ObjectManager({
                        clusterize: true,
                        geoObjectOpenBalloonOnClick: true,
                        clusterHasBalloon: false,
                        clusterOpenBalloonOnClick: true
                    })

                    this.instance.geoObjects.add(this.objectManager)

                    if (window.matchMedia('all and (max-width: 900px)').matches) {
                        this.instance.behaviors.disable('scrollZoom')
                    }
                    this.addPointsToMap()
                    this.centerMap()
                })
            },
            addPointsToMap() {
                this.objectManager.removeAll()

                let points = _.map(this.points, (point, index) => {
                    return {
                        type: "Feature",
                        id: point.index,
                        geometry: {
                            type: "Point",
                            coordinates: point.point_coord
                        },
                        properties: {
                            hintContent: point.point_name,
                            balloonContentBody: `
                            <div style="width:250px;color:#444;">
                                <div style="font-weight:bold;font-size:15px;margin:0 0 5px;">
                                    ${point.point_name}
                                </div>
                                <div>
                                    ${point.point_work}<br>
                                    ${point.point_address}<br>
                                    Срок доставки (дней): ${ +point.time + 1 }<br>
                                    ${this.order.delivery_price ? 'Стоимость доставки: '+this.order.delivery_price+' руб.' : ''}
                                </div>
                                <div>
                                    <button onclick="componentPickupPoints.setPointById('${point.point_id}');"
                                        style="outline:none;border:1px solid #c6cad2;color:#808b9e;background:white;margin:10px 0 5px;padding:5px 15px;border-radius:3px;font-weight:600;font-size:15px;display:inline-block;cursor:pointer;"
                                        type="button">Выбрать</button>
                                </div>
                            </div>
                            `
                        }
                    }
                })

                this.objectManager.add({
                    type: "FeatureCollection",
                    features: points
                })

                this.centerMap()
                setTimeout(this.centerMap, 1000)
            },
            centerMap() {
                if (_.isArray(this.center)) {
                    if (this.center.length == 2) {
                        this.instance.setCenter(this.center, this.instance.getZoom(), { checkZoomRange: true })
                    }
                }
                if (!_.isNull(this.instance.geoObjects.getBounds())) {
                    this.instance.setBounds( this.instance.geoObjects.getBounds(), { checkZoomRange: true } )
                }
            },
            setPoint(point) {
                this.order.delivery_point_id = point.id
                this.order.delivery_pickup_point = point
                this.order.delivery_pickup_point.parent = this.order.id
                this.$store.commit('set', ['order', this.order])
                this.$store.dispatch('updateOrder', this.order)
                this.$emit('close')
                this.$store.dispatch('setOrderPickupPoint', this.order.delivery_pickup_point)
            },
            setPointById(point_id) {
                let index = _.findIndex(this.points, point => {
                    return point.point_id === point_id
                })
                this.setPoint(this.points[index])
            },
            resetPoint() {
                this.order.delivery_point_id = false
                this.$store.dispatch('updateOrder', this.order)
                setTimeout(() => {
                    this.centerMap()
                }, 500)

            }
        }
    }
</script>

<style lang="less">
    .pointSelector{
        position: fixed;
        right: 0;
        bottom: 0;
        width: ~"calc(100vw - 120px)";
        z-index: 9999;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        &__map{
            width: 100%;
            height: 500px;
        }
        &__empty{
            width: 100%;
            height: 500px;
            display:flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            background: #fff;
        }
        &__panel{
            display:flex;
            flex-direction: row;
            align-items: center;
            width: 100%;
            height: 50px;
            background: #fff;
        }
    }
</style>