(function ($) {
    mzMap = window.mzMap = {
        Storage: {
            apiKey: EASLMZMAP.apiKey,
            mapData: {},
            map: {},
            styledMap: {}
        },
        style: function () {
            return [
                {
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#eaeaea"
                        }
                    ]
                },
                {
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative.land_parcel",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative.neighborhood",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        }
                    ]
                }
            ]
        },
        init: function () {
            if (typeof google === "undefined" || typeof google.maps === "undefined") {
                var tag = document.createElement('script');
                tag.async = true;
                tag.defer = true;
                tag.src = "https://maps.googleapis.com/maps/api/js?key=" + this.Storage.apiKey + "&callback=mzInitMaps";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            } else {
                this.initMaps();
            }
        },
        initMaps: function () {
            var styledMapType = new google.maps.StyledMapType(this.style());
            var map = new google.maps.Map(document.getElementById('easl-mz-stat-map'), {
                center: {lat: 43.371, lng: 9.282},
                zoom: 1.4,
                mapTypeControlOptions: {
                    mapTypeIds: ['roadmap', 'satellite', 'hybrid', 'terrain',
                        'mz_top_country_map']
                },
                zoomControl: false,
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                rotateControl: false,
                fullscreenControl: false
            });
            map.mapTypes.set('mz_top_country_map', styledMapType);
            map.setMapTypeId('mz_top_country_map');
        }
    };
    window.mzInitMaps = function () {
        mzMap.initMaps();
    };
    $(document).ready(function () {
        mzMap.init();
    });
})(jQuery);