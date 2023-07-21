/**
 * Code contains all stuffs related to admin
 *
 * @package Discount_Deals
 */

(function ($, data) {
	'use strict';
	let discount_deals = {}

	function init_discounts() {
		let discount_meta_box  = $("#discount_deals_workflow_discounts_box");
		let promotion_meta_box = $("#discount_deals_workflow_promotions_box");
		$(document).on('click', '.discount-deals-remove-cart-discount', function () {
			let $row = $(this).closest('tr');
			$row.remove();
		});
		$(document).on('change', '.discount-deals-free-type', function () {
			let value = $(this).val();
			let $row  = $(this).closest('tr');
			if ('cheapest_in_category' === value || 'biggest_in_category' === value) {
				$row.find('.bxgy-free-category').removeClass('discount-deals-hidden');
				$row.find('.bxgy-free-product').addClass('discount-deals-hidden');
			} else if ('products' === value) {
				$row.find('.bxgy-free-category').addClass('discount-deals-hidden');
				$row.find('.bxgy-free-product').removeClass('discount-deals-hidden');
			} else {
				$row.find('.bxgy-free-category').addClass('discount-deals-hidden');
				$row.find('.bxgy-free-product').addClass('discount-deals-hidden');
			}
			$row.find('.discount-deals-bxgy-products-select option').remove();
			$row.find('.discount-deals-bxgy-category-select option').remove();
		})
		$(document).on('change', '.cart-discount-type', function () {
			let value = $(this).val();
			let $row  = $(this).closest('tr');
			if (value === "free_shipping" || value === "free") {
				$row.find('.cart-discount-value').attr('disabled', true).val('');
				$row.find('.discount-value-symbol').html('');
				$row.find('.bxgy-show-promotion-for-y').addClass('discount-deals-hidden');
			} else if (value === "flat") {
				$row.find('.cart-discount-value').attr('disabled', false);
				$row.find('.cart-max-discount').attr('disabled', true);
				let symbol = $row.find('.discount-value-symbol').data('currency');
				$row.find('.discount-value-symbol').html(symbol);
				$row.find('.bxgy-show-promotion-for-y').removeClass('discount-deals-hidden');
			} else {
				$row.find('.cart-discount-value').attr('disabled', false);
				$row.find('.discount-value-symbol').html('%');
				$row.find('.bxgy-show-promotion-for-y').removeClass('discount-deals-hidden');
			}
		});
		$(document).on('change', '.noc-discount-type', function () {
			let value = $(this).val();
			let $row  = $(this).closest('tr');
			if (value === "flat") {
				let symbol = $row.find('.discount-value-symbol').data('currency');
				$row.find('.discount-value-symbol').html(symbol);
			} else {
				$row.find('.discount-value-symbol').html('%');
			}
		});
		$(document).on('click', '.discount-deals-add-cart-discount', function () {
			let $table              = discount_meta_box.find('.cart-discount-details-table tbody');
			let total_discounts     = $table.find('tr').length;
			let $new_discount       = $table.children().last().clone(true);
			let timestamp           = Date.now();
			const change_input_name = function ($input) {
				let name = $input.data('name');
				if (name) {
					let new_name = name.replaceAll('--rule_id--', timestamp);
					$input.attr('name', new_name);
				}
			}
			$new_discount.find('.bxgy-product-select-container').html($("#temp-free-products-select").html());
			$new_discount.find('.bxgy-category-select-container').html($("#temp-free-category-select").html());
			$new_discount.find('input').each(function (i) {
				change_input_name($(this))
				if ('checkbox' !== $(this).attr('type')) {
					$(this).val('');
				}
			});
			$new_discount.find('select').each(function (i) {
				change_input_name($(this))
				let default_value = $(this).data('default-val');
				$(this).val(default_value);
			});
			let symbol = $new_discount.find('.discount-value-symbol').data('currency');
			$new_discount.find('.discount-value-symbol').html(symbol);
			$new_discount.find('.cart-discount-value').attr('disabled', false);
			if (total_discounts >= 1) {
				$new_discount.find('.discount-deals-remove-cart-discount').removeClass('discount-deals-hidden')
			}
			$table.append($new_discount);
			$(document.body).trigger('wc-enhanced-select-init');
			$('.cart-discount-type').trigger('change');
		});
		$(document).on("change", "#discount_deals_workflow_type", function () {
			let discount_type = $(this).val();
			discount_meta_box.find('tr.discount-options-field-container').remove();
			promotion_meta_box.find('table #discount-deals-promotion-details-container tr').remove();
			promotion_meta_box.find('table tfoot').removeClass('discount-deals-hidden');
			if (discount_type) {
				$(".discount-deals-fp-loader").removeClass('discount-deals-hidden');
				fetch_discount_details(discount_type).done(function (response) {
					$(".discount-deals-fp-loader").addClass('discount-deals-hidden');
					if (!response.success) {
						return;
					}
					promotion_meta_box.find('table tfoot').addClass('discount-deals-hidden');
					discount_meta_box.find('tbody').append(response.data.fields);
					promotion_meta_box.find('table #discount-deals-promotion-details-container').append(response.data.promotional_fields);
					discount_deals.workflow.set('discount_type', response.data.discount_details);
					discount_deals.rules.clear_incompatible_rules();
					setTimeout(function () {
						$('input[name="discount_deals_workflow[dd_promotion][enable]"]').trigger('change')
					}, 100)
				});
			} else {
				discount_deals.workflow.set('discount_type', false);
				discount_deals.rules.clear_incompatible_rules();
				promotion_meta_box.find('table tfoot').removeClass('discount-deals-hidden');
			}
		});

		const fetch_discount_details = function (discount_type) {
			return $.ajax({
				method: 'GET', url: ajaxurl, data: {
					action: 'discount_deals_fill_discount_fields', discount_type: discount_type
				}
			});
		}
	}

	function init_rules() {
		let workflow   = Backbone.Model.extend({});
		let rule_group = Backbone.Model.extend({
			initialize: function (app) {
				this.set('id', _.uniqueId('rule_group_'));
				this.set('app', app);
				this.set('rules', []);
			}, create_rule: function () {
				let rules    = this.get('rules');
				let rule_obj = new rule(this);
				rules.push(rule_obj);
				this.set('rules', rules);
				return rule_obj;
			}, remove_rule: function (id) {
				let rules = this.get('rules');
				// Find rule index - note we cant use _.findIndex due to backwards compatibility.
				let index = rules.map(function (rule) {
					return rule.id;
				}).indexOf(id);
				// If only 1 rule left delete the whole group object.
				if (rules.length > 1) {
					rules[index].destroy();
					rules.splice(index, 1);
					this.set('rules', rules);
				} else {
					rules[index].destroy(); // Destroy the last rule.
					this.clear();
				}
			}, clear: function () {
				let app = this.get('app');
				app.remove_group(this.id);
			}, destroy: function () {
				this.trigger('destroy');
			}
		});
		let rules      = Backbone.Model.extend({
			initialize: function () {
				let app          = this;
				let rule_options = [];
				if (this.get('raw_rule_options')) {
					_.each(this.get('raw_rule_options'), function (raw_rule_group) {
						let group = new rule_group(app);
						let rules = [];

						_.each(raw_rule_group, function (raw_rule) {
							let rule_obj = new rule(group);
							rule_obj.set('name', raw_rule.name);
							rule_obj.reset_options();
							rule_obj.set('compare', raw_rule.compare);
							rule_obj.set('value', raw_rule.value);
							// For objects.
							if (raw_rule.selected) {
								rule_obj.set('selected', raw_rule.selected);
							}
							rules.push(rule_obj);
						});
						group.set('rules', rules);
						rule_options.push(group);
					});
				}
				this.set('rule_options', rule_options);
				this.reset_available_rules();
			}, defaults: function () {
				return {
					all_rules: {}, available_rules: {}, rule_options: []
				};
			}, reset_available_rules: function () {
				let discount_type = discount_deals.workflow.get('discount_type');
				this.set('available_rules', _.filter(this.get('all_rules'), function (rule) {
					return discount_type && discount_type.supplied_data_items.indexOf(rule.data_item) !== -1;
				}));
				let grouped_rules = {};
				_.each(this.get('available_rules'), function (rule) {
					if (!grouped_rules[rule.group]) {
						grouped_rules[rule.group] = [];
					}
					grouped_rules[rule.group].push(rule);

				});
				this.set('grouped_rules', grouped_rules);
			}, create_group: function () {
				let rule_groups      = this.get('rule_options');
				let rule_group_model = new rule_group(this);
				rule_group_model.create_rule();
				rule_groups.push(rule_group_model);
				this.set('rule_options', rule_groups);
				this.trigger('rule_group_change');
				return rule_group_model;
			}, remove_group: function (id) {
				let groups = this.get('rule_options');
				// Find index - note we cant use _.findIndex due to backwards compatibility.
				let index = groups.map(function (group) {
					return group.id;
				}).indexOf(id);

				groups[index].destroy();
				groups.splice(index, 1);
				this.set('rule_options', groups);
				this.trigger('rule_group_change');
			}, is_rule_available: function (rule_name) {
				let available_rules = discount_deals.rules.get('available_rules');
				let names           = _.pluck(available_rules, 'name');
				return _.indexOf(names, rule_name) !== -1;
			}, clear_incompatible_rules: function () {
				let rules_to_remove = [];
				_.each(discount_deals.rules.get('rule_options'), function (rule_group) {
					_.each(rule_group.get('rules'), function (rule) {
						if (rule && !discount_deals.rules.is_rule_available(rule.get('name'))) {
							rules_to_remove.push(rule);
						}
					});
				});

				// Clear out of initial loop to avoid index changing issues, when rules are cleared
				_.each(rules_to_remove, function (rule) {
					rule.clear();
				});
			},
		});
		let rule       = Backbone.Model.extend({
			initialize: function (group) {
				this.set('id', _.uniqueId('rule_'));
				this.set('group', group);
				this.reset_options();
			}, get_rule_object: function () {
				return data.all_rules[this.get('name')];
			}, reset_options: function () {
				let name       = this.get('name');
				let ruleObject = this.get_rule_object();
				if (name) {
					this.set('object', ruleObject);
				} else {
					this.set('object', {});
				}
				this.set('compare', false);
				this.set('value', false);
				this.load_select_options();
				return this;
			}, load_select_options: function () {
				let self        = this;
				let rule_object = this.get_rule_object();
				if (!rule_object || rule_object.type !== 'select' || rule_object.select_choices) {
					return this;
				}
				self.set('is_value_loading', true);
				$.getJSON(ajaxurl, {
					action: 'discount_deals_get_rule_select_choices', rule_name: rule_object.name
				}, function (response) {
					if (!response.success) {
						return;
					}
					rule_object.select_choices = response.data.select_choices;
					self.set('is_value_loading', false);
					self.set('object', rule_object);
					self.trigger('options_loaded');
				});
				return this;
			}, clear: function () {
				let group = this.get('group');
				group.remove_rule(this.id);
			}, destroy: function () {
				this.trigger('destroy');
			}
		});

		let rules_view = Backbone.View.extend({
			el: $("#discount-deals-rules-meta-box-container"),
			meta_box: $("#discount_deals_workflow_rules_box"),
			template: wp.template("discount-deals-rules-container"),
			events: {
				'click .discount-deals-add-rule-group': 'add_rule_group'
			},
			add_rule_group: function () {
				let model = this.model.create_group();
				let view  = new rule_group_view({model: model});
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
				let self = this;
				self.$el.html(self.template({
					app: self, discount: false
				}));
				let rule_groups      = self.$el.find('.discount-deals-rule-groups');
				let available_groups = self.model.get('rule_options');

				if (available_groups && available_groups.length > 0) {
					_.each(available_groups, function (group) {
						let view = new rule_group_view({model: group});
						rule_groups.append(view.render().el);
					});
				} else {
					this.add_empty_rules_message();
				}
				$(document.body).trigger('wc-enhanced-select-init');
				return this;
			}
		});

		let rule_view = Backbone.View.extend({
			className: 'discount-deals-rule-container', template: wp.template('discount-deals-rule'), events: {
				'click .discount-deals-remove-rule': 'clear',
				'change .discount-deals-rule-select': 'updated_rule_name',
				'change .discount-deals-rule-compare-field': 'updated_rule_compare_type',
				'change .discount-deals-rule-value-field': 'updated_rule_value',
				'change .discount-deals-rule-value-from': 'update_minimum_from_value_date',
			}, update_minimum_from_value_date: function () {
				let $from = this.$el.find('.discount-deals-rule-value-from');
				let $to   = this.$el.find('.discount-deals-rule-value-to');

				if ($from.length && $to.length) {
					$to.datetimepicker({
						format: 'Y-m-d H:i', minDate: $from.val()
					});
				}
			}, updated_rule_value: function (e) {
				let value;
				if (this.has_multiple_value_fields()) {
					value = [];
					this.$el.find('.discount-deals-rule-value-field').each(function () {
						value.push($(this).val());
					});
				} else {
					value = e.target.value;
				}
				this.model.set('value', value);
			}, has_multiple_value_fields: function () {
				let object = this.model.get('object');
				return object && object.has_multiple_value_fields;
			}, updated_rule_name: function (e) {
				this.model.set('name', e.target.value).reset_options();
				this.render();
			}, updated_rule_compare_type: function (e) {
				this.model.set('compare', e.target.value);
				this.render();
			}, clear: function () {
				this.model.clear();
			}, initialize: function () {
				this.listenTo(this.model, 'change:id', this.render);
				this.listenTo(this.model, 'change:group', this.render);
				this.listenTo(this.model, 'options_loaded', this.render);
				this.listenTo(this.model, 'destroy', this.remove);
			}, get_field_name_base: function () {
				let id    = this.model.get('id');
				let group = this.model.get('group');
				return 'discount_deals_workflow[rule_options][' + group.id + '][' + id + ']';
			}, render: function () {
				let self = this;
				self.$el.html(self.template({
					rule: self.model.toJSON(),
					grouped_rules: discount_deals.rules.get('grouped_rules'),
					field_name_base: self.get_field_name_base()
				}));
				this.set_name();
				this.set_compare();
				this.set_value();
				this.init_date_picker();
				this.maybe_toggle_value_display();
				$(document.body).trigger('wc-enhanced-select-init');
				return this;
			}, set_value: function () {
				let selected_title = this.model.get('selected');
				let selected_id    = this.model.get('value');
				let value_field;
				let select_value = this.$el.find('.discount-deals-rule-select').val();
				if (['cart_item_count', 'cart_total', 'customer_total_spent', 'customer_order_count'].includes(select_value)) {
					this.$el.find('.discount-deals-rule-value-field').attr('type', 'number');
				}
				// TODO: check && selected_id is causing any issue
				if (selected_title && selected_id) {
					value_field = this.$el.find('.discount-deals-rule-value-field');
					if (value_field.is('select')) {
						if (_.isArray(selected_id)) {
							_.each(selected_id, function (id, i) {
								value_field.append($('<option>', {
									value: id, text: selected_title[i],
								}));
							});
						} else {
							value_field.append($('<option>', {
								value: selected_id, text: selected_title
							}));
						}
					} else {
						value_field.attr('data-selected', selected_title);
					}
				}

				if (selected_id) {
					let $fields = this.$el.find('.discount-deals-rule-value-field');
					let self    = this;
					if (this.has_multiple_value_fields()) {
						if (_.isArray(selected_id)) {
							$fields.each(function (i, el) {
								$(el).val(selected_id[i]);
							});
						}
						if (_.isObject(selected_id)) {
							Object.keys(selected_id).forEach(function (key) {
								$('.discount-deals-rule-value-' + key, self.$el).val(selected_id[key]);
							});
						}
					} else {
						$fields.val(selected_id);
					}
				}
			}, maybe_toggle_value_display: function () {
				let compare     = this.model.get('compare');
				let value_field = this.$el.find('[data-discount-deals-compare]');
				if (value_field.length) {
					// Hide value fields.
					value_field.addClass('discount-deals-hidden').prop('required', false).find('select, input').prop('required', false);

					// Show our selected rules.
					value_field.filter('[data-discount-deals-compare~="' + compare + '"]').removeClass('discount-deals-hidden').prop('required', true).find('select, input').prop('required', true);
				}
			}, init_date_picker: function () {
				let compare = this.$el.find('.discount-deals-rule-compare-field').val();
				if (['is_not_on', 'is_on'].includes(compare)) {
					this.$el.find('.discount-deals-date-picker').datepicker({
						dateFormat: 'yy-mm-dd', showButtonPanel: true,
					});
				} else if (['is_after', 'is_before'].includes(compare)) {
					this.$el.find('.discount-deals-date-picker').attr('pattern', '[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}');
					this.$el.find('.discount-deals-date-picker').datetimepicker({
						format: 'Y-m-d H:i',
					});
				} else {
					this.$el.find('.discount-deals-date-picker').datetimepicker({
						format: 'Y-m-d H:i',
					});
					this.$el.find('.discount-deals-date-time-picker').datetimepicker({
						format: 'Y-m-d H:i',
					});
				}
			}, set_name: function () {
				this.$el.find('.discount-deals-rule-select').val(this.model.get('name'));
			}, set_compare: function () {
				let compare_field = this.$el.find('.discount-deals-rule-compare-field');
				let compare       = this.model.get('compare');

				// Default selected value to first option.
				if (compare_field.filter('select').length && !compare) {
					let option_field = compare_field.find('option:first-child');
					let option_value = compare_field.find('option:first-child').prop('value');

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

		let rule_group_view = Backbone.View.extend({
			className: 'discount-deals-rule-group', template: wp.template('discount-deals-rule-group'), events: {
				'click .discount-deals-add-rule': 'add_rule'
			}, initialize: function () {
				this.listenTo(this.model, 'refresh_rules', this.refresh_rules);
				this.listenTo(this.model, 'change:id', this.refresh_rules);
				this.listenTo(this.model, 'destroy', this.remove);
			}, refresh_rules: function () {
				_.each(this.model.get('rules'), function (rule) {
					rule.trigger('change:group');
				});
			}, add_rule: function () {
				let model = this.model.create_rule();
				let view  = new rule_view({model: model});
				this.$el.find('.rules').append(view.render().el);
				$(document.body).trigger('wc-enhanced-select-init');
				return this;
			}, render: function () {
				let self = this;
				if (self.model.get('rules').length) {
					self.$el.html(self.template(self.model.toJSON()));
					self.$el.find('.rules').empty();

					_.each(self.model.get('rules'), function (rule) {
						let view = new rule_view({model: rule});
						self.$el.find('.rules').append(view.render().el);
					});
				}
				$(document.body).trigger('wc-enhanced-select-init');
				return this;
			}, clear: function () {
				this.undelegateEvents();
				this.model.clear();
			}
		});

		let workflow_view       = Backbone.View.extend({
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
				this.update_discount_type_description();
				// update the prev trigger value.
				this.model.set('previous_discount_type', this.trigger_select.val());
				$(document.body).trigger('discount_deals_discount_type_changed');
			},
			update_discount_type_description: function () {
				let discount_type = this.model.get('discount_type');
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
		discount_deals.rules         = new rules({
			all_rules: data.all_rules, raw_rule_options: data.rule_options
		});
		discount_deals.rules_view    = new rules_view({
			model: discount_deals.rules
		});
	}

	function init_promotions() {
		const toggle_promotion_fields = function (status) {
			if (status === "yes") {
				$("#discount_deals_workflow_promotions_box tbody tr:gt(0)").removeClass('discount-deals-hidden');
			} else {
				$("#discount_deals_workflow_promotions_box tbody tr:gt(0)").addClass('discount-deals-hidden');
			}
		}

		$(document).on('change', 'input[type=radio][name="discount_deals_workflow[dd_promotion][enable]"]', function () {
			toggle_promotion_fields($(this).val());
		})
	}

	$(function () {
		init_discounts();
		init_rules();
		init_promotions();
	});
})(jQuery, discount_deals_workflow_localize_script);
