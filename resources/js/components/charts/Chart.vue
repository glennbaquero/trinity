<template>
	<div>
		<canvas ref="elem" :width="width" :height="height"></canvas>
	</div>
</template>

<script type="text/javascript">
import Chart from 'chart.js';
import ArrayHelpers from '../../mixins/array.js';

export default {
	watch: {
		items(value) {
			if (value) {
				this.initChart(value);
			}
		},
	},
	methods: {
		initChart(array) {
			var $this = this;
			let ctx = this.$refs.elem.getContext('2d');

			// let config = {
			//     type: this.type,
			//     data: {
			// 	    labels: this.array_pluck(array, this.itemLabel),
			//         datasets: [{
			//             label: this.label,
			//             data: this.array_pluck(array, this.itemData),
			//             backgroundColor: this.array_pluck(array, this.itemBgColor),
			//             borderColor: '#ddd',
			//             borderWidth: 1
			//         }]
			//     },
			//     options: {
			//         legend: {
			//         	display: true,
			//         },
			//         title: {
			//         	display: true,
			//         	text: this.title,
			//         	position: this.titlePosition,
			//         	fontSize: this.fontSize,
			//         }
			//     }
			// };

			// let myChart = new Chart(ctx, config);
			let myChart = new Chart(ctx, {
			    type: this.type,
			    data: {
			        labels: [ '', ...Object.keys(array) ],
			        datasets: [{
			            data: [ 0, ...Object.values(array) ],
			            fill: false,
			            pointBackgroundColor: '#21a1e1',
			            borderWidth: 1,
			            borderCapStyle: 'square',
			            borderColor: 'rgba(54, 162, 235)',
			            pointBorderWidth: 3
			        }]
			    },
			    options: {
			    	tooltips: {
						custom(tooltip) {
							tooltip.displayColors = false;
						},
			    		callbacks: {
			    			label(item, data) {
			    				var item = item.yLabel.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') + '.00';
			    				if($this.format) {
			    					item = $this.format + ' ' + item;
			    				}
			    				return item;
			    			}
			    		}
			    	},
	    	        legend: {
			        	display: false,
			        },
			        title: {
			        	display: true,
			        	text: this.title,
			        	position: this.titlePosition,
			        	fontSize: this.fontSize,
			        },
			        scales: {
			            yAxes: [{
			                ticks: {
			                	display: false,
			                    beginAtZero: true
			                }
			            }],
			            xAxes: [{
			                ticks: {
			                    beginAtZero: true
			                }
			            }]
			        }
			    }
			});
		},
	},

	props: {
		items: {
			default: {},
			type: Object,
		},

		format: {
			type: String,
			default: null,
		},

		height: {
			default: 400,
		},

		width: {
			default: 400,
		},

		itemLabel: {
			default: 'label',
			type: String,
		},

		itemData: {
			default: 'data',
			type: String,
		},

		itemBgColor: {
			default: 'backgroundColor',
			type: String,
		},

		label: String,
		title: String,

		fontSize: {
			default: 14,
		},

		titlePosition: {
			default: 'bottom',
			type: String,
		},

		type: {
			default: 'pie',
			type: String,
		},
	},

	data() {
		return {
			loading:false,
		}
	},

	mixins: [ ArrayHelpers ],
}
</script>