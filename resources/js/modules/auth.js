import { currentUser } from "./int";
const user = currentUser();

export default {
    state: {
        currentUser: user,
        isLoggedIn: !!user,
        loading: false,
        authError: null,
        type : null,
    },
    getters: {
        IS_LOADING: state => {
            return state.loading;
        },
        IS_LOGGEND_IN: state => {
            return state.isLoggedIn;
        },
        CURRENT_USER: state => {
            return state.currentUser;
        },
        AUTH_ERROR: state => {
            return state.authError;
        },
    },
    mutations: {
        LOGIN: state => {
            state.loading = true;
            state.authError = null;
        },
        LOGIN_SUCCESS: (state, payload) => {
            state.authError = null;
            state.isLoggedIn = true;
            state.loading = false;
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});
            localStorage.setItem('token' , payload.access_token);
            localStorage.setItem('user', JSON.stringify(state.currentUser));
            state.type = payload.type;
            localStorage.setItem('type', payload.type);
            localStorage.setItem('id', payload.id);

        },
        LOGIN_FAILED: (state, payload) => {
            state.authError = payload.err;
            state.loading = false;
        },
        LOGOUT: (state) => {
            localStorage.removeItem('user');
            localStorage.removeItem('token');
            state.isLoggedIn = false;
            state.currentUser = null;
            state.type = null;
            localStorage.removeItem('type');
            localStorage.removeItem('id');


        },
    },
    actions: {
        LOGIN: (context) => {
            context.commit('LOGIN');
        },
    }
}