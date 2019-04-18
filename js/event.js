$(function  () {
	window.App = {
		Models: {},
		Collections: {},
		Views:{}
	};


	window.template = function(id) {
		return _.template( $('#' + id).html() );
	};

	App.Models.Task = Backbone.Model.extend({});

	App.Views.Task = Backbone.View.extend({

		initialize: function() {
			this.model.on('change', this.render, this);
			this.model.on('destroy', this.remove, this);
		},
		tagName: 'li',
		template: template('taskTemplate'),
		render: function (){
			var template = this.template(this.model.toJSON());
			this.$el.html( template );
			return this;
		},
		events:{
			'click .edit': 'editTask',
			'click .delete': 'deleteTask',
		},
		editTask: function() {
			var newTaskTitle = prompt('Kak nazovem zada4u?', this.model.get('title'));
			if (! $.trim(newTaskTitle)) return;			
			this.model.set('title',newTaskTitle);
		},
		deleteTask: function() {
			this.model.destroy();
			console.log( tasksCollection );
		}
	});

	App.Collections.Task = Backbone.Collection.extend({
		model: App.Models.Task
	})
	App.Views.Tasks = Backbone.View.extend({

		tagName: 'ul',

		initialize: function(){
			this.collection.on('add', this.addOne,this)
		},
		render: function  () {
			this.collection.each(this.addOne, this);
			return this;
		},
		addOne: function (task) {
			//sozdat noviy do4erniy view
			var taskView = new App.Views.Task({model: task});
			//dobavit ego v kornevoy element
			this.$el.append(taskView.render().el);
		}

	});

	App.Views.AddTask = Backbone.View.extend({
		el: '#addTask',

		events: {
			'submit': 'submit'
		},

		submit: function  (e) {
			e.preventDefault();
			var newTaskTitle = $(e.currentTarget).find('input[type=text]').val();
			var newTask = new App.Models.Task({title: newTaskTitle});
			this.collection.add(newTask);
		},

		initialize: function  () {
		},
	})


	window.tasksCollection = new App.Collections.Task([
		{
			title: 'Shodit v magazin',
			priority: 4
		},
		{
			title: 'Polu4it po4tu',
			priority: 3
		},
		{
			title: 'Shodit na rabotu',
			priority: 5
		},
	]);
	var tasksView = new App.Views.Tasks({collection: tasksCollection});
	$('.tasks').html(tasksView.render().el);

	var addTaskView = new App.Views.AddTask({collection: tasksCollection});
}())						