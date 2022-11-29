<template>
	<div>
		<div class="col-sm-7 col-md-7">
			<div class="row">
				<box-widget
				class="col-sm-12 col-md-12"
				:label="`Total Sales for year ${new Date().getFullYear()}`"
				bg-icon="bg-primary"
				icon="far fa-money-bill-alt"
				:count="totalSales"
				></box-widget>
			</div>

			<div class="card elevation-1">
				<div class="card-body">					
					<chart
					:items="admin_chart"
					format="Php"
					type="line"
					:title="`Total Sales per month for year ${new Date().getFullYear()}`"
					title-position="top"
					></chart>
				</div>
			</div>

			<h5 class="text-secondary font-weight-bold mt-2">Administrators</h5>
			<div class="row">
				<box-widget
				class="col-sm-6 col-md-6"
				label="Active"
				bg-icon="bg-success"
				icon="fas fa-check"
				:count="admins"
				></box-widget>

				<box-widget
				class="col-sm-6 col-md-6"
				label="Inactive"
				bg-icon="bg-danger"
				icon="fa fa-ban"
				:count="admins_inactive"
				></box-widget>
			</div>

			<h5 class="text-secondary font-weight-bold mt-2">App Users</h5>
			<div class="row">
				<box-widget
				class="col-sm-6 col-md-6"
				label="Patients"
				bg-icon="bg-warning"
				icon="fas fa-user-injured"
				:count="patientsCount"
				></box-widget>

				<box-widget
				class="col-sm-6 col-md-6"
				label="Doctors"
				bg-icon="bg-success"
				icon="fa fa-user-md"
				:count="doctorsCount"
				></box-widget>

				<box-widget
				class="col-sm-6 col-md-6"
				label="Med Reps"
				bg-icon="bg-danger"
				icon="fa fa-briefcase-medical"
				:count="medRepsCount"
				></box-widget>
			</div>
		</div>

		<div class="col-sm-5 col-md-5">
			<div class="row">
				<div class="col-sm-12 col-md-12 d-flex align-items-center justify-content-between">
					<h5 class="text-secondary font-weight-bold">Inventory Stocks Status</h5>
					<a class="text-info" :href="inventoryUrl">View all</a>
				</div>

				<template v-for="report in inventory_reports">
					<box-widget
					class="col-sm-6 col-md-6"
					:label="report['label']"
					:bg-icon="report['class']"
					:icon="report['icon']"
					:count="report['count']"
					></box-widget>				
				</template>
			</div>

			<div class="row mt-3">
				<h5 class="col-sm-12 col-md-12 text-secondary font-weight-bold">Orders</h5>
				<div v-for="(status, s) in statuses" :key="s" class="col-sm-6 col-md-6 mt-2">
					<div class="elevation-1 p-3 d-flex align-items-center justify-content-between">
						<span class="text-secondary">{{ status.name }}</span>
						<span class="font-weight-bold">{{ status.count }}</span>
					</div>
				</div>
			</div>

			<div class="row mt-5">
				<div class="col-sm-12 col-md-12 d-flex align-items-center justify-content-between">
					<h5 class="text-secondary font-weight-bold">Recent Activities</h5>
					<a class="text-info" :href="logsIndexUrl">View all</a>
				</div>
				<div v-for="(activity, i) in activities" :key="i" :class="`col-sm-12 col-md-12 ${i ? 'mt-3' : ''}`">
				    <div class="elevation-1 p-3 bg-palewhite">
				        <p class="m-0">{{ `${activity.name.replace('Item', activity.subject_type)} by ${activity.caused_by}` }}</p>
				        <i class="text-secondary">{{ activity.date }} ({{ activity.time }})</i>
				    </div>
				</div>
			</div>
		</div>
		
		<loader :loading="loading"></loader>
	</div>
</template>

<style scoped>
	.bg-palewhite {
		background: #efefef;
	}
</style>

<script type="text/javascript">
import FetchMixin from 'Mixins/fetch.js';

import Charts from 'Components/charts/Chart.vue';
import BoxWidget from 'Components/widgets/BoxWidget.vue';

export default {
	mounted() {
		this.fetchData(this.salesUrl, 'admin_chart', 'sales');
		this.fetchData(this.logsUrl, 'activities', 'recent');
		this.fetchData('admin/status-types/get', 'statuses', 'statuses');
		this.fetchData('admin/users/get', 'patientsCount', 'patientsCount');
		this.fetchData('admin/doctors/get', 'doctorsCount', 'doctorsCount');
		this.fetchData('admin/representatives/get', 'medRepsCount', 'medRepsCount');
		this.fetchData('admin/inventories/get/status-report', 'inventory_reports', 'inventory_reports');
	},
	methods: {
		fetchData(url, stateKey, payloadKey) {
			axios.get(url)
				.then(response => {
					this[stateKey] = response.data[payloadKey];
				});
		},
		fetchSuccess(data) {
			this.admins = data.admins;
			this.admins_inactive = data.admins_inactive;
			this.items = data.items;
		}
	},

	data() {
		return {
			admins: 0,
			userItems: [],
			admin_chart: {},
			admins_inactive: [],
			items: [],
			activities: [],
			statuses: [],
			patientsCount: null,
			doctorsCount: null,
			medRepsCount: null,
			inventory_reports: [],
			totalSales: 0,
		}
	},

	watch: {
		admin_chart: {
			handler: function() {
				var obj = this.admin_chart,
					total = 0;
				for (var key in obj) {
					if(obj.hasOwnProperty(key) && obj[key]) {
						total += parseFloat(obj[key]);
					}
				}
				this.totalSales = 'Php ' + total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') + '.00';
			}, 
			deep: true,
		},
	},

	props: ['logsUrl', 'logsIndexUrl', 'salesUrl', 'inventoryUrl'],

	components: {
		'chart': Charts,
		'box-widget': BoxWidget,
	},

	mixins: [ FetchMixin ],
}
</script>