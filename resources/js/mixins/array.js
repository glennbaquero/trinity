export default {
	methods: {
		inArray(value, array, column = null) {
			if (array) {
				for (var i = array.length - 1; i >= 0; i--) {
					if (column) {
						if (value == array[i][column]) {
							return true;
						}
					}

					if (value == array[i]) {
						return true;
					}
				}
			}
			

			return false;
        },

        isArray(value) {
        	return Array.isArray(value);
        },

        array_count(value) {
            if (!value) { return 0; }

            if (Array.isArray(value)) {
                return value.length;
            }
            
            return 0;
        },

        array_pluck(array, key) {
			let plucked = [];
			for (let i = 0; i < array.length; i++){
				plucked[i] = array[i][key];
			}

			return plucked;
		},
	}
}