/*
 * Chapter 14 Example 4
 * Simple string formatting
 */

// Save all processing until the
// DOM is completely loaded
Ext.onReady(function(){
	var cls = "Band";
	var member = {
		firstName: 'Eric',
		lastName: 'Clapton',
		position: 'Lead Guitarist'
	};
	
	var pnl = new Ext.Panel({
		applyTo: 'chap14_ex04',
		width: 200,
		height: 100,
		bodyStyle: 'padding:5px',
		title: 'Band Member',
		html: String.format('<div class=\'{0}\'>{2}, {1}: {3}</div>', cls, member.firstName, member.lastName, member.position)
	});
});