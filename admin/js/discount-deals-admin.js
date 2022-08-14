/**
 * Code contains all stuffs related to admin
 *
 * @package Discount_Deals
 */

(function ($, data) {
	'use strict';
	let discount_deals = {}
	console.log(data)

	function init_discounts() {
		$(document).on(
			"change",
			"#discount_deals_workflow_type",
			function () {
				let discount_type = $(this).val();
				if (discount_type) {
					fetch_discount_details(discount_type).done(function (response) {
						if (!response.success) {
							return;
						}
						console.log(response)
						discount_deals.workflow.set('discount_type', response.data.trigger);
					})
				} else {
					discount_deals.workflow.set('discount_type', false);
				}
			}
		);

		const fetch_discount_details = function (discount_type) {
			return $.ajax(
				{
					method: 'GET',
					url: ajaxurl,
					data: {
						action: 'discount_deals_fill_discount_fields',
						discount_type: discount_type
					}
				}
			);
		}
	}

	function init_rules() {
		var workflow = Backbone.Model.extend({});
		var rule_group = Backbone.Model.extend({
			initialize: function (app) {
				this.set('id', _.uniqueId('rule_group_'));
				this.set('app', app);
				this.set('rules', []);
			},
			create_rule: function () {
				var rules = this.get('rules');
				var rule_obj = new rule(this);
				rules.push(rule_obj);
				this.set('rules', rules);
				return rule_obj;
			},
			remove_rule: function (id) {
				var rules = this.get('rules');
				// find rule index - note we cant use _.findIndex due to backwards compatibility
				var index = rules.map(function (rule) {
					return rule.id;
				}).indexOf(id);
				// if only 1 rule left delete the whole group object
				if (rules.length > 1) {
					rules[index].destroy();
					rules.splice(index, 1);
					this.set('rules', rules);
				} else {
					rules[index].destroy(); // destroy the last rule
					this.clear();
				}
			},
			clear: function () {
				var app = this.get('app');
				app.remove_group(this.id);
			},
			destroy: function () {
				this.trigger('destroy');
			}
		});
		var rules = Backbone.Model.extend({
			initialize: function () {
				var app = this;
				var rule_options = [];
				this.set('rule_options', rule_options);
				this.reset_available_rules();
			},
			defaults: function () {
				return {
					all_rules: {},
					available_rules: {},
					rule_options: []
				};
			},
			reset_available_rules: function () {
				var discount_type = discount_deals.workflow.get('discount_type');
				this.set('available_rules', _.filter(this.get('all_rules'), function (rule) {
					return discount_type && discount_type.supplied_data_items.indexOf(rule.data_item) !== -1;
				}));
				var grouped_rules = {};
				_.each(this.get('available_rules'), function (rule) {
					if (!grouped_rules[rule.group]) grouped_rules[rule.group] = [];
					grouped_rules[rule.group].push(rule);

				});
				this.set('grouped_rules', grouped_rules);
			},
			create_group: function () {
				var rule_groups = this.get('rule_options');
				var rule_group_model = new rule_group(this);
				rule_group_model.create_rule();
				rule_groups.push(rule_group_model);
				this.set('rule_options', rule_groups);
				this.trigger('rule_group_change');
				return rule_group_model;
			},
			remove_group: function (id) {
				var groups = this.get('rule_options');
				// find index - note we cant use _.findIndex due to backwards compatibility
				var index = groups.map(function (group) {
					return group.id;
				}).indexOf(id);

				groups[index].destroy();
				groups.splice(index, 1);
				this.set('rule_options', groups);
				this.trigger('rule_group_change');
			}
		});
		var rule = Backbone.Model.extend({
			initialize: function (group) {
				this.set('id', _.uniqueId('rule_'));
				this.set('group', group);
				this.reset_options();
			},
			get_rule_object: function () {
				return data.all_rules[this.get('name')];
			},
			reset_options: function () {
				var name = this.get('name');
				var ruleObject = this.get_rule_object();
				if (name) {
					this.set('object', ruleObject);
				} else {
					this.set('object', {});
				}
				this.set('compare', false);
				this.set('value', false);
				this.load_select_options();
				return this;
			},
			load_select_options: function () {
				var self = this;
				var rule_object = this.get_rule_object();
				if (!rule_object || rule_object.type !== 'select' || rule_object.select_choices) {
					return this;
				}
				self.set('is_value_loading', true);
				$.getJSON(ajaxurl, {
					action: 'aw_get_rule_select_choices',
					rule_name: rule_object.name
				}, function (response) {
					if (!response.success)
						return;
					rule_object.select_choices = response.data.select_choices;
					self.set('is_value_loading', false);
					self.set('object', rule_object);
					self.trigger('options_loaded');
				});
				return this;
			},
			clear: function () {
				var group = this.get('group');
				group.remove_rule(this.id);
			},
			destroy: function () {
				this.trigger('destroy');
			}
		});

		var rules_view = Backbone.View.extend({
			el: $("#discount-deals-rules-meta-box-container"),
			meta_box: $("#discount_deals_workflow_rules_box"),
			template: wp.template("discount-deals-rules-container"),
			events: {
				'click .discount-deals-add-rule-group': 'add_rule_group'
			},
			add_rule_group: function () {
				var model = this.model.create_group();
				var view = new rule_group_view({model: model});
				this.$el.find('.discount-deals-rule-groups').append(view.render().el);

				$(document.body).trigger('wc-enhanced-select-init');
				return this;
			},
			add_empty_rules_message: function () {
				this.$el.find('.discount-deals-rule-groups').html(wp.template('discount-deals-empty-rule-groups'))
			},
			initialize: function () {
				this.listenTo(this.model, 'rule_group_change', this.maybe_show_empty_message);
				this.listenTo(this.model, 'change:grouped_rules', this.refresh_rules);
				this.render();
			},
			maybe_show_empty_message: function () {
				if (this.model.get('rule_options').length) {
					this.remove_empty_message();
				} else {
					this.add_empty_message();
				}
			},
			remove_empty_message: function () {
				this.$el.find('.discount-deals-rules-empty-message').remove();
			},
			add_empty_message: function () {
				this.$el.find('.discount-deals-rule-groups').html(wp.template('discount-deals-empty-rule-groups'));
			},
			refresh_rules: function () {
				_.each(this.model.get('rule_options'), function (group) {
					group.trigger('refresh_rules');
				});
			},
			render: function () {
				var self = this;
				self.$el.html(self.template({
					app: self,
					discount: false
				}));
				var rule_groups = self.$el.find('.discount-deals-rule-groups');
				var available_groups = self.model.get('rule_options');

				if (available_groups && available_groups.length > 0) {
					console.log("add groups");
				} else {
					this.add_empty_rules_message();
				}
				$(document.body).trigger('wc-enhanced-select-init');
				return this;
			}
		});

		var rule_view = Backbone.View.extend({
			className: 'discount-deals-rule-container',
			template: wp.template('discount-deals-rule'),
			events: {
				'click .discount-deals-remove-rule': 'clear',
				'change .discount-deals-rule-select': 'updated_rule_name',
				'change .discount-deals-rule-compare-field': 'updated_rule_compare_type',
				'change .discount-deals-rule-value-field': 'updated_rule_value',
			},
			updated_rule_value: function (e) {
				var value;
				if (this.has_multiple_value_fields()) {
					value = [];
					this.$el.find('.discount-deals-rule-value-field').each(function () {
						value.push($(this).val());
					});
				} else {
					value = e.target.value;
				}
				this.model.set('value', value);
			},
			has_multiple_value_fields: function () {
				var object = this.model.get('object');
				return object && object.has_multiple_value_fields;
			},
			updated_rule_name: function (e) {
				this.model.set('name', e.target.value).reset_options();
				this.render();
			},
			updated_rule_compare_type: function (e) {
				this.model.set('compare', e.target.value);
				this.render();
			},
			clear: function () {
				this.model.clear();
			},
			initialize: function () {
				this.listenTo(this.model, 'change:id', this.render);
				this.listenTo(this.model, 'change:group', this.render);
				this.listenTo(this.model, 'options_loaded', this.render);
				this.listenTo(this.model, 'destroy', this.remove);
			},
			get_field_name_base: function () {
				var id = this.model.get('id');
				var group = this.model.get('group');
				return 'discount_deals_workflow[rule_options][' + group.id + '][' + id + ']';
			},
			render: function () {
				var self = this;
				self.$el.html(self.template({
					rule: self.model.toJSON(),
					grouped_rules: discount_deals.rules.get('grouped_rules'),
					field_name_base: self.get_field_name_base()
				}));
				this.set_name();
				this.set_compare();
				$(document.body).trigger('wc-enhanced-select-init');
				return this;
			},
			set_name: function () {
				this.$el.find('.discount-deals-rule-select').val(this.model.get('name'));
			},
			set_compare: function () {
				var compare_field = this.$el.find('.discount-deals-rule-compare-field');
				var compare = this.model.get('compare');

				// Default selected value to first option.
				if (compare_field.filter('select').length && !compare) {
					var option_field = compare_field.find('option:first-child');
					var option_value = compare_field.find('option:first-child').prop('value');

					option_field.prop('selected', true);
					compare_field.val(option_value);
					this.model.set('compare', option_value);
				}
				if (compare) {
					compare_field.val(compare);
					compare_field.find('option[value~="' + compare + '"]').prop('selected', true);
				}
			},
		});

		var rule_group_view = Backbone.View.extend({
			className: 'discount-deals-rule-group',
			template: wp.template('discount-deals-rule-group'),
			events: {
				'click .discount-deals-add-rule': 'add_rule'
			},
			initialize: function () {
				this.listenTo(this.model, 'refresh_rules', this.refresh_rules);
				this.listenTo(this.model, 'change:id', this.refresh_rules);
				this.listenTo(this.model, 'destroy', this.remove);
			},
			refresh_rules: function () {
				_.each(this.model.get('rules'), function (rule) {
					rule.trigger('change:group');
				});
			},
			add_rule: function () {
				var model = this.model.create_rule();
				var view = new rule_view({model: model});
				this.$el.find('.rules').append(view.render().el);
				$(document.body).trigger('wc-enhanced-select-init');
				return this;
			},
			render: function () {
				var self = this;
				if (self.model.get('rules').length) {
					self.$el.html(self.template(self.model.toJSON()));
					self.$el.find('.rules').empty();

					_.each(self.model.get('rules'), function (rule) {
						var view = new rule_view({model: rule});
						self.$el.find('.rules').append(view.render().el);
					});
				}
				$(document.body).trigger('wc-enhanced-select-init');
				return this;
			},
			clear: function () {
				this.undelegateEvents();
				this.model.clear();
			}
		});

		var workflow_view = Backbone.View.extend({
			el: $('form#post'),
			trigger_select: $('#discount_deals_workflow_type'),
			trigger_description: $('.discount_deals_workflow_type_description'),
			initialize: function () {
				this.listenTo(this.model, 'change:discount_type', this.change_discount_type);
				this.model.set('previous_discount_type', this.trigger_select.val());
			},
			change_discount_type: function () {
				discount_deals.rules.reset_available_rules();
				$(document.body).trigger('wc-enhanced-select-init');
				this.complete_discount_type_change();
			},
			complete_discount_type_change: function () {
				// discount_deals.rules.clearIncompatibleRules();
				this.update_discount_type_description();
				// update the prev trigger value
				this.model.set('previous_discount_type', this.trigger_select.val());
				$(document.body).trigger('discount_deals_discount_type_changed');
			},
			update_discount_type_description: function () {
				var discount_type = this.model.get('discount_type');
				if (discount_type && discount_type.description) {
					this.trigger_description.html('<p class="discount-deals-field-description">' + discount_type.description + '</p>');
				} else {
					this.trigger_description.html('');
				}
			},
		});
		discount_deals.workflow = new workflow(data);

		discount_deals.workflow_view = new workflow_view({
			model: discount_deals.workflow
		});

		discount_deals.rules = new rules({
			all_rules: data.all_rules,
			raw_rule_options: data.rule_options
		});

		discount_deals.rules_view = new rules_view({
			model: discount_deals.rules
		});
	}


	$(function () {
		init_discounts();
		init_rules();
	});
})(jQuery, discount_deals_workflow_localize_script);
