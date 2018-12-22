/*
 * Chapter 12 Example 2
 * Simple Ajax Panel - Dynamic Data
 */

// Save all processing until the
// DOM is completely loaded
Ext.onReady(function(){
	var example2 = new Ext.Panel({
		applyTo:'chap12_ex02',
		title:'Chapter 12: Example 2',
		width:250,
		height:250,
		frame:true,
		autoLoad:{
			url:'Chapter12Example.cfc',
			params:{
				method:'example2',
				returnFormat:'plain',
				id:1
			}
		}
	});
});