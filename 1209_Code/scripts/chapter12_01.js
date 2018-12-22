/*
 * Chapter 12 Example 1
 * Simple Ajax Panel
 */

// Save all processing until the
// DOM is completely loaded
Ext.onReady(function(){
	var example1 = new Ext.Panel({
		applyTo:'chap12_ex01',
		title:'Chapter 12: Example 1',
		width:250,
		height:250,
		frame:true,
		autoLoad:{
			url:'example1ajax.html'
		}
	});
});