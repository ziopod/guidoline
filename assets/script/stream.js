/**
* Stream config
**/
$(document).ready(function(){

$.get('templates/partials/members/table_entry.mustache', function(html)
{
	var data = null;
//	console.log(html);
	var template = Mustache.compile(html);
	var view = function(member, index){
		//t =	$.get('templates/partials/members/table_entry.mustache');
		t = template({member: member, index: index});
		return t;
	};
	var $summary = $('#summary');
	var $found = $('#found') ;
	var $record_count = $('#record_count');
	var total_count = false;
	$('#found').hide();

	var callbacks = {
		pagination: function(summary)
		{

			if ($.trim($('#st_search').val()).length > 0)
			{
				$found.text('Found : '+summary.total).show();
			}
			else
			{
				$summary.text(summary.from + 'to ' + summary.to + ' of ' + summary.total + 'entries');
			}
		},
		after_add: function()
		{
	//		console.log(total_count);
		//	console.log(this.data);
			var percent = Math.round(this.data.length*100/total_count);
			$record_count.text(percent + '%').attr('style', 'width:' + percent + '%');

			if (this.data.length >= total_count)
			{
				this.stopStreaming();
				$('#progress_bar').removeClass('active').hide();
			}
			$('.modale').nyroModal();
			$('.tip').tooltipster({position: 'bottom'});
		},
		before_add: function(data)
		{
			//return data;
			if (! total_count)
			{
				total_count = data.total_count;
			}

			return data.members;
		}
	};

	var st = StreamTable('#table_members', {
		view: view,
		data_url: 'members.json',
		stream_after: 0.000001,
		fetch_data_limit: 60,
		fields: ['id','name','firstname','email','city'],
		callbacks: callbacks,
		pagination: {span: 5, next_text: 'Suivant &rarr;', prev_text: '&larr; Précédent',per_page_opts: [10,25,50,100,500],per_page: 10}
		}
		, data
	);
});


// $.get('views/person.mustache', function(template){
// 	var result = Mustache.render(template, data);
// 	$('#list_of_persons').html(result).hide().fadeIn();
// 	$("#notification").fadeIn();
// });
});