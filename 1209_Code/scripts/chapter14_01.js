/*
 * Chapter 14 Example 1
 * Simple DateField
 */

// Save all processing until the
// DOM is completely loaded
Ext.onReady(function(){
	var formPanel = new Ext.form.FormPanel({
		title:'DateField Example',
		applyTo:'chap14_ex01',
		layout:'form',
		labelAlign:'top',
		width:210,
		autoHeight:true,
		frame:true,
		items:[{
			xtype:'datefield',
			fieldLabel:'Date of Birth',
			name:'dob',
			width:190,
			allowBlank:false
		}]
	});
});