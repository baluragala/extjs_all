<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Complex chart example</title>
<link rel="stylesheet" type="text/css" href="../lib/extjs/resources/css/ext-all.css" />
<script type="text/javascript" src="../lib/extjs/adapter/ext/ext-base-debug.js"></script>
<script type="text/javascript" src="../lib/extjs/ext-all-debug.js"></script>
<!-- Includes for V3 of Google Maps -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript" src="Ext.ux.GMapPanel3.js"></script>
<script type="text/javascript">
Ext.chart.Chart.CHART_URL = '../resources/charts.swf';
Ext.QuickTips.init();

//Plugin to lock multiple slider thumbs together if dragged with SHIFT+mousedown
Ext.ux.SliderThumbSync = new function() {

    var onSliderDragStart = function(slider, e, thumb) {
        if (thumb.shift = e.browserEvent.shiftKey) {
            for (var ths = slider.thumbs, l = ths.length, i = 0, th; i < l; i++) {
                if ((th = ths[i]) !== thumb) {
                    th.offset = th.value - thumb.value;
                }
            }
        }
    }, onSliderDrag = function(slider, e, thumb) {
        if (thumb.shift) {
            for (var ths = slider.thumbs, l = ths.length, i = 0, th; i < l; i++) {
                if ((th = ths[i]) !== thumb) {
                    slider.setValue(i, thumb.value + th.offset, false);
                }
            }
        }
    }, onSliderDragEnd = function(slider, e) {
        for (var ths = slider.thumbs, l = ths.length, i = 0, th; i < l; i++) {
            delete ths[i].shift;
        }
    }, listenersObject = {
        dragstart: onSliderDragStart,
        drag: onSliderDrag,
        dragend: onSliderDragEnd,
    };

    this.init = function(slider) {
        slider.on(listenersObject);
    };
};

Ext.onReady(function(){
    var TrackPoint = Ext.data.Record.create([
        { name: 'lon', mapping: 'Position/LongitudeDegrees', type: 'float' },
        { name: 'lat', mapping: 'Position/LatitudeDegrees', type: 'float'},
        { name: 'elevation', mapping: 'AltitudeMeters', type: 'float' },
        { name: 'distance', mapping: 'DistanceMeters', type: 'float' },
        { name: 'time', mapping: 'Time', type: 'date', dateFormat: 'c' },
        { name: 'heartRate', mapping: 'HeartRateBpm>Value', type: 'int' },
        { name: 'speed', mapping: 'Extensions/TPX/Speed', type: 'float',
            convert: function(v) {
                return v * 2.23693629;  // Metres/sec to miles/hour
            }
        },
        { name: 'elapsed', mapping: 'Time', type: 'date',
            convert: (function() {
                var start;
                return function(v, raw) {
                    v = Date.parseDate(v, 'c');
                    if (!start) {
                        start = v;
                    }
                    return new Date((v.getTime() - start.getTime()));
                }
            })()
        }
    ]),
    reader = new Ext.data.XmlReader({
        record: 'Trackpoint'
    }, TrackPoint);
    store = new Ext.data.Store({
        url: 'ride-data.xml',
        reader: reader,
        autoLoad: true,
        listeners: {
            load: function(store, recs, options) {
                var l = recs[recs.length - 1].get('elapsed').getTime() / 1000, 
                    mkrs = [],
                    plinepnts = new google.maps.MVCArray(),
                    titletpl = new Ext.XTemplate('Speed: {speed:number("0.00")}, ' +
                        'H/R: {heartRate}, ' +
                        'Time: {elapsed:date("H:i:s")}');

//              Set the Slider's max value in seconds.
                timeSlider.setMaxValue(l);
                timeSlider.setValue(1, l);

                store.each(function(rec, i){
                    var lat = rec.get('lat'),
                        lon = rec.get('lon');

                    if (lat && lon) {
                        if (i == 0){
                            trackpointMap.getMap().setCenter(new google.maps.LatLng(lat, lon));
                        }
                        if (i % 10 == 0) {
                            mkrs.push({
                                lat: lat,
                                lng: lon,
                                marker: {
                                    title: titletpl.apply(rec.data)
                                }
                            });
                        }
                        plinepnts.push(new google.maps.LatLng(lat, lon));
                    }
                });

                var pline = new google.maps.Polyline({
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                    path: plinepnts
                });

                pline.setMap(trackpointMap.getMap());
                trackpointMap.addMarkers(mkrs);
            },
            single: true
        }
    }),
    editTrackpoint = null,
    trackpointEditForm = new Ext.form.FormPanel({
        title: 'Edit Trackpoint',
        region: 'north',
        height: 280,
        split: true,
        collapsible: true,
        padding: 5,
        labelWidth: 110,
        defaults: {
            anchor: '100%'
        },
        items: [{
            fieldLabel: 'Latitude',
            xtype: 'numberfield',
            name: 'lon'
        }, {
            fieldLabel: 'Longitude',
            xtype: 'numberfield',
            name: 'lat'
        }, {
            fieldLabel: 'Elevation',
            xtype: 'numberfield',
            name: 'elevation',
        }, {
            fieldLabel: 'Distance (metres)',
            xtype: 'numberfield',
            name: 'distance'
        }, {
            fieldLabel: 'Heart rate',
            xtype: 'numberfield',
            allowDecimals: false,
            name: 'heartRate'
        }, {
            fieldLabel: 'Speed (mph)',
            xtype: 'numberfield',
            name: 'speed'
        }, {
            fieldLabel: 'Time',
            xtype: 'textfield',
            name: 'time',
            setValue: function(v) {
                this.value = v;
                this.el.dom.value = v ? v.format("d/m/Y H:i:s") : '';
            },
            getValue: function() {
                return this.value;
            },
            readOnly: true
        }, {
            fieldLabel: 'Elapsed time',
            xtype: 'textfield',
            name: 'elapsed',
            setValue: function(v) {
                this.value = v;
                this.el.dom.value = v ? v.format("H:i:s") : '';
            },
            getValue: function() {
                return this.value;
            },
            readOnly: true
        }],
        buttonAlign: 'center',
        fbar: {
            items: [{
                iconCls: 'x-tbar-page-prev',
                repeat: {
                    accelerate: true
                },
                handler: function() {
                    var idx = store.indexOf(editTrackpoint);
                    if (idx < 1) {
                        idx = store.getCount();
                    }
                    idx -= 1;
                    editTrackpoint = store.getAt(idx);
                    trackpointEditForm.getForm().loadRecord(editTrackpoint);
                },
                tooltip: 'Go to the previous TrackPoint'
            }, {
                text: 'Update',
                handler: function() {
                    trackpointEditForm.getForm().updateRecord(editTrackpoint);
                },
                tooltip: 'Update the store'
            },{
                text: 'Delete',
                handler: function() {
                    if (editTrackpoint) {
                        var idx = store.indexOf(editTrackpoint),
                            nextEdit = store.getAt(idx + 1) || store.getAt(idx - 1);
                        store.remove(editTrackpoint);
                        editTrackpoint = nextEdit;
                        trackpointEditForm.getForm().loadRecord(nextEdit);
                    }
                },
                tooltip: 'Delete this TrackPoint'
            }, {
                iconCls: 'x-tbar-page-next',
                repeat: {
                    accelerate: true
                },
                handler: function() {
                    var idx = store.indexOf(editTrackpoint) + 1;
                    if (idx == store.getCount()) {
                        idx = 0;
                    }
                    editTrackpoint = store.getAt(idx);
                    trackpointEditForm.getForm().loadRecord(editTrackpoint);
                },
                tooltip: 'Go to the next TrackPoint'
            }]
        }
    }),
    timeSlider = new Ext.slider.MultiSlider({
        flex: 1,
        values: [0, 0],
        plugins : [
            Ext.ux.SliderThumbSync,
            new Ext.slider.Tip({
                getText: function(thumb) {
                    var start = new Date(thumb.slider.thumbs[0].value * 1000),
                        end = new Date(thumb.slider.thumbs[1].value * 1000);
                    return '<b>' + start.format("i:s") + ' to ' + end.format('i:s') + '</b>';
                }
            })
        ],
        listeners: {
            change: function() {
                var v = timeSlider.getValues();
                store.filterBy(function(rec) {
                    var e = rec.get("elapsed").getTime() / 1000;
                    return (e >= v[0]) && (e <= v[1]);
                });
            },
            buffer: 50
        }
    }),
    bbar = new Ext.Toolbar({
        layout: {
            type: 'hbox'
        },
        items: [
            'Time range: ',
            timeSlider,
            {
                xtype: 'checkbox',
                margins: '3 0 0 5',
                boxLabel: 'Show speed',
                checked: true,
                listeners: {
                    check: function(cb, checked) {
                        speedSeries.style.visibility = checked ? 'visible' : 'hidden';
                        window.chart.refresh();
                    }
                }
            }, {
                xtype: 'checkbox',
                margins: '3 0 0 5',
                boxLabel: 'Show heart rate',
                checked: true,
                listeners: {
                    check: function(cb, checked) {
                        heartRateSeries.style.visibility = checked ? 'visible' : 'hidden';
                        window.chart.refresh();
                    }
                }
            }
        ]
    }),
    heartRateSeries = {
        yField: 'heartRate',
        style: {
            color: 0xff1100,
            size: 8
        }
    },
    speedSeries = {
        yField: 'speed',
        axis: 'secondary',
        style: {
            color: 0x00aa11,
            size: 8
        }
    },
    chart = new Ext.chart.LineChart({
        store: store,
        xField: 'elapsed',
        xAxis: new Ext.chart.TimeAxis({
            title: 'Elapsed time',
            labelRenderer: function(date) {
                return date.format("H:i:s");
            }
        }),
        yAxes: [
            new Ext.chart.NumericAxis({
                minimum: 40,
                maximum: 220,
                title: 'Heart rate',
                position: 'left'
            }),
            new Ext.chart.NumericAxis({
                minimum: 0,
                maximum: 40,
                majorUnit: 5,
                title: 'Speed\nMPH',
                position: 'right',
                order: 'secondary'
            })
        ],
        tipTpl: 'Speed: {speed:number("0.00")}\n' +
            'H/R: {heartRate}\n' +
            'Time: {elapsed:date("H:i:s")}\n' +
            'Click to edit trackpoint',
        tipRenderer: function(chart, rec, index, series) {
            if (Ext.isString(chart.tipTpl)) {
                chart.tipTpl = new Ext.XTemplate(chart.tipTpl);
            }
            return chart.tipTpl.apply(rec.data);
        },
        series: [ heartRateSeries, speedSeries ],
        listeners: {
            itemclick: function(evt) {
                editTrackpoint = store.getAt(evt.index);
                trackpointEditForm.getForm().loadRecord(editTrackpoint);
                trackpointEditForm.getForm().findField(evt.seriesIndex ? 'speed' : 'heartRate').focus();
            }
        }
    }),
    trackpointMap = new Ext.ux.GMapPanel({
        region: 'center',
        zoomLevel: 12,
        gmapType: 'map',
        border: true,
        mapConfOpts: ['enableScrollWheelZoom','enableDoubleClickZoom','enableDragging'],
        mapControls: ['GSmallMapControl','GMapTypeControl'],
        setCenter: {
            lat: 42.339641,
            lng: -71.094224
        }
    });

    new Ext.Viewport({
        layout: 'fit',
        items: {
            border: false,
            title: 'Edit Event Data',
            layout: 'border',
            items: [{
                xtype: 'container',
                region: 'west',
                margins: '5 0 5 5',
                width: 300,
                split: true,
                collapsible: true,
                layout: 'border',
                items: [ trackpointEditForm, trackpointMap ],
            }, {
                region: 'center',
                margins: '5 5 5 0',
                title: 'Line chart. Heart rate and speed over time',
                layout: 'fit',
                items: chart,
                bbar: bbar
            }]
        }
    });
});
</script>
</head>
<body>
</body>
</html>