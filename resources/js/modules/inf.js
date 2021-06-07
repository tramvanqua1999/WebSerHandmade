export default {
    state: {
        temp: null,
        checked: false
    },
    getters: {
        IS_LOADING: state => {
            return state.temp;
        },
        IS_CHECKING: state => {
            return state.checked;
        },
    },
    mutations: {
        STORE_SUCCESS: (state, payload) => {
            state.temp = payload;
            state.checked = true;
            localStorage.setItem('temp' , payload);
        },
    }
}
