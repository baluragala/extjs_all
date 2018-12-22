/*
 * Chapter 14 Example 2
 * Simple TimeField
 */

// Save all processing until the
// DOM is completely loaded
Ext.onReady(function(){
	var formPanel = new Ext.form.FormPanel({
		title:'TimeField Example',
		applyTo:'chap14_ex02',
		layout:'form',
		labelAlign:'top',
		width:210,
		autoHeight:true,
		frame:true,
		items:[{
			xtype:'timefield',
			fieldLabel:'Time',
			minValue: '9:00 AM',
		    maxValue: '6:00 PM',
		    increment: 30
		}]
	});
});