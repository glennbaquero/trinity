<template>
	<div>
		<div class="row">
			<div class="col-md-4">
				<label>Sort by: </label>
				<select
				v-model="selectedSort"
				class="form-control">
					<template v-for="type in sortTypes">
						<option :value="type">{{ type.name }}</option>							
					</template>
				</select>		
			</div>

			<template v-if="selectedSort">
				
				<template v-if="selectedSort.value === 1">
					<selector class="col-sm-4"
					v-model="filter"
					name="month"
					label="Months"
					:items="months"
					item-value="value"
					item-text="name"
					empty-text="None"
					placeholder="Please select a month"
					></selector>
				</template>

				<template v-else>
					<selector class="col-sm-4"
					v-model="filter"
					name="quarter"
					label="Quarters"
					:items="quarters"
					item-value="value"
					item-text="name"
					empty-text="None"
					placeholder="Please select a Quarter"
					></selector>
				</template>


				<selector class="col-sm-4"
				v-model="year"
				name="years"
				label="Year"
				:items="years"
				item-value="years"
				item-text="years"
				empty-text="None"
				placeholder="Please select a year"
				></selector>

			</template>

			<div class="col-md-12">
				<div class="text-right">
					<button 
					@click="submit()"
					:disabled="!selectedSort || !filter || !year"
					class="btn btn-primary">Submit</button>
				</div>
			</div>
		</div>

		<loader
			:loading="loading"
		></loader>

		<template v-if="reports">
		
			<hr />

			<div class="row">
			
				<box-widget
				class="col-sm-4 col-md-4"
				label="Target Sales"
				bg-icon="bg-info"
				icon="fas fa-chart-bar"
				:count="reports.target"
				></box-widget>

				<box-widget
				class="col-sm-4 col-md-4"
				label="Sales"
				bg-icon="bg-success"
				icon="fas fa-chart-bar"
				:count="reports.sales"
				></box-widget>

				<box-widget
				class="col-sm-4 col-md-4"
				label="Sales Percentage"
				bg-icon="bg-primary"
				icon="fas fa-percent"
				:count="reports.salesPercentage"
				></box-widget>

				<box-widget
				class="col-sm-4 col-md-4"
				label="Total Incentives"
				bg-icon="bg-danger"
				icon="fas fa-money-bill-wave-alt"
				:count="reports.incentives"
				></box-widget>

				<box-widget
				class="col-sm-4 col-md-4"
				label="Target Patients"
				bg-icon="bg-info"
				icon="fas fa-user"
				:count="reports.patientsTarget"
				></box-widget>

				<box-widget
				class="col-sm-4 col-md-4"
				label="Active Patients"
				bg-icon="bg-success"
				icon="fas fa-user"
				:count="reports.actualPatients"
				></box-widget>

				<box-widget
				class="col-sm-4 col-md-4"
				label="Target Doctors"
				bg-icon="bg-info"
				icon="fas fa-user-md"
				:count="reports.doctorsTarget"
				></box-widget>

				<box-widget
				class="col-sm-4 col-md-4"
				label="Active Doctors"
				bg-icon="bg-info"
				icon="fas fa-user-md"
				:count="reports.actualDoctors"
				></box-widget>


				<box-widget
				class="col-sm-4 col-md-4"
				label="Target Call Reach"
				bg-icon="bg-info"
				icon="fas fa-phone-alt"
				:count="reports.targetCallReach"
				></box-widget>				

				<box-widget
				class="col-sm-4 col-md-4"
				label="Actual Call"
				bg-icon="bg-info"
				icon="fas fa-phone-alt"
				:count="reports.actualCalls"
				></box-widget>

				<box-widget
				class="col-sm-4 col-md-4"
				label="Target Call Rate"
				bg-icon="bg-info"
				icon="fas fa-phone-alt"
				:count="reports.targetCallRate"
				></box-widget>

				<box-widget
				class="col-sm-4 col-md-4"
				label="Target Call Rate"
				bg-icon="bg-info"
				icon="fas fa-phone-alt"
				:count="reports.targetCallRate"
				></box-widget>


			</div>

		</template>

	</div>
</template>
<script>

import Select from 'Components/inputs/Select.vue';
import Loader from 'Components/loaders/Loader.vue';

import BoxWidget from 'Components/widgets/BoxWidget.vue';

export default {
	props: {
		submitUrl: String,

		months: {
			type: Array,
			default: [],
		},

		years: {
			type: Array,
			default: [],
		},
	},

	components: {
		'selector': Select,
		'loader': Loader,
		'box-widget': BoxWidget,
	},

	watch: {

	},

	data: function() {
		return {
			reports: null,

			sortTypes: [
				{name: 'Month', value: 1},
				{name: 'Quarter', value: 2},				
			],

			quarters: [
				{name: '1st', value: 1},
				{name: '2nd', value: 2},
				{name: '3rd', value: 3},
				{name: '4th', value: 4},
			],
			selectedSort: null,

			filter: null,
			year: null,

			loading: false,
		}
	},

	methods: {

		submit: function() {
			var data = {
				sortType: this.selectedSort.value,
				filter: this.filter,
				year: this.year,
			};

			this.loading = true;
			axios.post(this.submitUrl, data)
				.then((response) => {
					this.loading = false;

					if(response.status === 200) {
						this.reports = response.data.reports;
					}

				}).catch((error)=> {
					this.loading = false;
				});			
		}

	},
}

</script>