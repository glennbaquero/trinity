<template>
	<div>
		<div class="row">
			<div class="col-md-8">
				<GmapMap ref="mapRef"
				  :center="{
				  		lat: parseFloat(filteredLogs[0].latitude), 
				  		lng: parseFloat(filteredLogs[0].longitude)
				  }"
				  :zoom="17"
				  map-type-id="roadmap"
				  :options="mapOptions"		
				  style="height: 500px"		  
				>
					
					<template v-for="(log, index) in filteredLogs">
						<GmapMarker 
							:ref="'myMarker' + index" 
							:name="log.medrep.fullname"
							:icon="renderIcon(log)"  
							:width="'20px'"
						    :position="google && new google.maps.LatLng(log.latitude, log.longitude)" 
							@click="toggleInfoWindow(log,index)"

						/>
				    </template>

					<gmap-info-window
						:options="infoOptions"
						:position="infoWindowPos"
						:opened="infoWinOpen"
						@closeclick="infoWinOpen=false"
					>
					<div v-html="infoContent"></div>
					</gmap-info-window>
				</GmapMap>
			</div>
			<div class="col-md-4 form-group">
				<h4>Medical Representative Logs</h4>
				<hr />
				
				<div class="col-md-12 form-group">
					<div class="text-left">
						<button 
						@click="viewAll()" 
						class="btn btn-success btn-sm">View All</button>
					</div>
				</div>

				<div style="height: 400px; overflow-x: hidden; overflow-y: auto;">
					<template v-for="(log, index) in logs">
						<div class="row">
							<div class="col-md-8 form-group">
								<label>{{ log.medrep.fullname }}</label>
							</div>
							<div class="col-md-4 form-group">
								<button
								@click="locateMedRep(log)"
								class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></button>
							</div>
						</div>
					</template>
				</div>
			</div>		
		</div>		
	</div>
</template>
<script>

import {gmapApi} from 'vue2-google-maps'

export default {

	props: {
		logs: {
			type: Array,
			default: [],
		},
	},

	computed: {
		google: gmapApi,


		filteredLogs: function() {
			var logs = this.logs;

			if(this.selectedLog) {
				logs = logs.filter((log) => {
					console.log(log.medical_representative_id + ' ' + this.selectedLog.medical_representative_id);
					return log.medical_representative_id == this.selectedLog.medical_representative_id;
				});
			}

			return logs;
		},
	},	

	data: function () {
		return {
			mapOptions: {
			   zoomControl: false,
			   mapTypeControl: false,
			   scaleControl: true,
			   streetViewControl: false,
			   rotateControl: false,
			   fullscreenControl: false,
			   disableDefaultUi: false,
			},

			infoContent: '',
			infoWindowPos: {
				lat: 0,
				lng: 0
			},
			infoWinOpen: false,
			currentMidx: null,
			//optional: offset infowindow so it visually sits nicely on top of our marker
			infoOptions: {
				pixelOffset: {
					width: 0,
					height: -35
				}
			},

			selectedLog: null,

		}
	},

	methods: {
		/**
		 * Render Marker icon
		 * 
		 */
		renderIcon: function(log) {
			if(log.medrep.image_path) {
				return {
					url: log.medrep.image_path.replace('public', 'storage'),
				    size: {width: 80, height: 80, f: 'px', b: 'px'},
				    scaledSize: {width: 80, height: 80, f: 'px', b: 'px'}					
				};			
			}
		},

		/**
		 * Toggle info window per marker
		 * 
		 * @param  object marker
		 * @param  string idx  
		 */
		toggleInfoWindow: function (marker, idx) {

			this.infoWindowPos = {
		  		lat: parseFloat(marker.latitude), 
		  		lng: parseFloat(marker.longitude)
		  	};

			this.infoContent = "<h4>"+ marker.medrep.fullname +"</h4>" 
			+"<p>Email: <b>"+ marker.medrep.email +"</b></p>"
			+"<p>Contact Number: <b>"+ marker.medrep.mobile +"</b></p>";

			//check if its the same marker that was selected if yes toggle
			if (this.currentMidx == idx) {
				this.infoWinOpen = !this.infoWinOpen;
			} else {
				this.infoWinOpen = true;
				this.currentMidx = idx;
			}
     	},

     	/**
     	 * Locate specific medical representative
     	 * 
     	 * @param  Object log
     	 */
     	locateMedRep: function(log) {
     		this.selectedLog = log;
     	},

     	/**
     	 * View all medical representative
     	 * 
     	 */
     	viewAll: function() {
     		this.selectedLog = null;
     	},
 	},

}
</script>