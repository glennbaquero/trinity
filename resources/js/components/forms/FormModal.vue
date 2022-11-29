<template>
	<div :class="label ? 'd-inline-block' : ''">
		<button v-if="label" @click="show" type="button" class="btn" :class="[ color, buttonSize ]"><i :class="icon ? icon + ' mr-1' : ''"></i>{{ label }}</button>

		<div ref="elem" class="modal fade show" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog relative" role="document">
				<div class="modal-content">
					<form-request 
					:submit-url="submitUrl" 
					@load="load" 
					@success="success" 
					@error="error" 
					:confirm-dialog="confirmDialog" 
	                :title="title"
	                :message="message"
					:sync-on-success="syncOnSuccess">
						<div class="modal-header">
							<h5 class="modal-title text-dark"><slot name="title"></slot></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" :disabled="loading">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body text-dark">
							<slot></slot>
						</div>
						<div class="modal-footer">

							<button 
							v-if="cancelText" 
							@click="hide" 
							type="button" 
							class="btn" 
							:class="[ cancelColor ]" 
							:disabled="loading">
								{{ cancelText }}
							</button>

							<action-button 
							v-if="okText" 
							type="submit"
							:disabled="loading" 
							:loading="loading" 
							:color="okColor">
								{{ okText }}
							</action-button>

							<slot name="footer"></slot>
						</div>
					</form-request>
				</div>
			</div>
		</div>
	</div>

</template>

<script type="text/javascript">
import FormRequest from './FormRequest.vue';
import ActionButton from '../buttons/ActionButton.vue';
import LoadMixin from '../loaders/mixin.js';
import ConfirmProps from '../../mixins/confirm/props.js';

export default {
	mounted() {
		this.setup();
	},

	methods: {
		setup() {
			this.elem = $(this.$refs.elem);
		},

		show() {
			this.elem.modal({
				backdrop: this.persistent ? 'static': true,
			});
		},

		hide() {
			$(this.$refs.elem).modal('hide');
		},

		success() {
			if (!this.persistOnSuccess) {
				this.hide();
			}

			this.$emit('success');
		},

		error() {
			this.$emit('error');
		},
	},

	computed: {
		buttonSize() {
			return this.small ? 'btn-sm' : null;
		},
	},

	watch: {
		value(value) {
			if (value && this.elem) {
				this.show();
			} else {
				this.hide();
			}
		}
	},

	data() {
		return {
			elem: null,
		}
	},

	props: {
		/**
		 * Form
		 */

		submitUrl: String,
		okText: String,
		okColor: String,
		cancelText: String,
		cancelColor: String,

		/**
		 * Show Modal Button
		 */
		
		label: String,
		icon: String,
		color: String,

		small: {
			default: false,
			type: Boolean,
		},

		persistOnSuccess: {
			default: false,
			type: Boolean,
		},

		persistent: {
			default: false,
			type: Boolean,
		},

		syncOnSuccess: {
			default: false,
			type: Boolean,
		},

		value: {},
	},

	model: {
		props: 'value',
		event: 'change',
	},

	components: {
		'form-request': FormRequest,
		'action-button': ActionButton,
	},

	mixins: [ LoadMixin, ConfirmProps ],
}
</script>