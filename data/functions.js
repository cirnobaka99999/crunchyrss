function shcn(el){
	var sel = el.parentNode.getElementsByClassName('countries_names')[0];
	switch(sel.style.display){
		case'none':
			sel.style.display = 'inline';
			break;
		case'inline':
			sel.style.display = 'none';
			break;
		default:
			sel.style.display = 'inline';
	}
}