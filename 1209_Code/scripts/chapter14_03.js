/*
 * Chapter 14 Example 3
 * Simple HtmlEditor
 */

// Save all processing until the
// DOM is completely loaded
Ext.onReady(function(){
	var formPanel = new Ext.form.FormPanel({
		title:'HtmlEditor Example',
		applyTo:'chap14_ex03',
		layout:'form',
		labelAlign:'top',
		width:600,
		autoHeight:true,
		frame:true,
		items:[{
			xtype:'htmleditor',
            id:'bio',
            fieldLabel:'Blog Entry',
            height:200,
            anchor:'98%'
		}]
	});
});