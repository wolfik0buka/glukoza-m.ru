<template>
    <div>
        <div v-if="$store.getters.order.activeCity != 1" class="basket__formItem">
            <div class="basket__formLabel">
                Город доставки
            </div>
            <div class="basket__formContainer">
                <input
                    type="text"
                    id="pickup_points_city_target"
                    v-model="order.city_name"
                    placeholder="Начните вводить для поиска"
                    class="basket__formField autocomplete">
                <div v-if="map.points" class="top-5 font-s14 text-muted">
                    {{ map.points.length | numString("точка|точки|точек") }} выдачи
                </div>
            </div>
        </div>
        
        <div
            v-if="$store.getters.order.activeCity == 1"
            class="top--25 bottom-30 font-s14 text-muted">
            Срок доставки до пункта выдачи товара - 2-3 рабочих дня
        </div>
        
        <div v-if="order.deliveryPoint" class="basket__formItem">
            
            <div class="basket__formLabel">Точка самовывоза</div>
            
            <div class="basket__formContainer">
                
                <div class="point__name">
                    {{ order.deliveryPoint.point_name.replace(/\_/g, " ") }}
                </div>
                
                <div class="font-s15 top-10">
                    <p class="bottom-0 text-muted">Режим работы:</p>
                    <p>{{ order.deliveryPoint.point_work }}</p>
                    <p class="bottom-0 text-muted">Адрес:</p>
                    <p>{{ order.deliveryPoint.point_address }}</p>
                </div>
                
                <button
                    @click="resetPoint()"
                    type="button"
                    class="btn btn-default">Изменить
                </button>
            
            </div>
        </div>
        
        <div
            v-show="map.points && !order.deliveryPoint"
            :class="{'top--20 remove-border-t' : ($store.getters.order.activeCity === 1) }"
            class="basketPickupPoints">
            
            <div class="basketPickupPoints__header">
                <div class="font-w500">Выберите точку самовывоза</div>
                <div class="pointSelector">
                    <div
                        @click="map.active = false"
                        class="pointSelector__item"
                        :class="{active:!map.active}">Списком
                    </div>
                    <div
                        @click="map.active = true"
                        class="pointSelector__item"
                        :class="{active:map.active}">На карте
                    </div>
                </div>
            </div>
            
            <div v-if="!map.active" class="basketPickupPoints__list">
                <div
                    class="basketPickupPoints__listItem"
                    @click="point.expanded = !point.expanded"
                    v-for="point in map.points">
                    <div class="point__name">
                        {{ point.point_name.replace(/\_/g, " ") }}
                    </div>
                    <div class="font-s14 text-muted">
                        Доставим за {{ order.delivery_days | numString("день|дня|дней") }} (цена: {{ order.delivery_price }} руб.)<br>
                        Режим работы: {{ point.point_work }}<br>
                        Адрес: {{ point.point_address }}
                    </div>
                    <div v-if="point.expanded" class="font-s14 top-10">
                        <p>{{ point.point_way.replace(/\`/g, "") }}</p>
                        <button
                            @click="setPoint(point)"
                            type="button"
                            class="btn btn-default">
                            Выбрать
                        </button>
                    </div>
                </div>
            </div>
            
            <div
                v-show="map.active"
                class="basketPickupPoints__map"
                id="mapWithPickupPoints">
            </div>
        
        </div>
    
    </div>
</template>

<script>
    module.exports = {
        name: "basketDeliveryRussiaPickupPoint",
        data() {
            return {
                map: {
                    active: true,
                    instance: false,
                    objectManager: false,
                    center: false,
                    points: false,
                    allCities: false
                }
            };
        },
        computed: {
            order() {
                return this.$store.getters.order;
            },
            sourceDelivery() {
                return new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace("value"),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: "/services/search_city/%QUERY",
                        wildcard: "%QUERY",
                        filter: function(matches) {
                            return matches;
                        }
                    }
                });
            }
        },
        mounted() {
            this.initTypeahead();
            this.createMap();
            if (this.order.city_id) {
                this.calculatePrice();
                this.getCityPoints();
            }
            window.componentPickupPoints = this;
        },
        methods: {
            initTypeahead() {
                $("#pickup_points_city_target").typeahead(null, {
                    name: "source-delivery",
                    display: (item) => {
                        return `${item.name}`;
                    },
                    source: this.sourceDelivery
                }).on("typeahead:selected", (event, payload) => {
                    this.setCity(payload);
                });
            },
            setCity(payload) {
                this.order.city_id = payload.id;
                this.order.city_name = payload.name;
                this.order.delivery_price = false;
                this.$store.dispatch("updateOrder", this.order);
                this.map.points = false;
                this.calculatePrice();
                this.getCityPoints();
            },
            getCityPoints() {
                axios.get(`/services/city/points/${this.order.city_id}`).then(response => {
                    let disabledPoints = ["GLU1", "GLU2", "GLU3", "SPB36"];
                    this.map.points = _.chain(response.data).map((point, index) => {
                        return {
                            index: index,
                            id_obl: "",
                            city_id: point.city_id,
                            city_name: this.order.city_name,
                            point_id: point.point_id,
                            point_address: point.address,
                            point_name: point.name,
                            point_phone: "",
                            point_way: "",
                            point_work: point.work,
                            point_coord: point.coord.split(","),
                            time: point.time,
                            expanded: false
                        };
                    }).filter(point => {
                        return disabledPoints.indexOf(point.point_id) === -1;
                    }).sortBy("point_name").value();

                    ymaps.ready(() => {
                        this.addPointsToMap();
                        setTimeout(this.centerMap(), 1000);
                    });
                });
            },
            calculatePrice() {
                axios.get(`/services/city/points/price/${this.order.city_id}`).then(response => {
                    this.order.delivery_price = (this.order.city_id == 137)
                        ? 150
                        : +response.data.payload.price;
                    this.order.delivery_days = +response.data.payload.deliveryPeriodMin + 1;
                    this.$store.dispatch("updateOrder", this.order);
                });
            },
            createMap() {
                ymaps.ready(() => {
                    this.map.instance = new window.ymaps.Map("mapWithPickupPoints", {
                        center: [59.941679, 30.317928],
                        zoom: 9,
                        controls: ["zoomControl", "fullscreenControl"]
                    });
                    this.map.objectManager = new ymaps.ObjectManager({
                        clusterize: true,
                        geoObjectOpenBalloonOnClick: true,
                        clusterHasBalloon: false,
                        clusterOpenBalloonOnClick: true
                    });

                    this.map.instance.geoObjects.add(this.map.objectManager);

                    if (window.matchMedia("all and (max-width: 900px)").matches) {
                        this.map.instance.behaviors.disable("scrollZoom");
                    }
                    this.addPointsToMap();
                    this.centerMap();
                });
            },
            addPointsToMap() {
                this.map.objectManager.removeAll();

                let points = _.map(this.map.points, (point, index) => {
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
                            <div style="width:270px;color:#444;">
                                <div style="font-weight:bold;font-size:15px;margin:0 0 5px;">
                                    ${point.point_name}
                                </div>
                                <div>
                                    ${point.point_work}<br>
                                    ${point.point_address}<br>
                                    Срок доставки (дней): ${ +point.time + 1 }<br>
                                    ${this.order.delivery_price ? "Стоимость доставки: " + this.order.delivery_price + " руб." : ""}<br>
                                    ${point.city_id === 137 ? "При оплате картой на сайте - бесплатно" : ""}
                                </div>
                                <div>
                                    <button onclick="componentPickupPoints.setPointById('${point.point_id}');"
                                        style="outline:none;border:1px solid #c6cad2;color:#808b9e;background:white;margin:10px 0 5px;padding:5px 15px;border-radius:3px;font-weight:600;font-size:15px;display:inline-block;cursor:pointer;"
                                        type="button">Выбрать</button>
                                </div>
                            </div>
                            `
                        }
                    };
                });

                this.map.objectManager.add({
                    type: "FeatureCollection",
                    features: points
                });

                this.centerMap();
                setTimeout(this.centerMap, 1000);
            },
            centerMap() {
                if (_.isArray(this.map.center)) {
                    if (this.map.center.length == 2) {
                        this.map.instance.setCenter(this.map.center, this.map.instance.getZoom(), {checkZoomRange: true});
                    }
                }
                if (!_.isNull(this.map.instance.geoObjects.getBounds())) {
                    this.map.instance.setBounds(this.map.instance.geoObjects.getBounds(), {checkZoomRange: true});
                }
            },
            setPoint(point) {
                this.order.deliveryPoint = point;
                this.map.active = false;
                this.$store.dispatch("updateOrder", this.order);
            },
            setPointById(point_id) {
                let index = _.findIndex(this.map.points, point => {
                    return point.point_id === point_id;
                });
                this.setPoint(this.map.points[index]);
            },
            resetPoint() {
                this.order.deliveryPoint = false;
                this.$store.dispatch("updateOrder", this.order);
                setTimeout(() => {
                    this.centerMap();
                }, 500);

            }
        }
    };
</script>
