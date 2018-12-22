<html>
<head>
<title>Layout With Many Additions</title>
<link rel="stylesheet" type="text/css"
	href="../../resources/css/ext-all.css" />
<style>
.bomb {
	background-image: url(images/bomb.png) !important;
}
.highlight { background-color: #ffff80; }
</style>
<script type="text/javascript" src="../../adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="../../ext-all.js"></script>
<script>

Ext.ns('Ext.ux');

Ext.ux.GridSearchWindow = Ext.extend(Ext.util.Observable, {
    init: function(cmp){
        this.hostCmp = cmp;
        this.hostCmp.on('render', this.onRender, this, {delay:200});
    },
    onRender: function(){
        this.win = new Ext.Window({
            width: 200,
            height: 120,
            x: 300, y: 300,
            title: 'Grid Search',
            layout: 'fit',
            closable: false,
            constrain: true,
            renderTo: this.hostCmp.id,
            items: this.getFormConfig(),
            buttons: [{
                text: 'Find',
                handler: this.doSearch,
                scope: this
            }, '->', {
                text: 'Clear',
                handler: this.clearSearch,
                scope: this
            }]
        });
        this.win.show();
    },
    doSearch: function(){
        var s = this.hostCmp.getStore();
        s.filter(this.win.field.getValue(),this.win.value.getValue(),true);
    },
    clearSearch: function(){
        this.hostCmp.getStore().clearFilter();
    },
    getFormConfig: function(){
        return {
            xtype: 'form',
            bodyStyle: 'padding: 5px;',
            border: false,
            labelAlign: 'right',
            labelWidth: 60,
            items: [{
                fieldLabel: 'Column',
                xtype: 'combo',
                ref: '../field',
                triggerAction: 'all',
                displayField: 'display',
                valueField: 'field',
                mode: 'local',
                width: 110,
                value: 'title',
                store: {
                    xtype: 'jsonstore',
                    fields: ['display','field'],
                    root: 'data',
                    data: {data:[]}
                }
            },{
                fieldLabel: 'Find',
                xtype: 'textfield',
                ref: '../value',
                width: 110
            }]
        }
    }
});

Ext.preg('gridsearchwin',Ext.ux.GridSearchWindow);
    
	Ext.onReady(function(){
		Ext.BLANK_IMAGE_URL = 'images/s.gif';
        Ext.chart.Chart.CHART_URL = '../../resources/charts.swf';
		Ext.QuickTips.init();
		
        Ext.form.VTypes.nameVal  = /^([A-Z]{1})[A-Za-z\-]+ ([A-Z]{1})[A-Za-z\-]+/;
		Ext.form.VTypes.nameMask = /[A-Za-z\- ]/;
		Ext.form.VTypes.nameText = 'Invalid Director Name.';
		Ext.form.VTypes.name 	= function(v){
			return Ext.form.VTypes.nameVal.test(v);
		};
		
		var Movies = function() {
			return {
				showHelp : function(btn){
                    var moreinfo = Ext.getCmp('movieview').moreInfo;
					if (!moreinfo.isVisible()){ moreinfo.expand(); }
					moreinfo.load({url:'html/'+btn.helpfile+'.txt'});
				},
				showFullDesc : function(grid,index){
                    var moreinfo = Ext.getCmp('movieview').moreInfo;
					var record = grid.getSelectionModel().getSelected().data;
					if (!moreinfo.isVisible()){ moreinfo.expand(); }
					moreinfo.load({url:'html/'+record.id+'.txt'});
				},
				doSearch : function(frm,evt){
                    var moreinfo = Ext.getCmp('movieview').moreInfo;
					if (!moreinfo.isVisible()){ moreinfo.expand(); }
					if (evt.getKey() == evt.ENTER) {
						moreinfo.body.dom.innerHTML = Ext.getCmp('genre').getValue()+' : '+frm.getValue();
					}
				},
				showFullDetails : function(grid,index){
                    var moreinfo = Ext.getCmp('movieview').moreInfo;
					if (moreinfo.isVisible()){ moreinfo.collapse(); }
					var record = grid.getSelectionModel().getSelected().data;
					Ext.getCmp('movieview').findById('movietabs').add({
						title: record.title,
						close: true
					}).show().load({url: 'html/'+record.id+'.txt'});
				},
                filterList: function(grid,idx){
                    var rec = grid.getStore().getAt(idx), fg = grid.ownerCt.filteredGrid;
                    fg.getStore().filter('genre',rec.get('id'));
                    fg.setTitle(fg.initialConfig.title+' ('+rec.get('genre')+')');
                }
			};
		}();
		
		function genre_name(val){
			return genres.queryBy(function(rec){
				return rec.data.id == val;
			}).itemAt(0).data.genre;
		}

		// A longer format that does the same thing as above, but is easier to read
		/*function genre_name(val){
			return genres.queryBy(function(rec){
				if (rec.data.id == val){
					return true;
				}else{
					return false;
				}
			}).itemAt(0).data.genre;
		}*/

		function title_img(val, x, store){
			return  '<img src="images/'+store.data.coverthumb+'" width="50" height="68" align="left">'+
					'<b style="font-size: 13px;">'+val+'</b><br>'+
					'Director:<i> '+store.data.director+'</i><br>'+
					store.data.tagline;
		}
		
        var chartStore = new Ext.data.JsonStore({
            fields: ['year','comedy','drama','action'],
            root: 'data'
        });

		var loadChartFn = function(s){
			var tmpData = {}, yearData = [];
			s.each(function(rec){
				var genre = genre_name(rec.get('genre')),
					rel_year = rec.get('released').format('Y');
				
				if(!tmpData[''+rel_year]){
					tmpData[''+rel_year] = {Comedy:0,Drama:0,Action:0};
				}
				tmpData[''+rel_year][genre]++;
			});
			for (year in tmpData){
				yearData.push({
					year: year,
					comedy: tmpData[year].Comedy,
					drama: tmpData[year].Drama,
					action: tmpData[year].Action
				});
			}
			chartStore.loadData({data:yearData});
		};
        
	    var store = new Ext.data.GroupingStore({
			url: 'movies.json',
			sortInfo: {
				field: 'genre', 
				direction: "ASC"
			},
			groupField: 'genre',
			reader: new Ext.data.JsonReader({
				root:'rows',
				id:'id'
			}, [
				'id',
				'coverthumb',
				'title',
				'director',
				{name: 'released', type: 'date', dateFormat: 'Y-m-d'},
				'genre',
				'tagline',
				{name: 'price', type: 'float'},
				{name: 'available', type: 'bool'}
			]),
            autoLoad: true,
            listeners: {
                load: loadChartFn,
                add: loadChartFn,
                remove: loadChartFn,
                update: loadChartFn
            }
	    });
		
	    var genres = new Ext.data.SimpleStore({
	        fields: ['id', 'genre'],
	        data : [['0','New Genre'],['1','Comedy'],['2','Drama'],['3','Action']]
	    });
		
		var viewport = new Ext.Viewport({
			layout: 'border',
			id: 'movieview',
			renderTo: Ext.getBody(),
			items: [{
				region: "north",
				xtype: 'toolbar',
				border: false,
				height: 28,
				items: [{
					xtype: 'tbspacer'
				},{
					xtype: 'tbbutton',
					text: 'Button',
					handler: function(btn){
						btn.disable();
					}
				},{
					xtype: 'tbfill'
				},{
					xtype: 'tbbutton',
					text: 'Menu Button',
					menu: [{
						text: 'Better'
					},{
						text: 'Good'
					},{
						text: 'Best'
					}]
				},{
					xtype: 'tbseparator'
				},{
					xtype: 'tbsplit',
					text: 'Help',
					menu: [{
						text: 'Genre',
						helpfile: 'genre',
						handler: Movies.showHelp
					},{
						text: 'Director',
						helpfile: 'director',
						handler: Movies.showHelp
					},{
						text: 'Title',
						helpfile: 'title',
						handler: Movies.showHelp
					}]
				},{
					xtype: 'tbseparator'
				},{
					xtype: 'tbtext',
					text: 'Genre:'
				},{
					xtype: 'tbspacer'
				},{
					xtype: 'combo',
					id: 'genre',
					name: 'genre',
					mode: 'local',
					store: genres,
					displayField:'genre',
					width: 70
				},{
					xtype: 'tbspacer'
				},{
					xtype: 'textfield',
					listeners: {
						specialkey: Movies.doSearch
					}
				},{
					xtype: 'tbspacer'
				}]
			},{
				region: 'west',
                layout: 'border',
                border: false,
                split: true,
                width: 250,
    			minSize: 250,
                split: true,
				collapseMode: 'mini',
                items: [{
                    region: 'north',
                    items: [{
                        xtype: 'linechart',
                        store: chartStore,
                        yAxis: new Ext.chart.NumericAxis({majorUnit: 1, minorUnit: .25}),
                        xField: 'year',
                        series: [{
                        	 yField: 'comedy',
                        	 displayName: 'Comedy',
                             style:{
                                 color:0x953030
                             }
                        },{
	                       	 yField: 'drama',
	                       	 displayName: 'Drama',
	                         style:{
	                             color:0xFF8C40
	                         }
	                    },{
	                       	 yField: 'action',
	                       	 displayName: 'Action',
	                         style:{
	                             color:0xFFDB59
	                         }
	                    }],
                        extraStyle: {
                            padding: 2,
                            animationEnabled: true,
                            yAxis: {
                                majorGridLines: {
                                    color: 0xEBEBEB
                                },
                                minorTicks: {
                                    color: 0xEBEBEB
                                }
                            },
                            xAxis: {
                                labelRotation: -45
                            },
                            legend: {
                                display: 'bottom'
                            }
                        },
                        tipRenderer : function(chart, record, index, series){
                            var genre = series.displayName, 
                                genre_id = genres.getAt(genres.find('genre', genre)).get('id'), 
                                year = record.get('year'),
                                movies = [];
                                
                            store.each(function(rec){
                                if(rec.get('genre') == genre_id && rec.get('released').format('Y') == year){
                                    movies.push(rec.get('title'));
                                }
                            });
                            
                            if (movies.length) {
                                return movies.join(', ');
                            }else{
                                return 'None';
                            }
                        }
                    }],
                    split: true,
                    height: 250
                },{
                    region: 'center',
                    xtype: 'form',
    				title: 'Movie Information Form',
    				bodyStyle:'padding:5px;',
    				items: [{
    					xtype: 'textfield',
    					fieldLabel: 'Title',
    					name: 'title',
    					anchor: '100%',
    					allowBlank: false,
    					listeners: {
    						specialkey: function(f,e){
    							if (e.getKey() == e.ENTER) {
    								movie_form.getForm().submit();
    							}
    						}
    					}
    			    },{
    					xtype: 'textfield',
    					fieldLabel: 'Director',
    					name: 'director',
    					anchor: '100%',
    					vtype: 'name'
    			    },{
    					xtype: 'datefield',
    					fieldLabel: 'Released',
    					name: 'released',
    					disabledDays: [1,2,3,4,5]
    			    },{
    					xtype: 'radio',
    					fieldLabel: 'Filmed In',
    					name: 'filmed_in',
    					boxLabel: 'Color'
    				},{
    					xtype: 'radio',
    					hideLabel: false,
    					labelSeparator: '',
    					name: 'filmed_in',
    					boxLabel: 'Black & White'
    				},{
    					xtype: 'checkbox',
    					fieldLabel: 'Available?',
    					name: 'available'
    				},{
    					xtype: 'combo',
    					name: 'genre',
    					fieldLabel: 'Genre',
    					mode: 'local',
    					store: genres,
    					displayField:'genre',
    					width: 130,
    					listeners: {
    						select: function(f,r,i){
    							if (i === 0){
    								Ext.Msg.prompt('New Genre','Name',Ext.emptyFn);
    							}
    						}
    					}
    				},{
    					xtype: 'textarea',
    					name: 'description',
    					hideLabel: true,
                        emptyText: 'Description',
    					labelSeparator: '',
    					anchor: '100% -185'
    				}],
    				buttons: [{
    					text: 'Save',
    					handler: function(){
    						movie_form.getForm().submit({
    							success: function(f,a){
    								Ext.Msg.alert('Success', 'It worked');
    							},
    							failure: function(f,a){
    								console.log(a);
    								Ext.Msg.alert('Warning', a.result.errormsg);
    							}
    						});
    					}
    				}, {
    					text: 'Reset',
    					handler: function(){
    						movie_form.getForm().reset();
    					}
    				}]
                }]
			},{
				region: 'center',
				id: 'movietabs',
				xtype: 'tabpanel',
                deferredRender: false,
                hideMode: 'offsets',
				activeTab: 0,
				items: [{
					title: 'Movie Grid',
					xtype: 'grid',
					store: store,
                    plugins: [{
                        ptype: 'gridsearchwin'
                    }],
					autoExpandColumn: 'title',
					columns: [
						{header: "Title", dataIndex: 'title', renderer: title_img, id: 'title', sortable: true},
						{header: "Director", dataIndex: 'director', hidden: true},
						{header: "Released", dataIndex: 'released', sortable: true, renderer: Ext.util.Format.dateRenderer('m/d/Y'), width: 80},
						{header: "Genre", dataIndex: 'genre', renderer: genre_name, sortable: true, width: 80},
						{header: "Tagline", dataIndex: 'tagline', hidden: true},
						{header: "Price", dataIndex: 'price', renderer: 'usMoney', sortable: true, width: 60}
				    ],
					listeners: {
						rowdblclick: {
							fn: Movies.showFullDetails
						},
						rowclick: {
							fn: Movies.showFullDesc
						}
					},
					view: new Ext.grid.GroupingView(),
					sm: new Ext.grid.RowSelectionModel({
						singleSelect: true
					})
				},{
					title: 'Filter Test',
					xtype: 'panel',
					layout: 'hbox',
					align: 'stretch',
					items: [{
                        autoHeight: true,
                        border: false,
						flex: 1,
						title: 'Genre Filter',
						xtype: 'grid',
						columns: [{header: 'Genre',dataIndex: 'genre',id: 'genre'}],
						store: genres,
						autoExpandColumn: 'genre',
    					listeners: {
    						rowclick: {
    							fn: Movies.filterList
    						}
    					}
					},{
                        autoHeight: true,
                        border: false,
						flex: 3,
                        title: 'Movie Filter Results',
                        ref: 'filteredGrid',
                        xtype: 'grid',
    					store: store,
    					autoExpandColumn: 'title',
    					columns: [
    						{header: "Title", dataIndex: 'title', renderer: title_img, id: 'title', sortable: true},
    						{header: "Director", dataIndex: 'director', hidden: true},
    						{header: "Released", dataIndex: 'released', sortable: true, renderer: Ext.util.Format.dateRenderer('m/d/Y'), width: 80},
    						{header: "Genre", dataIndex: 'genre', renderer: genre_name, sortable: true, width: 80},
    						{header: "Tagline", dataIndex: 'tagline', hidden: true},
    						{header: "Price", dataIndex: 'price', renderer: 'usMoney', sortable: true, width: 60}
    				    ]
					}]
				},{
					title: 'Data Transformation',
					xtype: 'panel',
					layout: 'hbox',
					align: 'stretch',
					items: [{
                        autoHeight: true,
                        border: false,
						width: 180,
						title: 'Genres',
						xtype: 'grid',
						columns: [{header: 'Genre',dataIndex: 'genre',renderer: genre_name,width: 100},{header: 'Year', dataIndex:'released', renderer: Ext.util.Format.dateRenderer('Y'), width: 70}],
						store: store
					},{
                        autoHeight: true,
                        border: false,
						width: 340,
                        title: 'Genre Summary',
                        xtype: 'grid',
    					store: chartStore,
    					columns: [
    						{header: "Year", dataIndex: 'year', width: 100},
    						{header: "Comedies", dataIndex: 'comedy', width: 70},
    						{header: "Dramas", dataIndex: 'drama', width: 70},
    						{header: "Action", dataIndex: 'action', width: 70}
    				    ]
					}]
				}]
			},{
				region: 'east',
				xtype: 'panel',
				ref: 'moreInfo',
				bodyStyle:'padding:5px;font-family:Arial;font-size:10pt;',
				split: true,
				collapsed: true,
				collapsible: true,
				collapseMode: 'mini',
				width: 200
			}]
		}); 

	}); 
	</script>
</head>
<body>
</body>
</html>
