jQuery('document').ready(function($){
	
	var mapJSON = $('input.pw_map').val();
	
	var nestableOptions = {
		json: mapJSON,
        contentCallback: function(item) {return item.name || '' ? item.name : item.id;},
        listNodeName: 'ul',
        itemNodeName: 'li',
        handleNodeName: 'div',
        contentNodeName: 'div',
        rootClass: 'bwp-lms-pathway',
        listClass: 'pw',
        itemClass: 'pw-item',
        dragClass: 'pw-dragel',
        handleClass: 'pw-handle',
        contentClass: 'pw-title',
        collapsedClass: 'pw-collapsed',
        placeClass: 'pw-placeholder',
        noDragClass: 'pw-nodrag',
        noChildrenClass: 'pw-nochildren',
        emptyClass: 'pw-empty',
        expandBtnHTML: '<button class="pw-expand" data-action="expand" type="button">Expand</button>',
        collapseBtnHTML: '<button class="pw-collapse" data-action="collapse" type="button">Collapse</button>',
        maxDepth: 10,
        scroll: false,
        scrollSensitivity: 1,
        scrollSpeed: 5,
        scrollTriggers: {
            top: 40,
            left: 40,
            right: -40,
            bottom: -40
        },
        effect: {
            animation: 'none',
            time: 'slow'
        },
        callback: function(l, e, p) {},
        onDragStart: function(l, e, p) {},
        beforeDragStop: function(l, e, p) {},
        listRenderer: function(children, options) {
			
            var html = '<' + options.listNodeName + ' class="' + options.listClass + '">';
            html += children;
            html += '</' + options.listNodeName + '>';

            return html;
        },
        itemRenderer: function(item_attrs, content, children, options, item) {
			/*
		 * 
<li id="pw-item-<?php echo $unit_id; ?>" 
	class="pw-item pw-item-<?php echo $unit_type; ?> pw-item-<?php echo $unit_type; ?>-<?php echo $unit_id; ?>" 
	data-id="<?php echo $unit_order; ?>">
	<div class="pw-handle"></div>
	<div class="pw-title"><?php echo $unit_name; ?></div>
	<div class="pw-edit-link"><?php echo $unit_edit_url; ?></div>
</li>
		 */
		item_attrs['id'] = options.itemClass+'-'+ item_attrs['unit_id'];
		item_attrs['class'] = item_attrs['class'] + ' ' + options.itemClass+'-'+item_attrs['unit_type'];
		
            var item_attrs_string = $.map(item_attrs, function(value, key) {
                return ' ' + key + '="' + value + '"';
            }).join(' ');

            var html = '<' + options.itemNodeName + item_attrs_string + '>';
			
            html += '<' + options.handleNodeName + ' class="' + options.handleClass + '">';
            html += '<' + options.contentNodeName + ' class="' + options.contentClass + '">';
            html += content;
            html += '</' + options.contentNodeName + '>';
			html += '<div class="pw-edit-link"><a href="#">Edit</a></div>'; 
            html += '</' + options.handleNodeName + '>';
            html += children;
            html += '</' + options.itemNodeName + '>';

            return html;
        }
    };
	
	$('.bwp-lms-pathway').nestable(nestableOptions);
});


