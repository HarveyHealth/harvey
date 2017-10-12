import Util from '../util';

export default {
  userPost: {
    email: Util.data.fromStorage('email') || '',
    first_name: Util.data.fromStorage('first_name') || '',
    last_name: Util.data.fromStorage('last_name') || '',
    password: Util.data.fromStorage('password') || '',
    terms: '',
    zip: '',
  },
  zipValidation: null,
}
