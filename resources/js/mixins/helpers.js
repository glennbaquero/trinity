export default {
	methods: {
		empty(value) {
			let type = typeof value;
			if (type === 'undefined') {
				return true;
			}
			if (type === 'boolean') {
				return !value;
			}
			if (value === null) {
				return true;
			}
			if (value === undefined) {
				return true;
			}
			if (value instanceof Array) {
				if (value.length < 1) {
					return true;
				}
			} else if (type === 'string') {
				if (value.length < 1) {
					return true;
				}
				if (value === '0') {
					return true;
				}
			} else if (type === 'object') {
				if (Object.keys(value).length < 1) {
					return true;
				}
				if (value === 0) {
					return true;
				}
			}
			return false;
		}
	}
}