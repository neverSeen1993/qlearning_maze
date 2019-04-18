(function () {
	window.App = {
		Models: {},
		Views: {},
		Collections: {}
	};	


	//model 4eloveka
	App.Models.Person = Backbone.Model.extend({
		defaults: {
			age: 40,
			name: 'Ivan',
			job: 'Slesar'
		}
	});

	//view 4eloveka

	App.Views.Person = Backbone.View.extend({
		tagName: 'li',

		template: _.template('<%= name %>(<%= age %>) - <%= job %>'),

		initialize: function() {
			this.render();
		},

		render: function() {
			this.$el.html ( this.template(this.model.toJSON() ) );
		}

	});

		//collection

		App.Collections.People = Backbone.Collection.extend({
			model: App.Models.Person
		});

		//view of collection

	App.Views.People = Backbone.View.extend({

		tagName: 'ul',

		initialize: function() {
		},

		render: function() {

			this.collection.each(function(person){
				var personView = new App.Views.Person({model: person});
				this.$el.append(personView.el);
			}, this);	
			
			
			return this;
		}

	});


	var person = new App.Models.Person();
	var person1 = new App.Models.Person();
	var person2 = new App.Models.Person({'age': 21, 'name': 'Sasha', 'job': 'Looking for a job'});
	var person3 = new App.Models.Person({'name': 'ANYA'});
	var personView = new App.Views.Person({model: person2});
	var peopleCollection = new App.Collections.People([person1, person2, person3]);
	var peopleView = new App.Views.People({collection: peopleCollection});

	$(document.body).append(peopleView.render().el);

}());
