 <template>
	<div class="form-group">
		<div class="input-group col-sm-12">
			<input v-model="startdate" type="hidden" name="start_date">
			<input v-model="enddate" type="hidden" name="end_date">
			<button ref="elem" type="button" class="btn btn-light border-bottom-right-radius-0 border-top-right-radius-0 border d-inline-block">
				<i class="fa fa-calendar mr-2"></i>
				<span>{{ startdate && enddate ? startDisplay + ' - ' + endDisplay : ' Select date here...' }}</span>
				<i class="fa fa-caret-down ml-2"></i>
			</button>
            <span @click="clear" class="btn btn-light border border-bottom-left-radius-0 border-top-left-radius-0 d-inline-block">
				<i class="fa fa-times"></i>
			</span>
		</div>
	</div>
</template>

<script type="text/javascript">
import dayjs from 'dayjs';
import daterangepicker from 'bootstrap-daterangepicker';
import daterangepickercss from 'bootstrap-daterangepicker/daterangepicker.css';

export default {
	mounted() {
		this.$nextTick(() => {
			this.init();
		});
	},

	methods: {
		init() {
			let daterange = $(this.$refs.elem);
			let startDate = dayjs().startOf('year').format('YYYY-MM-DD');
			let endDate = dayjs().endOf('year').format('YYYY-MM-DD');

			setTimeout(() => {
				this.elem = daterange.daterangepicker({
					locale: {
			    		format: 'YYYY-MM-DD',
				    },
					startDate: startDate,
					endDate: endDate,
					opens: 'right',
					ranges: {
						'This Year': [dayjs().startOf('year').format('YYYY-MM-DD'), dayjs().endOf('year').format('YYYY-MM-DD')],
						'Last Year': [dayjs().subtract(1, 'year').startOf('year').format('YYYY-MM-DD'), dayjs().subtract(1, 'year').endOf('year').format('YYYY-MM-DD')],
						'This Month': [dayjs().startOf('month').format('YYYY-MM-DD'), dayjs().endOf('month').format('YYYY-MM-DD')],
						'Last Month': [dayjs().subtract(1, 'month').startOf('month').format('YYYY-MM-DD'), dayjs().subtract(1, 'month').endOf('month').format('YYYY-MM-DD')],
						'Today': [dayjs().startOf('day').format('YYYY-MM-DD'), dayjs().endOf('day').format('YYYY-MM-DD')],
						'Yesterday': [dayjs().startOf('day').subtract(1, 'day').format('YYYY-MM-DD'), dayjs().endOf('day').subtract(1, 'day').format('YYYY-MM-DD')],
					},
				}, (start, end) => {
					this.update(start, end);
				});

				this.update(startDate, endDate);
			}, 500);
		},

		update: function(startDate, endDate) {
			this.startdate = dayjs(startDate).format('YYYY-MM-DD');
			this.enddate = dayjs(endDate).format('YYYY-MM-DD');

			this.startDisplay = dayjs(startDate).format('MMMM D, YYYY');
			this.endDisplay = dayjs(endDate).format('MMMM D, YYYY');

			this.change();
       	},

		change() {
			this.$emit('change', {
				start_date: this.startdate,
				end_date: this.enddate,
			});
		},

		clear() {
			this.startdate = null;
			this.end_date = null;

			this.change();
		},
	},

	data() {
		return {
			elem: null,
			startdate: null,
			enddate: null,

			startDisplay: null,
			endDisplay: null,
		}
	},

	props: {
		id: {
			default: 'date-range'
		},

		name: String,
	}
}
</script>

<style scoped>
.btn {
	cursor: pointer;
}

.border-top-left-radius-0 {
	border-top-left-radius: 0px;
}

.border-top-right-radius-0 {
	border-top-right-radius: 0px;
}

.border-bottom-left-radius-0 {
	border-bottom-left-radius: 0px;
}

.border-bottom-right-radius-0 {
	border-bottom-right-radius: 0px;
}
</style>