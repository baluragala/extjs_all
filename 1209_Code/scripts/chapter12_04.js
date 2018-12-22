/*
 * Chapter 12 Example 4
 * Data Store from custom reader - Filtering
 */

// Simple function/object to 'clone' objects
cloneConfig = function (config) {
    for (i in config) {
        if (typeof config[i] == 'object') {
            this[i] = new cloneConfig(config[i]);
        }
        else
            this[i] = config[i];
    }
}

// Save all processing until the
// DOM is completely loaded
Ext.onReady(function(){
	var recordModel = [
		{name:'file_name',mapping:'NAME'},
		{name:'file_size',mapping:'SIZE'},
		{name:'type',mapping:'TYPE'},
		{name:'lastmod',mapping:'DATELASTMODIFIED'},
		{name:'file_attributes',mapping:'ATTRIBUTES'},
		{name:'mode',mapping:'MODE'},
		{name:'directory',mapping:'DIRECTORY'}
	];
	
	var ourReader = new Ext.data.CFJsonReader({id:'NAME',root:'DATA'},recordModel);
	
	var initialBaseParams = {
		method: 'getDirectoryContents',
		returnFormat: 'JSON',
		queryFormat: 'column',
		startPath: '/testdocs/'
	};
	
	var ourStore = new Ext.data.Store({
		url:'Chapter12Example.cfc',
		baseParams: new cloneConfig(initialBaseParams),
		reader: ourReader,
		fields: recordModel,
		listeners:{
			beforeload:{
				fn: function(store, options){
					for(var i in options){
						if(options[i].length > 0){
							store.baseParams[i] = options[i];
						}
					}
				},
				scope:this
			},
			load: {
				fn: function(store, records, options){
					console.log(records);
				},
				scope: this
			},
			update: {
				fn: function(store, record, operation){
					switch (operation){
						case Ext.record.EDIT:
							// Do something with the edited record
							break;
						case Ext.record.REJECT:
							// Do something with the rejected record
							break;
						case Ext.record.COMMIT:
							// Do something with the committed record
							break;
					}
				},
				scope:this
			}
		}
	});
	
	ourStore.load({recurse:true});
	
	filterStoreByType = function (type){
		ourStore.load({dirFilter:type});
	}
	
	filterStoreByFileType = function (fileType){
		ourStore.load({fileFilter:fileType});
	}
	
	clearFilters = function (){
		ourStore.baseParams = new cloneConfig(initialBaseParams);
		ourStore.load();
	}
});