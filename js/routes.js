(function  () {
	window.App = {
		Views: {},
		Models: {},
		Collections: {},
		Router: {}
	}

	App.Router = Backbone.Router.extend({
		routes: {
			'' : 'index',
			'page/:id/*' : 'page',

		},

		index: function(){
			console.log( 'Holla' );
		},
		page: function(id){
			console.log( 'page#'+id);
		}

	});

	new App.Router();
	Backbone.history.start();
}());