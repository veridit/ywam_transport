Protoplasm.use('ratingbar').transform('.rating_bar', { onclick: rateItem });

function listUserFiles(directory, callback) {
	new Ajax.Request('/software/protoplasm/control/fileManager.php', {
			parameters: { 'a': 'listdir', 'd': (directory || '') },
			onComplete: function(response) {
				try {
					callback(eval('(' + response.responseText + ')'));
				} catch(e) {
					callback({status:'error'});
				}
			}
		});
}

function rateItem(ratingbar) {
	var code = ratingbar.element.id.replace(/rating_/, '');
	var rating = ratingbar.rating;
	ratingbar.setLoading(true);
	new Ajax.Request('/ratings/rate.php', {
			parameters: {'m': 'rpc', 'r': rating, 'c': code},
			onSuccess: function(transport) {
				ratingbar.setLoading(false);
				try {
					var response = eval('(' + transport.responseText + ')');
					ratingbar.rating = response.rating;
					ratingbar.resetRating();
					$('rating_'+code+'_average').innerHTML = response.rating;
					$('rating_'+code+'_votes').innerHTML = response.votes;
				} catch(e) {
					alert(e.message);
				}
			},
			onFailure: function(transport) {
				ratingbar.setLoading(false);
				ratingbar.resetRating();
			}
		});
}
